<?php


/**
 * class Preset implements a preset (combination of fields)
 */
class Preset extends Object {
	
	/*
	 * class-variables
	 */
	private $id;
	private $layout;
	
	/*
	 * getter/setter
	 */
	private function get_id(){
		return $this->id;
	}
	private function set_id($id) {
		$this->id = $id;
	}
	private function get_layout(){
		return $this->layout;
	}
	private function set_layout($layout) {
		$this->layout = $layout;
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
	 * get_from_db gets the preset for the given presetid
	 * 
	 * @param int $id id of the fieldentry
	 * @return void
	 */
	private function get_from_db($id) {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql-statement
		$stmt = $db->prepare(	'
						SELECT p.layout
						FROM preset AS p
						WHERE p.id = ?');
		
		// insert variables
		$stmt->bind_param('i',$id);
		
		// execute statement
		$stmt->execute();
		
		// bind variables to result
		$layout = '';
		$stmt->bind_result($layout);
		
		// fetch result
		$stmt->fetch();
		
		// set variables to object
		$this->set_id($id);
		$this->set_layout($layout);
		
		// close db
		$stmt->close();
		$db->close();
	}
}



?>
