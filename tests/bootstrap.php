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

// set $_SERVER array
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['REQUEST_URI'] = '';
$_SERVER['SCRIPT_FILENAME'] = __FILE__;

// setup autoload
require('lib/common.inc.php');

// document root
$_SERVER['DOCUMENT_ROOT'] = JIPATH;

// global test classes

class TestView extends PageView {
	
	function __construct() {
		parent::__construct();
	}
}

class TestDb extends Db {
	
	function __construct() {
		parent::__construct();
	}
	
	
	
	
	/**
	 * getAutoincrement($table) gets the autoincrement value of $table
	 * 
	 * @param string $table name of the table to get the autoincrement value from
	 * @return int autoincrement value of $table
	 */
	public static function getAutoincrement($table) {
		
		// prepare statement
		$sql = '	SELECT `auto_increment`
					FROM `information_schema`.`tables`
					WHERE `table_schema`=\'#?\' AND `table_name`=\'#?\'';
		
		// get value and return
		return self::singleValue($sql, array(self::$config['db']['database'], $table));
		
	}
	
	
	/**
	 * resetAutoincrement($table, $value) sets the autoincrement value to $value for given $table
	 * 
	 * @param string $table name of the table the autoincrement value is set
	 * @param int $value the value autoincrement is set to
	 * @return void
	 */
	public static function resetAutoincrement($table, $value) {
		
		// prepare statement
		$sql = 'ALTER TABLE `#?` AUTO_INCREMENT=#?';
		
		// get value and return
		self::executeQuery($sql, array($table, $value));
		
	}
}


?>
