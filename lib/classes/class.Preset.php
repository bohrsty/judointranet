<?php
/* ********************************************************************************************
 * Copyright (c) 2011 Nils Bohrs
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this
 * software and associated documentation files (the "Software"), to deal in the Software
 * without restriction, including without limitation the rights to use, copy, modify, merge,
 * publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons
 * to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or
 * substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
 * PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE
 * FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 * 
 * Thirdparty licenses see LICENSE
 * 
 * ********************************************************************************************/

// secure against direct execution
if(!defined("JUDOINTRANET")) {die("Cannot be executed directly! Please use index.php.");}

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
	private $path;
	private $filename;
	private $view;
	private $useDraft;
	
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
	public function get_path(){
		return $this->path;
	}
	public function set_path($path) {
		$this->path = $path;
	}
	public function get_filename(){
		return $this->filename;
	}
	public function set_filename($filename) {
		$this->filename = $filename;
	}
	public function getView(){
		return $this->view;
	}
	public function setView(&$view) {
		$this->view = $view;
		
		// check view in fields
		$fields = $this->get_fields();
		// if not set in fields (view manually added via setView())
		if(isset($fields[0]) && $fields[0]->getView() == null) {
			
			// set view in any field
			foreach($fields as $field) {
				$field->setView($view);
			}
		}
	}
	public function getUseDraft(){
		return $this->useDraft;
	}
	public function setUseDraft($useDraft) {
		$this->useDraft = $useDraft;
	}
	
	
	/*
	 * constructor/destructor
	 */
	public function __construct($id,$table,$table_id=null, &$view=null) {
	
		// parent constructor
		parent::__construct();
		
		// set view
		$this->setView($view);
		
		// get field for given id
		$this->get_from_db($id);
		if(!is_null($table_id)) {
			$this->read_fields($id,$table,$table_id);
		}
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
		$sql = "SELECT p.name,p.desc,p.path,p.filename, p.use_draft
				FROM preset AS p
				WHERE p.id = $id";
		
		// execute
		$result = $db->query($sql);
		
		// fetch result
		list($name,$desc,$path,$filename, $useDraft) = $result->fetch_array(MYSQLI_NUM);
		
		// set variables to object
		$this->set_id($id);
		$this->set_name($name);
		$this->set_desc($desc);
		$this->set_path($path);
		$this->set_filename($filename);
		$this->setUseDraft($useDraft);
		
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
		$fields[-1] = null;
		$tempFields = array();
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql-statement
		$sql = "SELECT f2p.field_id
				FROM fields2presets AS f2p
				WHERE f2p.pres_id = $id";
		
		// execute
		$result = $db->query($sql);
		
		// fetch result
		while(list($field_id) = $result->fetch_array(MYSQLI_NUM)) {
			
			$tempId = $this->get_id();
			$tempView = $this->getView();
			$tempFields[] = new Field($field_id,$table,$table_id, $tempId, $tempView);
		}
		
		// walk through fields
		foreach($tempFields as $tempField) {
			$fields[$tempField->get_id()] = $tempField;
		}
		
		// unset draft field if not used
		if($this->getUseDraft() == 0) {
			 unset($fields[-1]);
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
			
			$field->readValue();
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
		while(list($id,$name) = $result->fetch_array(MYSQLI_NUM)) {
			$presets[$id] = $name;
		}
		
		// close db
		$db->close();
		
		// return
		return $presets;
	}
	
	
	/**
	 * readAllTplPaths reads all preset path information from db and returns them
	 * as an array with the preset id as key
	 * 
	 * @param string $table table of the paths to be read
	 * @return array array containing all preset paths
	 */
	public static function readAllPaths($table) {
		
		// prepare sql-statement
		$sql = 'SELECT `id`,`path`
				FROM `preset`
				WHERE `table`=\'#?\'';
		
		// execute
		$result = Db::resultValue(
				$sql,
				array($table)
			);
		
		// fetch result
		$presets = array();
		if($result) {
			while(list($id, $path) = $result->fetch_array(MYSQLI_NUM)) {
				$presets[$id] = 'templates/protocols/tmce_'.$path.'.css';
			}
		} else {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
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
			$field->readValue();
			// get version
			$version = max(strtotime($announcement['version']), (int)$field->getLastModified());
			$announcement['version'] = date('d.m.Y', $version);

			// check html
			if($html === true) {
				$announcement['field_'.$field->get_id().'_name'] = nl2br(htmlentities($field->get_name(),ENT_QUOTES,'UTF-8'));
			} else {
				$announcement['field_'.$field->get_id().'_name'] = $field->get_name();
			}
			
			// check defaults
			if($field->get_value() == '' && $field->get_defaults() != 0) {
				// check html
				if($html === true) {
					$announcement['field_'.$field->get_id().'_value'] = nl2br(htmlentities($field->returnDefaultsValue($field->get_defaults()),ENT_QUOTES,'UTF-8'));
				} else {
					$announcement['field_'.$field->get_id().'_value'] = $field->returnDefaultsValue($field->get_defaults());
				}
			} else {
				
				// check type
				if($field->get_type() == 'dbselect') {
					
					// get separator from config
					$config = $field->get_config();
					
					// get value
					$values = $field->dbselectValue();
					// check multiple
					$all = array();
					if(isset($values[0])) {
						
						// walk through multiple
						foreach($values as $i => $multiple) {
							
							// walk through values
							foreach($multiple as $name => $value) {
								// check html
								if($html === true) {
									$announcement['field_'.$field->get_id().'_value_'.$i.'_'.$name] = nl2br(htmlentities($value,ENT_QUOTES,'UTF-8'));
								} else {
									$announcement['field_'.$field->get_id().'_value_'.$i.'_'.$name] = $value;
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
								$announcement['field_'.$field->get_id().'_value_all_'.$all_name] = nl2br(htmlentities($all_value,ENT_QUOTES,'UTF-8'));
							} else {
								$announcement['field_'.$field->get_id().'_value_all_'.$all_name] = $all_value;
							}
						}
					} else {
						
						// get values from db for empty all
						$sql = $config['sql'][1];
						$wherePos =strpos($sql, 'WHERE'); 
						// remove WHERE
						if($wherePos !== false) {
							$sql = substr($sql, 0, $wherePos -1);
						}
						// execute statement
						$result = DB::arrayValue($sql, MYSQLI_ASSOC);
						if(!$result) {
							$n = null;
							throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
						}
						
						// set empty "all"-values
						foreach($result[0] as $row => $temp) {
							$all[$row] = '';
						}
						
						// walk through values
						foreach($values as $name => $value) {
							// check html
							if($html === true) {
								$announcement['field_'.$field->get_id().'_value_0_'.$name] = nl2br(htmlentities($value,ENT_QUOTES,'UTF-8'));
							} else {
								$announcement['field_'.$field->get_id().'_value_0_'.$name] = $value;
							}
							
							// add to all
							$all[$name] .= $value.$config['separators'][$name];
						}
						
						foreach($all as $all_name => $all_value) {
							$all_value = substr($all_value,0,-strlen($config['separators'][$all_name]));
							
							// add to announcement
							// check html
							if($html === true) {
								$announcement['field_'.$field->get_id().'_value_all_'.$all_name] = nl2br(htmlentities($all_value,ENT_QUOTES,'UTF-8'));
							} else {
								$announcement['field_'.$field->get_id().'_value_all_'.$all_name] = $all_value;
							}
						}
					}
				} elseif($field->get_type() == 'dbhierselect') {
					
					// walk through values
					// check if value is set
					if(!is_null($field->dbhierselectValue())) {
						foreach($field->dbhierselectValue() as $name => $value) {
							// check html
							if($html === true) {
								$announcement['field_'.$field->get_id().'_value_'.$name] = nl2br(htmlentities($value,ENT_QUOTES,'UTF-8'));
							} else {
								$announcement['field_'.$field->get_id().'_value_'.$name] = $value;
							}
						}
					}
				} elseif($field->get_type() == 'date') {
					// check html
					if($html === true) {
						$announcement['field_'.$field->get_id().'_value'] = nl2br(htmlentities($field->get_value()));
						$announcement['field_'.$field->get_id().'_value_d_m_Y'] = nl2br(htmlentities(date('d.m.Y',strtotime($field->get_value())),ENT_QUOTES,'UTF-8'));
						$announcement['field_'.$field->get_id().'_value_j_F_Y'] = nl2br(htmlentities(strftime('%e. %B %Y',strtotime($field->get_value())),ENT_QUOTES,'UTF-8'));
						$announcement['field_'.$field->get_id().'_value_Y-m-d'] = nl2br(htmlentities(date('Y-m-d',strtotime($field->get_value())),ENT_QUOTES,'UTF-8'));
					} else {
						$announcement['field_'.$field->get_id().'_value'] = $field->get_value();
						$announcement['field_'.$field->get_id().'_value_d_m_Y'] = date('d.m.Y',strtotime($field->get_value()));
						$announcement['field_'.$field->get_id().'_value_j_F_Y'] = strftime('%e. %B %Y',strtotime($field->get_value()));
						$announcement['field_'.$field->get_id().'_value_Y-m-d'] = date('Y-m-d',strtotime($field->get_value()));
					}
				} else {
					// check html
					if($html === true) {
						$announcement['field_'.$field->get_id().'_value'] = nl2br(htmlentities($field->get_value(),ENT_QUOTES,'UTF-8'));
					} else {
						$announcement['field_'.$field->get_id().'_value'] = $field->get_value();
					}
				}
			}
		}
	}
	
	
	/**
	 * fieldById($id) returns the field with the given $id or false
	 * 
	 * @param int $id id of the field to be returned
	 * @return mixed field object, if $id exists, false otherwise
	 */
	public function fieldById($id) {
		
		// get fields
		$fields = $this->get_fields();
		
		// check if $id exists
		if(isset($fields[$id])) {
			return $fields[$id];
		} else {
			return false;
		}
	}
}



?>
