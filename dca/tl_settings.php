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
 * @package    Backend
 * @license    LGPL
 * @filesource
 */

/**
 * Class tl_settings_memberextension
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Helmut Schottmüller 2008
 * @author     Helmut Schottmüller <helmut.schottmueller@aurealis.de>
 * @package    Controller
 */
class tl_settings_memberextension extends Backend
{

	/**
	 * Return all member extensions that could be disabled
	 * @return array
	 */
	public function getExtensions()
	{
		$this->loadLanguageFile('tl_member');
		$this->loadDataContainer('tl_member');
		$extensions = array();
		foreach ($GLOBALS['TL_DCA']['tl_member']['fields'] as $fieldname => $field)
		{
			if ($field['eval']['configure'])
			{
				$extensions[$fieldname] = $field['label'][0];
			}
		}
		return $extensions;
	}
}

$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] = str_replace('defaultChmod', 'defaultChmod;{member_legend},inactivememberfields,memberurlparameter', $GLOBALS['TL_DCA']['tl_settings']['palettes']['default']); 

/**
 * Add fields
 */
$GLOBALS['TL_DCA']['tl_settings']['fields']['inactivememberfields'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['inactivememberfields'],
	'inputType'               => 'checkbox',
	'options_callback'        => array('tl_settings_memberextension', 'getExtensions'),
	'eval'                    => array('multiple'=>true)
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['memberurlparameter'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['memberurlparameter'],
	'default'                 => 'member',
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>30, 'mandatory' => true)
);

?>