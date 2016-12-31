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
		$result = array();
		
		// add school holidays
		$result = $this->addSchoolHolidays($result);
		
		// add holidays
		$result = $this->addHolidays($result);
		
		// get permitted and filtered ids
		if($this->get('filter') == '') {
			$filterId = false;
		}
		$ids = self::staticGetUser()->permittedItems('calendar', 'r', $this->get('start'), $this->get('end'));
				
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
		$sqlResult = Db::ArrayValue($sql,
			MYSQLI_ASSOC,
			array($mysqlData,));
		if($sqlResult === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// return
		return array_merge($result, $sqlResult);
	}
	
	
	/**
	 * addHolidays($result) adds the holiday as background events to $result
	 * 
	 * @param array $result array containing the result from calendar table
	 * @return array result array with added holidays
	 */
	private function addHolidays($result) {
		
		// prepare calculations
		$start = strtotime($this->get('start'));
		$end = strtotime($this->get('end'));
		
		// get holidays
		$startHolidays = Holiday::getHolidays(date('Y', $start));
		
		// prepare and add background events for start year
		foreach($startHolidays as $name => $timestamp) {
			
			// check if between start and end
			if($timestamp >= $start && $timestamp <= $end) {
				$result[] = array(
						'start' => date('Y-m-d', $timestamp),
						'end' => null,
						'title' => htmlentities($name),
						'rendering' => 'background',
					);
			}
		}
		
		// check end year
		if(date('Y', $start) != date('Y', $end)) {
			
			// get holidays
			$endHolidays = Holiday::getHolidays(date('Y', $end));
			
			// prepare and add background events for end year
			foreach($endHolidays as $name => $timestamp) {
					
				// check if between start and end
				if($timestamp >= $start && $timestamp <= $end) {
					$result[] = array(
							'start' => date('Y-m-d', $timestamp),
							'end' => null,
							'title' => htmlentities($name),
							'rendering' => 'background',
					);
				}
			}
		}
		
		// return
		return $result;
	}
	
	
	/**
	 * addSchoolHolidays($result) adds the school holiday as background events to $result
	 * 
	 * @param array $result array containing the result from calendar table
	 * @return array result array with added school holidays
	 */
	private function addSchoolHolidays($result) {
		
		// prepare dates
		$start = $this->get('start');
		$end = $this->get('end');
		
		// prepare sql
		$sql = '
			SELECT `date` AS `start`, IF(ISNULL(`end_date`), NULL, ADDDATE(`end_date`,1)) AS `end`, `name` AS `title`, TRUE AS `allDay`, \'background\' AS `rendering`
			FROM `holiday`
			WHERE `date` BETWEEN \'#?\' AND \'#?\' 
				OR `end_date` BETWEEN \'#?\' AND  \'#?\'
				OR \'#?\' BETWEEN `date` AND `end_date`
				OR \'#?\' BETWEEN `date` AND `end_date`
		';
		
		$sqlResult = Db::ArrayValue($sql,
				MYSQLI_ASSOC,
				array($start,
						$end,
						$start,
						$end,
						$start,
						$end,
					));
		if($sqlResult === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// return
		return array_merge($result, $sqlResult);
	}
}
?>
