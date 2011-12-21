<?php


/**
 * class calendar implements a date (i.e. event)
 */
class Calendar extends Object {
	
	/*
	 * class-variables
	 */
	private $id;
	private $name;
	private $shortname;
	private $date;
	private $type;
	private $ann_id;
	
	/*
	 * getter/setter
	 */
	private function get_id(){
		return $this->id;
	}
	private function set_id($id) {
		$this->id = $id;
	}
	private function get_name(){
		return $this->name;
	}
	private function set_name($name) {
		$this->name = $name;
	}
	private function get_shortname(){
		return $this->shortname;
	}
	private function set_shortname($shortname) {
		$this->shortname = $shortname;
	}
	private function get_date(){
		return $this->date;
	}
	private function set_date($date) {
		$this->date = $date;
	}
	private function get_type(){
		return $this->type;
	}
	private function set_type($type) {
		$this->type = $type;
	}
	private function get_ann_id(){
		return $this->ann_id;
	}
	private function set_ann_id($ann_id) {
		$this->ann_id = $ann_id;
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
	 * get_from_db gets the calendar for the given calendarid
	 * 
	 * @param int $id id of the calendarentry
	 * @return void
	 */
	private function get_from_db($id) {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql-statement
		$stmt = $db->prepare(	'
						SELECT c.name,c.shortname,c.date,c.type,c.ann_id
						FROM calendar AS c
						WHERE c.id = ?');
		
		// insert variables
		$stmt->bind_param('i',$id);
		
		// execute statement
		$stmt->execute();
		
		// bind variables to result
		$name = $shortname = $date = $type = ''; $ann_id = 0;
		$stmt->bind_result($name,$shortname,$date,$type,$ann_id);
		
		// fetch result
		$stmt->fetch();
		
		// set variables to object
		$this->set_id($id);
		$this->set_name($name);
		$this->set_shortname($shortname);
		$this->set_date($date);
		$this->set_type($type);
		$this->set_ann_id($ann_id);
		
		// close db
		$stmt->close();
		$db->close();
	}
}



?>
