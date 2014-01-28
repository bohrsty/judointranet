CREATE TABLE IF NOT EXISTS `files_attached` (
  `table_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `table_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  PRIMARY KEY (`table_name`,`table_id`,`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;