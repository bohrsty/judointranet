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
 * class Group implements the properties of a group
 */
class Group extends Object {
	
	/*
	 * class-variables
	 */
	private $id;
	private $name;
	private $subGroups;
	private $parent;
	private $valid;
	
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
	public function getSubGroups(){
		return $this->subGroups;
	}
	public function setSubGroups($subGroups) {
		$this->subGroups = $subGroups;
	}
	public function getParent(){
		return $this->parent;
	}
	public function setParent($parent) {
		$this->parent = $parent;
	}
	public function getValid(){
		return $this->valid;
	}
	public function setValid($valid) {
		$this->valid = $valid;
	}
	
	
	
	/*
	 * constructor/destructor
	 */
	public function __construct($id) {
		
		// parent constructor
		parent::__construct();
		
		// set class variables
		$this->setId($id);
		
		// get data from db
		$this->dbLoadGroup();
	}
	
	
	/*
	 * methods
	 */
	/**
	 * dbLoadGroup() loads the details of the group from database
	 * 
	 * @return void
	 */
	private function dbLoadGroup() {
		
		// get db object
		$db = Db::newDb();
		
		// prepare sql statement to get group details
		$sql = 'SELECT name,parent,valid
				FROM groups
				WHERE id=\''.$db->real_escape_string($this->getId()).'\'';
		
		// execute statement
		$result = $db->query($sql);
		
		// get data
		if($result) {
			list($name, $parent, $valid) = $result->fetch_array(MYSQL_NUM);
		} else {
			$errno = $this->getError()->error_raised('MysqlError', $db->error);
			$this->getError()->handle_error($errno);
		}
		
		// prepare sql statement to get subgroups
		$sql = 'SELECT id
				FROM groups
				WHERE parent=\''.$db->real_escape_string($this->getId()).'\'';
		
		// execute statement
		$result = $db->query($sql);
		
		// close db
		$db->close();
		
		// get data
		$subGroups = array();
		if($result) {
			
			while(list($subId) = $result->fetch_array(MYSQL_NUM)) {
				$subGroups[] = new Group($subId);
			}
		} else {
			$errno = $this->getError()->error_raised('MysqlError', $db->error);
			$this->getError()->handle_error($errno);
		}
		
		// set values
		$this->setName($name);
		$this->setSubGroups($subGroups);
		$this->setParent($parent);
		$this->setValid($valid);
	}
	
	
	/**
	 * allGroups() returns an array containing arrays of the own id/name and the ids/names
	 * of all subgroups
	 * 
	 * @return array array containing the own group object and the ids/names of all subgroup objects
	 */
	public function allGroups() {
		
		// prepare return
		$allGroups = array();
		
		// walk through subgroups recursively
		if(count($this->getSubGroups()) == 0) {
			$allGroups[$this->getId()] = $this;
		} else {
			
			foreach($this->getSubGroups() as $subGroup) {
				
				// check if has subgroups
				if(count($subGroup->getSubGroups()) == 0) {
					$allGroups[$subGroup->getId()] = $subGroup;
				} else {
					
					// get ids
					$tempGroups = $subGroup->allGroups();
					$allGroups = $allGroups + $tempGroups;
				}
			}
			
			// add own object
			$allGroups[$this->getId()] = $this;
		}
		
		// return
		return $allGroups;
	}
	
	
	/**
	 * allExistingGroups() returns the an array containing the infos of all existing groups
	 * 
	 * @return array array containing the infos of all existing groups
	 */
	public static function allExistingGroups() {
		
		// get db object
		$db = Db::newDb();
		
		// prepare sql statement to get group details
		$sql = 'SELECT id
				FROM groups
				WHERE parent=\''.$db->real_escape_string('-1').'\'';
		
		// execute statement
		$result = $db->query($sql);
		
		// get data
		if($result) {
			list($id) = $result->fetch_array(MYSQL_NUM);
		} else {
			$errno = $this->getError()->error_raised('MysqlError', $db->error);
			$this->getError()->handle_error($errno);
		}
		
		// create group
		$group = new Group($id);
		
		// return all groups
		return $group->allGroups();
	}
	
	
	
	
	
	/**
	 * fakePublic() returns a group object with the id 0 for public access
	 * 
	 * @return object group object with id 0 for public access
	 */
	public static function fakePublic() {
		
		// create object
		$group = new Group(0);
		
		// set values
		$group->setName(parent::lang('class.Group#fakePublic#public#name'));
		$group->setSubGroups(array());
		$group->setParent(-1);
		$group->setValid(1);
		
		// return
		return $group; 
	}
}

?>
