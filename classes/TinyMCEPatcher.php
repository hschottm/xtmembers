<?php

/**
 * @copyright  Helmut Schottmüller
 * @author     Helmut Schottmüller <https://github.com/hschottm>
 * @license    LGPL
 */

namespace Contao;

/**
 * Class TinyMCEPatcher
 *
 * Front end module "personal data".
 * @copyright  Helmut Schottmüller 2008
 * @author     Helmut Schottmüller <helmut.schottmueller@aurealis.de>
 * @package    Controller
 */
class TinyMCEPatcher extends \Frontend
{
	public function parseFrontendTemplate($content, $name)
	{
		return $content;
	}
	
	public function outputFrontendTemplate($content, $name)
	{
		if (strpos($content, "mod_personalData") > 0)
		{
			$tpl = new FrontendTemplate('tinyMCE');
			$tpl->base = \Environment::get('base');
			$tpl->brNewLine = $GLOBALS['TL_CONFIG']['pNewLine'] ? false : true;
			$rtefields = array("ctrl_text");
			foreach ($GLOBALS['TL_DCA']['tl_member']['fields'] as $k=>$v)
			{
				if (strcmp($v['eval']['rte'], 'tinyMCE') == 0)
				{
					array_push($rtefields, $k);
				}
			}
			$tpl->rteFields = join($rtefields, ',');
			$tpl->language = 'en';

			// Fallback to English if the user language is not supported
			if (file_exists(TL_ROOT . '/assets/tinymce/langs/' . $GLOBALS['TL_LANGUAGE'] . '.js'))
			{
				$tpl->language = $GLOBALS['TL_LANGUAGE'];
			}
			$content = str_replace("</head>", $tpl->parse() . "\n</head>", $content);
		}
		return $content;
	}
}

?>