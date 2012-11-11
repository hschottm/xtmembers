<?php

/**
 * @copyright  Helmut Schottmüller
 * @author     Helmut Schottmüller <https://github.com/hschottm>
 * @license    LGPL
 */

$GLOBALS['TL_DCA']['tl_member_group']['palettes']['default'] = str_replace('{redirect_legend', '{member_legend},disabledmemberfields;{redirect_legend', $GLOBALS['TL_DCA']['tl_member_group']['palettes']['default']); 

/**
 * Add fields
 */
$GLOBALS['TL_DCA']['tl_member_group']['fields']['disabledmemberfields'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_member_group']['disabledmemberfields'],
	'inputType'               => 'checkbox',
	'options_callback'        => array('tl_member_group_extended', 'getMemberFields'),
	'eval'                    => array('multiple'=>true),
	'sql'                     => "blob NULL"
);

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
