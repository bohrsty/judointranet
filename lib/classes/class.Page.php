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
 * Page is the parent-class for the page-objects
 */
 class Page extends Object {
 	
 	/*
	 * class-variables
	 */
	private $id;
	
	/*
	 * getter/setter
	 */
	public function getId(){
		return $this->id;
	}
	public function setId($id) {
		$this->id = $id;
	}
	// stay for compatibility reason
	public function get_id(){
		return $this->getId();
	}
	public function set_id($id) {
		$this->setId($id);
	}
	
	
	/*
	 * constructor/destructor
	 */
	public function __construct() {
		
		// parent constructor
		parent::__construct();
	}
	
	/*
	 * methods
	 */
	/**
	 * isPermittedFor($groupId) returns true if the group has permissions, false otherwise
	 * 
	 * @param int $groupId the id of the group to check
	 * @return bool true if the group has permissions, false otherwise
	 */
	public function isPermittedFor($groupId) {
		
		// get db object
		$db = Db::newDb();
		
		// prepare sql statement to get group details
		$sql = 'SELECT *
				FROM permissions
				WHERE item_table=\''.$db->real_escape_string($this).'\'
					AND item_id=\''.$db->real_escape_string($this->get_id()).'\'
					AND group_id=\''.$db->real_escape_string($groupId).'\'
					AND mode=\''.$db->real_escape_string('r').'\'';
		
		// execute statement
		$result = $db->query($sql);
		
		// get data
		$items = array();
		if($result) {
			return $result->num_rows == 1;
		} else {
			$errno = self::getError()->error_raised('MysqlError', $db->error, $sql);
			self::getError()->handle_error($errno);
		}
		
		// close db
		$db->close();
	}
	
	
	/**
	 * __toString() returns an string representation of this object
	 * 
	 * @return string string representation of this object
	 */
	public function __toString() {
		return 'Page';
	}
	
	
	/**
	 * dbDeletePermissions() removes all permissions that are directly given to $this object
	 * from database
	 * 
	 * @return void
	 */
	public function dbDeletePermission() {
		
		// get db object
		$db = Db::newDb();
		
		// prepare sql statement to delete permissions
		$sql = 'DELETE FROM permissions
				WHERE item_table=\''.$db->real_escape_string(strtolower($this)).'\'
					AND item_id=\''.$db->real_escape_string($this->get_id()).'\'';
		
		// execute statement
		$result = $db->query($sql);
		
		// get data
		$items = array();
		if(!$result) {
			$errno = self::getError()->error_raised('MysqlError', $db->error, $sql);
			self::getError()->handle_error($errno);
		}
		
		// close db
		$db->close();
	}
	
	
	/**
	 * dbWritePermissions($permissions) writes the permissions given in the $permissions array
	 * to database
	 * 
	 * @param array $permissions array containing group objects and the given permission value
	 * 		that should be granted to the corresponding group
	 * @return void 
	 */
	public function dbWritePermission($permissions) {
		
		// get db object
		$db = Db::newDb();
		
		// create values
		$values = '';
		foreach($permissions as $groupId => $permission) {
			
			// set groups w/o admin
			if($groupId != 1) {
				if($permission['value'] != '0') {
					$values .= '(
								\''.$db->real_escape_string(strtolower($this)).'\',
								\''.$db->real_escape_string($this->get_id()).'\',
								\''.$db->real_escape_string('-1').'\',
								\''.$db->real_escape_string($groupId).'\',
								\''.$db->real_escape_string($permission['value']).'\',
								CURRENT_TIMESTAMP,
								\''.$db->real_escape_string($this->getUser()->get_id()).'\'
								),';
				}
			}
		}
		
		// if values to insert
		if(strlen($values) > 0) {
			
			// remove last ","
			$values = substr($values, 0, -1);
			
			// prepare sql statement to get group details
			$sql = 'INSERT IGNORE INTO permissions
						(`item_table`, `item_id`, `user_id`, `group_id`, `mode`, `last_modified`, `modified_by`)
					VALUES
						'.$values;
			
			// execute statement
			$result = $db->query($sql);
			
			// get data
			$items = array();
			if(!$result) {
				$errno = self::getError()->error_raised('MysqlError', $db->error, $sql);
				self::getError()->handle_error($errno);
			}
		}
		
		// close db
		$db->close();
	}
	
	
	/**
	 * createCachedFile($fid) (re)creates the cached file in database
	 * 
	 * @param mixed $fid file id or false if cached file does not exists
	 * @return int file id (new if created new file)
	 */
	public function createCachedFile($fid) {
		
		// get actual file content
		$fileFactory = $this->cacheFile();
		
		// check if cache exists
		if($fid !== false) {
			
			// cache file
			$file = new File($fid);
			$data = array(
					'content' => $fileFactory['content'],
				);
			$file->update($data);
			$file->writeDb();
		} else {
			
			// create file object
			$file = File::factory($fileFactory);
			$file->writeDb();
			$fid = $file->getId();
		}
		
		// return
		return $fid;
	}
	
	
	/**
	 * exists($table, $tableId) checks if the given $tableId exists in the given $table
	 * 
	 * @return bool true if id exists, false otherwise
	 */
	public static function exists($table, $tableId) {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql
		$sql = 'SELECT id
				FROM `'.$db->real_escape_string($table).'`
				WHERE id='.$db->real_escape_string($tableId);
		
		// execute
		$result = $db->query($sql);
		
		if($result) {
			if($result->num_rows == 0) {
				return false;
			} else {
				return true;
			}
		} else {
			$errno = self::getError()->error_raised('MysqlError', $db->error, $sql);
			self::getError()->handle_error($errno);
		}
	}
 	
 	
 }

?>
