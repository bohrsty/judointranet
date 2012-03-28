<?php


/**
 * class Value implements a value for a field per announcement
 */
class Value extends Object {
	
	/*
	 * class-variables
	 */
	private $id;
	private $ann_id;
	private $field_id;
	private $value;
	
	/*
	 * getter/setter
	 */
	private function get_id(){
		return $this->id;
	}
	private function set_id($id) {
		$this->id = $id;
	}
	private function get_ann_id(){
		return $this->ann_id;
	}
	private function set_ann_id($ann_id) {
		$this->ann_id = $ann_id;
	}
	private function get_field_id(){
		return $this->field_id;
	}
	private function set_field_id($field_id) {
		$this->field_id = $field_id;
	}
	private function get_value(){
		return $this->value;
	}
	private function set_value($value) {
		$this->value = $value;
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
	 * get_from_db gets the valueentrys for the given valueid
	 * 
	 * @param int $id id of the valueentry
	 * @return void
	 */
	private function get_from_db($id) {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql-statement
		$stmt = $db->prepare(	'
						SELECT v.ann_id,v.field_id,v.value
						FROM value AS v
						WHERE v.id = ?');
		
		// insert variables
		$stmt->bind_param('i',$id);
		
		// execute statement
		$stmt->execute();
		
		// bind variables to result
		$ann_id = $field_id = 0; $value = '';
		$stmt->bind_result($ann_id,$field_id,$value);
		
		// fetch result
		$stmt->fetch();
		
		// set variables to object
		$this->set_id($id);
		$this->set_ann_id($ann_id);
		$this->set_field_id($field_id);
		$this->set_value($value);
		
		// close db
		$stmt->close();
		$db->close();
	}
}



?>
