UPDATE `config` SET `value` = 'calendar,category,config,defaults,field,fields2presets,group,group2group,inventory,inventory_movement,preset,rights,user,user2group,value,protocol,protocol_correction,helpmessages' WHERE `config`.`name` = 'systemtables';

INSERT INTO `bfv_newdb`.`config` (`name`, `value`, `comment`) VALUES ('help.buttonClass', 'helpButton', ''), ('help.dialogClass', 'helpDialog', ''), ('help.effect', 'fade', ''), ('help.effectDuration', '500', ''), ('global.version', '0.0.4', '');
