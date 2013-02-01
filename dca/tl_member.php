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
 * Class tl_member_extended
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Helmut Schottmüller 2008
 * @author     Helmut Schottmüller <helmut.schottmueller@aurealis.de>
 * @package    Controller
 */
class tl_member_extended extends tl_member
{
	public function getAgreementText()
	{
		return "aaaa";
	}
	
	public static function getActiveFields($fields)
	{
		$inactiveFields = unserialize($GLOBALS['TL_CONFIG']['inactivememberfields']);
		if (!is_array($inactiveFields)) return join(',', $fields);
		$usedfields = array();
		foreach ($fields as $field)
		{
			if (!in_array($field, $inactiveFields)) array_push($usedfields, $field);
		}
		return join(',', $usedfields);
	}

	/**
	 * Return all editable fields of table tl_member
	 * @return array
	 */
	public function getViewableMemberProperties()
	{
		$return = array();

		$this->loadLanguageFile('tl_member');
		$this->loadDataContainer('tl_member');
		$inactivefields = deserialize($GLOBALS['TL_CONFIG']['inactivememberfields'], true);
		foreach ($GLOBALS['TL_DCA']['tl_member']['fields'] as $k=>$v)
		{
			if ($k == 'username' || $k == 'password' || $k == 'newsletter' || $k == 'publicFields' || $k == 'allowEmail')
			{
				continue;
			}

			if (($v['eval']['feViewable']) && (!in_array($k, $inactivefields)))
			{
				$return[$k] = $GLOBALS['TL_DCA']['tl_member']['fields'][$k]['label'][0];
			}
		}

		return $return;
	}
}

// add additional fields
if (strlen(tl_member_extended::getActiveFields(array('title','title_extended'))))
{
	$GLOBALS['TL_DCA']['tl_member']['palettes']['default'] = str_replace('firstname', tl_member_extended::getActiveFields(array('title','title_extended')) . ',firstname', $GLOBALS['TL_DCA']['tl_member']['palettes']['default']);
}
if (strlen(tl_member_extended::getActiveFields(array('salutation'))))
{
	$GLOBALS['TL_DCA']['tl_member']['palettes']['default'] = str_replace('gender', 'gender,salutation', $GLOBALS['TL_DCA']['tl_member']['palettes']['default']);
}

if (strlen(tl_member_extended::getActiveFields(array('description','workscope'))))
{
	$GLOBALS['TL_DCA']['tl_member']['palettes']['default'] = str_replace('country;', 'country;{work_legend},' . tl_member_extended::getActiveFields(array('description','workscope')) . ";", $GLOBALS['TL_DCA']['tl_member']['palettes']['default']);
}
if (strlen(tl_member_extended::getActiveFields(array('department'))))
{
	$GLOBALS['TL_DCA']['tl_member']['palettes']['default'] = str_replace('company', 'company,' . tl_member_extended::getActiveFields(array('department')), $GLOBALS['TL_DCA']['tl_member']['palettes']['default']);
}
if (strlen(tl_member_extended::getActiveFields(array('officehours'))))
{
	$GLOBALS['TL_DCA']['tl_member']['palettes']['default'] = str_replace('website', 'website,' . tl_member_extended::getActiveFields(array('officehours')), $GLOBALS['TL_DCA']['tl_member']['palettes']['default']);
}
if (strlen(tl_member_extended::getActiveFields(array('address2','room','building'))))
{
	$GLOBALS['TL_DCA']['tl_member']['palettes']['default'] = str_replace('street', 'street,' . tl_member_extended::getActiveFields(array('address2','room','building')), $GLOBALS['TL_DCA']['tl_member']['palettes']['default']);
}
if (strlen(tl_member_extended::getActiveFields(array('joined','resigned'))))
{
	$GLOBALS['TL_DCA']['tl_member']['palettes']['default'] = str_replace('website', 'website,' . tl_member_extended::getActiveFields(array('joined','resigned')), $GLOBALS['TL_DCA']['tl_member']['palettes']['default']);
}
if (strlen(tl_member_extended::getActiveFields(array('notes','campaign','business_connection','branch','jobtitle','jobtitle_bc'))))
{
	$GLOBALS['TL_DCA']['tl_member']['palettes']['default'] = str_replace('{groups_legend}', '{customer_legend},' . tl_member_extended::getActiveFields(array('notes','campaign','business_connection','branch','jobtitle','jobtitle_bc')) . ';{groups_legend}', $GLOBALS['TL_DCA']['tl_member']['palettes']['default']);
}

if (array_key_exists("avatar", $GLOBALS['TL_DCA']['tl_member']['fields']))
{
	$GLOBALS['TL_DCA']['tl_member']['fields']['avatar']['eval']['feViewable'] = true;
}

$GLOBALS['TL_DCA']['tl_member']['fields']['agreement'] = array
(
	'label'				=> &$GLOBALS['TL_LANG']['tl_member']['agreement'],
	'exclude'			=> true,
	'inputType'			=> 'agreement',
	'eval'				=> array('agreement_headline' => (get_class($this) == 'ModuleRegistrationExtended') ? $this->agreement_headline : '', 'agreement_text' => (get_class($this) == 'ModuleRegistrationExtended') ? $this->agreement_text : '', 'feGroup'=>'agreement')
);

$GLOBALS['TL_DCA']['tl_member']['fields']['groupselection'] = array
(
	'label'				=> &$GLOBALS['TL_LANG']['tl_member']['groupselection'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'              => (get_class($this) == 'ModuleRegistrationExtended') ? $this->getGroupSelection() : array(),
	'eval'				=> array('mandatory' => true, 'feGroup'=>'login', 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_member']['fields']['title'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_member']['title'],
	'search'                  => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>50, 'feEditable' => true, 'feViewable'=>true, 'feGroup'=>'personal', 'tl_class'=>'w50', 'configure' => true)
);

$GLOBALS['TL_DCA']['tl_member']['fields']['title_extended'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_member']['title_extended'],
	'search'                  => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>50, 'feEditable' => true, 'feViewable'=>true, 'feGroup'=>'personal', 'tl_class'=>'w50', 'configure' => true)
);

$GLOBALS['TL_DCA']['tl_member']['fields']['salutation'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_member']['salutation'],
	'search'                  => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>40, 'feEditable' => true, 'feViewable'=>true, 'feGroup'=>'personal', 'tl_class'=>'w50', 'configure' => true)
);

$GLOBALS['TL_DCA']['tl_member']['fields']['description'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_member']['description'],
	'search'                  => true,
	'inputType'               => 'textarea',
	'eval'                    => array('allowHtml'=>true, 'rte' => 'tinyMCE', 'cols' => 40, 'style'=>'height:80px;width:250px;', 'feEditable' => true, 'feViewable'=>true, 'feGroup'=>'personal', 'tl_class'=>'long', 'configure' => true)
);

$GLOBALS['TL_DCA']['tl_member']['fields']['notes'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_member']['notes'],
	'search'                  => true,
	'inputType'               => 'textarea',
	'eval'                    => array('allowHtml'=>true, 'rte' => 'tinyMCE', 'cols' => 40, 'style'=>'height:80px;width:250px;', 'feEditable' => true, 'feViewable'=>true, 'feGroup'=>'customer', 'tl_class'=>'long', 'configure' => true)
);

$GLOBALS['TL_DCA']['tl_member']['fields']['address2'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_member']['address2'],
	'search'                  => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>150, 'feEditable' => true, 'feViewable'=>true, 'feGroup'=>'address', 'tl_class'=>'w50', 'configure' => true)
);

$GLOBALS['TL_DCA']['tl_member']['fields']['room'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_member']['room'],
	'search'                  => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>14, 'feEditable' => true, 'feViewable'=>true, 'feGroup'=>'address', 'tl_class'=>'w50', 'configure' => true)
);

$GLOBALS['TL_DCA']['tl_member']['fields']['building'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_member']['building'],
	'search'                  => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>50, 'feEditable' => true, 'feViewable'=>true, 'feGroup'=>'address', 'tl_class'=>'w50', 'configure' => true)
);

$GLOBALS['TL_DCA']['tl_member']['fields']['department'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_member']['department'],
	'search'                  => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>150, 'feEditable' => true, 'feViewable'=>true, 'feGroup'=>'address', 'tl_class'=>'w50', 'configure' => true)
);

$GLOBALS['TL_DCA']['tl_member']['fields']['officehours'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_member']['officehours'],
	'search'                  => true,
	'inputType'               => 'textarea',
	'eval'                    => array('allowHtml'=>true, 'rte' => 'tinyMCE', 'cols' => 40, 'style'=>'height:80px;width:250px;', 'feEditable' => true, 'feViewable'=>true, 'feGroup'=>'personal', 'tl_class'=>'clr long', 'configure' => true)
);

$GLOBALS['TL_DCA']['tl_member']['fields']['joined'] = array
(
	'exclude'                 => true,
	'label'                   => &$GLOBALS['TL_LANG']['tl_member']['joined'],
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>10, 'rgxp'=>'date', 'datepicker'=>$this->getDatePickerString(), 'feEditable' => true, 'tl_class'=>'w50 wizard', 'configure' => true)
);

$GLOBALS['TL_DCA']['tl_member']['fields']['resigned'] = array
(
	'exclude'                 => true,
	'label'                   => &$GLOBALS['TL_LANG']['tl_member']['resigned'],
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>10, 'rgxp'=>'date', 'datepicker'=>$this->getDatePickerString(), 'feEditable' => true, 'tl_class'=>'w50 wizard', 'configure' => true)
);

$GLOBALS['TL_DCA']['tl_member']['fields']['workscope'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_member']['workscope'],
	'search'                  => true,
	'inputType'               => 'textarea',
	'eval'                    => array('allowHtml'=>true, 'rte' => 'tinyMCE', 'cols' => 40, 'style'=>'height:80px;width:250px;', 'feEditable' => true, 'feViewable'=>true, 'feGroup'=>'personal', 'tl_class'=>'long', 'configure' => true)
);

$GLOBALS['TL_DCA']['tl_member']['fields']['campaign'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_member']['campaign'],
	'search'                  => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>150, 'feEditable' => true, 'feViewable'=>true, 'feGroup'=>'customer', 'tl_class'=>'w50', 'configure' => true)
);

$GLOBALS['TL_DCA']['tl_member']['fields']['business_connection'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_member']['business_connection'],
	'search'                  => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>150, 'feEditable' => true, 'feViewable'=>true, 'feGroup'=>'customer', 'tl_class'=>'w50', 'configure' => true)
);

$GLOBALS['TL_DCA']['tl_member']['fields']['branch'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_member']['branch'],
	'search'                  => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>100, 'feEditable' => true, 'feViewable'=>true, 'feGroup'=>'customer', 'tl_class'=>'w50', 'configure' => true)
);

$GLOBALS['TL_DCA']['tl_member']['fields']['jobtitle'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_member']['jobtitle'],
	'search'                  => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>50, 'feEditable' => true, 'feViewable'=>true, 'feGroup'=>'customer', 'tl_class'=>'w50', 'configure' => true)
);

$GLOBALS['TL_DCA']['tl_member']['fields']['jobtitle_bc'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_member']['jobtitle_bc'],
	'search'                  => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>50, 'feEditable' => true, 'feViewable'=>true, 'feGroup'=>'customer', 'tl_class'=>'w50', 'configure' => true)
);

$GLOBALS['TL_DCA']['tl_member']['fields']['publicFields']['options_callback'] = array('tl_member_extended', 'getViewableMemberProperties');

?>