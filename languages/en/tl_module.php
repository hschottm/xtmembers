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
$GLOBALS['TL_LANG']['tl_module']['member_template']      = array('Page layout', 'Please choose a page layout. You can add custom <em>memberpage_</em> layouts to folder <em>templates</em>.');
$GLOBALS['TL_LANG']['tl_module']['memberlist_template']      = array('List layout', 'Please choose a list layout. You can add custom <em>memberlist_</em> layouts to folder <em>templates</em>.');
$GLOBALS['TL_LANG']['tl_module']['memberlist_fields']      = array('Visible fields', 'Please select one or more fields to be visible in the front end.');
$GLOBALS['TL_LANG']['tl_module']['memberlist_sort']      = array('Initial sort order', 'Please select the initial sort order of the memberlist.');
$GLOBALS['TL_LANG']['tl_module']['singlemember']      = array('Member', 'Please select the member whose details should be shown.');
$GLOBALS['TL_LANG']['tl_module']['memberlist_show_searchfield']      = array('Show search field', 'Please choose if a member search field should be shown on top of the member list.');
$GLOBALS['TL_LANG']['tl_module']['show_member_name']      = array('Show member name in title', 'Show the name of a member in the page title if the member details are selected.');
$GLOBALS['TL_LANG']['tl_module']['memberlist_showdetailscolumn']      = array('Extra details column', 'Please choose if an extra column with a link to the member details is shown at the end of every row. The details column will only be shown if a redirect page is given.');
$GLOBALS['TL_LANG']['tl_module']['memberlist_detailscolumn']      = array('Details column', 'Please select the field name of the column that should be used to link to the member details. The column will only be used to link to the member details if a redirect page is given.');

$GLOBALS['TL_LANG']['tl_module']['registration_show_agreement']      = array('Show user agreement', 'Please choose if a user agreement should be shown during the registration process. The registration can be completed only if the user agreement will be accepted.');
$GLOBALS['TL_LANG']['tl_module']['registration_agreement_text']      = array('User agreement', 'Please enter the user agreement. The user agreement will be shown during the registration process.');
$GLOBALS['TL_LANG']['tl_module']['registration_agreement_headline']      = array('User agreement request', 'Please enter the request text to accept the user agreement. This text will be shown near a checkbox to accept the user agreement during the registration process.');

$GLOBALS['TL_LANG']['tl_module']['personaldata_page_editor']      = array('Activate page editor', 'Please choose if members should be able to extend their personal pages with a page editor.');

$GLOBALS['TL_LANG']['tl_module']['reg_select_group']      = '-- Select member group --';

$GLOBALS['TL_LANG']['tl_module']['new_page']      = 'New page';
$GLOBALS['TL_LANG']['tl_module']['content']      = 'Content';
$GLOBALS['TL_LANG']['tl_module']['pageTitle']      = 'Page Title';
$GLOBALS['TL_LANG']['tl_module']['deletePage']      = 'Delete page';
$GLOBALS['TL_LANG']['tl_module']['add']      = 'Add';
$GLOBALS['TL_LANG']['tl_module']['about_person']      = 'About';
$GLOBALS['TL_LANG']['tl_module']['confirmDeletePage']      = 'Are you sure that you want to delete the active page?';
$GLOBALS['TL_LANG']['tl_module']['pagetype']['essay']      = 'Text';
$GLOBALS['TL_LANG']['tl_module']['memberlist_where']       = array('Condition', 'Here you can enter a condition to filter the results (e.g. <em>locked=1</em> or <em>lastname!="Miller"</em>).');

/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_module']['pageeditor_legend']     = 'Page Editor';
$GLOBALS['TL_LANG']['tl_module']['agreement_legend']     = 'User Agreement';

?>