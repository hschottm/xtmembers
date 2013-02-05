-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the TYPOlight *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************

-- 
-- Table `tl_member`
-- 

CREATE TABLE `tl_member` (
  `title` varchar(50) NOT NULL default '',
  `description` text NULL,
  `officehours` text NULL,
  `notes` text NULL,
  `room` varchar(14) NOT NULL default '',
  `building` varchar(50) NOT NULL default '',
  `department` varchar(150) NOT NULL default '',
  `joined` varchar(10) NOT NULL default '',
  `address2` varchar(150) NOT NULL default '',
  `resigned` varchar(10) NOT NULL default '',
  `groupselection` int(10) unsigned NOT NULL default '0',
  `agreement` char(1) NOT NULL default '',
  `workscope` text NULL,
  `campaign` varchar(150) NOT NULL default '',
  `business_connection` varchar(150) NOT NULL default '',
  `branch` varchar(100) NOT NULL default '',
  `salutation` varchar(40) NOT NULL default '',
  `title_extended` varchar(50) NOT NULL default '',
  `jobtitle` varchar(50) NOT NULL default '',
  `jobtitle_bc` varchar(50) NOT NULL default '',
  `member_pages` blob NULL
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Table `tl_member_group`
-- 

CREATE TABLE `tl_member_group` (
  `disabledmemberfields` blob NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_module`
-- 

CREATE TABLE `tl_module` (
  `member_template` varchar(32) NOT NULL default '',
  `member_groups` blob NULL,
  `show_searchfield` char(1) NOT NULL default '',
  `show_member_name` char(1) NOT NULL default '',
  `memberlist_template` varchar(32) NOT NULL default '',
  `memberlist_groups` blob NULL,
  `memberlist_jumpTo` smallint(5) unsigned NOT NULL default '0',
  `memberlist_fields` blob NULL,
  `memberlist_showdetailscolumn` char(1) NOT NULL default '',
  `memberlist_detailscolumn` varchar(255) NOT NULL default 'username',
  `memberlist_where` varchar(255) NOT NULL default '',
  `memberlist_filters` blob NULL,
  `singlemember` int(10) unsigned NOT NULL default '0',
  `show_agreement` char(1) NOT NULL default '',
  `allow_groupselection` char(1) NOT NULL default '',
  `groupselection_groups` blob NULL,
  `agreement_headline` varchar(150) NULL default '',
  `agreement_text` text NULL,
  `page_editor` char(1) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



-- 
-- Table `tl_member_pages`
-- 

CREATE TABLE `tl_member_pages` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `position` int(10) NOT NULL default '1',
  `pagetype` varchar(25) NOT NULL default 'memberpage',
  `content` text NULL,
  `is_visible` char(1) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

