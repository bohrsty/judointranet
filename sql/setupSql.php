<?php
/* ********************************************************************************************
 * Copyright (c) 2011 Nils Bohrs
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this
 * software and associated documentation files (the "Software"), to deal in the Software
 * without restriction, including without limitation the rights to use, copy, modify, merge,
 * publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons
 * to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or
 * substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
 * PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE
 * FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 * 
 * Thirdparty licenses see LICENSE
 * 
 * ********************************************************************************************/

// protect against direct access
if(!defined('SETUPSQL')) {die('Cannot be executed directly! Please use setup.php.');}

/**
 * initMysql() imports the initial mysql statements
 * 
 * @return array array containing the 'returnValue' (true if successful, false otherwise) and 'returnMessage'
 */
function initMysql() {
	
	// prepare return
	$return = array(
			'returnValue' => true,
			'returnMessage' => '',
		);
	
	// read init_mysql.sql
	$initMysqlFile = 'sql/init_mysql.sql';
	if(file_exists($initMysqlFile) && is_readable($initMysqlFile)) {
		
		$fp = fopen($initMysqlFile, 'r');
		$sql = fread($fp, filesize($initMysqlFile));
		fclose($fp);
	} else {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#initMysqlNotExistsReadable');
		return $return;
	}
	
	// execute sql
	if(!Db::executeQuery($sql)) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// return
	return $return;
}


/**
 * versionMysql() calls any existing mysql_XXX function to update the database and migrate the data
 * 
 * @return array array containing the 'returnValue' (true if successful, false otherwise) and 'returnMessage'
 */
function versionMysql() {
	
	// prepare return
	$return = array(
			'returnValue' => true,
			'returnMessage' => '',
		);
	
	// check db version
	if($_SESSION['setup']['dbVersion'] === false) {
		$dbVersion = 1;
	} elseif($_SESSION['setup']['dbVersion'] == '0.0.4') {
		$dbVersion = 4;
	} else {
		$dbVersion = (int)$_SESSION['setup']['dbVersion'];
	}
	
	// walk through version functions
	for($i = $dbVersion; $i <= (int)CONF_GLOBAL_VERSION; $i++) {
		
		// excute functions and return their return value
		if(function_exists('mysql_'.$i)) {
			
			$return = call_user_func('mysql_'.$i);
			// check return 
			if($return['returnValue'] === true) {
				
				if($i >= 3) {
					
					// update version in database
					if(!Db::executeQuery('
						UPDATE `config`
							SET `value`=\''.str_pad($i, 3, '0', STR_PAD_LEFT).'\'
						WHERE `name`=\'global.version\'
					')) {
						$return['returnValue'] = false;
						$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
						return $return;
					}
				}
			} else {
				return $return;
			}
		}
	}
	
	return $return;
}


/* ***************************
 * * mysql version functions *
 * ***************************/

function mysql_1() {
	
	// prepare return
	$return = array(
			'returnValue' => true,
			'returnMessage' => '',
		);
	
	// create table protocol
	if(!Db::executeQuery('
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
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// create table protocol_types
	if(!Db::executeQuery('
		CREATE TABLE IF NOT EXISTS `protocol_types` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
		  `valid` int(11) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// update systemtables
	if(!Db::executeQuery('
		UPDATE `config`
			SET `value` = \'calendar,category,config,defaults,field,fields2presets,group,group2group,inventory,inventory_movement,preset,rights,user,user2group,value,protocol\'
		WHERE `config`.`name` = \'systemtables\'
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// insert default protocol_type
	if(!Db::executeQuery('
		INSERT IGNORE INTO `protocol_types` (`id`, `name`, `valid`)
			VALUES (NULL, \'Default\', \'1\')
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// insert tcme css settings into config
	if(!Db::executeQuery('
		INSERT IGNORE INTO `config` (`name`, `value`, `comment`)
			VALUES (\'tmce.default.css\', \'default\', \'Name of default CSS style (tcme_<tcme.default.css>.css)\')
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// return
	return $return;
}

function mysql_2() {
	
	// prepare return
	$return = array(
			'returnValue' => true,
			'returnMessage' => '',
		);
	
	// alter table protocol
	if(Db::columnType('protocol', 'correctable') != 'TEXT') {
		if(!Db::executeQuery('
			ALTER TABLE `protocol`
				CHANGE `correctable` `correctable` TEXT NOT NULL
		')) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
	}
	
	// create table protocol_correction
	if(!Db::executeQuery('
		CREATE TABLE IF NOT EXISTS `protocol_correction` (
		  `uid` int(11) NOT NULL,
		  `pid` int(11) NOT NULL,
		  `protocol` text COLLATE utf8_unicode_ci NOT NULL,
		  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  `finished` BOOLEAN NOT NULL,
		  `valid` BOOLEAN NOT NULL,
		  PRIMARY KEY (`uid`,`pid`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// update systemtables
	if(!Db::executeQuery('
		UPDATE `config`
			SET `value` = \'calendar,category,config,defaults,field,fields2presets,group,group2group,inventory,inventory_movement,preset,rights,user,user2group,value,protocol,protocol_correction\'
		WHERE `config`.`name` = \'systemtables\'
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// return
	return $return;
}

function mysql_3() {
	
	// prepare return
	$return = array(
			'returnValue' => true,
			'returnMessage' => '',
		);
	
	// create table helpmessages
	if(!Db::executeQuery('
		CREATE TABLE IF NOT EXISTS `helpmessages` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `title` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
		  `message` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// update systemtables
	if(!Db::executeQuery('
		UPDATE `config`
			SET `value` = \'calendar,category,config,defaults,field,fields2presets,group,group2group,inventory,inventory_movement,preset,rights,user,user2group,value,protocol,protocol_correction,helpmessages\'
		WHERE `config`.`name` = \'systemtables\'
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// insert helpmessages
	if(!Db::executeQuery('
		INSERT IGNORE INTO `helpmessages` (`id`, `title`, `message`)
			VALUES (1, \'class.Help#global#title#about\', \'class.Help#global#message#about\')
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// insert helpmessages config
	if(!Db::executeQuery('
		INSERT IGNORE INTO `config` (`name`, `value`, `comment`)
			VALUES
				(\'help.buttonClass\', \'helpButton\', \'\'),
				(\'help.dialogClass\', \'helpDialog\', \'\'),
				(\'help.effect\', \'fade\', \'\'),
				(\'help.effectDuration\', \'500\', \'\'),
				(\'global.version\', \'0.0.4\', \'\')
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// return
	return $return;
}

function mysql_4() {
	
	// prepare return
	$return = array(
			'returnValue' => true,
			'returnMessage' => '',
		);
	
	// insert helpmessages
	if(!Db::executeQuery('
		INSERT IGNORE INTO `helpmessages` (`id`, `title`, `message`)
			VALUES
				(2, \'class.Help#global#title#fieldDate\', \'class.Help#global#message#fieldDate\'),
				(3, \'class.Help#global#title#fieldName\', \'class.Help#global#message#fieldName\'),
				(4, \'class.Help#global#title#fieldShortname\', \'class.Help#global#message#fieldShortname\'),
				(5, \'class.Help#global#title#fieldType\', \'class.Help#global#message#fieldType\'),
				(6, \'class.Help#global#title#fieldContent\', \'class.Help#global#message#fieldContent\'),
				(7, \'class.Help#global#title#fieldSort\', \'class.Help#global#message#fieldSort\'),
				(8, \'class.Help#global#title#fieldIsPublic\', \'class.Help#global#message#fieldIsPublic\'),
				(9, \'class.Help#global#title#calendarNew\', \'class.Help#global#message#calendarNew\'),
				(10, \'class.Help#global#title#calendarListall\', \'class.Help#global#message#calendarListall\'),
				(11, \'class.Help#global#title#calendarListAdmin\', \'class.Help#global#message#calendarListAdmin\'),
				(12, \'class.Help#global#title#delete\', \'class.Help#global#message#delete\'),
				(13, \'class.Help#global#title#calendarListSortlinks\', \'class.Help#global#message#calendarListSortlinks\'),
				(14, \'class.Help#global#title#FieldText\', \'class.Help#global#message#FieldText\'),
				(15, \'class.Help#global#title#Login\', \'class.Help#global#message#Login\'),
				(16, \'class.Help#global#title#FieldCheckbox\', \'class.Help#global#message#FieldCheckbox\'),
				(17, \'class.Help#global#title#FieldDbselect\', \'class.Help#global#message#FieldDbselect\'),
				(18, \'class.Help#global#title#FieldDbhierselect\', \'class.Help#global#message#FieldDbhierselect\')
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// insert systemcontact config
	if(!Db::executeQuery('
		INSERT IGNORE INTO `config` (`name`, `value`, `comment`)
			VALUES
				(\'global.systemcontactEmail\', \'email@adres.se\', \'\'),
				(\'global.systemcontactName\', \'Systembetreuer\', \'\')
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// return
	return $return;
}

function mysql_5() {
	
	// prepare return
	$return = array(
			'returnValue' => true,
			'returnMessage' => '',
		);
	
	// add last_modified column to calendar
	if(!Db::columnExists('calendar', 'last_modified')) {
		if(!Db::executeQuery('
			ALTER TABLE `calendar`
				ADD `last_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
		')) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
	}
	// add modified_by column to calendar
	if(!Db::columnExists('calendar', 'modified_by')) {
		if(!Db::executeQuery('
			ALTER TABLE `calendar`
				ADD `modified_by` INT( 11 ) NOT NULL
		')) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
	}
	
	// add last_modified column to value
	if(!Db::columnExists('value', 'last_modified')) {
		if(!Db::executeQuery('
			ALTER TABLE `value`
				ADD `last_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
		')) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
	}
	// add modified_by column to value
	if(!Db::columnExists('value', 'modified_by')) {
		if(!Db::executeQuery('
			ALTER TABLE `value`
				ADD `modified_by` INT( 11 ) NOT NULL
		')) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
	}
	
	// return
	return $return;
}

function mysql_6() {
	
	// prepare return
	$return = array(
			'returnValue' => true,
			'returnMessage' => '',
		);
	
	// insert usertable config
	if(!Db::executeQuery('
		INSERT IGNORE INTO `config` (`name`, `value`, `comment`) 
			VALUES
				(\'usertableCols.club\', \'\', \'\'),
				(\'usertableCols.contact\', \'\', \'\'),
				(\'usertableCols.judo\', \'\', \'\'),
				(\'usertableCols.judo_belt\', \'\', \'\'),
				(\'usertableCols.location\', \'\', \'\'),
				(\'usertableCols.protocol_types\', \'\', \'\'),
				(\'usertableCols.staff\', \'\', \'\'),
				(\'usertableCols.defaults\', \'\', \'\')
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// return
	return $return;
}

function mysql_7() {
	
	// prepare return
	$return = array(
			'returnValue' => true,
			'returnMessage' => '',
		);
	
	// insert helpmessages
	if(!Db::executeQuery('
		INSERT IGNORE INTO `helpmessages` (`id`, `title`, `message`)
			VALUES
				(\'19\', \'class.Help#global#title#adminUsertableSelect\', \'class.Help#global#message#adminUsertableSelect\'),
				(\'20\', \'class.Help#global#title#adminUsertableTasks\', \'class.Help#global#message#adminUsertableTasks\')
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// return
	return $return;
}

function mysql_8() {
	
	// prepare return
	$return = array(
			'returnValue' => true,
			'returnMessage' => '',
		);
	
	// create table groups
	if(!Db::executeQuery('
		CREATE TABLE IF NOT EXISTS `groups` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
		  `parent` int(11) NOT NULL,
		  `valid` tinyint(1) NOT NULL,
		  `modified_by` int(11) NOT NULL DEFAULT \'0\',
		  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// create table user2groups
	if(!Db::executeQuery('
		CREATE TABLE IF NOT EXISTS `user2groups` (
		  `user_id` int(11) NOT NULL,
		  `group_id` int(11) NOT NULL,
		  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  PRIMARY KEY (`user_id`,`group_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// create table filter
	if(!Db::executeQuery('
		CREATE TABLE IF NOT EXISTS `filter` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
		  `valid` tinyint(1) NOT NULL,
		  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// create table item2filter
	if(!Db::executeQuery('
		CREATE TABLE IF NOT EXISTS `item2filter` (
		  `item_table` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
		  `item_id` int(11) NOT NULL,
		  `filter_id` int(11) NOT NULL,
		  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  PRIMARY KEY (`item_table`,`item_id`,`filter_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// insert admin group
	if(!Db::executeQuery('
		INSERT INTO `groups` (`id`, `name`, `parent`, `valid`, `modified_by`, `last_modified`)
			VALUES (1, \'Admins\', \'-1\', \'1\', \'0\', CURRENT_TIMESTAMP)
		ON DUPLICATE KEY
			UPDATE `name`=\'Admins\',
					`parent`=\'-1\',
					`valid`=\'1\',
					`modified_by`=\'0\',
					`last_modified`=CURRENT_TIMESTAMP
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// add admin user to admin group
	if(!Db::executeQuery('
		INSERT IGNORE INTO `user2groups` (`user_id`, `group_id`, `last_modified`)
			VALUES (\'1\', \'1\', CURRENT_TIMESTAMP)
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// add filter for "all" entries (TODO: translate)
	if(!Db::executeQuery('
		INSERT INTO `filter` (`id`, `name`, `valid`, `last_modified`)
			VALUES (1, \'Alle\', \'1\', CURRENT_TIMESTAMP)
		ON DUPLICATE KEY
			UPDATE `name`=\'Alle\',
					`valid`=\'1\',
					`last_modified`=CURRENT_TIMESTAMP
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	/*
	 * migrate calendar entry with id 1
	 */
	// check if calendar entry "1" exists
	if(Db::rowExists('calendar', 'id', '1')
		&& Db::singleValue('
				SELECT `name`
				FROM `calendar`
				WHERE `id`=1
		') != 'Unix Epoch') {
		
		// get row with id 1
		$firstRow = Db::arrayValue('
				SELECT *
				FROM `calendar`
				WHERE `id`=1
		');
		if(!$firstRow) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
		
		unset($firstRow[0][0]);
		
		if(!Db::executeQuery('
			INSERT INTO `calendar` (`id`, `name`, `shortname`, `date`, `type`, `content`, `preset_id`, `valid`, `last_modified`, `modified_by`)
				VALUES (NULL, \'#?\', \'#?\', \'#?\', \'#?\', \'#?\', #?, #?, \'#?\', #?)
		',
		$firstRow[0])) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
		$newId = Db::$insertId;
		
		// update value and rights table
		// value
		if(!Db::executeQuery('
			UPDATE `value`
				SET `table_id`=#?
			WHERE `table_name`=\'calendar\'
				AND `table_id`=1
		',
		array($newId))) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
		// rights if exists
		if(Db::tableExists('rights')) {
			if(!Db::executeQuery('
				UPDATE `rights`
					SET `table_id`=#?
				WHERE `table_name`=\'calendar\'
					AND `table_id`=1
			',
			array($newId))) {
				$return['returnValue'] = false;
				$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
				return $return;
			}
		}
		
		// update first row
		if(!Db::executeQuery('
			UPDATE `calendar`
				SET 
					`name`=\'Unix Epoch\',
					`shortname`=\'UE\',
					`date`=\'1970-01-01\',
					`type`=\'event\',
					`content`=\'Begin of Unix Time Epoch\',
					`preset_id`=0,
					`valid`=0,
					`last_modified`=CURRENT_TIMESTAMP,
					`modified_by`=0
			WHERE `id`=1
		')) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
	} else {
	
		// add first calendar entry
		if(!Db::executeQuery('
			INSERT IGNORE INTO `calendar` (`id`, `name`, `shortname`, `date`, `type`, `content`, `preset_id`, `valid`, `last_modified`, `modified_by`)
				VALUES (1, \'Unix Epoch\', \'UE\', \'1970-01-01\', \'event\', \'Begin of Unix Time Epoch\', 0, 0, CURRENT_TIMESTAMP, 0)
		')) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;

		}
	}
	
	// put first calendar entry in "all" filter
	if(!Db::executeQuery('
		INSERT IGNORE INTO `item2filter` (`item_table`, `item_id`, `filter_id`, `last_modified`)
			VALUES (\'calendar\', \'1\', \'1\', CURRENT_TIMESTAMP)
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	/*
	 * migrate sortable groups to filter
	 */
	if(Db::tableExists('group')) {
		// get sortable groups as array
		$sortableGroups = Db::arrayValue('
				SELECT *
				FROM `group`
				WHERE `sortable`=1
			',
			MYSQL_ASSOC
		);
	
		if(!is_array($sortableGroups)) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
		// insert into filter
		$sqlIds = array();
		for($i=0; $i<count($sortableGroups); $i++) {
			
			if(!Db::executeQuery('
					INSERT INTO `filter` (`id`, `name`, `valid`, `last_modified`)
						VALUES (NULL, \'#?\', 1, CURRENT_TIMESTAMP)
				',
				array($sortableGroups[$i]['name'])
			)) {
				$return['returnValue'] = false;
				$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
				return $return;
			}
			// save ids
			$sqlIds[$sortableGroups[$i]['id']] = Db::$insertId;
			
			// delete migrated entry from groups
			if(!Db::executeQuery('
					DELETE FROM `group`
					WHERE `id`=#?
				',
				array($sortableGroups[$i]['id'])
			)) {
				$return['returnValue'] = false;
				$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
				return $return;
			}
		}
		
		// get rights for calendar entries and insert into filter
		foreach($sqlIds as $oldGid => $newGid) {
			
			// get rights
			$rights = Db::arrayValue('
					SELECT `table_id`
					FROM `rights`
					WHERE `table_name`=\'calendar\'
						AND `g_id`=#?
				',
				MYSQL_ASSOC,
				array($oldGid)
			);
			if(!is_array($rights)) {
				$return['returnValue'] = false;
				$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
				return $return;
			}
			
			// insert item2filter
			$sqlValues = '';
			for($i=0; $i<count($rights); $i++) {
				$sqlValues .= '(\'calendar\', '.$rights[$i]['table_id'].', '.$newGid.', CURRENT_TIMESTAMP),';
			}
			if(!Db::executeQuery('
				INSERT IGNORE INTO `item2filter` (`item_table`, `item_id`, `filter_id`, `last_modified`)
					VALUES '.substr($sqlValues, 0, -1).'
			')) {
				$return['returnValue'] = false;
				$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
				return $return;
			}
			
			// delete migrated rights entry
			if(!Db::executeQuery('
				DELETE FROM `rights`
				WHERE `table_name`=\'#?\'
					AND `g_id`=#?
			',
			array('calendar', $oldGid))) {
				$return['returnValue'] = false;
				$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
				return $return;
			}
		}
	}
	
	/*
	 * migrate users and groups
	 */
	if(Db::tableExists('group')) {
		// get nonsortable groups from group as array and insert into groups
		$groups = Db::arrayValue('
				SELECT *
				FROM `group`
				WHERE `sortable`=0
					AND `id`<>1
			',
			MYSQL_ASSOC
		);
		if(!is_array($groups)) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
		// insert into groups
		$sqlIds[0] = 0;
		for($i=0; $i<count($groups); $i++) {
			
			if(!Db::executeQuery('
					INSERT INTO `groups` (`id`, `name`, `parent`, `valid`, `modified_by`, `last_modified`)
						VALUES (NULL, \'#?\', 1, 1, 0, CURRENT_TIMESTAMP)
				',
				array($groups[$i]['name'])
			)) {
				$return['returnValue'] = false;
				$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
				return $return;
			}
			// add public and save ids
			$sqlIds[$groups[$i]['id']] = Db::$insertId;
			
			// delete migrated rights entry
			if(!Db::executeQuery('
				DELETE FROM `group`
				WHERE `id`=#?
			',
			array($groups[$i]['id']))) {
				$return['returnValue'] = false;
				$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
				return $return;
			}
	
		}
		
		// save old-group-id => new-group-id in session
		$_SESSION['setup']['groupIds'] = $sqlIds;
		
		// migrate group2group to groups parent
		if(!Db::isTableEmpty('group2group')) {
			
			// get group2group
			$group2group = Db::arrayValue('
					SELECT *
					FROM `group2group`
				',
				MYSQL_ASSOC
			);
			// set parent accordingly
			for($i=0; $i<count($group2group); $i++) {
				
				if(!Db::executeQuery('
					UPDATE `groups`
						SET `parent`=#?
					WHERE `id`=#?
					',
					array($sqlIds[$group2group[$i]['g_id']], $sqlIds[$group2group[$i]['member_id']])
				)) {
					$return['returnValue'] = false;
					$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
					return $return;
				}
			}
		}
	}
	
	// return
	return $return;
}


function mysql_9() {
	
	// prepare return
	$return = array(
			'returnValue' => true,
			'returnMessage' => '',
		);
	
	// create table permission
	if(!Db::executeQuery('
		CREATE TABLE IF NOT EXISTS `permissions` (
		  `item_table` VARCHAR(50) NOT NULL,
		  `item_id` int(11) NOT NULL,
		  `user_id` int(11) NOT NULL DEFAULT \'-1\',
		  `group_id` int(11) NOT NULL DEFAULT \'-1\',
		  `mode` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT \'r\',
		  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  `modified_by` int(11) NOT NULL DEFAULT \'0\',
		  PRIMARY KEY (`item_table`,`item_id`,`user_id`,`group_id`,`mode`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// create table navi
	if(!Db::executeQuery('
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
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// insert public permissions for calendar entry 1
	if(!Db::executeQuery('
		INSERT IGNORE INTO `permissions` (`item_table`, `item_id`, `user_id`, `group_id`, `mode`, `last_modified`, `modified_by`)
			VALUES (\'calendar\', 1, -1, 0, \'r\', CURRENT_TIMESTAMP, 0)
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// insert navi entries
	if(!Db::executeQuery('
		INSERT IGNORE INTO `navi` (`id`, `name`, `parent`, `file_param`, `position`, `show`, `valid`, `last_modified`)
			VALUES 
				(1, \'class.Navi#item#name#mainPage\', \'0\', \'index.php|\', \'0\', \'1\', \'1\', CURRENT_TIMESTAMP),
				(2, \'class.Navi#item#name#mainPage.login\', \'1\', \'index.php|login\', \'0\', \'0\', \'1\', CURRENT_TIMESTAMP),
				(3, \'class.Navi#item#name#mainPage.logout\', \'1\', \'index.php|logout\', \'1\', \'0\', \'1\', CURRENT_TIMESTAMP),
				(4, \'class.Navi#item#name#calendarPage\', \'0\', \'calendar.php|\', \'1\', \'1\', \'1\', CURRENT_TIMESTAMP),
				(5, \'class.Navi#item#name#calendarPage.new\', \'4\', \'calendar.php|new\', \'0\', \'1\', \'1\', CURRENT_TIMESTAMP),
				(6, \'class.Navi#item#name#calendarPage.listall\', \'4\', \'calendar.php|listall\', \'1\', \'1\', \'1\', CURRENT_TIMESTAMP),
				(7, \'class.Navi#item#name#calendarPage.details\', \'4\', \'calendar.php|details\', \'2\', \'0\', \'1\', CURRENT_TIMESTAMP),
				(8, \'class.Navi#item#name#calendarPage.edit\', \'4\', \'calendar.php|edit\', \'3\', \'0\', \'1\', CURRENT_TIMESTAMP),
				(9, \'class.Navi#item#name#calendarPage.delete\', \'4\', \'calendar.php|delete\', \'4\', \'0\', \'1\', CURRENT_TIMESTAMP),
				(10, \'class.Navi#item#name#inventoryPage\', \'0\', \'inventory.php|\', \'2\', \'1\', \'1\', CURRENT_TIMESTAMP),
				(11, \'class.Navi#item#name#inventoryPage.my\', \'10\', \'inventory.php|my\', \'0\', \'1\', \'1\', CURRENT_TIMESTAMP),
				(12, \'class.Navi#item#name#inventoryPage.listall\', \'10\', \'inventory.php|listall\', \'1\', \'1\', \'1\', CURRENT_TIMESTAMP),
				(13, \'class.Navi#item#name#inventoryPage.give\', \'10\', \'inventory.php|give\', \'2\', \'0\', \'1\', CURRENT_TIMESTAMP),
				(14, \'class.Navi#item#name#inventoryPage.take\', \'10\', \'inventory.php|take\', \'3\', \'0\', \'1\', CURRENT_TIMESTAMP),
				(15, \'class.Navi#item#name#inventoryPage.cancel\', \'10\', \'inventory.php|cancel\', \'4\', \'0\', \'1\', CURRENT_TIMESTAMP),
				(16, \'class.Navi#item#name#inventoryPage.details\', \'10\', \'inventory.php|details\', \'5\', \'0\', \'1\', CURRENT_TIMESTAMP),
				(17, \'class.Navi#item#name#inventoryPage.movement\', \'10\', \'inventory.php|movement\', \'6\', \'0\', \'1\', CURRENT_TIMESTAMP),
				(18, \'class.Navi#item#name#announcementPage\', \'0\', \'announcement.php|\', \'3\', \'0\', \'1\', CURRENT_TIMESTAMP),
				(19, \'class.Navi#item#name#announcementPage.new\', \'18\', \'announcement.php|new\', \'0\', \'0\', \'1\', CURRENT_TIMESTAMP),
				(20, \'class.Navi#item#name#announcementPage.edit\', \'18\', \'announcement.php|edit\', \'1\', \'0\', \'1\', CURRENT_TIMESTAMP),
				(21, \'class.Navi#item#name#announcementPage.delete\', \'18\', \'announcement.php|delete\', \'2\', \'0\', \'1\', CURRENT_TIMESTAMP),
				(22, \'class.Navi#item#name#announcementPage.details\', \'18\', \'announcement.php|details\', \'3\', \'0\', \'1\', CURRENT_TIMESTAMP),
				(23, \'class.Navi#item#name#announcementPage.topdf\', \'18\', \'announcement.php|topdf\', \'4\', \'0\', \'1\', CURRENT_TIMESTAMP),
				(24, \'class.Navi#item#name#protocolPage\', \'0\', \'protocol.php|\', \'4\', \'1\', \'1\', CURRENT_TIMESTAMP),
				(25, \'class.Navi#item#name#protocolPage.new\', \'24\', \'protocol.php|new\', \'0\', \'1\', \'1\', CURRENT_TIMESTAMP),
				(26, \'class.Navi#item#name#protocolPage.listall\', \'24\', \'protocol.php|listall\', \'1\', \'1\', \'1\', CURRENT_TIMESTAMP),
				(27, \'class.Navi#item#name#protocolPage.details\', \'24\', \'protocol.php|details\', \'2\', \'0\', \'1\', CURRENT_TIMESTAMP),
				(28, \'class.Navi#item#name#protocolPage.edit\', \'24\', \'protocol.php|edit\', \'3\', \'0\', \'1\', CURRENT_TIMESTAMP),
				(29, \'class.Navi#item#name#protocolPage.show\', \'24\', \'protocol.php|show\', \'4\', \'0\', \'1\', CURRENT_TIMESTAMP),
				(30, \'class.Navi#item#name#protocolPage.topdf\', \'24\', \'protocol.php|topdf\', \'5\', \'0\', \'1\', CURRENT_TIMESTAMP),
				(31, \'class.Navi#item#name#protocolPage.correct\', \'24\', \'protocol.php|correct\', \'6\', \'0\', \'1\', CURRENT_TIMESTAMP),
				(32, \'class.Navi#item#name#protocolPage.delete\', \'24\', \'protocol.php|delete\', \'7\', \'0\', \'1\', CURRENT_TIMESTAMP),
				(33, \'class.Navi#item#name#protocolPage.showdecisions\', \'24\', \'protocol.php|showdecisions\', \'8\', \'1\', \'1\', CURRENT_TIMESTAMP),
				(34, \'class.Navi#item#name#administrationPage\', \'0\', \'administration.php|\', \'5\', \'1\', \'1\', CURRENT_TIMESTAMP),
				(35, \'class.Navi#item#name#administrationPage.field\', \'34\', \'administration.php|field\', \'0\', \'1\', \'1\', CURRENT_TIMESTAMP),
				(36, \'class.Navi#item#name#administrationPage.defaults\', \'34\', \'administration.php|defaults\', \'1\', \'1\', \'1\', CURRENT_TIMESTAMP)
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// insert initial permissions for navi
	if(!Db::executeQuery('
		INSERT IGNORE INTO `permissions` (`item_table`, `item_id`, `user_id`, `group_id`, `mode`, `last_modified`, `modified_by`)
			VALUES
				(\'navi\', 1, -1, 0, \'r\', CURRENT_TIMESTAMP, 0),
				(\'navi\', 2, -1, 0, \'r\', CURRENT_TIMESTAMP, 0),
				(\'navi\', 3, -1, 0, \'r\', CURRENT_TIMESTAMP, 0),
				(\'navi\', 4, -1, 0, \'r\', CURRENT_TIMESTAMP, 0),
				(\'navi\', 6, -1, 0, \'r\', CURRENT_TIMESTAMP, 0),
				(\'navi\', 7, -1, 0, \'r\', CURRENT_TIMESTAMP, 0),
				(\'navi\', 18, -1, 0, \'r\', CURRENT_TIMESTAMP, 0),
				(\'navi\', 22, -1, 0, \'r\', CURRENT_TIMESTAMP, 0),
				(\'navi\', 23, -1, 0, \'r\', CURRENT_TIMESTAMP, 0),
				(\'navi\', 24, -1, 0, \'r\', CURRENT_TIMESTAMP, 0),
				(\'navi\', 26, -1, 0, \'r\', CURRENT_TIMESTAMP, 0),
				(\'navi\', 27, -1, 0, \'r\', CURRENT_TIMESTAMP, 0),
				(\'navi\', 29, -1, 0, \'r\', CURRENT_TIMESTAMP, 0),
				(\'navi\', 30, -1, 0, \'r\', CURRENT_TIMESTAMP, 0),
				(\'navi\', 33, -1, 0, \'r\', CURRENT_TIMESTAMP, 0)
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// update config with new systemtables
	if(!Db::executeQuery('
		UPDATE `config` 
			SET `value` = \'calendar,category,config,defaults,field,fields2presets,group,group2group,inventory,inventory_movement,preset,rights,user,user2group,value,protocol,protocol_correction,helpmessages,user2groups,permissions,navi,item2filter,groups,filter\' 
		WHERE `config`.`name` = \'systemtables\'
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	/*
	 * migrate rights to permissions
	 */
	if(Db::tableExists('rights')) {
		// get group mapping
		$gids = $_SESSION['setup']['groupIds'];
		$gids[1] = 1;
		
		// get config
		$config = parse_ini_file(JIPATH.'/cnf/setup.ini', true);
		$writeGid = 1;
		if(isset($config['writeGid'])) {
			if(isset($gids[$config['writeGid']])) {
				$writeGid = $gids[$config['writeGid']];
			}
		}
		
		if(count($gids) > 0) {
			// calendar
			$calendarRights = Db::arrayValue('
					SELECT `g_id`, `table_id`
					FROM `rights`
					WHERE `table_name`=\'calendar\'
						AND `g_id` IN (#?)
				',
				MYSQL_ASSOC,
				array(implode(',', $gids))
			);
			
			// insert into permissions
			if(count($calendarRights) > 0) {
				$values = '';
				for($i=0; $i<count($calendarRights); $i++) {
					
					// add read permissions for public access and write permission for defined group
					if($calendarRights[$i]['g_id'] == 0) {
						$values .= '(\'calendar\', '.$calendarRights[$i]['table_id'].', -1, 0, \'r\', CURRENT_TIMESTAMP, 0),';
						$values .= '(\'calendar\', '.$calendarRights[$i]['table_id'].', -1, '.$writeGid.', \'w\', CURRENT_TIMESTAMP, 0),';
					} elseif($calendarRights[$i]['g_id'] == 1) {
						$values .= '(\'calendar\', '.$calendarRights[$i]['table_id'].', -1, '.$writeGid.', \'w\', CURRENT_TIMESTAMP, 0),';
					} else { 
						$values .= '(\'calendar\', '.$calendarRights[$i]['table_id'].', -1, '.$gids[$calendarRights[$i]['g_id']].', \'w\', CURRENT_TIMESTAMP, 0),';
					}
				}
				
				if(!Db::executeQuery('
					INSERT IGNORE INTO `permissions` (`item_table`, `item_id`, `user_id`, `group_id`, `mode`, `last_modified`, `modified_by`)
						VALUES '.substr($values, 0, -1).'
				')) {
					$return['returnValue'] = false;
					$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
					return $return;
				}
				
				// delete migrated rights entry
				if(!Db::executeQuery('
					DELETE FROM `rights`
					WHERE `table_name`=\'calendar\'
						AND `g_id` IN (#?)
				',
				array(implode(',', $gids)))) {
					$return['returnValue'] = false;
					$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
					return $return;
				}
			}
			
			// protocol
			$protocolRights = Db::arrayValue('
					SELECT `g_id`, `table_id`
					FROM `rights`
					WHERE `table_name`=\'protocol\'
						AND `g_id` IN (#?)
				',
				MYSQL_ASSOC,
				array(implode(',', $gids))
			);
			
			// insert into permissions
			if(count($protocolRights) > 0) {
				$values = '';
				for($i=0; $i<count($protocolRights); $i++) {
					
					// add read permissions for public access and write permission for defined group
					if($protocolRights[$i]['g_id'] == 0) {
						$values .= '(\'protocol\', '.$protocolRights[$i]['table_id'].', -1, 0, \'r\', CURRENT_TIMESTAMP, 0),';
						$values .= '(\'protocol\', '.$protocolRights[$i]['table_id'].', -1, '.$writeGid.', \'w\', CURRENT_TIMESTAMP, 0),';
					} elseif($calendarRights[$i]['g_id'] == 1) {
						$values .= '(\'protocol\', '.$protocolRights[$i]['table_id'].', -1, '.$writeGid.', \'w\', CURRENT_TIMESTAMP, 0),';
					} else {
						$values .= '(\'protocol\', '.$protocolRights[$i]['table_id'].', -1, '.$gids[$protocolRights[$i]['g_id']].', \'w\', CURRENT_TIMESTAMP, 0),';
					}
				}
				
				if(!Db::executeQuery('
					INSERT IGNORE INTO `permissions` (`item_table`, `item_id`, `user_id`, `group_id`, `mode`, `last_modified`, `modified_by`)
						VALUES '.substr($values, 0, -1).'
				')) {
					$return['returnValue'] = false;
					$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
					return $return;
				}
				
				// delete migrated rights entry
				if(!Db::executeQuery('
					DELETE FROM `rights`
					WHERE `table_name`=\'protocol\'
						AND `g_id` IN (#?)
				',
				array(implode(',', $gids)))) {
					$return['returnValue'] = false;
					$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
					return $return;
				}
			}
			
			// navi
			$navi = Db::arrayValue('
					SELECT `id`, `file_param`
					FROM `navi`
				',
				MYSQL_ASSOC
			);
			
			// read permission for each navi entry
			for($i=0; $i<count($navi); $i++) {
				
				// create md5 hash for file_param
				if(substr($navi[$i]['file_param'], -1, 1) == '|') {
					$md5 = md5(substr($navi[$i]['file_param'], 0, -1));
				} else {
					$md5 = md5($navi[$i]['file_param']);
				}
				$naviRights = Db::arrayValue('
						SELECT `g_id`
						FROM `rights`
						WHERE `table_id`=\'#?\'
							AND `g_id` IN (#?)
					',
					MYSQL_ASSOC,
					array($md5, implode(',', $gids))
				);
				
				// insert into permissions
				if(count($naviRights) > 0) {
					$values = '';
					for($j=0; $j<count($protocolRights); $j++) {
						$values .= '(\'navi\', '.$navi[$i]['id'].', -1, '.$gids[$naviRights[$j]['g_id']].', \'r\', CURRENT_TIMESTAMP, 0),';
					}
					
					if(!Db::executeQuery('
						INSERT INTO `permissions` (`item_table`, `item_id`, `user_id`, `group_id`, `mode`, `last_modified`, `modified_by`)
							VALUES '.substr($values, 0, -1).'
					')) {
						$return['returnValue'] = false;
						$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
						return $return;
					}
					
					// delete migrated rights entry
					if(!Db::executeQuery('
						DELETE FROM `rights`
						WHERE `table_name`=\'navi\'
							AND `g_id` IN (#?)
					',
					array(implode(',', $gids)))) {
						$return['returnValue'] = false;
						$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
						return $return;
					}
				}
			}
		}
		
		// delete not longer used rights entry (admin)
		if(!Db::executeQuery('
			DELETE FROM `rights`
			WHERE `g_id`=1
		',
		array(implode(',', $gids)))) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
	}
	
	// check for not migrated rights
	if(!Db::isTableEmpty('rights')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#notMigratedEntries').'"rights"';
		return $return;
	} else {
		
		// remove not longer needed tables
		// group
		if(Db::tableExists('group')) {
			if(!Db::executeQuery('
				DROP TABLE IF EXISTS `group`
			')) {
				$return['returnValue'] = false;
				$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
				return $return;
			}
		}
			
		// group2group
		if(Db::tableExists('group2group')) {
			if(!Db::executeQuery('
				DROP TABLE IF EXISTS `group2group`
			')) {
				$return['returnValue'] = false;
				$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
				return $return;
			}
		}
			
		// user2group
		if(Db::tableExists('user2group')) {
			if(!Db::executeQuery('
				DROP TABLE IF EXISTS `user2group`
			')) {
				$return['returnValue'] = false;
				$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
				return $return;
			}
		}
			
		// rights
		if(Db::tableExists('rights')) {
			if(!Db::executeQuery('
				DROP TABLE IF EXISTS `rights`
			')) {
				$return['returnValue'] = false;
				$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
				return $return;
			}
		}
	}
	
	// return
	return $return;
}


function mysql_10() {
	
	// prepare return
	$return = array(
			'returnValue' => true,
			'returnMessage' => '',
		);
	
	// alter navi to add required_permission
	if(!Db::columnExists('navi', 'required_permission')) {
		if(!Db::executeQuery('
			ALTER TABLE `navi`
				ADD `required_permission` VARCHAR( 1 ) NOT NULL DEFAULT \'r\' AFTER `valid`
		')) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
	}
	
	// set writable for new and editing navi entries and migrate permissions
	$naviIds = array(5, 8, 9, 13, 14, 19, 20, 21, 25, 28, 31, 32, 35, 36);
	for ($i=0; $i<count($naviIds); $i++) {
		
		if(!Db::executeQuery('
			UPDATE `navi`
				SET `required_permission` = \'w\'
			WHERE `id`=#?
		',
		array($naviIds[$i]))) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
		
		if(!Db::executeQuery('
			UPDATE `permissions`
				SET `mode` = \'w\'
			WHERE `item_table`=\'navi\'
				AND `item_id`=#?
		',
		array($naviIds[$i]))) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
	}
	
	
	// return
	return $return;
}


function mysql_11() {
	
	// prepare return
	$return = array(
			'returnValue' => true,
			'returnMessage' => '',
		);
	
	// make systemlogo configurable
	if(!Db::executeQuery('
		INSERT IGNORE INTO `config` (`name`, `value`, `comment`) 
			VALUES (\'global.systemLogo\', \'logo.png\', \'filesystem path for site logo below img\')
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// return
	return $return;
}


function mysql_12() {
	
	// prepare return
	$return = array(
			'returnValue' => true,
			'returnMessage' => '',
		);
	
	// add email field to user
	if(!Db::columnExists('user', 'email')) {
		if(!Db::executeQuery('
			ALTER TABLE `user`
				ADD `email` VARCHAR( 75 ) NOT NULL AFTER `name`
		')) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
	}
	
	// add last_modified field to user
	if(!Db::columnExists('user', 'last_modified')) {
		if(!Db::executeQuery('
			ALTER TABLE `user`
				ADD `last_modified` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
		')) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
	}
	
	// set email for admin user
	if(!Db::executeQuery('
		UPDATE `user`
			SET `email` = \'root@localhost\'
		WHERE `id`=1
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// introduce demomode (default off)
	if(!Db::executeQuery('
		INSERT IGNORE INTO `config` (`name`, `value`, `comment`)
			VALUES (\'global.systemDemo\', \'0\', \'0 = Demomode off, 1 = Demomode on\')
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// return
	return $return;
}


function mysql_13() {
	
	// prepare return
	$return = array(
			'returnValue' => true,
			'returnMessage' => '',
		);
	
	// create table file
	if(!Db::executeQuery('
		CREATE TABLE IF NOT EXISTS `file` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
		  `file_type` int(11) NOT NULL,
		  `filename` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
		  `content` mediumblob NOT NULL,
		  `cached` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `valid` tinyint(1) NOT NULL,
		  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  `modified_by` int(11) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// create tabel file_type
	if(!Db::executeQuery('
		CREATE TABLE IF NOT EXISTS `file_type` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
		  `mimetype` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
		  `extension` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// add last_modified to protocol
	if(!Db::columnExists('protocol', 'last_modified')) {
		if(!Db::executeQuery('
			ALTER TABLE `protocol`
				ADD `last_modified` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
		')) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
	}
	
	// add first file
	if(!Db::executeQuery('
		INSERT IGNORE INTO `file` (`id`, `name`, `file_type`, `filename`, `content`, `cached`, `valid`, `last_modified`, `modified_by`)
			VALUES (1, \'MIT License\', 1, \'MIT.txt\', 0x436f707972696768742032303131204e696c7320426f6872730a0a0a5065726d697373696f6e20697320686572656279206772616e7465642c2066726565206f66206368617267652c20746f20616e7920706572736f6e206f627461696e696e67206120636f7079206f6620746869730a736f66747761726520616e64206173736f63696174656420646f63756d656e746174696f6e2066696c657320287468652022536f66747761726522292c20746f206465616c20696e2074686520536f6674776172650a776974686f7574207265737472696374696f6e2c20696e636c7564696e6720776974686f7574206c696d69746174696f6e207468652072696768747320746f207573652c20636f70792c206d6f646966792c206d657267652c0a7075626c6973682c20646973747269627574652c207375626c6963656e73652c20616e642f6f722073656c6c20636f70696573206f662074686520536f6674776172652c20616e6420746f207065726d697420706572736f6e730a746f2077686f6d2074686520536f667477617265206973206675726e697368656420746f20646f20736f2c207375626a65637420746f2074686520666f6c6c6f77696e6720636f6e646974696f6e733a0a0a5468652061626f766520636f70797269676874206e6f7469636520616e642074686973207065726d697373696f6e206e6f74696365207368616c6c20626520696e636c7564656420696e20616c6c20636f70696573206f720a7375627374616e7469616c20706f7274696f6e73206f662074686520536f6674776172652e0a0a54484520534f4654574152452049532050524f564944454420224153204953222c20574954484f55542057415252414e5459204f4620414e59204b494e442c2045585052455353204f5220494d504c4945442c0a494e434c5544494e4720425554204e4f54204c494d4954454420544f205448452057415252414e54494553204f46204d45524348414e544142494c4954592c204649544e45535320464f52204120504152544943554c41520a505552504f534520414e44204e4f4e494e4652494e47454d454e542e20494e204e4f204556454e54205348414c4c2054484520415554484f5253204f5220434f5059524947485420484f4c44455253204245204c4941424c450a464f5220414e5920434c41494d2c2044414d41474553204f52204f54484552204c494142494c4954592c205748455448455220494e20414e20414354494f4e204f4620434f4e54524143542c20544f5254204f520a4f54484552574953452c2041524953494e472046524f4d2c204f5554204f46204f5220494e20434f4e4e454354494f4e20574954482054484520534f465457415245204f522054484520555345204f52204f544845520a4445414c494e475320494e2054484520534f4654574152452e0a, NULL, 0, CURRENT_TIMESTAMP, 0)
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// add first file_type
	if(!Db::executeQuery('
		INSERT IGNORE INTO `file_type` (`id`, `name`, `mimetype`, `extension`)
			VALUES
				(1, \'Textdokument\', \'text/plain\', \'txt\'),
				(2, \'PDF-Ddokument\', \'application/pdf\', \'pdf\')
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// move navi entry 34 to position 6
	if(!Db::executeQuery('
		UPDATE `navi`
			SET `position`=\'6\'
		WHERE `id`=34
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// add new navi entries
	if(!Db::executeQuery('
		INSERT IGNORE INTO `navi` (`id`, `name`, `parent`, `file_param`, `position`, `show`, `valid`, `required_permission`, `last_modified`)
			VALUES 
				(37, \'class.Navi#item#name#filePage\', \'0\', \'file.php|\', \'5\', \'1\', \'1\', \'r\', CURRENT_TIMESTAMP),
				(38, \'class.Navi#item#name#filePage.listall\', \'37\', \'file.php|listall\', \'0\', \'1\', \'1\', \'r\', CURRENT_TIMESTAMP),
				(39, \'class.Navi#item#name#filePage.details\', \'37\', \'file.php|details\', \'1\', \'0\', \'1\', \'r\', CURRENT_TIMESTAMP),
				(40, \'class.Navi#item#name#filePage.edit\', \'37\', \'file.php|edit\', \'2\', \'0\', \'1\', \'w\', CURRENT_TIMESTAMP),
				(41, \'class.Navi#item#name#filePage.delete\', \'37\', \'file.php|delete\', \'3\', \'0\', \'1\', \'w\', CURRENT_TIMESTAMP),
				(42, \'class.Navi#item#name#filePage.upload\', \'37\', \'file.php|upload\', \'4\', \'1\', \'1\', \'w\', CURRENT_TIMESTAMP),
				(43, \'class.Navi#item#name#filePage.cached\', \'37\', \'file.php|cached\', \'5\', \'0\', \'1\', \'r\', CURRENT_TIMESTAMP)
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// add permissions for new navi entries
	if(!Db::executeQuery('
		INSERT IGNORE INTO `permissions` (`item_table`, `item_id`, `user_id`, `group_id`, `mode`, `last_modified`, `modified_by`)
			VALUES
				(\'navi\', 37, -1, 0, \'r\', CURRENT_TIMESTAMP, 0),
				(\'navi\', 38, -1, 0, \'r\', CURRENT_TIMESTAMP, 0),
				(\'navi\', 39, -1, 0, \'r\', CURRENT_TIMESTAMP, 0),
				(\'navi\', 43, -1, 0, \'r\', CURRENT_TIMESTAMP, 0)
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// configuration for allowed filetypes
	if(!Db::executeQuery('
		INSERT IGNORE INTO `config` (`name`, `value`, `comment`)
			VALUES (\'file.allowedFileTypes\', \'1\', \'Ids of the allowed file extensions (file_type.id)\')
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// configuration for upload temp directory
	if(!Db::executeQuery('
		INSERT IGNORE INTO `config` (`name`, `value`, `comment`)
			VALUES (\'global.temp\', \'tmp\', \'filesystem path for temporary files (i.e. for upload)\')
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// configuration of maximum file age
	if(!Db::executeQuery('
		INSERT IGNORE INTO `config` (`name`, `value`, `comment`)
			VALUES (\'file.maxCacheAge\', \'30\', \'Days after which the file have to be regenerated even if unchanged\')
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// update systemtables
	if(!Db::executeQuery('
		UPDATE `config`
			SET `value` = \'calendar,category,config,defaults,field,fields2presets,group,group2group,inventory,inventory_movement,preset,rights,user,user2group,value,protocol,protocol_correction,helpmessages,user2groups,permissions,navi,item2filter,groups,filter,file,file_type\'
		WHERE `name` = \'systemtables\';
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// return
	return $return;
}


function mysql_14() {
	
	// prepare return
	$return = array(
			'returnValue' => true,
			'returnMessage' => '',
		);
	
	// configuration of navigation style
	if(!Db::executeQuery('
		INSERT IGNORE INTO `config` (`name`, `value`, `comment`)
			VALUES (\'navi.style\', \'default\', \'setting for the visible style of the navigation\')
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// return
	return $return;
}


function mysql_15() {
	
	// prepare return
	$return = array(
			'returnValue' => true,
			'returnMessage' => '',
		);
	
	// create table files_attached
	if(!Db::executeQuery('
		CREATE TABLE IF NOT EXISTS `files_attached` (
		  `table_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
		  `table_id` int(11) NOT NULL,
		  `file_id` int(11) NOT NULL,
		  PRIMARY KEY (`table_name`,`table_id`,`file_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// insert navi entry for useradmin
	if(!Db::executeQuery('
		INSERT IGNORE INTO `navi` (`id`, `name`, `parent`, `file_param`, `position`, `show`, `valid`, `last_modified`)
			VALUES
				(44, \'class.Navi#item#name#filePage.attach\', \'37\', \'file.php|attach\', \'6\', \'0\', \'1\', CURRENT_TIMESTAMP),
				(45, \'class.Navi#item#name#administrationPage.useradmin\', \'34\', \'administration.php|user\', \'2\', \'1\', \'1\', CURRENT_TIMESTAMP)
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// remove permissions of level 0 navi entries
	if(!Db::executeQuery('
		DELETE FROM `permissions`
		WHERE `item_table`=\'navi\'
			AND (`item_id`=1
			OR `item_id`=4
			OR `item_id`=18
			OR `item_id`=24
			OR `item_id`=37)
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// update systemtables
	if(!Db::executeQuery('
		UPDATE `config`
			SET `value` = \'calendar,category,config,defaults,field,fields2presets,group,group2group,inventory,inventory_movement,preset,rights,user,user2group,value,protocol,protocol_correction,helpmessages,user2groups,permissions,navi,item2filter,groups,filter,file,file_type,files_attached\'
		WHERE `name` = \'systemtables\';
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// return
	return $return;
}


function mysql_16() {
	
	// prepare return
	$return = array(
			'returnValue' => true,
			'returnMessage' => '',
		);
	
	// add field use_draft to preset
	if(!Db::columnExists('preset', 'use_draft')) {
		if(!Db::executeQuery('
			ALTER TABLE `preset`
				ADD `use_draft` BOOLEAN NOT NULL AFTER `filename`
		')) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
	}
	
	// add draft field
	if(!Db::executeQuery('
		INSERT IGNORE INTO `field` (`id`,`name`,`type`,`category`,`config`)
			VALUES
				(-1, \'als Entwurf speichern\', \'checkbox\', 0, \'\')
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// insert draft field into existing value
	$existingValues = Db::arrayValue('
			SELECT DISTINCT `table_name`,`table_id`
			FROM `value`
			WHERE `table_name`=\'calendar\'
		',
		MYSQL_ASSOC
	);
	if(!$existingValues) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// check existance of draft field in value
	$draftValues = Db::singleValue('
			SELECT COUNT(*)
			FROM `value`
			WHERE `table_name`=\'calendar\'
				AND `field_id`=-1
		'
	);
	if($draftValues === false) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	$draftValuesExists = $draftValues > 0;
	
	// insert into value
	if(count($existingValues) > 0 && $draftValuesExists) {
		$values = '';
		for($i=0; $i<count($existingValues); $i++) {
			
			// add field -1 with value 0
			$values .= '(NULL, \'calendar\', '.$existingValues[$i]['table_id'].', -1, 0, 0, CURRENT_TIMESTAMP, 0),';
		}
		
		if(!Db::executeQuery('
			INSERT IGNORE INTO `value` (`id`, `table_name`, `table_id`, `field_id`, `value`, `defaults`, `last_modified`, `modified_by`)
				VALUES '.substr($values, 0, -1).'
		')) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
	}
	
	// return
	return $return;
}


function mysql_17() {
	
	// prepare return
	$return = array(
			'returnValue' => true,
			'returnMessage' => '',
		);
	
	// insert helpmessages
	if(!Db::executeQuery('
		INSERT IGNORE INTO `helpmessages` (`id`, `title`, `message`)
			VALUES
				(21, \'class.Help#global#title#fileListall\', \'class.Help#global#message#fileListall\'),
				(22, \'class.Help#global#title#fileListAdmin\', \'class.Help#global#message#fileListAdmin\'),
				(23, \'class.Help#global#title#fileUpload\', \'class.Help#global#message#fileUpload\'),
				(24, \'class.Help#global#title#fieldFile\', \'class.Help#global#message#fieldFile\'),
				(25, \'class.Help#global#title#protocolListall\', \'class.Help#global#message#protocolListall\'),
				(26, \'class.Help#global#title#protocolListAdmin\', \'class.Help#global#message#protocolListAdmin\'),
				(27, \'class.Help#global#title#fieldAllText\', \'class.Help#global#message#fieldAllText\'),
				(28, \'class.Help#global#title#fieldPreset\', \'class.Help#global#message#fieldPreset\'),
				(29, \'class.Help#global#title#protocolNew\', \'class.Help#global#message#protocolNew\'),
				(30, \'class.Help#global#title#protocolCorrect\', \'class.Help#global#message#protocolCorrect\'),
				(31, \'class.Help#global#title#protocolCorrectable\', \'class.Help#global#message#protocolCorrectable\'),
				(32, \'class.Help#global#title#protocolCorrectors\', \'class.Help#global#message#protocolCorrectors\'),
				(33, \'class.Help#global#title#protocolDecisions\', \'class.Help#global#message#protocolDecisions\'),
				(34, \'class.Help#global#title#protocolDiff\', \'class.Help#global#message#protocolDiff\'),
				(35, \'class.Help#global#title#protocolDifflist\', \'class.Help#global#message#protocolDifflist\')
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// correct protocol structure
	if(Db::columnType('protocol', 'recorder') != 'VARCHAR') {
		if(!Db::executeQuery('
			ALTER TABLE `protocol` CHANGE `recorder` `recorder` VARCHAR( 75 ) NOT NULL
		')) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
	}
	if(Db::columnType('protocol', 'correctable') != 'TEXT') {
		if(!Db::executeQuery('
			ALTER TABLE `protocol`
				CHANGE `correctable` `correctable` TEXT NOT NULL
		')) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
	}
	if(!Db::executeQuery('
		CREATE TABLE IF NOT EXISTS `protocol_correction` (
		  `uid` int(11) NOT NULL,
		  `pid` int(11) NOT NULL,
		  `protocol` text COLLATE utf8_unicode_ci NOT NULL,
		  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  `finished` BOOLEAN NOT NULL,
		  `valid` BOOLEAN NOT NULL,
		  PRIMARY KEY (`uid`,`pid`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	if(!Db::executeQuery('
		UPDATE `config`
			SET `value` = \'calendar,category,config,defaults,field,fields2presets,group,group2group,inventory,inventory_movement,preset,rights,user,user2group,value,protocol,protocol_correction,helpmessages,user2groups,permissions,navi,item2filter,groups,filter,file,file_type,files_attached\'
		WHERE `name` = \'systemtables\';
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// return
	return $return;
}


function mysql_18() {
	
	// prepare return
	$return = array(
			'returnValue' => true,
			'returnMessage' => '',
		);
	
	// insert navi item in administration
	if(!Db::executeQuery('
		INSERT IGNORE INTO `navi` (`id`, `name`, `parent`, `file_param`, `position`, `show`, `valid`, `required_permission`, `last_modified`)
			VALUES 
				(46, \'class.Navi#item#name#administrationPage.club\', \'34\', \'administration.php|club\', \'3\', \'1\', \'1\', \'w\', CURRENT_TIMESTAMP)
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	
	/*
	 * switch club to system table
	 */
	// remove config for user table club
	if(!Db::executeQuery('
		DELETE FROM `config`
			WHERE `name`=\'usertableCols.club\'
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// update systemtables
	if(!Db::executeQuery('
		UPDATE `config`
			SET `value` = \'calendar,category,config,defaults,field,fields2presets,group,group2group,inventory,inventory_movement,preset,rights,user,user2group,value,protocol,protocol_correction,helpmessages,user2groups,permissions,navi,item2filter,groups,filter,file,file_type,files_attached,club\'
		WHERE `name` = \'systemtables\';
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// get usertableCols.* from config
	$usertableCols = Db::arrayValue('
		SELECT * FROM `config` WHERE `name` LIKE \'usertableCols.%\'
		',
		MYSQL_ASSOC);
	if(!is_array($usertableCols)) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	// walk through $usertableCols
	foreach($usertableCols as $usertableCol) {
		
		// split name
		list($temp, $table) = explode('.', $usertableCol['name']);
		
		// prepare array
		$tableConfig = array(
				'cols' => $usertableCol['value'],
				'fk' => array(
						),
				'orderBy' => '',
			);
		// get json
		$tableConfigJson = json_encode($tableConfig);
		
		// insert into config table
		if(!Db::executeQuery('
			INSERT IGNORE INTO `config` (`name`, `value`, `comment`)
				VALUES 
					(\'usertableConfig.'.$table.'\', \''.$tableConfigJson.'\', \'configuration for table '.$table.'\')
		')) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
		
		// delete old config
		if(!Db::executeQuery('
			DELETE FROM `config`
			WHERE `name`=\''.$usertableCol['name'].'\'
		')) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
	}
	
	/*
	 * migrate club entry with id 1
	 */
	// check if club entry "1" exists
	if(Db::rowExists('club', 'id', '1')
		&& Db::singleValue('
				SELECT `name`
				FROM `club`
				WHERE `id`=1
		') != 'Testverein') {
		
		// get row with id 1
		$firstRow = Db::arrayValue('
				SELECT *
				FROM `club`
				WHERE `id`=1
		');
		if(!$firstRow) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
		
		unset($firstRow[0][0]);
		
		if(!Db::executeQuery('
			INSERT INTO `club` (`id`, `number`, `name`, `valid`)
				VALUES (NULL, #?, \'#?\', #?)
		',
		$firstRow[0])) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
		$newId = Db::$insertId;
		
		// update contact and location table
		// contact
		if(!Db::executeQuery('
			UPDATE `contact`
				SET `club_id`=#?
			WHERE `club_id`=1
		',
		array($newId))) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
		// location
		if(!Db::executeQuery('
			UPDATE `location`
				SET `club_id`=#?
			WHERE `club_id`=1
		',
		array($newId))) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
		
		// update first row
		if(!Db::executeQuery('
			UPDATE `club`
				SET 
					`number`=0,
					`name`=\'Testverein\',
					`valid`=0
			WHERE `id`=1
		')) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
	} else {
	
		// add first calendar entry
		if(!Db::executeQuery('
			INSERT IGNORE INTO `club` (`id`, `number`, `name`, `valid`)
				VALUES (1, 0, \'Testverein\', 0)
		')) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;

		}
	}
	
	
	/*
	 * create tables for results
	 */
	// create table result
	if(!Db::executeQuery('
		CREATE TABLE IF NOT EXISTS `result` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `calendar_id` int(11) NOT NULL,
		  `preset_id` INT(11) NOT NULL DEFAULT \'0\',
		  `desc` VARCHAR( 150 ) NOT NULL,
		  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  `modified_by` int(11) NOT NULL,
		  `valid` BOOLEAN NOT NULL DEFAULT TRUE,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// create table standings
	if(!Db::executeQuery('
		CREATE TABLE IF NOT EXISTS `standings` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `result_id` int(11) NOT NULL,
		  `agegroup` varchar(50) NOT NULL,
		  `weightclass` varchar(50) NOT NULL,
		  `name` varchar(100) NOT NULL,
		  `club_id` int(11) NOT NULL,
		  `place` int(2) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// update systemtables
	if(!Db::executeQuery('
		UPDATE `config`
			SET `value` = \'calendar,category,config,defaults,field,fields2presets,group,group2group,inventory,inventory_movement,preset,rights,user,user2group,value,protocol,protocol_correction,helpmessages,user2groups,permissions,navi,item2filter,groups,filter,file,file_type,files_attached,club,result,standings\'
		WHERE `name` = \'systemtables\';
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// insert into result table
	if(!Db::executeQuery('
		INSERT IGNORE INTO `result` (`id`, `calendar_id`, `preset_id`, `last_modified`, `modified_by`, `valid`)
			VALUES 
				(1, 1, 1, CURRENT_TIMESTAMP, 1, TRUE)
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// insert into standings table
	if(!Db::executeQuery('
		INSERT IGNORE INTO `standings` (`id`, `result_id`, `agegroup`, `weightclass`, `name`, `club_id`, `place`)
			VALUES 
				(1, 1, \'Jugend U10\', \'-23,4\', \'Vorname Nachname\', 1, 1)
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	/*
	 * add navi entries for result
	 */
	// move navi entry administration
	if(!Db::executeQuery('
		UPDATE `navi`
			SET `position`=7
		WHERE `parent`=0
			AND `position`=6
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	// insert navi entries
	if(!Db::executeQuery('
		INSERT IGNORE INTO `navi` (`id`, `name`, `parent`, `file_param`, `position`, `show`, `valid`, `last_modified`)
			VALUES 
				(47, \'class.Navi#item#name#resultPage\', \'0\', \'result.php|\', \'6\', \'1\', \'1\', CURRENT_TIMESTAMP),
				(48, \'class.Navi#item#name#resultPage.listall\', \'47\', \'result.php|listall\', \'0\', \'1\', \'1\', CURRENT_TIMESTAMP),
				(49, \'class.Navi#item#name#resultPage.details\', \'47\', \'result.php|details\', \'1\', \'0\', \'1\', CURRENT_TIMESTAMP),
				(50, \'class.Navi#item#name#resultPage.delete\', \'47\', \'result.php|delete\', \'2\', \'0\', \'1\', CURRENT_TIMESTAMP),
				(51, \'class.Navi#item#name#resultPage.new\', \'47\', \'result.php|new\', \'3\', \'0\', \'1\', CURRENT_TIMESTAMP),
				(52, \'class.Navi#item#name#resultPage.list\', \'47\', \'result.php|list\', \'4\', \'0\', \'1\', CURRENT_TIMESTAMP)
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// add permissions for new navi entries
	if(!Db::executeQuery('
		INSERT IGNORE INTO `permissions` (`item_table`, `item_id`, `user_id`, `group_id`, `mode`, `last_modified`, `modified_by`)
			VALUES
				(\'navi\', 47, -1, 0, \'r\', CURRENT_TIMESTAMP, 0),
				(\'navi\', 48, -1, 0, \'r\', CURRENT_TIMESTAMP, 0),
				(\'navi\', 49, -1, 0, \'r\', CURRENT_TIMESTAMP, 0),
				(\'navi\', 52, -1, 0, \'r\', CURRENT_TIMESTAMP, 0)
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	/*
	 * add config
	 */
	if(!Db::executeQuery('
		INSERT IGNORE INTO `config` (`name`, `value`, `comment`)
		VALUES
			(\'result.cityField\', \'{"id":0,"value":""}\', \'Config of field containing the city of a calendar entry\')
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	/*
	 * add navi entries for accounting
	 */
	// move navi entry administration
	if(!Db::executeQuery('
		UPDATE `navi`
			SET `position`=8
		WHERE `parent`=0
			AND `position`=7
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	// insert navi entries
	if(!Db::executeQuery('
		INSERT IGNORE INTO `navi` (`id`, `name`, `parent`, `file_param`, `position`, `show`, `valid`, `last_modified`)
			VALUES 
				(53, \'class.Navi#item#name#accountingPage\', \'0\', \'accounting.php|\', \'7\', \'1\', \'1\', CURRENT_TIMESTAMP),
				(54, \'class.Navi#item#name#accountingPage.dashboard\', \'53\', \'accounting.php|dashboard\', \'0\', \'1\', \'1\', CURRENT_TIMESTAMP)
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	/*
	 * migrate group entry with id 2
	 */
	// check if group entry "2" exists
	if(Db::rowExists('groups', 'id', '2')
		&& Db::singleValue('
				SELECT `name`
				FROM `groups`
				WHERE `id`=2
		') != 'Finanzen') {
		
		// get row with id 2
		$secondRow = Db::arrayValue('
				SELECT *
				FROM `groups`
				WHERE `id`=2
		');
		if(!$secondRow) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
		
		unset($secondRow[0][0]);
		
		if(!Db::executeQuery('
			INSERT IGNORE INTO `groups` (`id`, `name`, `parent`, `valid`, `modified_by`, `last_modified`)
				VALUES (NULL, \'#?\', #?, #?, #?, CURRENT_TIMESTAMP)
		',
		$secondRow[0])) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
		$newId = Db::$insertId;
		
		// update user2groups and permission table
		// user2groups
		if(!Db::executeQuery('
			UPDATE `user2groups`
				SET `group_id`=#?
			WHERE `group_id`=2
		',
		array($newId))) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
		// rights if exists
		if(!Db::executeQuery('
			UPDATE `permissions`
				SET `group_id`=#?
			WHERE `group_id`=2
		',
		array($newId))) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
		
		// update second row
		if(!Db::executeQuery('
			UPDATE `groups`
				SET 
					`name`=\'Finanzen\',
					`parent`=1,
					`valid`=1,
					`last_modified`=CURRENT_TIMESTAMP,
					`modified_by`=0
			WHERE `id`=2
		')) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
	} else {
	
		// add first calendar entry
		if(!Db::executeQuery('
			INSERT IGNORE INTO `groups` (`id`, `name`, `parent`, `valid`, `last_modified`, `modified_by`)
				VALUES (2, \'Finanzen\', 1, 1, CURRENT_TIMESTAMP, 0)
		')) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;

		}
	}
	
	// add permissions for new navi entries
	if(!Db::executeQuery('
		INSERT IGNORE INTO `permissions` (`item_table`, `item_id`, `user_id`, `group_id`, `mode`, `last_modified`, `modified_by`)
			VALUES
				(\'navi\', 53, -1, 2, \'w\', CURRENT_TIMESTAMP, 0),
				(\'navi\', 54, -1, 2, \'w\', CURRENT_TIMESTAMP, 0)
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	
	/*
	 * add table for accounting tasks
	 */
	// check if table exists
	$accountingTasksExists = Db::tableExists('accounting_tasks');
	
	// create table accounting_tasks
	if(!Db::executeQuery('
		CREATE TABLE IF NOT EXISTS `accounting_tasks` (
		  `table_name` varchar(50) NOT NULL,
		  `table_id` int(11) NOT NULL,
		  `state` tinyint(1) NOT NULL DEFAULT \'0\',
		  PRIMARY KEY (`table_name`,`table_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	
	// add result tasks once, if table hasn't exists
	if($accountingTasksExists === false) {
		
		// get result ids
		$resultIds = Db::arrayValue('
				SELECT `id`
				FROM `result`
		',
		MYSQL_ASSOC);
		if(!$resultIds) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
		
		// add tasks
		foreach($resultIds as $resultId) {
			if(!Db::executeQuery('
				INSERT IGNORE INTO `accounting_tasks` (`table_name`, `table_id`, `state`)
				VALUES (\'result\', #?, \'0\')
			',
			array($resultId['id'],))) {
				$return['returnValue'] = false;
				$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
				return $return;
			}
		}
	}
	
	// update systemtables
	if(!Db::executeQuery('
		UPDATE `config`
			SET `value` = \'calendar,category,config,defaults,field,fields2presets,group,group2group,inventory,inventory_movement,preset,rights,user,user2group,value,protocol,protocol_correction,helpmessages,user2groups,permissions,navi,item2filter,groups,filter,file,file_type,files_attached,club,result,standings,accounting_tasks\'
		WHERE `name` = \'systemtables\';
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// insert navi entries
	if(!Db::executeQuery('
		INSERT IGNORE INTO `navi` (`id`, `name`, `parent`, `file_param`, `position`, `show`, `valid`, `last_modified`)
			VALUES
				(55, \'class.Navi#item#name#accountingPage.task\', \'53\', \'accounting.php|task\', \'1\', \'0\', \'1\', CURRENT_TIMESTAMP),
				(56, \'class.Navi#item#name#accountingPage.settings\', \'53\', \'accounting.php|settings\', \'2\', \'1\', \'1\', CURRENT_TIMESTAMP)
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	/*
	 * add config for announcement club field
	 */
	if(!Db::executeQuery('
		INSERT IGNORE INTO `config` (`name`, `value`, `comment`)
		VALUES
			(\'result.clubField\', \'{"id":0,"value":""}\', \'Config of field containing the club of a calendar entry\')
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	
	/*
	 * add accounting_costs table
	 */
	if(!Db::executeQuery('
		CREATE TABLE IF NOT EXISTS `accounting_costs` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(75) NOT NULL,
		  `type` varchar(75) NOT NULL,
		  `value` varchar(8) NOT NULL DEFAULT \'0,00\',
		  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  `modified_by` int(11) NOT NULL,
		  `valid` BOOLEAN NOT NULL DEFAULT TRUE,
		  PRIMARY KEY (`id`),
		  UNIQUE KEY `UNIQUE` (`name`,`type`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// insert required data
	if(!Db::executeQuery('
		INSERT IGNORE INTO `accounting_costs` (`id`, `name`, `type`, `value`, `last_modified`, `modified_by`, `valid`)
		VALUES
			(1, \'base\', \'payback\', \'0,00\', CURRENT_TIMESTAMP, 0, TRUE),
			(2, \'singleParticipant\', \'payback\', \'0,00\', CURRENT_TIMESTAMP, 0, TRUE),
			(3, \'singleParticipant\', \'payment\', \'0,00\', CURRENT_TIMESTAMP, 0, TRUE)
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// update systemtables
	if(!Db::executeQuery('
		UPDATE `config`
			SET `value` = \'calendar,category,config,defaults,field,fields2presets,group,group2group,inventory,inventory_movement,preset,rights,user,user2group,value,protocol,protocol_correction,helpmessages,user2groups,permissions,navi,item2filter,groups,filter,file,file_type,files_attached,club,result,standings,accounting_tasks,accounting_costs\'
		WHERE `name` = \'systemtables\';
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// replace navi translation
	if(!Db::executeQuery('
		UPDATE `navi`
		SET `name` = REPLACE(`name`, \'class.Navi#item#name#\', \'navi: \')
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
	}
	
	/*
	 * add config for internal api timeout
	 */
	if(!Db::executeQuery('
		INSERT IGNORE INTO `config` (`name`, `value`, `comment`)
		VALUES
			(\'internalApi.timeout\', \'900\', \'Time for internal api signature to expire\')
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	/*
	 * add city to calendar
	 */
	if(!Db::columnExists('calendar', 'city')) {
		if(!Db::executeQuery('
			ALTER TABLE `calendar` ADD `city` VARCHAR(150) NOT NULL AFTER `content`;
		')) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
	}
	
	// set city of first entry
	if(!Db::executeQuery('
		UPDATE `calendar`
		SET `city` = \'London\'
		WHERE `id` = 1
	'
	)) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
	}
	
	/*
	 * change field config format to JSON
	 */
	// get non empty configs
	$configs = Db::arrayValue('
			SELECT `id`,`config`
			FROM `field`
			WHERE `config`<>\'\'
	',
	MYSQL_ASSOC);
	if(!$configs) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// walk through configs
	foreach($configs as $row) {
		
		// check if already JSON
		if(substr($row['config'], 0, 1) == '{') {
			continue;
		} else {
			
			// unserialize old config
			$old = unserialize(stripcslashes($row['config']));
			
			// encode to JSON
			$json = json_encode($old);
			
			// update database
			if(!Db::executeQuery('
				UPDATE `field`
				SET `config` = \'#?\'
				WHERE `id` = #?
			',
			array(
					$json,
					$row['id'],
				))) {
				$return['returnValue'] = false;
				$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			}
		}
	}
	
	
	// return
	return $return;
}


function mysql_19() {
	
	// prepare return
	$return = array(
			'returnValue' => true,
			'returnMessage' => '',
		);
	
	/*
	 * move config usertableConfig.* to tableConfig.*
	 */
	// check if usertableConfig or tableConfig
	$tableNum = Db::singleValue('
		SELECT COUNT(*)
		FROM `config`
		WHERE `name` LIKE \'usertableConfig.%\'
	');
	if($tableNum === false) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	if($tableNum > 0) {
		
		// get table config entries
		$configs = Db::arrayValue('
			SELECT `name`
			FROM `config`
			WHERE `name` LIKE \'usertableConfig.%\'
		',
		MYSQL_NUM);
		if(!$configs) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
		// walk through config and rename
		foreach($configs as $row) {
			
			// get table
			list($name, $table) = explode('.', $row[0]);
			if(!Db::executeQuery('
				UPDATE `config`
				SET `name` = \'tableConfig.#?\'
				WHERE `name` = \'#?\'
			',
			array(
					$table,
					$row[0],
				))) {
				$return['returnValue'] = false;
				$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
				return $return;
			}
			
			// add "fieldType" key to usertable
			// get actual config as json
			$json = Db::singleValue('
				SELECT `value`
				FROM `config`
				WHERE `name`=\'tableConfig.#?\'
			',
			array(
				$table,
			));
			if($json === false) {
				$return['returnValue'] = false;
				$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
				return $return;
			}
			
			// add key
			$jsonConfig = json_decode($json, true);
			if(!isset($jsonConfig['fieldType'])) {
				$jsonConfig['fieldType'] = array();
			}
			
			// update value
			$json = json_encode($jsonConfig);
			if(!Db::executeQuery('
				UPDATE `config`
				SET `value` = \'#?\'
				WHERE `name` = \'tableConfig.#?\'
			',
			array(
					$json,
					$table,
				))) {
				$return['returnValue'] = false;
				$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
				return $return;
			}	
		}
		
		// add "fieldType" key to protocol_types
		// get actual config as json
		$json = Db::singleValue('
				SELECT `value`
				FROM `config`
				WHERE `name`=\'tableConfig.protocol_types\'
			'
		);
		if($json === false) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
		
		// add key
		$jsonConfig = json_decode($json, true);
		if(!isset($jsonConfig['fieldType'])) {
			$jsonConfig['fieldType'] = array();
		}
		
		// update value
		$json = json_encode($jsonConfig);
		if(!Db::executeQuery('
				UPDATE `config`
				SET `value` = \'#?\'
				WHERE `name` = \'tableConfig.protocol_types\'
			',
			array(
				$json,
			))) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
	}
	
	/*
	 * update config for defaults table
	 */
	if(!Db::executeQuery('
		UPDATE `config`
		SET `value` = \'{"cols":"name,category,value,valid","fk":{"category":"SELECT `id`,`name` AS `readable_name` FROM `category` WHERE `valid`=1"},"fieldType":{"value":"textarea"},"orderBy":"ORDER BY `category` ASC"}\',
			`comment` = \'configuration for table defaults\'
		WHERE `name`=\'tableConfig.defaults\'
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	/*
	 * delete navi entry for defaults
	 */
	if(!Db::executeQuery('
		DELETE FROM `navi` WHERE `id` = 36
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	/*
	 * update config for club table and make navi entry invisible
	 */
	// add table config
	if(!Db::executeQuery('
		INSERT IGNORE INTO `config` (`name`, `value`, `comment`)
		VALUES
			(\'tableConfig.club\', \'{"cols":"number,name,valid","fk":[],"fieldType":[],"orderBy":"ORDER BY `number` ASC"}\', \'configuration for table club\')
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	// make navi entry invisible
	if(!Db::executeQuery('
		UPDATE `navi`
		SET `show` = 0
		WHERE `id` = 46
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	/*
	 * add unique index for number
	 */
	// get numbers
	$numbers = Db::arrayValue('
			SELECT `id`,`number`
			FROM `club`
			ORDER BY `id` ASC
	',
	MYSQL_ASSOC);
	if(!$numbers) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	// walk through numbers and find dublicates
	$dublicateIds = array();
	foreach($numbers as $numberEntry) {
		foreach($numbers as $test) {
			if($numberEntry['number'] == $test['number'] && $numberEntry['id'] != $test['id']) {
				$dublicateIds[] = $test['id'];
			}
		}
	}
	// make dublicate ids unique
	$dublicateIds = array_merge(array_unique($dublicateIds, SORT_NUMERIC));
	
	// walk through ids and set number to 99999-x
	$counter = 99999;
	foreach($dublicateIds as $id) {
		
		if(!Db::executeQuery('
			UPDATE `club`
			SET `number` = #?
			WHERE `id` = #?
		',
		array(
				$counter,
				$id,
			))) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
		
		// decrement counter
		$counter--;
	}
	if(!Db::uniqueKeyExists('club', 'unique_number')) {
		
		if(!Db::executeQuery('
			ALTER TABLE `club`
			ADD UNIQUE KEY `unique_number` (`number`);
		')) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
	}
	
	/*
	 * insert navi entry for file download
	 */
	if(!Db::executeQuery('
		INSERT IGNORE INTO `navi` (`id`, `name`, `parent`, `file_param`, `position`, `show`, `valid`, `last_modified`)
			VALUES
				(57, \'navi: filePage.download\', \'37\', \'file.php|download\', \'7\', \'0\', \'1\', CURRENT_TIMESTAMP)
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	/*
	 * add config for system table "file_type"
	 */
	// add column "valid"
	if(!Db::columnExists('file_type', 'valid')) {
		if(!Db::executeQuery('
			ALTER TABLE `file_type`
			ADD `valid` BOOLEAN NOT NULL DEFAULT TRUE
		')) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
	}
	// add table config
	if(!Db::executeQuery('
		INSERT IGNORE INTO `config` (`name`, `value`, `comment`)
		VALUES
			(\'tableConfig.file_type\', \'{"cols":"name,mimetype,extension,valid","fk":[],"fieldType":[],"orderBy":"ORDER BY `name` ASC"}\', \'configuration for table file_type\')
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	/*
	 * insert navi entry for creation of new year
	 */
	if(!Db::executeQuery('
		INSERT IGNORE INTO `navi` (`id`, `name`, `parent`, `file_param`, `position`, `show`, `valid`, `required_permission`, `last_modified`)
			VALUES
				(58, \'navi: administrationPage.newYear\', \'34\', \'administration.php|newyear\', \'1\', \'0\', \'1\', \'w\', CURRENT_TIMESTAMP)
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	
	
	/*
	 * insert global redirect timeout value
	 */
	if(!Db::executeQuery('
		INSERT IGNORE INTO `config` (`name`, `value`, `comment`)
			VALUES (\'global.redirectTimeout\', 3, \'global timeout for automatic javascript redirects (seconds)\')
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	
	/*
	 * add is_team to table "result", change standings that weightclass can be NULL
	 */
	// add is_team
	if(!Db::columnExists('result', 'is_team')) {
		if(!Db::executeQuery('
			ALTER TABLE `result`
			ADD `is_team` BOOLEAN NOT NULL AFTER `desc`
		')) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
	}
	
	// change standings
	if(!Db::executeQuery('
		ALTER TABLE `standings`
		CHANGE `weightclass` `weightclass` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	if(!Db::executeQuery('
		ALTER TABLE `standings`
		CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// add accounting costs settings for team participant
	if(!Db::executeQuery('
		INSERT IGNORE INTO `accounting_costs` (`id`, `name`, `type`, `value`, `last_modified`, `modified_by`, `valid`)
		VALUES
			(4, \'teamParticipant\', \'payback\', \'0,00\', CURRENT_TIMESTAMP, 0, TRUE),
			(5, \'teamParticipant\', \'payment\', \'0,00\', CURRENT_TIMESTAMP, 0, TRUE)
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	/*
	 * insert navi entry for refresh announcement pdf
	 */
	if(!Db::executeQuery('
		INSERT IGNORE INTO `navi` (`id`, `name`, `parent`, `file_param`, `position`, `show`, `valid`, `required_permission`, `last_modified`)
			VALUES
				(59, \'navi: announcementPage.refreshpdf\', \'18\', \'announcement.php|refreshpdf\', \'5\', \'0\', \'1\', \'w\', CURRENT_TIMESTAMP)
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	
	
	
	
	// return
	return $return;
}


function mysql_20() {
	
	// prepare return
	$return = array(
			'returnValue' => true,
			'returnMessage' => '',
		);
	
	/*
	 * add col end_date for date ranges
	 */
	if(!Db::columnExists('calendar', 'end_date')) {
		if(!Db::executeQuery('
			ALTER TABLE `calendar`
			ADD `end_date` DATE NULL DEFAULT NULL AFTER `date`
		')) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
	}
	
	/*
	 * insert navi entry for calendar page
	 */
	if(!Db::executeQuery('
		INSERT IGNORE INTO `navi` (`id`, `name`, `parent`, `file_param`, `position`, `show`, `valid`, `required_permission`, `last_modified`)
			VALUES
				(60, \'navi: calendarPage.calendar\', \'4\', \'calendar.php|calendar\', \'5\', \'1\', \'1\', \'r\', CURRENT_TIMESTAMP)
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	/*
	 * insert navi entry for calendar page
	 */
	if(!Db::executeQuery('
		INSERT IGNORE INTO `navi` (`id`, `name`, `parent`, `file_param`, `position`, `show`, `valid`, `required_permission`, `last_modified`)
			VALUES
				(61, \'navi: resultPage.accounting\', \'47\', \'result.php|accounting\', \'5\', \'0\', \'1\', \'r\', CURRENT_TIMESTAMP)
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	/*
	 * add color
	 */
	if(!Db::columnExists('calendar', 'color')) {
		if(!Db::executeQuery('
			ALTER TABLE `calendar`
			ADD `color` VARCHAR(7) NULL DEFAULT NULL AFTER `preset_id`
		')) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
	}
	
	// default colors for simple-color into config
	if(!Db::executeQuery('
		INSERT IGNORE INTO `config` (`name`, `value`, `comment`)
			VALUES (\'calendar.colors\', \'["56bbe5", "e48ed1", "ffac75", "ffe25d", "bfff63", "78daff",]\', \'Colors for calendars color picker (without #)\'),
				(\'calendar.defaultColor\', \'#56bbe5\', \'Default color for calendar (incl. #)\'),
				(\'calendar.defaultExternalColor\', \'#ffac75\', \'Default color for external calendar entries (incl. #)\')
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	/*
	 * add is_external
	 */
	if(!Db::columnExists('calendar', 'is_external')) {
		if(!Db::executeQuery('
			ALTER TABLE `calendar`
			ADD `is_external` BOOLEAN NOT NULL DEFAULT FALSE AFTER `color`
		')) {
			$return['returnValue'] = false;
			$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
			return $return;
		}
	}
	
	
		
	// return
	return $return;
}


function mysql_21() {
	
	// prepare return
	$return = array(
			'returnValue' => true,
			'returnMessage' => '',
		);
	
	// create table protocol
	if(!Db::executeQuery('
		CREATE TABLE IF NOT EXISTS `holiday` (
		  `name` varchar(150) NOT NULL,
		  `date` date NOT NULL,
		  `end_date` date DEFAULT NULL,
		  `year` varchar(4) NOT NULL,
		  `valid` tinyint(1) NOT NULL,
		  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  `modified_by` int(11) NOT NULL,
		  PRIMARY KEY (`name`,`year`),
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// insert test holiday
	if(!Db::executeQuery('
		INSERT IGNORE INTO `holiday`
			(`name`, `date`, `end_date`, `year`, `valid`, `last_modified`, `modified_by`) 
			VALUES (\'Test Holiday\', \'1970-01-01\', \'1970-01-02\', \'1970\', 0, CURRENT_TIMESTAMP, 0)
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	// default holiday country and settings
	if(!Db::executeQuery('
		INSERT IGNORE INTO `config` (`name`, `value`, `comment`)
			VALUES (\'holiday.country\', \'germany\', \'Country to read holiday settings for\'),
				(\'holiday.settings\', \'{"germany":{"holidays":[{"name":"Neujahr","type":"fixed","date":"01.01."},{"name":"Karfreitag","type":"moving","distance":"-2","from":"Ostersonntag"},{"name":"Ostersonntag","type":"function","function":"easter_date"},{"name":"Ostermontag","type":"moving","distance":"+1","from":"Ostersonntag"},{"name":"Tag der Arbeit","type":"fixed","date":"01.05."},{"name":"Christi Himmelfahrt","type":"moving","distance":"+39","from":"Ostersonntag"},{"name":"Pfingstsonntag","type":"moving","distance":"+49","from":"Ostersonntag"},{"name":"Pfingstmontag","type":"moving","distance":"+50","from":"Ostersonntag"},{"name":"Tag der deutschen Einheit","type":"fixed","date":"03.10."},{"name":"1. Weihnachtstag","type":"fixed","date":"25.12."},{"name":"2. Weihnachtstag","type":"fixed","date":"26.12."}],"school holidays":["Winterferien","Oster-\/Frühjahrsferien","Himmelfahrtsferien","Pfingstferien","Sommerferien","Herbstferien","Weihnachtsferien"]}}\', \'Settings for the holidays and school holidays\')
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	/*
	 * insert navi entry for school holiday page
	 */
	if(!Db::executeQuery('
		INSERT IGNORE INTO `navi` (`id`, `name`, `parent`, `file_param`, `position`, `show`, `valid`, `required_permission`, `last_modified`)
			VALUES
				(62, \'navi: administrationPage.schoolholidays\', \'34\', \'administration.php|schoolholidays\', \'2\', \'1\', \'1\', \'w\', CURRENT_TIMESTAMP)
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	/*
	 * make zebra_form csrf feature configurable
	 */
	if(!Db::executeQuery('
		INSERT IGNORE INTO `config` (`name`, `value`, `comment`)
			VALUES (\'global.zebraFormCsrf\', \'1\', \'Enable or disable csrf feature in zebra_forms\')
	')) {
		$return['returnValue'] = false;
		$return['returnMessage'] = lang('setup#initMysql#error#dbQueryFailed').Db::$error.'['.Db::$statement.']';
		return $return;
	}
	
	
	
	
		
	// return
	return $return;
}
	

?>
