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
	private $level;
	private $used;
	
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
	public function getLevel(){
		return $this->level;
	}
	public function setLevel($level) {
		$this->level = $level;
	}
	public function getUsed(){
		return $this->used;
	}
	public function setUsed($used) {
		$this->used = $used;
	}
	
	
	
	/*
	 * constructor/destructor
	 */
	public function __construct($id = 0) {
		
		// parent constructor
		parent::__construct();
		
		// set class variables
		$this->setId($id);
		
		// get data from db
		if($id != 0) {
			$this->dbLoadGroup();
		}
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
			list($name, $parent, $valid) = $result->fetch_array(MYSQLI_NUM);
		} else {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
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
			
			while(list($subId) = $result->fetch_array(MYSQLI_NUM)) {
				$subGroups[] = new Group($subId);
			}
		} else {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// set values
		$this->setName($name);
		$this->setSubGroups($subGroups);
		$this->setParent($parent);
		$this->setValid($valid);
		$this->setLevel(null);
		$this->setUsed(Group::isUsed($this->getId()));
	}
	
	
	/**
	 * allGroups() returns an array containing arrays of the own id/name and the ids/names
	 * of all subgroups
	 * 
	 * @return array array containing the own group object and the ids/names of all subgroup objects
	 */
	public function allGroups($level = 0) {
		
		// prepare return
		$allGroups = array();
		
		// walk through subgroups recursively
		if(count($this->getSubGroups()) == 0) {
			$this->setLevel($level);
			$allGroups[$this->getId()] = $this;
		} else {
			
			// increment level for next recursion
			$level++;
			
			foreach($this->getSubGroups() as $subGroup) {
				$allGroups += $subGroup->allGroups($level);
			}
			
			// decrement level for this object
			$level--;
			
			// add own object
			$this->setLevel($level);
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
			list($id) = $result->fetch_array(MYSQLI_NUM);
		} else {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
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
		$group->setName(_l('public access'));
		$group->setSubGroups(array());
		$group->setParent(-1);
		$group->setValid(1);
		
		// return
		return $group; 
	}
	
	
	/**
	 * permissionFor($table, $tableId) returns the permission mode for the given $table/$tableId
	 * or empty string if no permission
	 * 
	 * @param string $table the table name of the permission to get
	 * @param int $tableId the id of the table entry to get the permission from
	 * @return string the permission mode (r/w) or empty string
	 */
	public function permissionFor($table, $tableId) {
		
		// get db object
		$db = Db::newDb();
		
		// prepare sql statement to get group details
		$sql = 'SELECT mode
				FROM permissions
				WHERE item_table=\''.$db->real_escape_string(strtolower($table)).'\'
				AND item_id=\''.$db->real_escape_string($tableId).'\'
				AND user_id=\''.$db->real_escape_string('-1').'\'
				AND group_id=\''.$db->real_escape_string($this->getId()).'\'';
		
		// execute statement
		$result = $db->query($sql);
		
		// get data
		if($result) {
			list($mode) = $result->fetch_array(MYSQLI_NUM);
		} else {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// return
		if($result->num_rows == 0) {
			return '';
		} else {
			return $mode;
		}
	}
	
	
	/**
	 * exists($gid) checks if a group with the given $gid exists in database
	 * 
	 * @param int $gid the id to be checked for existance
	 * @return bool true if group exists, false otherwise
	 */
	public static function exists($gid) {
		
		// prepare sql
		$sql = '
				SELECT COUNT(*)
				FROM `groups`
				WHERE `id`=#?
				';
		
		// get data
		$data = Db::singleValue($sql, array($gid));
		
		if(!is_null($data)) {
			return $data > 0;
		} else {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
	}
	
	
	/**
	 * update($data) sets the values from $data to object
	 * 
	 * @param array $data array containing the data to be set into object
	 * @return void
	 */
	public function update($data){
		
		// set class variables
		$this->setName((isset($data['name']) ? $data['name'] : ''));
		$this->setParent((isset($data['parent']) ? $data['parent'] : 0));
		$this->setValid((isset($data['valid']) ? $data['valid'] : 1));
	}
	
	
	/**
	 * writeDb() writes the actual data of the object to database and returns the id
	 * 
	 * @return int the new id of the inserted group
	 */
	public function writeDb() {
		
		// write to db
		// check new or update
		if($this->getId() == 0) {
			
			$sql = '
				INSERT INTO `groups`
					(`id`, `name`, `parent`, `valid`, `modified_by`, `last_modified`)
				VALUES
					(NULL, \'#?\', #?, #?, #?, CURRENT_TIMESTAMP)
			';
			
			$result = Db::executeQuery($sql, 
				array(
					$this->getName(),
					$this->getParent(),
					$this->getValid(),
					$this->getUser()->get_id(),
				)
			);
		} else {
			
			$sql = '
				UPDATE `groups`
					SET
						`name`=\'#?\',
						`parent`=#?,
						`valid`=#?,
						`modified_by`=#?,
						`last_modified`=CURRENT_TIMESTAMP
				WHERE `id`=#?
			';
			
			$result = Db::executeQuery($sql, 
				array(
					$this->getName(),
					$this->getParent(),
					$this->getValid(),
					$this->getUser()->get_id(),
					$this->getId(),
				)
			);
		}
		
		if(!$result) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// set new id and return it
		if($this->getId() == 0) {
			$this->setId(Db::$insertId);
		}
		return $this->getId();
	}
	
	
	/**
	 * delete() deletes the group from database
	 * 
	 * @return void
	 */
	public function delete() {
		
		// delete from database
		$sql = '
			DELETE FROM `groups`
				WHERE `id`=#?
		';
		
		$result = Db::executeQuery($sql, 
			array(
				$this->getId(),
			)
		);
		
		if(!$result) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
	}
	
	
	/**
	 * nameToTextIntended($config) returns the group name intended using $this->level and $config
	 * 
	 * @param array $config configuration of the intention
	 * @return string the intended group name
	 */
	public function nameToTextIntended($config) {
		
		// get level
		$level = $this->getLevel();
		
		// add intention according to $level
		if($level == 0) {
			return $config[0].$this->getName();
		}
		if($level == 1) {
			return $config[0].$config[1].$this->getName();
		}
		if($level > 1) {
			$return = $config[0];
			for($i = 2; $i <= $level; $i++) {
				$return .= $config['1+'];
			}
			return $return.$config[1].$this->getName();
		}
	}
	
	/**
	 * isUsed($gid) checks if a group with the given $gid is used in permission table
	 * 
	 * @param int $gid the id of the group to be checked
	 * @return bool true if is used, false otherwise
	 */
	public static function isUsed($gid) {
		
		// get usage
		$sql = '
			SELECT COUNT(*)
			FROM `permissions`
			WHERE `group_id`=#?
		';
		// get data
		$used = Db::singleValue($sql, array($gid));
		
		if(is_null($used)) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// return
		return $used > 0;
	}
}

?>
