-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 11. Apr 2012 um 09:18
-- Server Version: 5.5.19
-- PHP-Version: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `bfv_newdb`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `calendar`
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
-- Tabellenstruktur für Tabelle `config`
--

DROP TABLE IF EXISTS `config`;
CREATE TABLE IF NOT EXISTS `config` (
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `comment` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `defaults`
--

DROP TABLE IF EXISTS `defaults`;
CREATE TABLE IF NOT EXISTS `defaults` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `category` int(11) NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `field`
--

DROP TABLE IF EXISTS `field`;
CREATE TABLE IF NOT EXISTS `field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fields2presets`
--

DROP TABLE IF EXISTS `fields2presets`;
CREATE TABLE IF NOT EXISTS `fields2presets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pres_id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `required` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `group`
--

DROP TABLE IF EXISTS `group`;
CREATE TABLE IF NOT EXISTS `group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `sortable` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `group`
--

INSERT INTO `group` (`id`, `name`, `sortable`) VALUES
(1, 'Admins', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `group2group`
--

DROP TABLE IF EXISTS `group2group`;
CREATE TABLE IF NOT EXISTS `group2group` (
  `g_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  PRIMARY KEY (`g_id`,`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `inventory`
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
-- Tabellenstruktur für Tabelle `inventory_movement`
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
-- Tabellenstruktur für Tabelle `preset`
--

DROP TABLE IF EXISTS `preset`;
CREATE TABLE IF NOT EXISTS `preset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `table` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `desc` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rights`
--

DROP TABLE IF EXISTS `rights`;
CREATE TABLE IF NOT EXISTS `rights` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `g_id` int(11) NOT NULL,
  `table_name` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `table_id` bigint(15) NOT NULL,
  `comment` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=24 ;

--
-- Daten für Tabelle `rights`
--

INSERT INTO `rights` (`id`, `g_id`, `table_name`, `table_id`, `comment`) VALUES
(1, 1, 'navi', 4126450689, 'CalendarView'),
(2, 0, 'navi', 2349400854, 'MainView'),
(3, 1, 'navi', 316626287, 'CalendarView|listall'),
(4, 0, 'navi', 447709445, 'MainView|logout'),
(5, 0, 'navi', 2785044012, 'MainView|login'),
(6, 1, 'navi', 1338371484, 'CalendarView|new'),
(7, 1, 'navi', 982147, 'CalendarView|details'),
(8, 1, 'navi', 360902721, 'CalendarView|delete'),
(9, 1, 'navi', 2115932867, 'CalendarView|edit'),
(10, 1, 'navi', 3960320393, 'AnnouncementView'),
(11, 1, 'navi', 4169844043, 'AnnouncementView|listall'),
(12, 1, 'navi', 3704676583, 'AnnouncementView|new'),
(13, 1, 'navi', 3931860135, 'AnnouncementView|details'),
(14, 1, 'navi', 3109695354, 'AnnouncementView|edit'),
(15, 1, 'navi', 2505436613, 'AnnouncementView|delete'),
(16, 1, 'navi', 3652205019, 'InventoryView'),
(17, 1, 'navi', 2615517752, 'InventoryView|listall'),
(18, 1, 'navi', 521760874, 'InventoryView|my'),
(19, 1, 'navi', 3119052612, 'InventoryView|give'),
(20, 1, 'navi', 171651729, 'InventoryView|take'),
(21, 1, 'navi', 2421882413, 'InventoryView|cancel'),
(22, 1, 'navi', 2301889492, 'InventoryView|details'),
(23, 1, 'navi', 2029386500, 'InventoryView|movement');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
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

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `name`, `active`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Administrator', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user2group`
--

DROP TABLE IF EXISTS `user2group`;
CREATE TABLE IF NOT EXISTS `user2group` (
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`group_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `user2group`
--

INSERT INTO `user2group` (`group_id`, `user_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `value`
--

DROP TABLE IF EXISTS `value`;
CREATE TABLE IF NOT EXISTS `value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `table_id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


--
-- Spalte zufuegen
---

ALTER TABLE `value` ADD `defaults` INT( 11 ) NULL DEFAULT NULL 

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
