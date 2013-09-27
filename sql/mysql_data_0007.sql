INSERT INTO `groups` (`id`, `name`, `parent`, `valid`, `modified_by`, `last_modified`)
	VALUES (NULL, 'Admins', '-1', '1', '0', CURRENT_TIMESTAMP);


INSERT INTO `user2groups` (`user_id`, `group_id`, `last_modified`)
	VALUES ('1', '1', CURRENT_TIMESTAMP);


INSERT INTO `filter` (`id`, `name`, `valid`, `last_modified`)
	VALUES (NULL, 'Alle', '1', CURRENT_TIMESTAMP);


INSERT INTO `calendar` (`id`, `name`, `shortname`, `date`, `type`, `content`, `preset_id`, `valid`, `last_modified`, `modified_by`)
	VALUES (1, 'Unix Epoch', 'UE', '1970-01-01', 'event', 'Begin of Unix Time Epoch', 0, 0, CURRENT_TIMESTAMP, 0);


INSERT INTO `item2filter` (`item_table`, `item_id`, `filter_id`, `last_modified`)
	VALUES ('calendar', '1', '1', CURRENT_TIMESTAMP);