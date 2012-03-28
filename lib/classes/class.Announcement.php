<?php


/**
 * class Announcement implements a announcement for a calenderentry
 */
class Announcement extends Page {
	
	/*
	 * class-variables
	 */
	private $name;
	private $preset;
	
	/*
	 * getter/setter
	 */
	private function get_name(){
		return $this->name;
	}
	private function set_name($name) {
		$this->name = $name;
	}
	private function get_preset(){
		return $this->preset;
	}
	private function set_preset($preset) {
		$this->preset = $preset;
	}
	
	/*
	 * constructor/destructor
	 */
	public function __construct($id) {
		
		// parent constructor
		parent::__construct();
		
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
		$sql = "
			SELECT a.name,a.pres_id
			FROM announcement AS a
			WHERE a.id = $id";
		
		// execute
		$result = $db->query($sql);
		
		// fetch result
		list($name,$pres_id) = $result->fetch_array(MYSQL_NUM);
		
		// set variables to object
		$this->set_id($id);
		$this->set_name($name);
		$this->set_preset(new Preset($pres_id,strtolower(get_class($this)),$id));
		
		// close db
		$db->close();
	}
	
	
	
	
	
	
	/**
	 * return_announcements returns an array containing all announcements the
	 * user has rights to
	 * 
	 * @return array array containing the ann_ids the user has rights to
	 */
	public static function return_announcements() {
		
		// get ids
		$return = Rights::get_authorized_entries('announcement');
		
		// return
		return $return;
	}
	
	
	
	
	
	
	/**
	 * return_preset returns the value of $preset
	 * 
	 * @return object the preset-object attached to this announcement
	 */
	public function return_preset() {
		
		// return
		return $this->get_preset();
	}
}



?>
