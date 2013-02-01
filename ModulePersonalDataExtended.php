<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Helmut Schottmüller 2008
 * @author     Helmut Schottmüller <helmut.schottmueller@aurealis.de>
 * @package    memberextensions
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModulePersonalDataExtended
 *
 * Front end module "personal data".
 * @copyright  Helmut Schottmüller 2008
 * @author     Helmut Schottmüller <typolight@aurealis.de>
 * @package    Controller
 */
class ModulePersonalDataExtended extends ModulePersonalData
{
	/**
	 * Template
	 * @var string
	 */
	protected $showPageHead = TRUE;

	/**
	 * Return a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		$this->page_editor = ($this->page_editor) ? TRUE : FALSE;
		return parent::generate();
	}
	
	protected function deletePage($position)
	{
		if ($position > 1)
		{
			$pages = deserialize($this->User->member_pages, TRUE);
			$objFrontendPage = $this->Database->prepare("SELECT * FROM tl_member_pages WHERE id IN (" . implode(",", $pages) . ") AND position = ?")
				->execute($position);
			if ($objFrontendPage->next())
			{
				$pageid = $objFrontendPage->id;
				$objFrontendPage = $this->Database->prepare("DELETE FROM tl_member_pages WHERE id = ?")
					->execute($pageid);
				foreach ($pages as $key => $page)
				{
					if ($page == $pageid) unset($pages[$key]);
				}
				$this->Database->prepare("UPDATE tl_member SET member_pages=? WHERE id=?")
					->execute(serialize($pages), $this->User->id);
				$this->User->member_pages = serialize($pages);
			}
		}
	}
	
	public function setShowPageHead($blnValue)
	{
		$this->showPageHead = $blnValue;
	}
	
	public function getShowPageHead()
	{
		return $this->showPageHead;
	}
	
	protected function createDefaultPage()
	{
		if ((!is_array($this->User->member_pages) && strlen($this->User->member_pages) == 0) || (count(deserialize($this->User->member_pages, TRUE)) == 0))
		{
			$pages = array();
			$arrData = array();
			$arrData['tstamp'] = time();
			$arrData['title'] = $GLOBALS['TL_LANG']['tl_module']['about_person'];
			$arrData['position'] = 1;
			$arrData['pagetype'] = 'memberpage';
			$arrData['is_visible'] = '1';
			$arrData['content'] = '';
			$objFrontendPage = $this->Database->prepare("INSERT INTO tl_member_pages %s")->set($arrData)->execute();
			$insertId = $objFrontendPage->insertId;
			array_push($pages, $insertId);
			$this->Database->prepare("UPDATE tl_member SET member_pages=? WHERE id=?")
				->execute(serialize($pages), $this->User->id);
			$this->User->member_pages = serialize($pages);
		}
	}
	
	protected function addPage()
	{
		$pages = deserialize($this->User->member_pages, TRUE);
		$objFrontendPage = $this->Database->prepare("SELECT * FROM tl_member_pages WHERE id IN (" . implode(",", $pages) . ") ORDER BY position")
			->execute();
		$arrData = array();
		$arrData['tstamp'] = time();
		$arrData['title'] = $GLOBALS['TL_LANG']['tl_module']['new_page'];
		$arrData['position'] = $objFrontendPage->numRows + 1;
		$arrData['content'] = '';
		$arrData['is_visible'] = '';
		$arrData['pagetype'] = $this->Input->post('pagetype');
		$objFrontendPage = $this->Database->prepare("INSERT INTO tl_member_pages %s")->set($arrData)->execute();
		$insertId = $objFrontendPage->insertId;
		array_push($pages, $insertId);
		$this->Database->prepare("UPDATE tl_member SET member_pages=? WHERE id=?")
			->execute(serialize($pages), $this->User->id);
		$this->User->member_pages = serialize($pages);
	}
	
	protected function compile()
	{
		parent::compile();
		if ($this->page_editor)
		{
			$memberdata = $this->Template->parse();
			if ($this->page_editor)
			{
				$this->memberTpl = 'personaldata_default';
			}
			
			global $objPage;
			$this->import('FrontendUser', 'User');

			$GLOBALS['TL_LANGUAGE'] = $objPage->language;

			$this->loadLanguageFile('tl_member');
			$this->loadLanguageFile('tl_module');
			$this->loadDataContainer('tl_member');

			$this->createDefaultPage();

			$activepage = (strlen($this->Input->get("activepage"))) ? $this->Input->get("activepage") : 1;
			if (strlen($this->Input->post("addPage")))
			{
				// add a new page
				$this->addPage();
				$activepage = count(deserialize($this->User->member_pages, TRUE));
				$this->redirect($this->addToUrl("activepage=$activepage"));
			}

			if (strlen($this->Input->post("deletePage")))
			{
				// delete a page
				$this->deletePage($this->Input->get("activepage"));
				$page = $activepage-1;
				$this->redirect($this->addToUrl("activepage=" . $page));
			}

			if (strlen($this->Input->post("saveContent")) || strlen($this->Input->post('FORM_SUBMIT')))
			{
				$this->Database->prepare("UPDATE tl_member_pages SET title=?, content=?, is_visible=? WHERE position=? AND id IN (" . implode(",", deserialize($this->User->member_pages, TRUE)). ")")
					->execute($this->Input->post("pageTitle"), $this->Input->postHtml("content", TRUE), $this->Input->post("is_visible"), $activepage);
			}

			// Set template
			if (strlen($this->memberTpl))
			{
				$this->Template = new FrontendTemplate($this->memberTpl);
			}

			$arrFields = array();
			$doNotSubmit = false;
			$hasUpload = false;
			$this->Template->fields = '';

			$this->Template->pageaction = ampersand($this->Environment->request, ENCODE_AMPERSANDS);
			$pages = array();
			if (is_array($this->User->member_pages) || strlen($this->User->member_pages))
			{
				$pageArr = deserialize($this->User->member_pages, TRUE);
				if (count($pageArr))
				{
					$objFrontendPage = $this->Database->prepare("SELECT * FROM tl_member_pages WHERE id IN (" . implode(",", $pageArr) . ") ORDER BY position")
						->execute();
					while ($objFrontendPage->next())
					{
						$content = deserialize($objFrontendPage->content);
						if (!is_array($content)) $content = specialchars($content);
						$pg = array(
							"title" => $objFrontendPage->title, 
							"content" => $content, 
							"position" => $objFrontendPage->position, 
							"is_visible" => $objFrontendPage->is_visible, 
							"href" => $this->addToUrl("activepage=" . $objFrontendPage->position),
							"type" => $objFrontendPage->pagetype
						);
						$pages[] = $pg;
					}
				}
			}

			if (is_array($GLOBALS['TL_PERSONALDATA_EDITOR']))
			{
				if (array_key_exists($pages[$activepage-1]["type"], $GLOBALS['TL_PERSONALDATA_EDITOR']))
				{
					$this->import($GLOBALS['TL_PERSONALDATA_EDITOR'][$pages[$activepage-1]["type"]][0]);
					$this->Template->pageEditorContent = $this->$GLOBALS['TL_PERSONALDATA_EDITOR'][$pages[$activepage-1]["type"]][0]->$GLOBALS['TL_PERSONALDATA_EDITOR'][$pages[$activepage-1]["type"]][1]($pages[$activepage-1], $this);
				}
			}

			if ($activepage == 1) $this->Template->memberdata = $memberdata;
			$this->Template->page_editor = ($this->page_editor) ? TRUE : FALSE;
			$this->Template->textVisible = $GLOBALS['TL_LANG']['tl_member']['pageVisible'];
			$this->Template->activepage_position = $activepage;
			$this->Template->pages = $pages;
			$this->Template->activePageArr = $pages[$activepage-1];
			$this->Template->strDeletePage = $GLOBALS['TL_LANG']['tl_module']['deletePage'];
			$this->Template->showPageHead = $this->showPageHead;
			$this->Template->strAdd = $GLOBALS['TL_LANG']['tl_module']['add'];
			$this->Template->deletePage = $this->addToUrl("deletePage=" . $activepage);//$deleteURL;
			$pagetypes = $GLOBALS['TL_LANG']['tl_module']['pagetype'];
			asort($pagetypes);
			$this->Template->pagetypes = $pagetypes;
			$this->Template->save = $GLOBALS['TL_LANG']['MSC']['save'];
			$this->Template->content = $GLOBALS['TL_LANG']['tl_module']['content'];
			$this->Template->pageTitle = $GLOBALS['TL_LANG']['tl_module']['pageTitle'];
			$this->Template->confirmDeletePage = $GLOBALS['TL_LANG']['tl_module']['confirmDeletePage'];

			$this->Template->formId = 'tl_member_' . $this->id;
			$this->Template->slabel = specialchars($GLOBALS['TL_LANG']['MSC']['saveData']);
			$this->Template->action = ampersand($this->Environment->request, ENCODE_AMPERSANDS);
			$this->Template->enctype = $hasUpload ? 'multipart/form-data' : 'application/x-www-form-urlencoded';
			$this->Template->rowLast = 'row_' . count($this->editable) . ((($i % 2) == 0) ? ' odd' : ' even');
		}
	}
}

?>