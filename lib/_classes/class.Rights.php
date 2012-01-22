<?php


/**
 * class Rights implements rightmanagement
 */
class Rights extends Object {
	
	/*
	 * class-variables
	 */
	private $rights;
	private $table;
	
	/*
	 * getter/setter
	 */
	private function get_rights(){
		return $this->rights;
	}
	private function set_rights($rights) {
		$this->rights = $rights;
	}
	private function get_table(){
		return $this->table;
	}
	private function set_table($table) {
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
				AND r.table_id = '.$table_id;
		
		// execute
		$result = $db->query($sql);
		
		// fetch result
		$all_rights = array();
		while(list($id,$g_id) = $result->fetch_array(MYSQL_NUM)); {
		
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
		$group_ids = $_SESSION['user']->groups();
		
		// get db-object
		$db = Db::newDb();
		
		// groups
		$sql = 'SELECT r.table_id
				FROM rights AS r
				WHERE r.table_name = "'.$table.'"
				AND (';
		for($i = 0;$i<count($group_ids);$i++) {
			
			// field
			$sql .= 'r.g_id = ';
			
			// last entry
			if($group_ids[$i]==count($group_ids)-1){
				$sql .= $group_ids[$i];
			} else {
				$sql .= $group_ids[$i].' OR ';
			}
		}
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
		$entry_ids = array_unique($entry_ids,SORT_NUMERIC);
		
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
		$new_rights = array();
		// get action
		$action = $rights['action'];
		
		// get db-object
		$db = Db::newDb();
		
		// action
		if($action == 'new') {
			
			// insert
			// walk through array
			foreach($rights[$action] as $no => $g_id) {
				
				// add values
				$sql = 'INSERT INTO rights (id,g_id,table_name,table_id)';
				$sql .= 'VALUES (NULL,'.$g_id.',"'.$this->get_table().'",'.$table_id.')';
				
				// execute
				$db->query($sql);
				
				// prepare $new_rights
				$new_rights[$db->insert_id] = $g_id;
			}
		} elseif($action == 'update') {
			
			// update
		} else {
			
			// error
			$errno = $GLOBALS['Error']->error_raised('DbActionUnknown','write_rights',$action);
			throw new Exception('DbActionUnkknown',$errno);
		}
		
		// close db
		$db->close();
		
		// set new rights
		$this->set_rights($new_rights);
	}
	
	
	
	
	
	/**
	 * return_rights returns the actual group-ids from $rights
	 * 
	 * @return array group_ids from $rights
	 */
	public function return_rights() {
		return $this->get_rights();
	}
}



?>
