<?php


/**
 * class Rights implements rightmanagement
 */
class Rights extends Object {
	
	/*
	 * class-variables
	 */
	private $rights;
	
	/*
	 * getter/setter
	 */
	private function get_rights(){
		return $this->rights;
	}
	private function set_rights($rights) {
		$this->rights = $rights;
	}
	
	/*
	 * constructor/destructor
	 */
	public function __construct($table,$id) {
		
		// get right for given id
		$this->get_from_db($table,$id);
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
	private function get_from_db($table_name,$table_id) {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql-statement
		$stmt = $db->prepare(	'
						SELECT r.id,r.user_group,r.ug_id,r.rights
						FROM rights AS r
						WHERE r.table_name = ?
						AND r.ug_id = ?');
		
		// insert variables
		$stmt->bind_param('is',$table_id,$table_name);
		
		// execute statement
		$stmt->execute();
		
		// bind variables to result
		$id = $ug_id = 0; $user_group = $rights = '';
		$stmt->bind_result($id,$user_group,$ug_id,$rights);
		
		// fetch result
		$all_rights = array();
		while($stmt->fetch()); {
		
			// set variables to array
			$all_rights[$id] = array(
								'user_group' => $user_group,
								'ug_id' => $ug_id,
								'rights' => $rights);
			
			$this->set_rights($all_rights);
		}
		
		// close db
		$stmt->close();
		$db->close();
	}
	
	
	
	
	
	
	
	/**
	 * get_authoized_entries returns an array with id of the given table
	 * for that the user has sufficient rights
	 * 
	 * @param string $table tablename to get ids from
	 * @return array array containing ids for that the user has sufficient rights
	 */
	public static function get_authorized_entries($table) {
		
		// get userid and groups
		$userid = $_SESSION['user']->userid();
		$group_ids = $_SESSION['user']->groups();
		
		// get db-object
		$db = Db::newDb();
		
		// groups
		$sql_groups = '	SELECT r.table_id
						FROM rights AS r
						WHERE r.table_name = ?
						AND r.user_group = "group"
						AND r.ug_id = (';
		for($i = 0;$i<count($group_ids);$i++) {
			
			// last entry
			if($group_ids[$i]==count($group_ids)-1){
				$sql_groups .= $group_ids[$i];
			} else {
				$sql_groups .= $group_ids[$i].' OR ';
			}
		}
		$sql_groups .= ')';
		
		
		// prepare statement
		$stmt = $db->prepare($sql_groups);
		
		// insert variables
		$stmt->bind_param('s',$table);
		
		// execute
		$stmt->execute();
		
		// fetch results
		$entry_id = 0;
		$stmt->bind_result($entry_id);
		$entry_ids = array();
		while($stmt->fetch()) {
			$entry_ids[] = $entry_id;
		}
		
		// close statement
		$stmt->close();
		
		
		// user
		$sql_user = '	SELECT r.table_id
						FROM rights AS r
						WHERE r.table_name = ?
						AND r.user_group = "user"
						AND r.ug_id = ?';
		
		// prepare statement
		$stmt = $db->prepare($sql_user);
		
		// insert variables
		$stmt->bind_param('si',$table,$userid);
		
		// execute
		$stmt->execute();
		
		// fetch results
		$stmt->bind_result($entry_id);
		while($stmt->fetch()) {
			$entry_ids[] = $entry_id;
		}
		
		// close db
		$stmt->close();
		$db->close();
		
		// unique $calendar_ids
		$entry_ids = array_unique($entry_ids,SORT_NUMERIC);
		
		// return
		return $entry_ids;
	}
}



?>
