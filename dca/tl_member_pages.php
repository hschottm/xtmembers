<?php

/**
 * @copyright  Helmut Schottmüller
 * @author     Helmut Schottmüller <https://github.com/hschottm>
 * @license    LGPL
 */

/**
 * Table tl_log
 */
$GLOBALS['TL_DCA']['tl_member_pages'] = array
(

	// Config
	'config' => array
	(
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary'
			)
		)
	),

	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'title' => array
		(
			'sql'                     => "varchar(100) NOT NULL default ''"
		),
		'position' => array
		(
			'sql'                     => "int(10) NOT NULL default '1'"
		),
		'pagetype' => array
		(
			'sql'                     => "varchar(25) NOT NULL default 'memberpage'"
		),
		'content' => array
		(
			'sql'                     => "text NULL"
		),
		'is_visible' => array
		(
			'sql'                     => "char(1) NOT NULL default ''"
		)
	)
);

?>