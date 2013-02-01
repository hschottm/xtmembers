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
 * Class ModuleMemberPage
 *
 * Front end module "member page".
 * @copyright  Helmut Schottmüller 2008
 * @author     Helmut Schottmüller <typolight@aurealis.de>
 * @package    Controller
 */
class ModuleMemberPage extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_memberpage';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### MEMBER PAGE ###';

			return $objTemplate->parse();
		}

		$this->member_groups = deserialize($this->member_groups, true);

		// Return if there are no archives
		if (!is_array($this->member_groups) || count($this->member_groups) < 1)
		{
			return '';
		}

		return parent::generate();
	}

	/**
	 * Generate module
	 */
	protected function compile()
	{
		global $objPage;

		$this->Template->referer = 'javascript:history.go(-1)';
		$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];

		// Get member item
		$user_id = null;
		if (FE_USER_LOGGED_IN)
		{
			$this->import('FrontendUser', 'User');
			$user_id = $this->User->id;
		}
		$urlparameter = (strlen($GLOBALS['TL_CONFIG']['memberurlparameter'])) ? $GLOBALS['TL_CONFIG']['memberurlparameter'] : 'member';
		$memberitem = ($this->Input->get($urlparameter)) ? $this->Input->get($urlparameter) : $user_id;
		$objMember = $this->Database->prepare("SELECT * FROM tl_member WHERE (id=? OR username=?)")
		 	->limit(1)
			->execute((is_numeric($memberitem) ? $memberitem : 0), $memberitem);

		if ($objMember->numRows < 1)
		{
			$this->Template->member = '<p class="error">Invalid member ID</p>';

			// Do not index the page
			$objPage->noSearch = 1;
			$objPage->cache = 0;

			// Send 404 header
			header('HTTP/1.0 404 Not Found');
			return;
		}

		while ($objMember->next())
		{
			$this_member_groups = deserialize($objMember->groups, true);
			$found = FALSE;
			foreach ($this->member_groups as $group)
			{
				if (in_array($group, $this_member_groups))
				{
					$found = TRUE;
				}
			}
			if ($found)
			{
				$this->loadLanguageFile('tl_member');
				$this->loadDataContainer('tl_member');
				$objTemplate = new FrontendTemplate($this->member_template);

				$pages = array();
				$activepage = (strlen($this->Input->get("activepage"))) ? $this->Input->get("activepage") : 1;
				$activePageArr = array();
				if (strlen($objMember->member_pages))
				{
					$pageArr = deserialize($objMember->member_pages, TRUE);
					if (count($pageArr))
					{
						$objFrontendPage = $this->Database->prepare("SELECT * FROM tl_member_pages WHERE id IN (" . implode(",", $pageArr) . ") ORDER BY position")
							->execute();
						while ($objFrontendPage->next())
						{
							$pg = array(
								"title" => $objFrontendPage->title, 
								"content" => deserialize($objFrontendPage->content), 
								"position" => $objFrontendPage->position, 
								"is_visible" => $objFrontendPage->is_visible, 
								"href" => $this->addToUrl("activepage=" . $objFrontendPage->position),
								"type" => $objFrontendPage->pagetype
							);
							array_push($pages, $pg);
							if ($objFrontendPage->position == $activepage)
							{
								$activePageArr = $pg;
							}
						}
					}
				}
				$objTemplate->activepage = $activePageArr;
				$objTemplate->activepage_position = $activepage;
				$objTemplate->pages = $pages;
				if (is_array($GLOBALS['TL_PERSONALDATA']))
				{
					if (array_key_exists($activePageArr["type"], $GLOBALS['TL_PERSONALDATA']))
					{
						$this->import($GLOBALS['TL_PERSONALDATA'][$activePageArr["type"]][0]);
						$objTemplate->memberpageContent = $this->$GLOBALS['TL_PERSONALDATA'][$activePageArr["type"]][0]->$GLOBALS['TL_PERSONALDATA'][$activePageArr["type"]][1]($activePageArr);
					}
				}
				$this->import('String');
				$objTemplate->class =  $this->strTemplate;
				$publicFields = $this->getPublicFields($objMember);
				foreach ($GLOBALS['TL_DCA']['tl_member']['fields'] as $k=>$v)
				{
					if (in_array($k, $publicFields) || (strcmp($k, 'username') == 0))
					{
						$objTemplate->$k = str_replace("\n", "<br />", $objMember->$k);
						$lng = "lng" . ucfirst($k);
						$objTemplate->$lng = $GLOBALS['TL_LANG']['tl_member'][$k][0];
					}
				}
				$objTemplate->lngPostaladdress = $GLOBALS['TL_LANG']['tl_member']['postaladdress'];
				$postaladdress = array();
				if (in_array("company", $publicFields)) if (strlen($objMember->company)) array_push($postaladdress, $objMember->company);
				if (in_array("department", $publicFields)) if (strlen($objMember->department)) array_push($postaladdress, $objMember->department);
				if (in_array("street", $publicFields)) if (strlen($objMember->street)) array_push($postaladdress, $objMember->street);
				if (in_array("address2", $publicFields)) if (strlen($objMember->address2)) array_push($postaladdress, $objMember->address2);
				if (in_array("postal", $publicFields) && in_array("city", $publicFields)) if (strlen($objMember->postal.$objMember->city)) array_push($postaladdress, trim($objMember->postal . " " . $objMember->city));
				$objTemplate->postaladdress = $postaladdress;
				try
				{
					if (in_array("avatar", $publicFields)) $objTemplate->avatar = $objMember->avatar;
				}
				catch (Exception $e)
				{
					$objTemplate->avatar = "";
				}
				$this->Template->member = $objTemplate->parse();
			}
		}

		if ($this->show_member_name)
		{
			global $objPage;
			$objPage->pageTitle = trim(htmlspecialchars($objMember->firstname . " " . $objMember->lastname));
		}

		// Access control
		if ($objGroup->requireLogin && !BE_USER_LOGGED_IN && !FE_USER_LOGGED_IN)
		{
			$this->Template->protected = true;
			return;
		}
	}

	private function getPublicFields($objMember)
	{
		$publicFields = deserialize($objMember->publicFields, true);
		foreach ($publicFields as $key => $field)
		{
			try
			{
				$objMember->$field;
			}
			catch (Exception $e)
			{
				unset($publicFields[$key]);
			}
		}
		return $publicFields;
	}
}

?>