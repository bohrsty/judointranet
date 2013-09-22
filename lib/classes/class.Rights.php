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
 * class Rights implements rightmanagement
 */
class Rights extends Object {
	
	/*
	 * class-variables
	 */
	private $rights;
	private $new_rights;
	private $table;
	
	/*
	 * getter/setter
	 */
	public function get_rights(){
		return $this->rights;
	}
	public function set_rights($rights) {
		$this->rights = $rights;
	}
	public function get_new_rights(){
		return $this->new_rights;
	}
	public function set_new_rights($new_rights) {
		$this->new_rights = $new_rights;
	}
	public function get_table(){
		return $this->table;
	}
	public function set_table($table) {
		$this->table = $table;
	}
	
	/*
	 * constructor/destructor
	 */
	public function __construct($table,$arg) {
		
		// parent constructor
		parent::__construct();
		
		// set table
		$this->set_table($table);
		
		// check arg
		if(is_array($arg)) {
			
			// set rights
			$this->set_rights($arg);
		} else {
			
			// get right for given id
			$this->get_from_db($arg);			
		}
	}
	
	/*
	 * methods
	 */
	/**
	 * get_from_db gets the rightconfig for the given table and entry
	 * 
	 * @param string $table name of the content-table
	 * @param int $id id of the content-entry
	 * @return void
	 */
	private function get_from_db($table_id) {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql-statement
		$sql = 'SELECT r.id,r.g_id
				FROM rights AS r
				WHERE r.table_name = "'.$this->get_table().'"
				AND r.table_id = '.(int) $table_id;
		
		// execute
		$result = $db->query($sql);
		
		// fetch result
		$all_rights = array();
		while(list($id,$g_id) = $result->fetch_array(MYSQL_NUM)) {
		
			// set variables to array
			$all_rights[$id] = $g_id;
		}
		
		// close db
		$db->close();
		
		// set rights
		$this->set_rights($all_rights);
	}
	
	
	
	
	
	
	
	/**
	 * get_authoized_entries returns an array with id of the given table
	 * for that the user has sufficient rights
	 * 
	 * @param string $table tablename to get ids from
	 * @return array array containing ids for that the user has sufficient rights
	 */
	public static function get_authorized_entries($table) {
		
		// get groups
		$group_ids = self::getUser()->groups();
				
		// get db-object
		$db = Db::newDb();
		
		// groups
		$sql = 'SELECT r.table_id
				FROM rights AS r
				WHERE r.table_name = "'.$table.'"
				AND (';
		for($i = 0;$i<count($group_ids);$i++) {
			
			// field
			$sql .= 'r.g_id = '.$group_ids[$i].' OR ';
		}
		$sql = substr($sql,0,-4);
		$sql .= ')';	
		
		// execute
		$result = $db->query($sql);
		
		// fetch results
		$entry_ids = array();
		while(list($entry_id) = $result->fetch_array(MYSQL_NUM)) {
			$entry_ids[] = $entry_id;
		}
		
		// close db
		$db->close();
		
		// unique $entry_ids
		if($table == 'navi') {
			$entry_ids = array_unique($entry_ids);
		} else {
			$entry_ids = array_unique($entry_ids,SORT_NUMERIC);
		}
		
		// return
		return $entry_ids;
	}
	
	
	
	
	
	/**
	 * write_db writes the rights to the database
	 * 
	 * @param int $table_id id of the inserted element
	 * @return void
	 */
	public function write_db($table_id) {
		
		// read rights
		$rights = $this->get_rights();
		$update_rights = array();
		// get action
		$action = 'update';
		if(isset($rights['action'])) {
			$action = $rights['action'];
		}
		
		// get db-object
		$db = Db::newDb();
		
		// action
		if($action == 'new') {
			
			// insert
			// walk through array
			foreach($rights[$action] as $no => $g_id) {
				
				// add values
				$sql = 'INSERT INTO rights (id,g_id,table_name,table_id)
						VALUES (NULL,'.$g_id.',"'.$this->get_table().'",'.$table_id.')';
				
				// execute
				$db->query($sql);
				
				// prepare $update_rights
				$update_rights[$db->insert_id] = $g_id;
			}
		} elseif($action == 'update') {
			
			// update
			
			// get new_rights
			$new_rights = $this->get_new_rights();
						
			// walk through rights to insert
			if(count($new_rights['insert']) > 0) {
				foreach($new_rights['insert'] as $no => $g_id) {
					
					// prepare insert-statement
					$sql = 'INSERT INTO rights (id,g_id,table_name,table_id)
							VALUES (NULL,'.$g_id.',"'.$this->get_table().'",'.$table_id.')';
					
					// execute
					$db->query($sql);
					
					// prepare $update_rights
					$update_rights[$db->insert_id] = $g_id;
				}
			}
			
			// walk through rights to remove
			if(count($new_rights['remove']) > 0) {
				foreach($new_rights['remove'] as $id => $g_id) {
					
					// prepare delete-statement
					$sql = 'DELETE FROM rights
							WHERE id = '.$id;
					
					// execute
					$db->query($sql);
				}
			}
		} else {
			
			// error
			$errno = $this->getError()->error_raised('DbActionUnknown','write_rights',$action);
			throw new Exception('DbActionUnknown',$errno);
		}
		
		// close db
		$db->close();
		
		// set new rights
		// add inserted rights
		$merge_rights = array_merge($rights,$update_rights);
		// remove deleted rights
		$set_rights = array();
		if(isset($new_rights)) {
			$set_rights = array_diff($merge_rights,$new_rights['remove']);
		}
		$this->set_rights($set_rights);
	}
	
	
	
	
	
	/**
	 * update sets the new rights-entries to $new_rights separated in insert,
	 * update and delete
	 * 
	 * @param int $table_id id of the calendar-entry
	 * @param array $rights array containing new rights-entries
	 */
	public function update($table_id,$new_rights) {
		
		// get $rights
		$rights = $this->get_rights();
				
		// prepare update_rights
		$update_rights = array('insert' => array(),'remove' => array());
		
		// walk through $new_rights to get insert g-ids
		foreach($new_rights as $new_right) {
			
			// check if actual g_id is in $rights
			if(!in_array($new_right,$rights)) {
				$update_rights['insert'][] = $new_right;
			}
		}
		
		// delete
		// walk through $rights
		foreach($rights as $id => $g_id) {
			
			// check if actual right is not in new_rights => remove
			if(!in_array($g_id,$new_rights)) {
				$update_rights['remove'][$id] = $g_id;
			}
		}		
		$this->set_new_rights($update_rights);
	}
	
	
	
	
	
	/**
	 * check_rights if the loggedin user has rights on the given table and
	 * table_id
	 * 
	 * @param int $table_id id of the entry
	 * @param string $table name of the table
	 * @param bool $public if true, include public-access in check
	 * @return bool true if user has rights, false otherwise
	 */
	public static function check_rights($table_id,$table,$public=false) {
		
		// get groups
		$groups = self::getUser()->groups();
		
		// get rights for given id and table
		// get db-object
		$db = Db::newDb();
		
		// prepare sql-statement
		$sql = 'SELECT r.id,r.g_id
				FROM rights AS r
				WHERE r.table_name = "'.$table.'"
				AND r.table_id = '.(int) $table_id;
		
		// execute
		$result = $db->query($sql);
		
		// fetch result
		$all_rights = array();
		while(list($id,$g_id) = $result->fetch_array(MYSQL_NUM)) {
		
			// set variables to array
			$all_rights[$id] = $g_id;
		}
		
		// walk through groups and check if in rights
		foreach($groups as $no => $group_id) {
			if($public) {
				if(in_array($group_id,$all_rights)) {
					return true;
				}
			} else {
				if(in_array($group_id,$all_rights) && $group_id != 0) {
					return true;
				}
			}
		}
		
		// else return false
		return false;
	}
}



?>
