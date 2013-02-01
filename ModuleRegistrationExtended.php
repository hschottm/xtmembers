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
 * Class ModuleRegistrationExtended
 *
 * Front end module "registration".
 * @copyright  Helmut Schottmüller 2008
 * @author     Helmut Schottmüller <helmut.schottmueller@aurealis.de>
 * @package    Controller
 */
class ModuleRegistrationExtended extends ModuleRegistration
{
	/**
	 * Generate module
	 */
	protected function compile()
	{
		if ($this->show_agreement)
		{
			$GLOBALS['TL_FFL'] += array('agreement' => 'FormAgreement');
			$arr = $this->editable;
			array_push($arr, "agreement");
			$this->editable = $arr;
		}
		if ($this->allow_groupselection)
		{
			$fields = implode(",", $this->editable);
			if (strpos($fields, "password"))
			{
				$this->editable = explode(",", str_replace("password", "password,groupselection", $fields));
			}
			else
			{
				$this->editable = explode(",", $fields . ",groupselection");
			}
		}

		parent::compile();

		$this->Template->agreementDetails = $GLOBALS['TL_LANG']['tl_member']['agreement'];
	}
	
	public function getGroupSelection()
	{
		$this->loadLanguageFile('tl_module');
		$groups = array("" => $GLOBALS['TL_LANG']['tl_module']['reg_select_group']);
		if (strlen($this->groupselection_groups))
		{
			$allowed_groups = deserialize($this->groupselection_groups, TRUE);
			if (is_array($allowed_groups) && count($allowed_groups) > 0)
			{
				$objGroup = $this->Database->prepare("SELECT * FROM tl_member_group WHERE id IN (" . implode(",", $allowed_groups) . ")")
					->execute();
				if ($objGroup->numRows >= 1)
				{
					while ($objGroup->next())
					{
						$groups[$objGroup->id] = $objGroup->name;
					}
				}
			}
		}
		return $groups;
	}

	/**
	 * Send an admin notification e-mail
	 * @param integer
	 * @param array
	 */
	protected function sendAdminNotification($intId, $arrData)
	{
		if (array_key_exists('groups', $arrData) && is_array($arrData['groups']))
		{
			$objGroup = $this->Database->prepare("SELECT * FROM tl_member_group WHERE id IN (" . implode(",", $arrData['groups']) . ")")
				->execute();
			
			$newgroups = array();
			while ($objGroup->next())
			{
				array_push($newgroups, $objGroup->name . ' (ID ' . $objGroup->id . ')');
				$groups[$objGroup->id] = $objGroup->name;
			}
			$arrData['groups'] = $newgroups;
		}
		if (array_key_exists('groupselection', $arrData))
		{
			$arrGroup = $this->Database->prepare("SELECT * FROM tl_member_group WHERE id = ?")
				->execute($arrData['groupselection'])->fetchAssoc();
			$arrData['groupselection'] = $arrGroup['name'] . ' (ID ' . $arrData['groupselection'] . ')';
		}
		if (array_key_exists('dateAdded', $arrData))
		{
			$arrData['dateAdded'] = $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $arrData['dateAdded']);
		}
		parent::sendAdminNotification($intId, $arrData);
	}
}

?>