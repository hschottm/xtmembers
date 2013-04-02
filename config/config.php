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
 * Back end modules
 */

/**
 * Front end modules
 */
array_insert($GLOBALS['FE_MOD']['user'], 3, array
(
	'memberpage'    => 'ModuleMemberPage'
));

$GLOBALS['xtmembers']['default_memberlist'] = $GLOBALS['FE_MOD']['user']['memberlist'];

$GLOBALS['FE_MOD']['user']['singlemember'] = 'ModuleMemberDetails';
$GLOBALS['FE_MOD']['user']['registration'] = 'ModuleRegistrationExtended';
$GLOBALS['FE_MOD']['user']['personalData'] = "ModulePersonalDataExtended";

/**
 * FRONT END FORM FIELDS
 */

/**
 * Register hook functions
 */
$GLOBALS['TL_HOOKS']['outputFrontendTemplate'][] = array('TinyMCEPatcher', 'outputFrontendTemplate');
$GLOBALS['TL_HOOKS']['createNewUser'][] = array('CreatedMember', 'createNewUser');

/**
 * Set the member URL parameter as url keyword
 */
$urlparameter = (strlen($GLOBALS['TL_CONFIG']['memberurlparameter'])) ? $GLOBALS['TL_CONFIG']['memberurlparameter'] : 'member';
$GLOBALS['TL_CONFIG']['urlKeywords'] .= (strlen(trim($GLOBALS['TL_CONFIG']['urlKeywords'])) ? ',' : '') . $urlparameter . ",activepage";

?>