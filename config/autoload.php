<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Xtmembers
 * @link    http://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'Xtmembers',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'Contao\TinyMCEPatcher'             => 'system/modules/xtmembers/classes/TinyMCEPatcher.php',
	'Contao\CreatedMember'              => 'system/modules/xtmembers/classes/CreatedMember.php',

	// Forms
	'Contao\FormAgreement'              => 'system/modules/xtmembers/forms/FormAgreement.php',

	// Modules
	'Contao\ModuleMemberDetails'        => 'system/modules/xtmembers/modules/ModuleMemberDetails.php',
	'Xtmembers\ModuleMemberlist'        => 'system/modules/xtmembers/modules/ModuleMemberlist.php',
	'Contao\ModuleMemberPage'           => 'system/modules/xtmembers/modules/ModuleMemberPage.php',
	'Contao\ModulePersonalDataExtended' => 'system/modules/xtmembers/modules/ModulePersonalDataExtended.php',
	'Contao\ModuleRegistrationExtended' => 'system/modules/xtmembers/modules/ModuleRegistrationExtended.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'form_agreement'               => 'system/modules/xtmembers/templates',
	'memberpage_complete'          => 'system/modules/xtmembers/templates',
	'mod_memberlist_birth'         => 'system/modules/xtmembers/templates',
	'mod_memberlist_simple'        => 'system/modules/xtmembers/templates',
	'mod_memberpage'               => 'system/modules/xtmembers/templates',
	'personaldata_default'         => 'system/modules/xtmembers/templates',
	'tinyMCE'                      => 'system/modules/xtmembers/templates',
));
