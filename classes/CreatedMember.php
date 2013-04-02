<?php

/**
 * @copyright  Helmut Schottmüller
 * @author     Helmut Schottmüller <https://github.com/hschottm>
 * @license    LGPL
 */

namespace Contao;

/**
 * Class CreatedMember
 *
 * Newly created member.
 * @copyright  Helmut Schottmüller
 * @author     Helmut Schottmüller <https://github.com/hschottm>
 * @package    Controller
 */
class CreatedMember extends \Frontend
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