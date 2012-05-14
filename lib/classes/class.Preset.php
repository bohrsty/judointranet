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
	public function get_id(){
		return $this->id;
	}
	public function set_id($id) {
		$this->id = $id;
	}
	public function get_name(){
		return $this->name;
	}
	public function set_name($name) {
		$this->name = $name;
	}
	public function get_desc(){
		return $this->desc;
	}
	public function set_desc($desc) {
		$this->desc = $desc;
	}
	public function get_fields(){
		return $this->fields;
	}
	public function set_fields($fields) {
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
				WHERE f2p.pres_id = $id";
		
		// execute
		$result = $db->query($sql);
		
		// fetch result
		while(list($field_id) = $result->fetch_array(MYSQL_NUM)) {
			
			$fields[] = new Field($field_id,$table,$table_id,$this->get_id());
		}
		
		// close db
		$db->close();
		
		// set
		$this->set_fields($fields);
	}
	
	
	
	
	
	
	
	/**
	 * read_field_values reads the value of each attached field
	 */
	public function read_field_values() {
		
		// walk through fields
		foreach($this->get_fields() as $field) {
			
			$field->read_value();
		}
	}
	
	
	
	
	
	
	
	/**
	 * read_all_preset reads all preset-ids and name from db and returns them
	 * as an array
	 * 
	 * @return array array containing all presets
	 */
	public static function read_all_presets($table) {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql-statement
		$sql = "SELECT p.id,p.name
				FROM preset AS p
				WHERE p.table='$table'";
		
		// execute
		$result = $db->query($sql);
		
		// fetch result
		$presets = array();
		while(list($id,$name) = $result->fetch_array(MYSQL_NUM)) {
			$presets[$id] = $name;
		}
		
		// close db
		$db->close();
		
		// return
		return $presets;
	}
	
	
	
	
	/**
	 * check_preset checks if the given id exists in db and is of $table
	 * 
	 * @param int $id id of the preset
	 * @param string $table tablename the id is associated with
	 * @return bool true if id exists and match $table, false otherwise
	 */
	public static function check_preset($id,$table) {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql
		$sql = "SELECT p.id,p.table
				FROM preset AS p
				WHERE id=$id
				AND p.table='$table'";
		
		// execute
		$result = $db->query($sql);
		
		if($result->num_rows == 0) {
			return false;
		} else {
			return true;
		}
	}
	
	
	
	
	/**
	 * add_marks adds the marks and values to the given array
	 * 
	 * @param array $announcement array to fill with marks and values
	 * @param boolean $html convert special chars for html if true, does not if false
	 * @return void
	 */
	public function add_marks(&$announcement,$html=true) {
		
		// add fields
		$fields = $this->get_fields();
		
		// walk through fields
		foreach($fields as $field) {
			
			// read value
			$field->read_value();
			// check html
			if($html === true) {
				$announcement['field-'.$field->get_id().'-name'] = nl2br(htmlentities($field->get_name(),ENT_QUOTES,'UTF-8'));
			} else {
				$announcement['field-'.$field->get_id().'-name'] = $field->get_name();
			}
			
			// check defaults
			if($field->get_value() == '' && $field->get_defaults() != 0) {
				// check html
				if($html === true) {
					$announcement['field-'.$field->get_id().'-value'] = nl2br(htmlentities($field->return_defaults_value($field->get_defaults()),ENT_QUOTES,'UTF-8'));
				} else {
					$announcement['field-'.$field->get_id().'-value'] = $field->return_defaults_value($field->get_defaults());
				}
			} else {
				
				// check type
				if($field->get_type() == 'dbselect') {
					
					// get separator from config
					$config = $field->get_config();
					
					// get value
					$values = $field->dbselect_value();
					// check multiple
					$all = array();
					if(isset($values[0])) {
						
						// walk through multiple
						foreach($values as $i => $multiple) {
							
							// walk through values
							foreach($multiple as $name => $value) {
								// check html
								if($html === true) {
									$announcement['field-'.$field->get_id().'-value.'.$i.'.'.$name] = nl2br(htmlentities($value,ENT_QUOTES,'UTF-8'));
								} else {
									$announcement['field-'.$field->get_id().'-value.'.$i.'.'.$name] = $value;
								}
								
								// add to all
								if(!isset($all[$name])) {
									$all[$name] = $value.$config['separators'][$name];
								} else {
									$all[$name] .= $value.$config['separators'][$name];
								}
							}
						}
						
						foreach($all as $all_name => $all_value) {
							$all_value = substr($all_value,0,-strlen($config['separators'][$all_name]));
							
							// add to announcement
							// check html
							if($html === true) {
								$announcement['field-'.$field->get_id().'-value.all.'.$all_name] = nl2br(htmlentities($all_value,ENT_QUOTES,'UTF-8'));
							} else {
								$announcement['field-'.$field->get_id().'-value.all.'.$all_name] = $all_value;
							}
						}
					} else {
						
						// walk through values
						foreach($values as $name => $value) {
							// check html
							if($html === true) {
								$announcement['field-'.$field->get_id().'-value.0.'.$name] = nl2br(htmlentities($value,ENT_QUOTES,'UTF-8'));
							} else {
								$announcement['field-'.$field->get_id().'-value.0.'.$name] = $value;
							}
							
							// add to all
							if(!isset($all[$name])) {
								$all[$name] = $value.$config['separators'][$name];
							} else {
								$all[$name] .= $value.$config['separators'][$name];
							}
						}
						
						foreach($all as $all_name => $all_value) {
							$all_value = substr($all_value,0,-strlen($config['separators'][$all_name]));
							
							// add to announcement
							// check html
							if($html === true) {
								$announcement['field-'.$field->get_id().'-value.all.'.$all_name] = nl2br(htmlentities($all_value,ENT_QUOTES,'UTF-8'));
							} else {
								$announcement['field-'.$field->get_id().'-value.all.'.$all_name] = $all_value;
							}
						}
					}
				} elseif($field->get_type() == 'dbhierselect') {
					
					// walk through values
					foreach($field->dbhierselect_value() as $name => $value) {
						// check html
						if($html === true) {
							$announcement['field-'.$field->get_id().'-value.'.$name] = nl2br(htmlentities($value,ENT_QUOTES,'UTF-8'));
						} else {
							$announcement['field-'.$field->get_id().'-value.'.$name] = $value;
						}
					}
				} elseif($field->get_type() == 'date') {
					// check html
					if($html === true) {
						$announcement['field-'.$field->get_id().'-value'] = nl2br(htmlentities($field->get_value()));
						$announcement['field-'.$field->get_id().'-value-d.m.Y'] = nl2br(htmlentities(date('d.m.Y',strtotime($field->get_value())),ENT_QUOTES,'UTF-8'));
						$announcement['field-'.$field->get_id().'-value-j.F.Y'] = nl2br(htmlentities(strftime('%e. %B %Y',strtotime($field->get_value())),ENT_QUOTES,'UTF-8'));
					} else {
						$announcement['field-'.$field->get_id().'-value'] = $field->get_value();
						$announcement['field-'.$field->get_id().'-value-d.m.Y'] = date('d.m.Y',strtotime($field->get_value()));
						$announcement['field-'.$field->get_id().'-value-j.F.Y'] = strftime('%e. %B %Y',strtotime($field->get_value()));
					}
				} else {
					// check html
					if($html === true) {
						$announcement['field-'.$field->get_id().'-value'] = nl2br(htmlentities($field->get_value(),ENT_QUOTES,'UTF-8'));
					} else {
						$announcement['field-'.$field->get_id().'-value'] = $field->get_value();
					}
				}
			}
		}
	}
}



?>
