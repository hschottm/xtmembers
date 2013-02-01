<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Helmut Schottmüller 2011
 * @author     Helmut Schottmüller <contao@aurealis.de>
 * @package    Backend
 * @license    LGPL
 * @filesource
 */


$GLOBALS['TL_DCA']['tl_member_group']['palettes']['default'] = str_replace('{redirect_legend', '{member_legend},disabledmemberfields;{redirect_legend', $GLOBALS['TL_DCA']['tl_member_group']['palettes']['default']); 

/**
 * Add fields
 */
$GLOBALS['TL_DCA']['tl_member_group']['fields']['disabledmemberfields'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_member_group']['disabledmemberfields'],
	'inputType'               => 'checkbox',
	'options_callback'        => array('tl_member_group_extended', 'getMemberFields'),
	'eval'                    => array('multiple'=>true)
);

/**
 * Class tl_member_group
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class tl_member_group_extended extends tl_member_group
{

	/**
	 * Return all member extensions that could be disabled
	 * @return array
	 */
	public function getMemberFields()
	{
		$this->loadLanguageFile('tl_member');
		$this->loadDataContainer('tl_member');
		$extensions = array();
		foreach ($GLOBALS['TL_DCA']['tl_member']['fields'] as $fieldname => $field)
		{
			if (is_array($field['eval']) && array_key_exists('feEditable', $field['eval']))
			$extensions[$fieldname] = $field['label'][0];
		}
		return $extensions;
	}
}

?>
