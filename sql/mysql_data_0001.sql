UPDATE `config` SET `value` = 'calendar,category,config,defaults,field,fields2presets,group,group2group,inventory,inventory_movement,preset,rights,user,user2group,value,protocol' WHERE `config`.`name` = 'systemtables';

INSERT IGNORE INTO `protocol_types` (`id`, `name`, `valid`) VALUES (NULL, 'Default', '1');

INSERT IGNORE INTO `config` (`name`, `value`, `comment`) VALUES ('tmce.default.css', 'default', 'Name of default CSS style (tcme_<tcme.default.css>.css)');