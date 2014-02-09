UPDATE `user` SET `email` = 'root@localhost' WHERE `user`.`id` =1;

INSERT IGNORE INTO `config` (`name`, `value`, `comment`) VALUES ('global.systemDemo', '0', '0 = Demomode off; 1 = Demomode on');