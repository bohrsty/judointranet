SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Data for table `config`
--

INSERT INTO `config` (`name`, `value`, `comment`) VALUES
('pagesize', '30', 'Default pagesize'),
('systemtables', 'calendar,category,config,defaults,field,fields2presets,group,group2group,inventory,inventory_movement,preset,rights,user,user2group,value', 'Database tables used by system');

-- --------------------------------------------------------

--
-- Data for table `group`
--

INSERT INTO `group` (`id`, `name`, `sortable`) VALUES
(1, 'Admins', 0);

-- --------------------------------------------------------

--
-- Data for table `rights`
--

INSERT INTO `rights` (`id`, `g_id`, `table_name`, `table_id`, `comment`) VALUES
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

INSERT INTO `user` (`id`, `username`, `password`, `name`, `active`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Administrator', 1);

-- --------------------------------------------------------

--
-- Data for table `user2group`
--

INSERT INTO `user2group` (`group_id`, `user_id`) VALUES
(1, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;