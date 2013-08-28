ALTER TABLE `calendar` ADD `last_modified` TIMESTAMP NOT NULL ,
ADD `modified_by` INT( 11 ) NOT NULL;

ALTER TABLE `value` ADD `last_modified` TIMESTAMP NOT NULL ,
ADD `modified_by` INT( 11 ) NOT NULL;