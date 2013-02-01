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
 * @package    memberextensions
 * @license    LGPL
 * @filesource
 */


/**
 * Class TinyMCEPatcher
 *
 * Front end module "personal data".
 * @copyright  Helmut Schottmüller 2008
 * @author     Helmut Schottmüller <helmut.schottmueller@aurealis.de>
 * @package    Controller
 */
class TinyMCEPatcher extends Frontend
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
			$this->import('Environment');
			$tpl->base = $this->Environment->base;
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
			if (file_exists(TL_ROOT . '/plugins/tinyMCE/langs/' . $GLOBALS['TL_LANGUAGE'] . '.js'))
			{
				$tpl->language = $GLOBALS['TL_LANGUAGE'];
			}
			$content = str_replace("</head>", $tpl->parse() . "\n</head>", $content);
		}
		return $content;
	}
}

?>