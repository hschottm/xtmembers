<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Class MemberlistAjaxHelper
 *
 * Ajax helper class for memberlist fields
 * @copyright  Helmut Schottmüller 2013
 * @author     Helmut Schottmüller <contao@aurealis.de>
 * @package    Controller
 */
class MemberlistAjaxHelper extends Controller
{
	public function generateHtmlOutputForMemberlistSearch()
	{
		if (strcmp($this->Input->get('module'), 'memberlist') == 0)
		{
			$this->loadDataContainer('tl_member');
			$this->loadLanguageFile('tl_member');
			$this->loadLanguageFile('default');
			$field = $GLOBALS['TL_DCA']['tl_member']['fields'][$this->Input->get('field')];
			if ($field)
			{
				$result = array();
				switch ($field['inputType'])
				{
					case 'select':
					case 'radio':
						$options = array();
						$c = 0;
						foreach ($field['options'] as $key => $value)
						{
							$option = array();
							if (is_array($field['reference']))
							{
								$option['key'] = strlen($value) ? $value : '0';
								$option['value'] = $field['reference'][$value];
							}
							else
							{
								$option['key'] = strlen($key) ? $key : '0';
								$option['value'] = $value;
								$c++;
							}
							array_push($options, $option);
						}
						$result['field'] = array('type' => 'select', 'options' => $options);
						$keys = array();
						foreach ($options as $optionarray)
						{
							if (is_numeric($optionarray['key'])) array_push($keys, $optionarray['key']);
						}
						$order = true;
						if (count($keys) == count($options))
						{
							// all keys are numeric
							natsort($keys);
							$keys = array_values($keys);
							for ($i = 1; $i < count($keys); $i++)
							{
								if ($keys[$i] <= $keys[$i-1]) $order = false;
							}
						}
						else
						{
							$order = false;
						}
						if ($order)
						{
							$result['relations'] = array(
								array('key' => 'lt', 'value' => $GLOBALS['TL_LANG']['tl_member']['rellt']), 
								array('key' => 'leq', 'value' => $GLOBALS['TL_LANG']['tl_member']['relleq']), 
								array('key' => 'eq', 'value' => $GLOBALS['TL_LANG']['tl_member']['releq']), 
								array('key' => 'neq', 'value' => $GLOBALS['TL_LANG']['tl_member']['relneq']),
								array('key' => 'geq', 'value' => $GLOBALS['TL_LANG']['tl_member']['relgeq']), 
								array('key' => 'gt', 'value' => $GLOBALS['TL_LANG']['tl_member']['relgt']) 
							);
						}
						else
						{
							$result['relations'] = array(
								array('key' => 'eq', 'value' => $GLOBALS['TL_LANG']['tl_member']['releq']), 
								array('key' => 'neq', 'value' => $GLOBALS['TL_LANG']['tl_member']['relneq'])
							);
						}
						break;
					case 'checkbox':
						$result['relations'] = array(
							array('key' => 'checked', 'value' => $GLOBALS['TL_LANG']['tl_member']['checked']), 
							array('key' => 'unchecked', 'value' => $GLOBALS['TL_LANG']['tl_member']['unchecked']),
						);
						$result['field'] = array();
						break;
					case 'checkboxWizard':
					case 'fileTree':
					case 'text':
					default:
						if (strcmp($field['eval']['rgxp'], 'date') == 0)
						{
							$result['relations'] = array(
								array('key' => 'lt', 'value' => $GLOBALS['TL_LANG']['tl_member']['rellt']), 
								array('key' => 'leq', 'value' => $GLOBALS['TL_LANG']['tl_member']['relleq']), 
								array('key' => 'eq', 'value' => $GLOBALS['TL_LANG']['tl_member']['releq']), 
								array('key' => 'neq', 'value' => $GLOBALS['TL_LANG']['tl_member']['relneq']),
								array('key' => 'geq', 'value' => $GLOBALS['TL_LANG']['tl_member']['relgeq']), 
								array('key' => 'gt', 'value' => $GLOBALS['TL_LANG']['tl_member']['relgt']) 
							);
						}
						else
						{
							$result['relations'] = array(
								array('key' => 'eq', 'value' => $GLOBALS['TL_LANG']['tl_member']['releq']), 
								array('key' => 'neq', 'value' => $GLOBALS['TL_LANG']['tl_member']['relneq']),
								array('key' => 'CONTAINS', 'value' => $GLOBALS['TL_LANG']['tl_member']['relcontains'])
							);
						}
						$result['field'] = array('type' => 'text');
						break;
				}
				header('Content-Type: application/json');
				echo json_encode($result);
				exit;
			}
		}
	}
}

?>