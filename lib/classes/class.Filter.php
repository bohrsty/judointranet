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
 * class Filter implements the properties of a filter
 */
class Filter extends Object {
	
	/*
	 * class-variables
	 */
	private $id;
	private $name;
	
	/*
	 * getter/setter
	 */
	public function getId(){
		return $this->id;
	}
	public function setId($id) {
		$this->id = $id;
	}
	public function getName(){
		return $this->name;
	}
	public function setName($name) {
		$this->name = $name;
	}
	
	/*
	 * constructor/destructor
	 */
	public function __construct($id) {
		
		// parent constructor
		parent::__construct();
		
		// set id
		$this->setId($id);
		
		// set values from db
		$this->dbLoadFilter();
	}
	
	/*
	 * methods
	 */
	/**
	 * dbLoadFilter() loads the details of the filter from database
	 * 
	 * @return void
	 */
	private function dbLoadFilter() {
		
		// get db object
		$db = Db::newDb();
		
		// prepare sql statement to get group details
		$sql = 'SELECT name,valid
				FROM filter
				WHERE id=\''.$db->real_escape_string($this->getId()).'\'';
		
		// execute statement
		$result = $db->query($sql);
		
		// get data
		if($result) {
			list($name) = $result->fetch_array(MYSQL_NUM);
		} else {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// close db
		$db->close();
		
		// set values
		$this->setName($name);
	}
	
	
	/**
	 * allExistingFilter() returns the an array containing the infos of all existing filter
	 * 
	 * @param string $value definition of field that should be returned instead of whole object
	 * @return array array containing the infos of all existing filter
	 */
	public static function allExistingFilter($value=null) {
		
		// get db object
		$db = Db::newDb();
		
		// prepare sql statement to get group details
		$sql = 'SELECT id
				FROM filter
				WHERE valid=1
				ORDER BY name';
		
		// execute statement
		$result = $db->query($sql);
		
		// get data
		$allFilter = array();
		if($result) {
			while(list($id) = $result->fetch_array(MYSQL_NUM)) {
				
				$filter = new Filter($id);
				// check $value
				if(!is_null($value)) {
					$allFilter[$id] = $filter->propertyByString($value);
				} else {
					$allFilter[$id] = $filter;
				}
			}
		} else {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// return all filter
		return $allFilter;
	}
	
	
	/**
	 * allFilterOf($table, $itemId) returns an array containing all infos of the filter
	 * of the given item
	 * 
	 * @param string $table table of the item
	 * @param int $itemId the id of the item in $table
	 * @return array array containing all infos of the filter of the given item
	 */
	public static function allFilterOf($table, $itemId) {
		
		// get db object
		$db = Db::newDb();
		
		// prepare sql statement to get group details
		$sql = 'SELECT filter_id
				FROM item2filter
				WHERE item_table=\''.$db->real_escape_string($table).'\'
					AND item_id=\''.$db->real_escape_string($itemId).'\'';
		
		// execute statement
		$result = $db->query($sql);
		
		// get data
		$filter = array();
		if($result) {
			while(list($id) = $result->fetch_array(MYSQL_NUM)) {
				$filter[$id] = new Filter($id);
			}
		} else {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// close db
		$db->close();
		
		// return
		return $filter;
	}
	
	
	/**
	 * filterItems($filterId, $table) returns an array containing all items that matches
	 * this filter
	 * 
	 * @param int $filterId the id of the filter to match
	 * @param string $table the table to apply the filter to
	 * @param string $dateFrom if filtered by date the "from" date
	 * @param string $dateTo if filtered by date the "to" date
	 * @return array array containig all items that matches this filter
	 */
	public static function filterItems($filterId, $table, $dateFrom=null, $dateTo=null) {
		
		// get permitted items
		$permittedItems = self::staticGetUser()->permittedItems($table, 'w', $dateFrom, $dateTo);
		// prepare filtered items
		$filteredItems = array();
		
		// filter items if filter given
		if($filterId !== false) {
			
			// get db object
			$db = Db::newDb();
			
			// prepare sql statement to get filtered itemIds
			$sql = 'SELECT item_id
					FROM item2filter
					WHERE item_table=\''.$db->real_escape_string($table).'\'
						AND filter_id=\''.$db->real_escape_string($filterId).'\'';
			
			// execute statement
			$result = $db->query($sql);
			
			// close db
			$db->close();
			
			// get filtered items
			if($result) {
			
				while(list($id) = $result->fetch_array(MYSQL_NUM)) {
					$filteredItems[] = $id;
				}
			} else {
				$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
			}
		}
		
		// prepare return
		$items = array();
		
		// intersect permitted and filtered items
		if($filterId !== false) {
			$intersectedItems = array_intersect($permittedItems, $filteredItems);
		} else {
			$intersectedItems = $permittedItems;
		}
		foreach($intersectedItems as $intersectedItem) {
			
			// switch table to get correct objects
			switch($table) {
				
				case 'calendar':
					$items[$intersectedItem] = new Calendar($intersectedItem);
				break;
				
				default:
					return false;
				break;
			}
		}
		
		// return
		return $items;
	}
	
	
	/**
	 * propertiyByString($string) returns the property of the filter given by $string
	 * 
	 * @param string $string name of the property to be returned
	 * @return mixed the value of the property asked by string (default returns id)
	 */
	public function propertyByString($string) {
		
		// switch $string
		switch($string) {
			
			case 'name':
				return $this->getName();
			break;
				
			case 'id':
			default:
				return $this->getId();
			break;
			
		}
	}
	
	
	/**
	 * dbRemove($table, $itemId) removes any entries for the given item from the database
	 * 
	 * @param string $table table info in db
	 * @param int $itemId id of the item
	 * @return void
	 */
	public static function dbRemove($table, $itemId) {
		
		// get db object
		$db = Db::newDb();
		
		// prepare sql statement to get group details
		$sql = 'DELETE FROM item2filter
				WHERE item_table=\''.$db->real_escape_string($table).'\'
					AND item_id=\''.$db->real_escape_string($itemId).'\'';
		
		// execute statement
		$result = $db->query($sql);
		
		// get data
		$items = array();
		if(!$result) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// close db
		$db->close();
	}
	
	
	/**
	 * dbWrite($table, $itemId) writes the info for the item into the database
	 * 
	 * @param string $table table info in db
	 * @param int $itemId id of the item
	 * @return void
	 */
	public function dbWrite($table, $itemId) {
		
		// get db object
		$db = Db::newDb();
		
		// prepare sql statement to get group details
		$sql = 'INSERT INTO item2filter (item_table,item_id,filter_id)
				VALUES (\''.$db->real_escape_string($table).'\',
						\''.$db->real_escape_string($itemId).'\',
						\''.$db->real_escape_string($this->getId()).'\')';
		
		// execute statement
		$result = $db->query($sql);
		
		// get data
		$items = array();
		if(!$result) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// close db
		$db->close();
	}
	
	
	/**
	 * filterItemIdsAsArray($filterId, $table, $dateFrom, $dateTo) returns an array containing
	 * all item ids that matches this filter
	 * 
	 * @param int $filterId the id of the filter to match
	 * @param string $table the table to apply the filter to
	 * @param string $dateFrom if filtered by date the "from" date
	 * @param string $dateTo if filtered by date the "to" date
	 * @return array array containig all item ids that matches this filter
	 */
	public static function filterItemIdsAsArray($filterId, $table, $dateFrom=null, $dateTo=null) {
		
		// get permitted items
		$permittedItems = self::staticGetUser()->permittedItems($table, 'w', $dateFrom, $dateTo);
		$itemIds = 'SELECT FALSE';
		if(count($permittedItems) > 0) {
			$itemIds = implode(',', $permittedItems);
		}
		
		// filter items if filter given
		if($filterId !== false) {
			
			// prepare filter ids
			$filterIds = 'SELECT FALSE';
			if(is_array($filterId) && count($filterId) > 0) {
				$filterIds = implode(',', $filterId);
			}
			
			// get filtered ids from database
			$result = Db::ArrayValue('
					SELECT `item_id`
					FROM `item2filter`
					WHERE `item_table`=\'#?\'
						AND `item_id` IN (#?)
						AND `filter_id` IN (#?)
				',
				MYSQL_ASSOC,
				array(
						$table,
						$itemIds,
						$filterIds,
					));
			if($result === false) {
				$n = null;
				throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
			}
			
			// get filtered items
			$filteredItems = array();
			foreach($result as $row) {
				$filteredItems[] = $row['item_id'];
			}
			
			// intersect permitted and filtered items
			return $filteredItems;
		} else {
			return $permittedItems;
		}
	}
	
}
?>
