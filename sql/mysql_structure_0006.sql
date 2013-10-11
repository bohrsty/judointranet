CREATE TABLE IF NOT EXISTS `permissions` (
  `item_table` VARCHAR(50) NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '-1',
  `group_id` int(11) NOT NULL DEFAULT '-1',
  `mode` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'r',
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`item_table`,`item_id`,`user_id`,`group_id`,`mode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `navi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
  `parent` int(11) NOT NULL,
  `file_param` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
  `position` int(3) NOT NULL,
  `show` tinyint(1) NOT NULL,
  `valid` tinyint(1) NOT NULL,
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;