-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 02. Jun 2012 um 18:12
-- Server Version: 5.5.23
-- PHP-Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `bfv_newdb`
--

--
-- Daten für Tabelle `group`
--

INSERT INTO `group` (`id`, `name`, `sortable`) VALUES
(1, 'Admins', 0);

-- --------------------------------------------------------

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
(23, 1, 'navi', 2029386500, 'InventoryView|movement'),
(24, 1, 'navi', 3931860135, 'AnnouncementView|details'),
(25, 1, 'navi', 353262192, 'AnnouncementView|topdf'),
(26, 1, 'navi', 3403310372, 'AdministrationView'),
(27, 1, 'navi', 1221274410, 'AdministrationView|field'),
(28, 0, 'navi', 858116738, 'MainView|user'),
(29, 1, 'navi', 1626061281, 'AdministrationView|defaults');

-- --------------------------------------------------------

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `name`, `active`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Administrator', 1);

-- --------------------------------------------------------

--
-- Daten für Tabelle `user2group`
--

INSERT INTO `user2group` (`group_id`, `user_id`) VALUES
(1, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;