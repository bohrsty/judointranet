--
-- Structure for table `calendar`
--

DROP TABLE IF EXISTS `calendar`;
CREATE TABLE IF NOT EXISTS `calendar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `shortname` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `type` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `preset_id` int(11) NOT NULL DEFAULT '0',
  `valid` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure for table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `valid` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure for table `club`
--

DROP TABLE IF EXISTS `club`;
CREATE TABLE IF NOT EXISTS `club` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` int(5) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `valid` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure for table `config`
--

DROP TABLE IF EXISTS `config`;
CREATE TABLE IF NOT EXISTS `config` (
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  `comment` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Structure for table `contact`
--

DROP TABLE IF EXISTS `contact`;
CREATE TABLE IF NOT EXISTS `contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
  `club_id` int(11) NOT NULL,
  `valid` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure for table `defaults`
--

DROP TABLE IF EXISTS `defaults`;
CREATE TABLE IF NOT EXISTS `defaults` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `category` int(11) NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  `valid` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure for table `field`
--

DROP TABLE IF EXISTS `field`;
CREATE TABLE IF NOT EXISTS `field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `category` int(11) NOT NULL DEFAULT '0',
  `config` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure for table `fields2presets`
--

DROP TABLE IF EXISTS `fields2presets`;
CREATE TABLE IF NOT EXISTS `fields2presets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pres_id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `required` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure for table `group`
--

DROP TABLE IF EXISTS `group`;
CREATE TABLE IF NOT EXISTS `group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `sortable` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Structure for table `group2group`
--

DROP TABLE IF EXISTS `group2group`;
CREATE TABLE IF NOT EXISTS `group2group` (
  `g_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  PRIMARY KEY (`g_id`,`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure for table `inventory`
--

DROP TABLE IF EXISTS `inventory`;
CREATE TABLE IF NOT EXISTS `inventory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `inventory_no` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `serial_no` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `preset_id` int(11) NOT NULL,
  `valid` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure for table `inventory_movement`
--

DROP TABLE IF EXISTS `inventory_movement`;
CREATE TABLE IF NOT EXISTS `inventory_movement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `inventory_id` int(11) NOT NULL,
  `date_time` datetime NOT NULL,
  `action` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure for table `judo`
--

DROP TABLE IF EXISTS `judo`;
CREATE TABLE IF NOT EXISTS `judo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `weightclass` text COLLATE utf8_unicode_ci NOT NULL,
  `time` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `agegroups` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `valid` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure for table `judo_belt`
--

DROP TABLE IF EXISTS `judo_belt`;
CREATE TABLE IF NOT EXISTS `judo_belt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `valid` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure for table `location`
--

DROP TABLE IF EXISTS `location`;
CREATE TABLE IF NOT EXISTS `location` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `club_id` int(11) NOT NULL,
  `hall` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
  `street` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `zip` int(5) NOT NULL,
  `city` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
  `valid` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure for table `preset`
--

DROP TABLE IF EXISTS `preset`;
CREATE TABLE IF NOT EXISTS `preset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `table` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
  `filename` text COLLATE utf8_unicode_ci NOT NULL,
  `desc` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure for table `rights`
--

DROP TABLE IF EXISTS `rights`;
CREATE TABLE IF NOT EXISTS `rights` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `g_id` int(11) NOT NULL,
  `table_name` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `table_id` varchar(32) NOT NULL,
  `comment` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=30 ;

-- --------------------------------------------------------

--
-- Structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
CREATE TABLE IF NOT EXISTS `staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
  `street` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `zip` int(5) NOT NULL,
  `city` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
  `valid` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Structure for table `user2group`
--

DROP TABLE IF EXISTS `user2group`;
CREATE TABLE IF NOT EXISTS `user2group` (
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`group_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure for table `value`
--

DROP TABLE IF EXISTS `value`;
CREATE TABLE IF NOT EXISTS `value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `table_id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  `defaults` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


-- --------------------------------------------------------
-- --------------------------------------------------------
-- --------------------------------------------------------

--
-- Data for table `config`
--

INSERT IGNORE INTO `config` (`name`, `value`, `comment`) VALUES
('pagesize', '30', 'Default pagesize'),
('systemtables', 'calendar,category,config,defaults,field,fields2presets,group,group2group,inventory,inventory_movement,preset,rights,user,user2group,value', 'Database tables used by system');

-- --------------------------------------------------------

--
-- Data for table `group`
--

INSERT IGNORE INTO `group` (`id`, `name`, `sortable`) VALUES
(1, 'Admins', 0);

-- --------------------------------------------------------

--
-- Data for table `rights`
--

INSERT IGNORE INTO `rights` (`id`, `g_id`, `table_name`, `table_id`, `comment`) VALUES
(NULL, 1, 'navi', MD5('CalendarView'), 'CalendarView'),
(NULL, 0, 'navi', MD5('MainView'), 'MainView'),
(NULL, 1, 'navi', MD5('CalendarView|listall'), 'CalendarView|listall'),
(NULL, 0, 'navi', MD5('MainView|logout'), 'MainView|logout'),
(NULL, 0, 'navi', MD5('MainView|login'), 'MainView|login'),
(NULL, 1, 'navi', MD5('CalendarView|new'), 'CalendarView|new'),
(NULL, 1, 'navi', MD5('CalendarView|details'), 'CalendarView|details'),
(NULL, 1, 'navi', MD5('CalendarView|delete'), 'CalendarView|delete'),
(NULL, 1, 'navi', MD5('CalendarView|edit'), 'CalendarView|edit'),
(NULL, 1, 'navi', MD5('AnnouncementView'), 'AnnouncementView'),
(NULL, 1, 'navi', MD5('AnnouncementView|listall'), 'AnnouncementView|listall'),
(NULL, 1, 'navi', MD5('AnnouncementView|new'), 'AnnouncementView|new'),
(NULL, 1, 'navi', MD5('AnnouncementView|details'), 'AnnouncementView|details'),
(NULL, 1, 'navi', MD5('AnnouncementView|edit'), 'AnnouncementView|edit'),
(NULL, 1, 'navi', MD5('AnnouncementView|delete'), 'AnnouncementView|delete'),
(NULL, 1, 'navi', MD5('InventoryView'), 'InventoryView'),
(NULL, 1, 'navi', MD5('InventoryView|listall'), 'InventoryView|listall'),
(NULL, 1, 'navi', MD5('InventoryView|my'), 'InventoryView|my'),
(NULL, 1, 'navi', MD5('InventoryView|give'), 'InventoryView|give'),
(NULL, 1, 'navi', MD5('InventoryView|take'), 'InventoryView|take'),
(NULL, 1, 'navi', MD5('InventoryView|cancel'), 'InventoryView|cancel'),
(NULL, 1, 'navi', MD5('InventoryView|details'), 'InventoryView|details'),
(NULL, 1, 'navi', MD5('InventoryView|movement'), 'InventoryView|movement'),
(NULL, 1, 'navi', MD5('AnnouncementView|topdf'), 'AnnouncementView|topdf'),
(NULL, 1, 'navi', MD5('AdministrationView'), 'AdministrationView'),
(NULL, 1, 'navi', MD5('AdministrationView|field'), 'AdministrationView|field'),
(NULL, 0, 'navi', MD5('MainView|user'), 'MainView|user'),
(NULL, 1, 'navi', MD5('AdministrationView|defaults'), 'AdministrationView|defaults');

-- --------------------------------------------------------

--
-- Data for table `user`
--

INSERT IGNORE INTO `user` (`id`, `username`, `password`, `name`, `active`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Administrator', 1);

-- --------------------------------------------------------

--
-- Data for table `user2group`
--

INSERT IGNORE INTO `user2group` (`group_id`, `user_id`) VALUES
(1, 1);
