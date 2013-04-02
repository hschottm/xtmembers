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
$GLOBALS['TL_LANG']['tl_member']['title'] = array('Title', 'Please enter the title/degree.');
$GLOBALS['TL_LANG']['tl_member']['title_extended'] = array('Title (extended)', 'Please enter an extended title/degree.');
$GLOBALS['TL_LANG']['tl_member']['salutation'] = array('Salutation', 'Please enter the salutation to address the member.');
$GLOBALS['TL_LANG']['tl_member']['branch'] = array('Line of business', 'Please enter the line of business.');
$GLOBALS['TL_LANG']['tl_member']['business_connection'] = array('Business connection', 'Please enter the type of business connection.');
$GLOBALS['TL_LANG']['tl_member']['campaign'] = array('Campaign', 'Please enter the campaign.');
$GLOBALS['TL_LANG']['tl_member']['jobtitle'] = array('Job title', 'Please enter the job title.');
$GLOBALS['TL_LANG']['tl_member']['jobtitle_bc'] = array('Job title (Business Card)', 'Please enter the job title from the business card.');
$GLOBALS['TL_LANG']['tl_member']['description'] = array('Position description', 'Please enter an alternative description of your working position.');
$GLOBALS['TL_LANG']['tl_member']['notes'] = array('Notes', 'Please enter your notes.');
$GLOBALS['TL_LANG']['tl_member']['company'] = array('Institution', 'Please enter the name of the institution.');
$GLOBALS['TL_LANG']['tl_member']['officehours'] = array('Office hours', 'Please enter the office hours.');
$GLOBALS['TL_LANG']['tl_member']['street'] = array('Address 1', 'Please enter an address line.');
$GLOBALS['TL_LANG']['tl_member']['address2'] = array('Address 2', 'Please enter an address line.');
$GLOBALS['TL_LANG']['tl_member']['room'] = array('Room', 'Please enter the room number.');
$GLOBALS['TL_LANG']['tl_member']['department'] = array('Department', 'Please enter a department/organisational unit.');
$GLOBALS['TL_LANG']['tl_member']['building'] = array('Building', 'Please enter the name or number of the building.');
$GLOBALS['TL_LANG']['tl_member']['joined'] = array('Joined', 'Please enter the date of joining.');
$GLOBALS['TL_LANG']['tl_member']['resigned'] = array('Resigned', 'Please enter the date of resigning.');
$GLOBALS['TL_LANG']['tl_member']['workscope'] = array('Workscope', 'Please enter a description of the workscope.');
$GLOBALS['TL_LANG']['tl_member']['groupselection'] = array('Group', 'Please select a member group you want to join.');

$GLOBALS['TL_LANG']['tl_member']['postaladdress'] = "Postal address";
$GLOBALS['TL_LANG']['tl_member']['name'] = "Name";
$GLOBALS['TL_LANG']['tl_member']['agreement'] = 'User agreement';
$GLOBALS['TL_LANG']['tl_member']['pageVisible'] = array('Page is visible', 'Please check if the page should be visible to everyone.');

/**
 * For registration mail. Add missing dateAdded field
 */
$GLOBALS['TL_LANG']['tl_member']['dateAdded']       = array('Date added', 'The date the member was added.');

/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_member']['work_legend']     = 'Work Descriptions';
$GLOBALS['TL_LANG']['tl_member']['customer_legend']     = 'Customer relations';

?>