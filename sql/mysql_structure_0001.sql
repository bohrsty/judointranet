CREATE TABLE IF NOT EXISTS `protocol` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `type` int(11) COLLATE utf8_unicode_ci NOT NULL,
  `location` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
  `member` text COLLATE utf8_unicode_ci NOT NULL,
  `protocol` text COLLATE utf8_unicode_ci NOT NULL,
  `preset_id` int(11) NOT NULL,
  `valid` tinyint(1) NOT NULL,
  `owner` int(11) NOT NULL,
  `correctable` tinyint(1) NOT NULL,
  `recorder` int(11) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `protocol_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
  `valid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;