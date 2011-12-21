<?php


/**
 * class Announcement implements a announcement for a calenderentry
 */
class Announcement extends Object {
	
	/*
	 * class-variables
	 */
	private $id;
	private $pres_id;
	
	/*
	 * getter/setter
	 */
	private function get_id(){
		return $this->id;
	}
	private function set_id($id) {
		$this->id = $id;
	}
	private function get_pres_id(){
		return $this->pres_id;
	}
	private function set_pres_id($pres_id) {
		$this->pres_id = $pres_id;
	}
	
	/*
	 * constructor/destructor
	 */
	public function __construct($id) {
		
		// get field for given id
		$this->get_from_db($id);
	}
	
	/*
	 * methods
	 */
	/**
	 * get_from_db gets the announcement for the given announcementid
	 * 
	 * @param int $id id of the announcemententry
	 * @return void
	 */
	private function get_from_db($id) {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql-statement
		$stmt = $db->prepare(	'
						SELECT a.pres_id
						FROM announcement AS a
						WHERE a.id = ?');
		
		// insert variables
		$stmt->bind_param('i',$id);
		
		// execute statement
		$stmt->execute();
		
		// bind variables to result
		$pres_id = 0;
		$stmt->bind_result($pres_id);
		
		// fetch result
		$stmt->fetch();
		
		// set variables to object
		$this->set_id($id);
		$this->set_pres_id($pres_id);
		
		// close db
		$stmt->close();
		$db->close();
	}
}



?>
