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
 * Class tl_module_memberextensions
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Helmut Schottmüller 2008
 * @author     Helmut Schottmüller <helmut.schottmueller@aurealis.de>
 * @package    Controller
 */
class tl_module_memberextensions extends tl_module
{
	/**
	 * Return all editable fields of table tl_member
	 * @return array
	 */
	public function getEditableMemberProperties()
	{
		$return = array();

		$this->loadLanguageFile('tl_member');
		$this->loadDataContainer('tl_member');
		foreach ($GLOBALS['TL_DCA']['tl_member']['fields'] as $k=>$v)
		{
			if ($v['eval']['feEditable'])
			{
				if (strlen($this->getActiveFields(array($k))))
				{
					$return[$k] = $GLOBALS['TL_DCA']['tl_member']['fields'][$k]['label'][0];
				}
			}
		}
		return $return;
	}

	public function getMemberListTemplates(DataContainer $dc)
	{
		if (version_compare(VERSION.BUILD, '2.9.0', '>=')) 
		{
			return $this->getTemplateGroup('memberlist_', $dc->activeRecord->pid);
		} 
		else 
		{
			return $this->getTemplateGroup('memberlist_');
		}
	}

	public function getMemberPageTemplates(DataContainer $dc)
	{
		if (version_compare(VERSION.BUILD, '2.9.0', '>=')) 
		{
			return $this->getTemplateGroup('memberpage_', $dc->activeRecord->pid);
		} 
		else 
		{
			return $this->getTemplateGroup('memberpage_');
		}
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
	 * Return all visible fields of table tl_member
	 * @return array
	 */
	public function getMemberListFields()
	{
		$return = array();

		$this->loadLanguageFile('tl_member');
		$this->loadDataContainer('tl_member');
		foreach ($GLOBALS['TL_DCA']['tl_member']['fields'] as $k=>$v)
		{
			if ($v['eval']['feEditable'])
			{
				$return[$k] = $GLOBALS['TL_DCA']['tl_member']['fields'][$k]['label'][0];
			}
		}
		return $return;
	}
	
	/**
	 * Return a list of available members
	 * @return array
	 */
	public function getMemberList()
	{
		$objMembers = $this->Database->prepare("SELECT * FROM tl_member WHERE disable <> ? ORDER BY lastname, firstname")
			->execute(1);
		$members = array();
		while ($objMembers->next())
		{
			$members[$objMembers->id] = specialchars(trim($objMembers->firstname . ' ' . $objMembers->lastname) . (($objMembers->login) ? ' (' . $objMembers->username . ')' : ''));
		}
		return $members;
	}

}

$GLOBALS['TL_DCA']['tl_module']['palettes']['memberpage']    = '{title_legend},name,headline,type;{config_legend},member_groups,member_template,show_member_name;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['memberlist']    = '{title_legend},name,headline,type;{config_legend},memberlist_groups,show_searchfield,show_member_name,perPage,memberlist_template,ml_sort,memberlist_where,memberlist_fields;{redirect_legend},memberlist_jumpTo,memberlist_showdetailscolumn,memberlist_detailscolumn;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['singlemember']  = '{title_legend},name,headline,type;{config_legend},singlemember;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['registration']  = str_replace('reg_assignDir;', 'reg_assignDir;{agreement_legend},show_agreement;{groupselection_legend},allow_groupselection;', $GLOBALS['TL_DCA']['tl_module']['palettes']['registration']);
$GLOBALS['TL_DCA']['tl_module']['palettes']['personalData']  = str_replace("{template_legend:hide},memberTpl", "{pageeditor_legend},page_editor", $GLOBALS['TL_DCA']['tl_module']['palettes']['personalData']);

$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'show_agreement';
$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'allow_groupselection';

$GLOBALS['TL_DCA']['tl_module']['subpalettes']['show_agreement'] = 'agreement_headline,agreement_text';
$GLOBALS['TL_DCA']['tl_module']['subpalettes']['allow_groupselection'] = 'groupselection_groups';

$GLOBALS['TL_DCA']['tl_module']['fields']['page_editor'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['personaldata_page_editor'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50 m12')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['editable'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['editable'],
	'exclude'                 => true,
	'inputType'               => 'checkboxWizard',
	'options_callback'        => array('tl_module_memberextensions', 'getEditableMemberProperties'),
	'eval'                    => array('multiple'=>true)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['show_searchfield'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['memberlist_show_searchfield'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('0', '1', '2'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module']['search_field'],
	'eval'                    => array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['show_member_name'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['show_member_name'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50 m12')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['memberlist_showdetailscolumn'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['memberlist_showdetailscolumn'],
	'default'                 => '1',
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50 m12')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['memberlist_detailscolumn'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['memberlist_detailscolumn'],
	'default'                 => 'username',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_memberextensions', 'getMemberListFields'),
	'eval'                    => array('tl_class'=>'w50', 'includeBlankOption' => true)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['allow_groupselection'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['registration_allow_groupselection'],
	'exclude'       => true,
	'inputType'     => 'checkbox',
	'eval'          => array('submitOnChange'=>true)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['groupselection_groups'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['registration_groupselection_groups'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'foreignKey'              => 'tl_member_group.name',
	'eval'                    => array('multiple'=>true)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['show_agreement'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['registration_show_agreement'],
	'exclude'       => true,
	'inputType'     => 'checkbox',
	'eval'          => array('submitOnChange'=>true)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['agreement_text'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['registration_agreement_text'],
	'exclude'                 => true,
	'inputType'               => 'textarea',
	'eval'                    => array('style'=>'height:120px;', 'mandatory' => true, 'allowHtml' => true, 'rte' => 'tinyMCE', 'tl_class'=>'clr long')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['agreement_headline'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['registration_agreement_headline'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>150, 'mandatory' => true, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['member_groups'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['groups'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'foreignKey'              => 'tl_member_group.name',
	'eval'                    => array('multiple'=>true)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['member_template'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['member_template'],
	'default'                 => 'memberpage_complete',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_memberextensions', 'getMemberPageTemplates'),
	'eval'                    => array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['memberlist_template'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['memberlist_template'],
	'default'                 => 'memberlist_simple',
	'exclude'                 => true,
	'inputType'               => 'select',
  'options_callback'        => array('tl_module_memberextensions', 'getMemberListTemplates'),
	'eval'                    => array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['memberlist_groups'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['ml_groups'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'foreignKey'              => 'tl_member_group.name',
	'eval'                    => array('multiple'=>true)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['singlemember'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['singlemember'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_memberextensions', 'getMemberList'),
	'eval'                    => array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['memberlist_jumpTo'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['jumpTo'],
	'exclude'                 => true,
	'inputType'               => 'pageTree',
	'explanation'             => 'jumpTo',
	'eval'                    => array('fieldType'=>'radio', 'helpwizard'=>true)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['memberlist_fields'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['memberlist_fields'],
	'exclude'                 => true,
	'inputType'               => 'checkboxWizard',
	'options_callback'        => array('tl_module_memberextensions', 'getMemberListFields'),
	'eval'                    => array('multiple'=>true, 'tl_class'=>'clr')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['memberlist_where'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['memberlist_where'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('preserveTags'=>true, 'maxlength'=>255, 'tl_class'=>'w50')
);

?>
