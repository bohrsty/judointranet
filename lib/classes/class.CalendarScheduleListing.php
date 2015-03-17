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
 * class CalendarScheduleListing implements the data handling of the schedule listing
 * from the database
 */
class CalendarScheduleListing extends Listing implements ListingInterface {
	
	
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
	 * listingAsArray() returns the listing data as array of associative
	 * arrays
	 * 
	 * @return array array of associative arrays to use with template
	 */
	public function listingAsArray() {
		
		// get data from db
		$dates = array_merge($this->getAppointments(), $this->getHolidays(), $this->getSchoolHolidays());
		
		// sort by date
		usort($dates, array($this, 'callbackSortSchedule'));
		
		// prepare data
		$data = array();
		foreach($dates as $date) {
			
			// get month
			$month = strftime('%B', $date['date']);
			// format dates
			$date['date'] = date('d.m.', $date['date']);
			$date['end_date'] = (is_null($date['end_date']) ? null : date('d.m.', $date['end_date']));
			
			// set entry in month order
			$data[$month][] = $date;
		}
		
		// return prepared data
		return $data;
	}
	
	
	/**
	 * listingAsHtml($templatefile, $assign) returns the listing data as HTML string
	 * generated from $templatefile; with the use of the required $assign fields
	 * 
	 * @param string $templatefile filename of the template to generate the listing
	 * @param array $assign array of fields required to be assigned in $templatefile
	 * @return string generated HTML string from $template
	 */
	public function listingAsHtml($templatefile = '', $assign = array()) {
		
		// return empty string
		return '';
	}
	
	
	/**
	 * getAppointments() gets all required appointment data from database and returns it as array
	 * 
	 * @return array array containing all required data
	 */
	private function getAppointments() {

		// prepare dates (this year)
		$start = date('Y-m-d', strtotime('first day of january'));
		$end = date('Y-m-d', strtotime('last day of december'));
		
		// prepare public user
		$publicUser = new User(false);
		$ids = $publicUser->permittedItems('calendar', 'r', $start, $end);
		
		// check if empty result
		$mysqlData = implode(',', $ids);
		if(count($ids) == 0) {
			$mysqlData = 'SELECT FALSE';
		}
		
		// get data from database
		$result = Db::ArrayValue('
				SELECT `c`.`name`, UNIX_TIMESTAMP(`c`.`date`) AS `date`, UNIX_TIMESTAMP(`c`.`end_date`) AS `end_date`, `c`.`city`, `c`.`color`, `is_external`, \'0\' AS `is_holiday`
				FROM `calendar` AS `c`
				WHERE `c`.`valid`=1
					AND `c`.`id` IN (#?)
			',
			MYSQL_ASSOC,
			array($mysqlData,));
		if($result === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// return data
		return $result;
	}
	
	
	/**
	 * getSchoolHolidays() gets all required school holiday data from database and returns it as array
	 * 
	 * @return array array containing all required data
	 */
	private function getSchoolHolidays() {
		
		// prepare dates (this year)
		$start = date('Y-m-d', strtotime('first day of january'));
		$end = date('Y-m-d', strtotime('last day of december'));
		
		// get data from database
		$result = Db::ArrayValue('
				SELECT `h`.`name`, UNIX_TIMESTAMP(`h`.`date`) AS `date`, UNIX_TIMESTAMP(`h`.`end_date`) AS `end_date`, \'\' AS `city`, \'\' AS `color`, \'0\' AS `is_external`, \'1\' AS `is_holiday`
				FROM `holiday` AS `h`
				WHERE `h`.`valid`=1
					AND (`h`.`date` BETWEEN \'#?\' AND \'#?\' 
						OR `h`.`end_date` BETWEEN \'#?\' AND  \'#?\'
						OR \'#?\' BETWEEN `h`.`date` AND `h`.`end_date`
						OR \'#?\' BETWEEN `h`.`date` AND `h`.`end_date`)
			',
			MYSQL_ASSOC,
			array(	$start,
					$end,
					$start,
					$end,
					$start,
					$end,
			));
		if($result === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// return data
		return $result;
	}
	
	
	/**
	 * getHolidays() gets all required holiday data from configuration and returns it as array
	 * 
	 * @return array array containing all required data
	 */
	private function getHolidays() {
		
		// prepare year
		$year = date('Y');
		
		// get data from config calculated
		$holidays = Holiday::getHolidays($year);
		
		// put in required array structure
		$data = array();
		foreach($holidays as $name => $date) {
			
			$data[] = array(
					'name' => $name,
					'date' => $date,
					'end_date' => null,
					'city' => '',
					'color' => '',
					'is_external' => 0,
					'is_holiday' => 1,
				);
		}
		
		// return data
		return $data;
	}
	
	
	/**
	 * callbackSortSchedule($first, $second) is used to sort the schedule array by date via usort
	 * 
	 * @param $first first array element to compare
	 * @param $second second array element to compare
	 * @return int -1 if $first<$second, 0 if equal, 1 if $first>$second
	 */
	private function callbackSortSchedule($first, $second) {
		
		// compare position
		if($first['date'] < $second['date']) {
			return -1;
		}
		if($first['date'] == $second['date']) {
			return 0;
		}
		if($first['date'] > $second['date']) {
			return 1;
		}
	}
	
}
?>
