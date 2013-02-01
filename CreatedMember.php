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
 * Class CreatedMember
 *
 * Newly created member.
 * @copyright  Helmut Schottmüller 2008
 * @author     Helmut Schottmüller <helmut.schottmueller@aurealis.de>
 * @package    Controller
 */
class CreatedMember extends Frontend
{
	public function createNewUser($id, $arrData)
	{
		if ($arrData["groupselection"])
		{
			// Get member
			$objMember = $this->Database->prepare("SELECT * FROM tl_member WHERE id=?")
										->limit(1)
										->execute($id);
			$groups = deserialize($objMember->groups, true);
			if (!is_array($groups)) $groups = array();
			$groupids = array();
			foreach ($groups as $groupid)
			{
				if ($groupid) array_push($groupids, $groupid);
			}
			if (!in_array($arrData["groupselection"], $groupids)) array_push($groupids, $arrData["groupselection"]);
			$this->Database->prepare("UPDATE tl_member SET groups=? WHERE id=?")
						   ->execute(serialize($groupids), $id);
		}
	}
}

?>