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
 * class holiday implements a holiday appointment
 */
class Holiday extends Page {
	
	/*
	 * class-variables
	 */
	private $name;
	private $date;
	private $endDate;
	private $year;
	
	/*
	 * getter/setter
	 */
	public function getName(){
		return $this->name;
	}
	public function setName($name) {
		$this->name = $name;
	}
	public function getDate($format = ''){
		
		// check if format given
		if($format != '') {
			return date($format, strtotime($this->date));
		} else {
			return $this->date;
		}
	}
	public function setDate($date) {
		$this->date = $date;
	}
	public function getEndDate($format = ''){
		
		// check if set
		if(is_null($this->endDate)) {
			return null;
		}
		
		// check if format given
		if($format != '') {
			return date($format, strtotime($this->endDate));
		} else {
			return $this->endDate;
		}
	}
	public function setEndDate($endDate) {
		$this->endDate = $endDate;
	}
	public function getYear(){
		return $this->year;
	}
	public function setYear($year) {
		$this->year = $year;
	}
	
	/*
	 * constructor/destructor
	 */
	public function __construct($arg, $year=null) {
		
		// parent constructor
		parent::__construct();
		
		// if $arg is array, create new entry, else get entry from db by given name and $year
		if(is_array($arg)) {
				
			// set variables to object
			$this->setName($arg['name']);
			$this->setDate($arg['date']);
			$this->setEndDate((isset($arg['endDate']) ? $arg['endDate'] : null));
			$this->setYear($arg['year']);
			$this->setValid($arg['valid']);
		} else {
				
			// get field for given name and $year
			$this->getFromDb($arg, $year);
		}
	}
	
	/*
	 * methods
	 */
	/**
	 * getFromDb gets the appointment for the given holiday name and year
	 * 
	 * @param string $name name of the holiday entry
	 * @param int $year year of the holiday entry
	 * @return void
	 */
	private function getFromDb($name, $year) {
		
		// get values from db
		$result = Db::ArrayValue('
			SELECT `h`.`name`,
				`h`.`date`,
				`h`.`end_date`,
				`h`.`year`,
				`h`.`valid`,
				`h`.`last_modified`,
				`h`.`modified_by`
			FROM `holiday` AS `h`
			WHERE `h`.`name`=\'#?\'
				AND `h`.`year`=\'#?\'
		',
		MYSQL_ASSOC,
		array($name, $year,));
		if($result === false) {
			throw new MysqlErrorException($this, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// set variables to object, if result exists
		if(Db::$num_rows > 0) { 
			$this->setName($result[0]['name']);
			$this->setDate($result[0]['date']);
			$this->setEndDate((!is_null($result[0]['end_date']) ? $result[0]['end_date'] : null));
			$this->setYear($result[0]['year']);
			$this->setValid($result[0]['valid']);
			$this->setLastModified((strtotime($result[0]['last_modified']) < 0 ? 0 : strtotime($result[0]['last_modified'])));
			$this->setModifiedBy($result[0]['modified_by']);
		}
	}
	
	
	/**
	 * update sets the values from given array to the holiday object
	 * 
	 * @param array $holida array containing the new values
	 * @return void
	 */
	public function update($holiday) {
		
		// walk through array
		foreach($holiday as $name => $value) {
			
			// check $name
			if($name == 'date') {
				$this->setDate($value);
			} elseif($name == 'endDate') {
				$this->setEndDate($value);
			} elseif($name == 'name') {
				$this->setName($value);
			} elseif($name == 'year') {
				$this->setYear($value);
			}elseif($name == 'valid') {
				$this->setValid($value);
			}
		}
	}
	
	
	/**
	 * writeDb writes the holiday appointment to db
	 * 
	 * @return void
	 */
	public function writeDb() {
		
		// prepare timestamp
		$timestamp = date('Y-m-d', strtotime($this->getDate()));
		$endTimestamp = (is_null($this->getEndDate()) ? null : date('Y-m-d', strtotime($this->getEndDate())));
		
		// insert into database
		if(!Db::executeQuery('
			INSERT INTO `holiday` (`name`,`date`,`end_date`,`year`,`valid`,`last_modified`,`modified_by`)
			VALUES (\'#?\', \'#?\', '.(is_null($endTimestamp) ? '#?' : '\'#?\'').',\'#?\', #?, CURRENT_TIMESTAMP, #?)
			ON DUPLICATE KEY UPDATE
				`date`=\'#?\',
				`end_date`='.(is_null($endTimestamp) ? '#?' : '\'#?\'').',
				`valid`=#?,
				`last_modified`=CURRENT_TIMESTAMP,
				`modified_by`=#?
			',
			array(// insert
				$this->getName(),
				$timestamp,
				(is_null($endTimestamp) ? 'NULL' : $endTimestamp),
				$this->getYear(),
				$this->getValid(),
				(int)$this->getUser()->get_id(),
				// update
				$timestamp,
				(is_null($endTimestamp) ? 'NULL' : $endTimestamp),
				$this->getValid(),
				(int)$this->getUser()->get_id(),))) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
	}
	
	
	/**
	 * getHolidays($year) returns an array with the calculated holidays for $year
	 * 
	 * @param int $year year to calculate the holidays for
	 * @param bool $school wether to return only the school holidays
	 * @return array array containing the calculated holidays for $year
	 */
	public static function getHolidays($year, $school=false) {
		
		// get holiday country from config
		$country = self::staticGetGc()->get_config('holiday.country');
		// get holiday settings from config
		$allHolidaySettings = json_decode(self::staticGetGc()->get_config('holiday.settings'), true);
		$holidaySettings = $allHolidaySettings['germany'];
		if(isset($allHolidaySettings[$country])) {
			$holidaySettings = $allHolidaySettings[$country];
		}
		
		// check $school
		if($school === true) {
			return $holidaySettings['school holidays'];
		}
		
		// prepare holidays
		$holidays = array();
		// prepare remaining
		$remaining = array();
		
		// walk through settings and calculate
		for($i = 0; $i < count($holidaySettings['holidays']); $i++) {
			
			// check type function
			if($holidaySettings['holidays'][$i]['type'] == 'function') {
				
				// check if callable
				if(is_callable($holidaySettings['holidays'][$i]['function'])) {
					$holidays[$holidaySettings['holidays'][$i]['name']] = call_user_func($holidaySettings['holidays'][$i]['function'], $year);
				} else {
					throw new HolidayFunctionNotCallableExeption();
				}
			}
			
			// check type fixed
			elseif($holidaySettings['holidays'][$i]['type'] == 'fixed') {
				
				// set date with year
				$holidays[$holidaySettings['holidays'][$i]['name']] = strtotime($holidaySettings['holidays'][$i]['date'].$year);
			}
			
			// check type moving (1st run)
			else {
				
				// check if 'from' already calculated
				if(!isset($holidays[$holidaySettings['holidays'][$i]['from']])) {
					$remaining[] = $holidaySettings['holidays'][$i];
				} else {
					
					// get day and month of 'from
					$fromDay = date('j', $holidays[$holidaySettings['holidays'][$i]['from']]);
					$fromMonth = date('n', $holidays[$holidaySettings['holidays'][$i]['from']]);
					
					// set date depending on 'distance'
					$holidays[$holidaySettings['holidays'][$i]['name']] = mktime(0, 0, 0, $fromMonth, $fromDay + $holidaySettings['holidays'][$i]['distance'], $year);
				}
			}
		}
		
		// next run on moving if there are remaining
		$count = count($remaining); 
		if($count > 0) {
			
			while($count > 0) {
				
				// walk through settings and calculate
				for($i = 0; $i < count($remaining); $i++) {
				
					// check if 'from' already calculated
					if(!isset($holidays[$remaining[$i]['from']])) {
						continue;
					} else {
						
						// get day and month of 'from
						$fromDay = date('j', $holidays[$remaining[$i]['from']]);
						$fromMonth = date('n', $holidays[$remaining[$i]['from']]);
						
						// set date depending on 'distance'
						$holidays[$remaining[$i]['name']] = mktime(0, 0, 0, $fromMonth, $fromDay + $remaining[$i]['distance'], $year);
						
						// unset if done
						unset($remaining[$i]);
					}
				}
				
				// reset keys
				$remaining = array_merge($remaining);
				
				// decrement counter
				$count--;
			}
			
			// check if still entries in $remaining
			if(count($remaining) > 0) {
				throw new HolidayCalculationErrorExeption();
			}
		}
		
		// sort holidays
		asort($holidays, SORT_NUMERIC);
		
		// return
		return $holidays;
	}
	
	
	/**
	 * listAllSchoolHolidays($year) gets all school holiday names from config and fills them with
	 * date values from database if exists
	 * 
	 * @param int $year the year to get the school holidays for
	 * @return array array containing the school holiday names and values if exists
	 */
	public static function listAllSchoolHolidays($year) {
		
		// get predefined names from config
		$schoolHolidays = array();
		$holidayConfig = self::getHolidays($year, true);
		foreach($holidayConfig as $holiday) {
			
			$schoolHolidays[$holiday] = array(
					'name' => $holiday,
					'date' => '',
					'endDate' => null,
					'fix' => true,
				);
		}
		
		// get values from database
		$result = Db::ArrayValue('
			SELECT `name`, `date`, `end_date`
			FROM `holiday`
			WHERE `valid`=1
				AND `year`=\'#?\'
		',
		MYSQL_ASSOC,
		array($year,));
		if($result === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// merge data
		foreach($result as $entry) {
			
			// check if exists in $schoolHolidays
			if(array_key_exists($entry['name'], $schoolHolidays)) {
				
				// set dates
				$schoolHolidays[$entry['name']]['date'] = $entry['date'];
				$schoolHolidays[$entry['name']]['endDate'] = $entry['end_date'];
			} else {
				$schoolHolidays[$entry['name']] = array(
					'name' => $entry['name'],
					'date' => $entry['date'],
					'endDate' => $entry['end_date'],
					'fix' => false,
				);
			}
		}
		
		// add user defined fields
		for($i=0; $i<5; $i++) {
			$schoolHolidays[_l('userdefined').'_'.($i+1)] = array(
					'name' => _l('userdefined').'_'.($i+1),
					'date' => '',
					'endDate' => null,
					'fix' => false,
				);
		}
		
		// return
		return $schoolHolidays;
	}
	
	
	/**
	 * deleteEntry() calls the static delete() method
	 */
	public function deleteEntry() {
		
		// call static delete method
		self::delete($this->getName(), $this->getYear());
	}
	
	
	/**
	 * delete($name, $year) deletes the holiday with the given $name and $year
	 * from database
	 * 
	 * @param string $name name of the holiday entry
	 * @param int $year year of the holiday entry
	 * @return void
	 */
	public static function delete($name, $year) {
		
		// delete result
		if(!Db::executeQuery('
			DELETE FROM `holiday` WHERE `name`=\'#?\' AND `year`=\'#?\'
				',
		array($name, $year,))) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
	}
	
	
	/**
	 * deleteAll($year) deletes all holiday with the given $year
	 * from database
	 * 
	 * @param int $year year of the holiday entries
	 * @return void
	 */
	public static function deleteAll($year) {
		
		// delete result
		if(!Db::executeQuery('
			DELETE FROM `holiday` WHERE `year`=\'#?\'
				',
		array($year,))) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
	}
	
	
}

?>
