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
 * Fields
 */
$GLOBALS['TL_LANG']['tl_settings']['inactivememberfields'] = array('Inactive Member Fields', 'Deactivate member fields which are no longer required.');
$GLOBALS['TL_LANG']['tl_settings']['memberurlparameter'] = array('URL parameter for memberpage calls', 'Enter the name of the URL parameter for memberpage calls. The URL parameter contains the member ID of the requested page, e.g. http://URL_TO_YOUR_MEBMERPAGE?member=ID. In this example the URL parameter is <strong>member</strong>');

/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_settings']['member_legend']    = 'Member settings';

?>