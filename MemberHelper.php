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
 * @copyright  Helmut Schottmüller 2013
 * @author     Helmut Schottmüller <contao@aurealis.de>
 * @package    memberextensions
 * @license    LGPL
 * @filesource
 */


/**
 * Class MemberHelper
 *
 * Helper class for xtmembers
 * @copyright  Helmut Schottmüller 2008-2010
 * @author     Helmut Schottmüller <contao@aurealis.de>
 * @package    Controller
 */
class MemberHelper extends Backend
{
	public function memberlistQuery($objMemberlist, &$strWhere, &$arrValues)
	{
		if (strlen($objMemberlist->memberlist_where))
		{
			$strWhere .= ((strlen($strWhere) == 0) ? "" : " AND ") . $objMemberlist->memberlist_where;
		}
		if (strlen($objMemberlist->memberlist_filters))
		{
			$c = 0;
			$filters = deserialize($objMemberlist->memberlist_filters, true);
			foreach ($filters as $filterarray)
			{
				if (is_array($filterarray) && count($filterarray) == 2 && strlen($filterarray[0]) && strlen($filterarray[1]))
				{
					for ($i = 0; $i < $objMemberlist->memberlist_filtercount; $i++)
					{
						$getvalue = $this->Input->get("f$i");
						if (strlen($getvalue))
						{
							if ($c == $getvalue)
							{
								$strWhere .= ((strlen($strWhere) == 0) ? "" : " AND ") . $filterarray[0];
							}
						}
					}
					$c++;
				}
			}
		}
		if (strlen($objMemberlist->show_searchfield == 2))
		{
			$this->Input->setGet('for', $objMemberlist->saved_for);
			// Search query
			if ($this->Input->get('search') && ($this->Input->get('for') != '' && $this->Input->get('for') != '*'))
			{
				$stop = false;
				if ($GLOBALS['TL_DCA']['tl_member']['fields'][$this->Input->get('search')]['eval']['rgxp'] && strcmp($GLOBALS['TL_DCA']['tl_member']['fields'][$this->Input->get('search')]['eval']['rgxp'], 'date') == 0)
				{
					// date field handling
					if (strlen($this->Input->get('for')) == 4)
					{
						switch (trim($this->Input->get('relation')))
						{
							case 'gt':
								$start = mktime(23,59,59,12,31,$this->Input->get('for'));
								$strWhere .= ((strlen($strWhere)) ? " AND (" : "(") . $this->Input->get('search') . " > ?)";
								$arrValues[] = $start;
								$stop = true;
								break;
							case 'geq':
								$start = mktime(0,0,0,1,1,$this->Input->get('for'));
								$strWhere .= ((strlen($strWhere)) ? " AND (" : "(") . $this->Input->get('search') . " >= ?)";
								$arrValues[] = $start;
								$stop = true;
								break;
							case 'lt':
								$start = mktime(0,0,0,1,1,$this->Input->get('for'));
								$strWhere .= ((strlen($strWhere)) ? " AND (" : "(") . $this->Input->get('search') . " < ?)";
								$arrValues[] = $start;
								$stop = true;
								break;
							case 'leq':
								$start = mktime(23,59,59,12,31,$this->Input->get('for'));
								$strWhere .= ((strlen($strWhere)) ? " AND (" : "(") . $this->Input->get('search') . " <= ?)";
								$arrValues[] = $start;
								$stop = true;
								break;
							case 'eq':
								$start = mktime(0,0,0,1,1,$this->Input->get('for'));
								$end = mktime(23,59,59,12,31,$this->Input->get('for'));
								$strWhere .= ((strlen($strWhere)) ? " AND (" : "(") . $this->Input->get('search') . " >= ? AND " . $this->Input->get('search') . " <= ?)";
								$arrValues[] = $start;
								$arrValues[] = $end;
								$stop = true;
								break;
							case 'neq':
								$start = mktime(0,0,0,1,1,$this->Input->get('for'));
								$end = mktime(23,59,59,12,31,$this->Input->get('for'));
								$strWhere .= ((strlen($strWhere)) ? " AND (" : "(") . $this->Input->get('search') . " < ? OR " . $this->Input->get('search') . " > ?)";
								$arrValues[] = $start;
								$arrValues[] = $end;
								$stop = true;
								break;
						}
					}
					else
					{
						switch (trim($this->Input->get('relation')))
						{
							case 'lt':
								$rel = "<";
								break;
							case 'leq':
								$rel = "<=";
								break;
							case 'eq':
								$rel = "=";
								break;
							case 'neq':
								$rel = "!=";
								break;
							case 'gt':
								$rel = ">";
								break;
							case 'geq':
								$rel = ">=";
								break;
						}
						if ($rel)
						{
							try {
								$start = new Date($this->Input->get('for'), $GLOBALS['TL_CONFIG']['dateFormat']);
								$strWhere .= ((strlen($strWhere)) ? " AND (" : "(") . $this->Input->get('search') . " $rel ?)";
								$arrValues[] = $start->timestamp;
								$stop = true;
							} catch (Exception $e) {
							}
						}
					}
				}
				if (!$stop)
				{
					switch (trim($this->Input->get('relation')))
					{
						case 'lt':
							$strWhere .= ((strlen($strWhere)) ? " AND " : "") . $this->Input->get('search') . " < ?";
							$arrValues[] = $this->Input->get('for');
							break;
						case 'leq':
							$strWhere .= ((strlen($strWhere)) ? " AND " : "") . $this->Input->get('search') . " <= ?";
							$arrValues[] = $this->Input->get('for');
							break;
						case 'eq':
							$strWhere .= ((strlen($strWhere)) ? " AND " : "") . $this->Input->get('search') . " = ?";
							$arrValues[] = $this->Input->get('for');
							break;
						case 'neq':
							$strWhere .= ((strlen($strWhere)) ? " AND " : "") . $this->Input->get('search') . " <> ?";
							$arrValues[] = $this->Input->get('for');
							break;
						case 'gt':
							$strWhere .= ((strlen($strWhere)) ? " AND " : "") . $this->Input->get('search') . " > ?";
							$arrValues[] = $this->Input->get('for');
							break;
						case 'geq':
							$strWhere .= ((strlen($strWhere)) ? " AND " : "") . $this->Input->get('search') . " >= ?";
							$arrValues[] = $this->Input->get('for');
							break;
						case 'CONTAINS':
							$strWhere .= ((strlen($strWhere)) ? " AND " : "") . $this->Input->get('search') . " REGEXP ?";
							$arrValues[] = $this->Input->get('for');
							break;
						case 'checked':
							$strWhere .= ((strlen($strWhere)) ? " AND " : "") . $this->Input->get('search') . " = ?";
							$arrValues[] = '1';
							break;
						case 'unchecked':
							$strWhere .= ((strlen($strWhere)) ? " AND " : "") . $this->Input->get('search') . " = ?";
							$arrValues[] = '0';
							break;
					}
				}
			}
		}
	}

}

?>