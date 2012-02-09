<?php


/**
 * class Preset implements a preset (combination of fields)
 */
class Preset extends Object {
	
	/*
	 * class-variables
	 */
	private $id;
	private $name;
	private $desc;
	private $fields;
	
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
	private function get_desc(){
		return $this->desc;
	}
	private function set_desc($desc) {
		$this->desc = $desc;
	}
	private function get_fields(){
		return $this->fields;
	}
	private function set_fields($fields) {
		$this->fields = $fields;
	}
	
	/*
	 * constructor/destructor
	 */
	public function __construct($id,$table,$table_id) {
		
		// parent constructor
		parent::__construct();
		
		// get field for given id
		$this->get_from_db($id);
		$this->read_fields($id,$table,$table_id);
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
		$sql = "SELECT p.name,p.desc
				FROM preset AS p
				WHERE p.id = $id";
		
		// execute
		$result = $db->query($sql);
		
		// fetch result
		list($name,$desc) = $result->fetch_array(MYSQL_NUM);
		
		// set variables to object
		$this->set_id($id);
		$this->set_name($name);
		$this->set_desc($desc);
		
		// close db
		$db->close();
	}
	
	
	
	
	
	
	
	/**
	 * read_fields reads the fields from db
	 * 
	 * @param int $id the id of this preset
	 * @param string $table name of the table the field is attached to
	 * @param int $table_id id of the element in $table
	 * @return void
	 */
	private function read_fields($id,$table,$table_id) {
		
		// prepare return
		$fields = array();
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql-statement
		$sql = "SELECT f2p.field_id
				FROM fields2presets AS f2p
				WHERE f2p.preset_id = $id";
		
		// execute
		$result = $db->query($sql);
		
		// fetch result
		while(list($field_id) = $result->fetch_array(MYSQL_NUM)) {
			
			$fields[] = new Field($id,$table,$table_id);
		}
		
		// close db
		$db->close();
		
		// set
		$this->set_fields($fields);
	}
	
	
	
	
	
	
	
	/**
	 * return_fields returns the value of $fields
	 * 
	 * @return array array containing the field-objects
	 */
	public function return_fields() {
		
		// return
		return $this->get_fields();
	}
}



?>
