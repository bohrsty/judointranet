INSERT IGNORE INTO `file` (`id`, `name`, `file_type`, `filename`, `cached`, `valid`, `last_modified`, `modified_by`)
VALUES (NULL, '', 0, 0, NULL, '', CURRENT_TIMESTAMP, 0);


INSERT IGNORE INTO `file_type` (`id`, `name`, `mimetype`, `extension`)
VALUES (NULL, 'Textdokument', 'text/plain', 'txt');


UPDATE `navi` SET `position` = '6' WHERE `navi`.`id` =34;


INSERT IGNORE INTO `navi` (`id`, `name`, `parent`, `file_param`, `position`, `show`, `valid`, `required_permission`, `last_modified`)
	VALUES 
		(37, 'class.Navi#item#name#filePage',			'0', 'file.php|', '5', '1', '1', 'r', CURRENT_TIMESTAMP),
		(38, 'class.Navi#item#name#filePage.listall',	'37', 'file.php|listall', '0', '1', '1', 'r', CURRENT_TIMESTAMP),
		(39, 'class.Navi#item#name#filePage.details',	'37', 'file.php|details', '1', '0', '1', 'r', CURRENT_TIMESTAMP),
		(40, 'class.Navi#item#name#filePage.edit',		'37', 'file.php|edit', '2', '0', '1', 'w', CURRENT_TIMESTAMP),
		(41, 'class.Navi#item#name#filePage.delete',	'37', 'file.php|delete', '3', '0', '1', 'w', CURRENT_TIMESTAMP),
		(42, 'class.Navi#item#name#filePage.upload',	'37', 'file.php|upload', '4', '1', '1', 'w', CURRENT_TIMESTAMP);


INSERT IGNORE INTO `permissions` (`item_table`, `item_id`, `user_id`, `group_id`, `mode`, `last_modified`, `modified_by`)
	VALUES
		('navi', 37, -1, 0, 'r', CURRENT_TIMESTAMP, 0),
		('navi', 38, -1, 0, 'r', CURRENT_TIMESTAMP, 0),
		('navi', 39, -1, 0, 'r', CURRENT_TIMESTAMP, 0);

INSERT IGNORE INTO `config` (`name`, `value`, `comment`)
	VALUES ('file.allowedFileTypes', '1', 'Ids of the allowed file extensions (file_type.id)');

INSERT IGNORE INTO `config` (`name`, `value`, `comment`)
	VALUES ('global.temp', 'tmp', 'filesystem path for temporary files (i.e. for upload)');

INSERT IGNORE INTO `config` (`name`, `value`, `comment`)
	VALUES ('file.maxCacheAge', '30', 'Days after which the file have to be regenerated even if unchanged');