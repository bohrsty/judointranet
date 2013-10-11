INSERT INTO `permissions` (`item_table`, `item_id`, `user_id`, `group_id`, `mode`, `last_modified`, `modified_by`)
	VALUES ('calendar', 1, -1, 0, 'r', CURRENT_TIMESTAMP, 0);


INSERT INTO `navi` (`id`, `name`, `parent`, `file_param`, `position`, `show`, `valid`, `last_modified`)
	VALUES 
		(1, 'class.Navi#item#name#mainPage',				'0', 'index.php|', '0', '1', '1', CURRENT_TIMESTAMP);
		(2, 'class.Navi#item#name#mainPage.login',			'1', 'index.php|login', '0', '0', '1', CURRENT_TIMESTAMP),
		(3, 'class.Navi#item#name#mainPage.logout',			'1', 'index.php|logout', '1', '0', '1', CURRENT_TIMESTAMP),
		(4, 'class.Navi#item#name#calendarPage',			'0', 'calendar.php|', '1', '1', '1', CURRENT_TIMESTAMP),
		(5, 'class.Navi#item#name#calendarPage.new',		'4', 'calendar.php|new', '0', '1', '1', CURRENT_TIMESTAMP),
		(6, 'class.Navi#item#name#calendarPage.listall',	'4', 'calendar.php|listall', '1', '1', '1', CURRENT_TIMESTAMP),
		(7, 'class.Navi#item#name#calendarPage.details',	'4', 'calendar.php|details', '2', '0', '1', CURRENT_TIMESTAMP),
		(8, 'class.Navi#item#name#calendarPage.edit',		'4', 'calendar.php|edit', '3', '0', '1', CURRENT_TIMESTAMP),
		(9, 'class.Navi#item#name#calendarPage.delete',		'4', 'calendar.php|delete', '4', '0', '1', CURRENT_TIMESTAMP);
		(10, 'class.Navi#item#name#inventoryPage',			'0', 'inventory.php|', '2', '1', '1', CURRENT_TIMESTAMP),
		(11, 'class.Navi#item#name#inventoryPage.my',		'10', 'inventory.php|my', '0', '1', '1', CURRENT_TIMESTAMP),
		(12, 'class.Navi#item#name#inventoryPage.listall',	'10', 'inventory.php|listall', '1', '1', '1', CURRENT_TIMESTAMP),
		(13, 'class.Navi#item#name#inventoryPage.give',		'10', 'inventory.php|give', '2', '0', '1', CURRENT_TIMESTAMP),
		(14, 'class.Navi#item#name#inventoryPage.take',		'10', 'inventory.php|take', '3', '0', '1', CURRENT_TIMESTAMP),
		(15, 'class.Navi#item#name#inventoryPage.cancel',	'10', 'inventory.php|cancel', '4', '0', '1', CURRENT_TIMESTAMP),
		(16, 'class.Navi#item#name#inventoryPage.details',	'10', 'inventory.php|details', '5', '0', '1', CURRENT_TIMESTAMP),
		(17, 'class.Navi#item#name#inventoryPage.movement',	'10', 'inventory.php|movement', '6', '0', '1', CURRENT_TIMESTAMP);
		(18, 'class.Navi#item#name#announcementPage',		'0', 'announcement.php|', '3', '0', '1', CURRENT_TIMESTAMP),
		(19, 'class.Navi#item#name#announcementPage.new',	'18', 'announcement.php|new', '0', '0', '1', CURRENT_TIMESTAMP),
		(20, 'class.Navi#item#name#announcementPage.edit',	'18', 'announcement.php|edit', '1', '0', '1', CURRENT_TIMESTAMP),
		(21, 'class.Navi#item#name#announcementPage.delete','18', 'announcement.php|delete', '2', '0', '1', CURRENT_TIMESTAMP),
		(22, 'class.Navi#item#name#announcementPage.details','18', 'announcement.php|details', '3', '0', '1', CURRENT_TIMESTAMP),
		(23, 'class.Navi#item#name#announcementPage.topdf',	'18', 'announcement.php|topfd', '4', '0', '1', CURRENT_TIMESTAMP),
		(24, 'class.Navi#item#name#protocolPage',			'0', 'protocol.php|', '4', '1', '1', CURRENT_TIMESTAMP),
		(25, 'class.Navi#item#name#protocolPage.new',		'24', 'protocol.php|new', '0', '1', '1', CURRENT_TIMESTAMP),
		(26, 'class.Navi#item#name#protocolPage.listall',	'24', 'protocol.php|listall', '1', '1', '1', CURRENT_TIMESTAMP),
		(27, 'class.Navi#item#name#protocolPage.details',	'24', 'protocol.php|details', '2', '0', '1', CURRENT_TIMESTAMP),
		(28, 'class.Navi#item#name#protocolPage.edit',		'24', 'protocol.php|edit', '3', '0', '1', CURRENT_TIMESTAMP),
		(29, 'class.Navi#item#name#protocolPage.show',		'24', 'protocol.php|show', '4', '0', '1', CURRENT_TIMESTAMP),
		(30, 'class.Navi#item#name#protocolPage.topdf',		'24', 'protocol.php|topdf', '5', '0', '1', CURRENT_TIMESTAMP),
		(31, 'class.Navi#item#name#protocolPage.correct',	'24', 'protocol.php|correct', '6', '0', '1', CURRENT_TIMESTAMP),
		(32, 'class.Navi#item#name#protocolPage.delete',	'24', 'protocol.php|delete', '7', '0', '1', CURRENT_TIMESTAMP),
		(33, 'class.Navi#item#name#protocolPage.showdecisions','24', 'protocol.php|showdecisions', '8', '1', '1', CURRENT_TIMESTAMP),
		(34, 'class.Navi#item#name#administrationPage',		'0', 'administration.php|', '5', '1', '1', CURRENT_TIMESTAMP),
		(35, 'class.Navi#item#name#administrationPage.field','34', 'administration.php|field', '0', '1', '1', CURRENT_TIMESTAMP),
		(36, 'class.Navi#item#name#administrationPage.defaults','34', 'administration.php|defaults', '1', '1', '1', CURRENT_TIMESTAMP);


INSERT INTO `permissions` (`item_table`, `item_id`, `user_id`, `group_id`, `mode`, `last_modified`, `modified_by`)
	VALUES
		('navi', 4, -1, 0, 'r', CURRENT_TIMESTAMP, 0),
		('navi', 6, -1, 0, 'r', CURRENT_TIMESTAMP, 0),
		('navi', 7, -1, 0, 'r', CURRENT_TIMESTAMP, 0),
		('navi', 18, -1, 0, 'r', CURRENT_TIMESTAMP, 0),
		('navi', 22, -1, 0, 'r', CURRENT_TIMESTAMP, 0),
		('navi', 23, -1, 0, 'r', CURRENT_TIMESTAMP, 0),
		('navi', 24, -1, 0, 'r', CURRENT_TIMESTAMP, 0),
		('navi', 26, -1, 0, 'r', CURRENT_TIMESTAMP, 0),
		('navi', 27, -1, 0, 'r', CURRENT_TIMESTAMP, 0),
		('navi', 29, -1, 0, 'r', CURRENT_TIMESTAMP, 0),
		('navi', 30, -1, 0, 'r', CURRENT_TIMESTAMP, 0),
		('navi', 33, -1, 0, 'r', CURRENT_TIMESTAMP, 0);


UPDATE `config` 
	SET `value` = 'calendar,category,config,defaults,field,fields2presets,group,group2group,inventory,inventory_movement,preset,rights,user,user2group,value,protocol,protocol_correction,helpmessages,user2groups,permissions,navi,item2filter,groups,filter' 
	WHERE `config`.`name` = 'systemtables';