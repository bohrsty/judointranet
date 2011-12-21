<?php


/**
 * class Rights implements rightmanagement
 */
class Rights extends Object {
	
	/*
	 * class-variables
	 */
	private $id;
	private $user;
	private $group;
	private $userrights;
	private $grouprights;
	
	/*
	 * getter/setter
	 */
	private function get_id(){
		return $this->id;
	}
	private function set_id($id) {
		$this->id = $id;
	}
	private function get_user(){
		return $this->user;
	}
	private function set_user($user) {
		$this->user = $user;
	}
	private function get_group(){
		return $this->group;
	}
	private function set_group($group) {
		$this->group = $group;
	}
	private function get_userrights(){
		return $this->userrights;
	}
	private function set_userrights($userrights) {
		$this->userrights = $userrights;
	}
	private function get_grouprights(){
		return $this->grouprights;
	}
	private function set_grouprights($grouprights) {
		$this->grouprights = $grouprights;
	}
	
	/*
	 * constructor/destructor
	 */
	public function __construct($id) {
		
		// get right for given id
		$this->get_from_db($id);
	}
	
	/*
	 * methods
	 */
	/**
	 * get_from_db gets the rightconfig for the given rightentry
	 * 
	 * @param int $id id of the rightentry
	 * @return void
	 */
	private function get_from_db($id) {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql-statement
		$stmt = $db->prepare(	'
						SELECT r.user_id,r.group_id,r.user_rights,r.group_rights
						FROM right AS r
						WHERE r.id = ?');
		
		// insert variables
		$stmt->bind_param('i',$id);
		
		// execute statement
		$stmt->execute();
		
		// bind variables to result
		$user_id = $group_id = 0; $userrights = $grouprights = '';
		$stmt->bind_result($user_id,$group_id,$userrights,$grouprights);
		
		// fetch result
		$stmt->fetch();
		
		// set variables to object
		$this->set_id($id);
		$this->set_user($user_id);
		$this->set_group($group_id);
		$this->set_userrights($userrights);
		$this->set_grouprights($grouprights);
		
		// close db
		$stmt->close();
		$db->close();
	}
}



?>
