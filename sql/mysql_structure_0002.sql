ALTER TABLE `protocol` CHANGE `correctable` `correctable` TEXT NOT NULL;


--
-- Tabellenstruktur für Tabelle `protocol_correction`
--

CREATE TABLE IF NOT EXISTS `protocol_correction` (
  `uid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `protocol` text COLLATE utf8_unicode_ci NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `finished` BOOLEAN NOT NULL,
  `valid` BOOLEAN NOT NULL,
  PRIMARY KEY (`uid`,`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;