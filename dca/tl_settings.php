<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * @copyright  Helmut Schottmüller
 * @author     Helmut Schottmüller <https://github.com/hschottm>
 * @license    LGPL
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