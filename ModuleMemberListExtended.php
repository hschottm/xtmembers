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

// deal with eventual ModuleMemberlist extensions
eval('class ModuleMemberListExtendedSuperClass extends ' . $GLOBALS['xtmembers']['default_memberlist'] . ' {}');

/**
 * Class ModuleMemberListExtended
 *
 * @copyright  Helmut Schottmüller 2008
 * @author     Helmut Schottmüller <helmut.schottmueller@aurealis.de>
 * @package    memberextensions
 */
class ModuleMemberListExtended extends ModuleMemberListExtendedSuperClass
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'memberlist_simple';

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');
			switch ($this->type)
			{
				case 'singlemember':
					$objTemplate->wildcard = '### MEMBER DETAILS ###';
					break;
				default:
					$objTemplate->wildcard = '### MEMBERLIST ###';
					break;
			}
			return $objTemplate->parse();
		}

		$this->memberlist_where = $this->replaceInsertTags($this->memberlist_where);

		if (strcmp($this->type, 'singlemember') != 0)
		{
			if (strlen($this->memberlist_groups))
			{
				$this->arrMlGroups = deserialize($this->memberlist_groups, true);
			}
			else
			{
				$this->arrMlGroups = array();
			}
			if (strlen($this->memberlist_fields))
			{
				$this->arrMlFields = deserialize($this->memberlist_fields, true);
			}
			else
			{
				$this->arrMlFields = array();
			}
			if (count($this->arrMlGroups) < 1 || count($this->arrMlFields) < 1)
			{
				return '';
			}
		}

		$this->strTemplate = $this->memberlist_template;
		
		return Module::generate();
	}

	/**
	 * Generate module
	 */
	protected function compile()
	{
		if (strcmp($this->type, 'singlemember') == 0)
		{
			$this->arrMlGroups = $this->getAllGroups();
			$this->Input->setGet('show', $this->singlemember);
		}
		parent::compile();
	}
	
	protected function getAllGroups()
	{
		return $this->Database->prepare("SELECT id FROM tl_member_group")
			->execute()
			->fetchEach('id');
	}

	/**
	 * List a single member
	 * @param integer
	 */
	protected function listSingleMember($id)
	{
		parent::listSingleMember($id);
		$objMember = $this->Database->prepare("SELECT * FROM tl_member WHERE id=?")
				->limit(1)
				->execute($id);
		if ($this->show_member_name)
		{
			global $objPage;
			$objPage->pageTitle = trim(htmlspecialchars($objMember->firstname . " " . $objMember->lastname));
		}
		$this->Template->membergroups = deserialize($objMember->groups, true);
	}

	/**
	 * List all members
	 */
	protected function listAllMembers()
	{
		parent::listAllMembers();
		if ($this->memberlist_jumpTo)
		{
			$page = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
									  ->limit(1)
									  ->execute($this->memberlist_jumpTo);
			$pagerow = $page->row();
			$arr = $this->Template->tbody;
			$urlparameter = (strlen($GLOBALS['TL_CONFIG']['memberurlparameter'])) ? $GLOBALS['TL_CONFIG']['memberurlparameter'] : 'member';
			foreach ($arr as $key => $member)
			{
				foreach ($member as $colidx => $col)
				{
					$arr[$key][$colidx]['jumpTo'] = $this->generateFrontendUrl($pagerow, '/' . $urlparameter . '/' . $this->Template->tbody[$key][$colidx]['id']);
				}
			}
			$this->Template->tbody = $arr;
		}
		$this->Template->show_searchfield = $this->show_searchfield;
		$this->Template->showDetailsColumn = $this->memberlist_showdetailscolumn;
		$this->Template->detailsColumn = $this->memberlist_detailscolumn;
	}


	/**
	 * Format a value
	 * @param string
	 * @param mixed
	 * @param boolean
	 * @return mixed
	 */
	protected function formatValue($k, $value, $blnListSingle=false)
	{
		$data = deserialize($value);

		// Return if empty
		if (is_string($data) && !strlen($data))
		{
			return '-';
		}

		// Array
		if ($GLOBALS['TL_DCA']['tl_member']['fields'][$k]['inputType'] == 'avatar')
		{
			$data = Avatar::img($data);
			return strlen($data) ? $data :  '-';
		}
		else
		{
			return parent::formatValue($k, $value, $blnListSingle=false);
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