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
 * class Tribute implements the representation of a tribute object
 */
class Tribute extends Page {
	
	/*
	 * class-variables
	 */
	private $name;
	private $year;
	private $startDate;
	private $plannedDate;
	private $date;
	private $testimonialId;
	private $description;
	
	/*
	 * getter/setter
	 */
	public function getName(){
		return $this->name;
	}
	public function setName($name) {
		$this->name = $name;
	}
	public function getYear(){
		return $this->year;
	}
	public function setYear($year=null) {
		
		// check if year should be set explicitly
		if(!is_null($year)) {
			$this->year = $year;
		} else {
			
			// check other dates
			if(!is_null($this->date)) {
				$this->year = date('Y', strtotime($this->date));
			} elseif(!is_null($this->plannedDate)) {
				$this->year = date('Y', strtotime($this->plannedDate));
			} elseif(!is_null($this->startDate)) {
				$this->year = date('Y', strtotime($this->startDate));
			} else {
				$this->year = date('Y');
			}
		}
	}
	public function getStartDate(){
		return $this->startDate;
	}
	public function setStartDate($startDate) {
		$this->startDate = $startDate;
	}
	public function getPlannedDate(){
		return $this->plannedDate;
	}
	public function setPlannedDate($plannedDate) {
		$this->plannedDate = $plannedDate;
	}
	public function getDate(){
		return $this->date;
	}
	public function setDate($date) {
		$this->date = $date;
	}
	public function getTestimonialId(){
		return $this->testimonialId;
	}
	public function setTestimonialId($testimonialId) {
		$this->testimonialId = $testimonialId;
	}
	public function getDescription(){
		return $this->description;
	}
	public function setDescription($description) {
		$this->description = $description;
	}
	
	/*
	 * constructor/destructor
	 */
	public function __construct($id = 0) {
		
		// parent constructor
		parent::__construct();
		
		// check $id
		if(is_array($id)) {
			
			// set values from array
			$this->setId(0);
			$this->setName($id['name']);
			$this->setStartDate($id['startDate']);
			$this->setPlannedDate((isset($id['plannedDate']) && $id['plannedDate'] != '' ? date('Y-m-d', strtotime($id['plannedDate'])) : null));
			$this->setDate((isset($id['date']) && $id['date'] != '' ? date('Y-m-d', strtotime($id['date'])) : null));
			$this->setTestimonialId($id['testimonialId']);
			$this->setDescription($id['description']);
			$this->setValid($id['valid']);
			
			// set year
			$this->setYear();
		} else {
			
			// check if $id is 0
			if($id == 0) {
				$this->setId(0);
			} else {
				// get from database
				$this->getFromDb($id);
			}
		}
	}
	
	/*
	 * methods
	 */
	/**
	 * getFromDb($id) retrieves the informations for the given $id from database
	 * 
	 * @param int $id the id of the entry to be retrieved from db
	 * @return void
	 */
	private function getFromDb($id) {
		
		// get result values from db
		$result = Db::ArrayValue('
			SELECT `name`, `year`, `start_date`, `planned_date`, `date`, `testimonial_id`, `description`, `last_modified`, `modified_by`, `valid`
			FROM `tribute`
			WHERE `id`=#?	
		',
		MYSQL_ASSOC,
		array($id,));
		if($result === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// set variables
		if(isset($result[0])) {
			
			$this->setId($id);
			$this->setName($result[0]['name']);
			$this->setStartDate($result[0]['start_date']);
			$this->setPlannedDate($result[0]['planned_date']);
			$this->setDate($result[0]['date']);
			$this->setTestimonialId($result[0]['testimonial_id']);
			$this->setDescription($result[0]['description']);
			$this->setValid($result[0]['valid']);
			$this->setLastModified((strtotime($result[0]['last_modified']) < 0 ? 0 : strtotime($result[0]['last_modified'])));
			$this->setModifiedBy($result[0]['modified_by']);
			
			// set year
			$this->setYear();
		}
	}
	
	
	/**
	 * update sets the values from given array to the tribute object
	 * 
	 * @param array $tribute array containing the new values
	 * @return void
	 */
	public function update($tribute) {
		
		// walk through array
		foreach($tribute as $name => $value) {
			
			// check $name
			if($name == 'name') {
				$this->setName($value);
			} elseif($name == 'startDate') {
				$this->setStartDate($value);
			} elseif($name == 'plannedDate') {
				$this->setPlannedDate(($value != '' ? date('Y-m-d', strtotime($value)) : null));
			} elseif($name == 'date') {
				$this->setDate(($value != '' ? date('Y-m-d', strtotime($value)) : null));
			} elseif($name == 'testimonialId') {
				$this->setTestimonialId($value);
			} elseif($name == 'description') {
				$this->setDescription($value);
			} elseif($name == 'valid') {
				$this->setValid($value);
			}
		}
		
		// set year
		$this->setYear();
	}
	
	
	/**
	 * writeDb writes the tribute data to db
	 * 
	 * @return int $this->id or id of new insert data
	 */
	public function writeDb() {
		
		// insert into database
		if(!Db::executeQuery('
			INSERT INTO `tribute` (`id`,`name`,`year`,`start_date`,`planned_date`,`date`,`testimonial_id`,`description`,`valid`,`last_modified`,`modified_by`)
			VALUES (#?, \'#?\', \'#?\', \'#?\', '.(is_null($this->getPlannedDate()) ? '#?' : '\'#?\'').', '.(is_null($this->getDate()) ? '#?' : '\'#?\'').', #?, \'#?\', #?, CURRENT_TIMESTAMP, #?)
			ON DUPLICATE KEY UPDATE
				`name`=\'#?\',
				`year`=\'#?\',
				`start_date`=\'#?\',
				`planned_date`='.(is_null($this->getPlannedDate()) ? '#?' : '\'#?\'').',
				`date`='.(is_null($this->getDate()) ? '#?' : '\'#?\'').',
				`testimonial_id`=#?,
				`description`=\'#?\',
				`valid`=#?,
				`last_modified`=CURRENT_TIMESTAMP,
				`modified_by`=#?
			',
			array(// insert
				($this->getId() == 0 ? 'NULL' : $this->getId()),
				$this->getName(),
				$this->getYear(),
				$this->getStartDate(),
				(is_null($this->getPlannedDate()) ? 'NULL' : $this->getPlannedDate()),
				(is_null($this->getDate()) ? 'NULL' : $this->getDate()),
				$this->getTestimonialId(),
				$this->getDescription(),
				$this->getValid(),
				(int)$this->getUser()->get_id(),
				// update
				$this->getName(),
				$this->getYear(),
				$this->getStartDate(),
				(is_null($this->getPlannedDate()) ? 'NULL' : $this->getPlannedDate()),
				(is_null($this->getDate()) ? 'NULL' : $this->getDate()),
				$this->getTestimonialId(),
				$this->getDescription(),
				$this->getValid(),
				(int)$this->getUser()->get_id(),))) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// set and return (new) id
		$newId = (isset(Db::$insertId) ? Db::$insertId : $this->getId());
		$this->setId($newId);
		return $newId;
	}
	
	
	/**
	 * deleteEntry() sets $this->valid to 0
	 */
	public function deleteEntry() {
		
		// set valid
		$this->setValid(0);
		$this->writeDb();
		
		// delete all history entries
		TributeHistory::deleteAll($this->getId());
	}
	
	
	/**
	 * delete($id) deletes the tribute with the given $id from database
	 * 
	 * @param string $id id of the tribute entry
	 * @return void
	 */
	public static function delete($id) {
		
		// delete result
		if(!Db::executeQuery('
			DELETE FROM `tribute` WHERE `id`=#?
				',
		array($id,))) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}		
	}
	
	
	/**
	 * getAllYears() gets all years from database and returns them distinct
	 * 
	 * @return array array containing all years
	 */
	public static function getAllYears() {
		
		// select all years
		$result = Db::ArrayValue('
			SELECT DISTINCT `year`
			FROM `tribute`
			WHERE `valid`=TRUE
			ORDER BY `year`
		',
		MYSQL_NUM,
		array());
		if($result === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// return
		$return = array();
		if(count($result) > 0) {
			
			foreach($result as $entry) {
				$return[] = $entry[0];
			}
		}
		return $return;
	}
	
	
	/**
	 * getAllTestimonials() gets all used (or all existing) testimonials from database
	 * and returns them
	 * 
	 * @param bool $all all existing if true, all used if false
	 * @return array array containing all testimonials
	 */
	public static function getAllTestimonials($all=false) {
		
		if($all === false) {
			
			// select all used testimonials
			$sql = '
				SELECT DISTINCT `t`.`testimonial_id` AS `id`, `tm`.`name`
				FROM `tribute` AS `t`, `testimonials` AS `tm`
				WHERE `tm`.`valid`=TRUE
					AND `t`.`testimonial_id`=`tm`.`id`
				ORDER BY `name`
			';
		} else {

			// select all existing testimonials
			$sql = '
				SELECT `id` AS `id`, `name`
				FROM `testimonials`
				WHERE `valid`=TRUE
				ORDER BY `name`
			';
		}
		
		$result = Db::ArrayValue($sql,
		MYSQL_ASSOC,
		array());
		if($result === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// return
		if(count($result) > 0) {
			return $result;
		} else {
			return array();
		}
	}
	
	
	/**
	 * getAllHistory($id) gets all history entries that belong to $id
	 * 
	 * @param int $id the id of the tribute the history belongs to
	 * @param bool $validOnly if is true, only valid history entries are returned
	 * @return array array containing the history objects
	 */
	public static function getAllHistory($id, $validOnly=false) {
		
		// check $validOnly
		$sqlAnd = '';
		if($validOnly === true) {
			$sqlAnd = 'AND `valid`=TRUE';
		}
		
		$result = Db::ArrayValue('
			SELECT `id`
			FROM `tribute_history`
			WHERE `tribute_id`=#?
				'.$sqlAnd.'
			ORDER BY `last_modified`, `history_type_id`
		',
				MYSQL_ASSOC,
				array($id,));
		if($result === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// return
		$return = array();
		if(count($result) > 0) {
				
			foreach($result as $entry) {
				$return[] = new TributeHistory($entry['id']);
			}
		}
		return $return;
	}
	
	
	/**
	 * getAllFiles($id) gets all file entries that belong to $id
	 * 
	 * @param int $id the id of the tribute the file belongs to
	 * @param bool $validOnly if is true, only valid file entries are returned
	 * @return array array containing the file objects
	 */
	public static function getAllFiles($id, $validOnly=false) {
		
		// check $validOnly
		$sqlAnd = '';
		if($validOnly === true) {
			$sqlAnd = 'AND `valid`=TRUE';
		}
		
		$result = Db::ArrayValue('
			SELECT `id`
			FROM `tribute_file`
			WHERE `tribute_id`=#?
				'.$sqlAnd.'
			ORDER BY `last_modified`
		',
				MYSQL_ASSOC,
				array($id,));
		if($result === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// return
		$return = array();
		if(count($result) > 0) {
				
			foreach($result as $entry) {
				$return[] = new TributeFile($entry['id']);
			}
		}
		return $return;
	}
}