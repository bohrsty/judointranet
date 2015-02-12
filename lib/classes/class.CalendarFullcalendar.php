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

// secure against direct execution
if(!defined("JUDOINTRANET")) {die("Cannot be executed directly! Please use index.php.");}

/**
 * class CalendarFullcalendar implements the data handling of retrieving the calendars from 
 * the database for fullcalendar view
 */
class CalendarFullcalendar extends Object {
	
	
	/*
	 * class-variables
	 */
	
	
	/*
	 * getter/setter
	 */
	
	/*
	 * constructor/destructor
	 */
	public function __construct() {
		
		// setup parent
		parent::__construct();
	}
	
	/*
	 * methods
	 */
	/**
	 * getEvents() retrieves the required data from db
	 */
	public function getEvents() {
		
		// prepare return
		$return = array();
		
		// get permitted and filtered ids
		if($this->get('filter') == '') {
			$filterId = false;
		}
		$ids = self::getUser()->permittedItems('calendar', 'r', $this->get('start'), $this->get('end'));
				
		// check if empty result
		$mysqlData = implode(',', $ids);
		if(count($ids) == 0) {
			$mysqlData = 'SELECT FALSE';
		}
		
		// prepare sql statement
		$sql = '
			SELECT `c`.`id`,`c`.`date` AS `start`, IF(ISNULL(`c`.`end_date`), NULL, ADDDATE(`c`.`end_date`,1)) AS `end`, CONCAT(`c`.`name`,\'\n\',`c`.`city`) AS `title`, TRUE AS `allDay`, `color`
			FROM `calendar` AS `c`
			WHERE `c`.`id` IN (#?)
				AND `c`.`valid`=TRUE
		';
		$result = Db::ArrayValue($sql,
		MYSQL_ASSOC,
		array($mysqlData,));
		if($result === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// return
		return $result;
	}
}
?>
