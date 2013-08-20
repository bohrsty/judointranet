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
 * class Field implements fieldconfig
 */
class Field extends Object {
	
	/*
	 * class-variables
	 */
	private $id;
	private $name;
	private $type;
	private $quickform;
	private $value;
	private $table;
	private $table_id;
	private $required;
	private $category;
	private $defaults;
	private $config;
	
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
	public function get_type(){
		return $this->type;
	}
	public function set_type($type) {
		$this->type = $type;
	}
	public function get_quickform(){
		return $this->quickform;
	}
	public function set_quickform($quickform) {
		$this->quickform = $quickform;
	}
	public function get_value(){
		return $this->value;
	}
	public function set_value($value) {
		$this->value = $value;
	}
	public function get_table(){
		return $this->table;
	}
	public function set_table($table) {
		$this->table = $table;
	}
	public function get_table_id(){
		return $this->table_id;
	}
	public function set_table_id($table_id) {
		$this->table_id = $table_id;
	}
	public function get_required(){
		return $this->required;
	}
	public function set_required($required) {
		$this->required = $required;
	}
	public function get_category(){
		return $this->category;
	}
	public function set_category($category) {
		$this->category = $category;
	}
	public function get_defaults(){
		return $this->defaults;
	}
	public function set_defaults($defaults) {
		$this->defaults = $defaults;
	}
	public function get_config(){
		return $this->config;
	}
	public function set_config($config) {
		$this->config = $config;
	}
	
	/*
	 * constructor/destructor
	 */
	public function __construct($id,$table,$table_id,$pid) {
		
		// parent constructor
		parent::__construct();
		
		// get field for given id
		$this->get_from_db($id,$pid);
		
		// set class variables
		$this->set_table($table);
		$this->set_table_id($table_id);
	}
	
	/*
	 * methods
	 */
	/**
	 * get_from_db gets the fieldconfig for the given fieldid
	 * 
	 * @param int $id id of the fieldentry
	 * @param int $pid id of the preset
	 * @return void
	 */
	private function get_from_db($id,$pid) {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql-statement
		$sql = "SELECT f.name,f.type,f2p.required,f.category,f.config
				FROM field AS f,fields2presets AS f2p
				WHERE f.id = $id
				AND f2p.field_id = $id
				AND f2p.pres_id = $pid";
		
		// execute
		$result = $db->query($sql);
		
		// fetch result
		list($name,$type,$required,$category,$config) = $result->fetch_array(MYSQL_NUM);
		
		// set variables to object
		$this->set_id($id);
		$this->set_name($name);
		$this->set_type($type);
		$this->set_required($required);
		$this->set_category($category);
		$this->set_config(unserialize(stripcslashes($config)));
		
		// close db
		$db->close();
	}
	
	
	
	
	
	
	/**
	 * read_quickform set a HTML_Quickform2_Element-object to $quickform according
	 * to the $type
	 * 
	 * @param array $options array containing parameters for the input-tag
	 * @param bool $defaults text-fields with default-values if true
	 * @return string the value of the "id"-parameter of the input-tag
	 */
	public function read_quickform($options = array(),$defaults = false) {
		
		// prepare return
		$element = null;
		
		// prepare ids
		$element_ids = '';
		
		// check type
		if($this->get_type() == 'text') {
			
			// check defaults
			if($defaults === true) {
				
				// field-group
				$element = HTML_QuickForm2_Factory::createElement('group', $this->get_table().'-'.$this->get_id(),$options);
				$element->setLabel($this->get_name().':&nbsp;'.$GLOBALS['help']->getMessage(HELP_MSG_FIELDTEXT));
				
				// add select
				$select = $element->addElement('select','defaults',array());
				$select->setLabel(parent::lang('class.Field#element#label#textarea.defaults'));
				
				// add textarea
				$textarea = $element->addElement('textarea','manual',array());
				$textarea->setLabel(parent::lang('class.Field#element#label#textarea.manual'));
								
				// add options
				$this->read_defaults($select);
			} else {
				
				// textarea
				$element = HTML_QuickForm2_Factory::createElement('textarea', $this->get_table().'-'.$this->get_id(),$options);
				$element->setLabel($this->get_name().':&nbsp;'.$GLOBALS['help']->getMessage(HELP_MSG_FIELDTEXT));
				
				// add rules
				if($this->get_required() == 1) {
					$element->addRule('required',parent::lang('class.Field#element#rule#required.text'));
				}
				$element->addRule(
					'regex',
					parent::lang('class.Field#element#rule#regexp.allowedChars').' ['.$_SESSION['GC']->get_config('textarea.desc').']',
					$_SESSION['GC']->get_config('textarea.regexp'));
			}
			
			// add id to return
			$element_ids = $this->get_table().'-'.$this->get_id();
		} elseif($this->get_type() == 'date') {
			
			// date in input-text for use with jquery
			$element = HTML_QuickForm2_Factory::createElement('text', $this->get_table().'-'.$this->get_id(),$options);
			$element->setLabel($this->get_name().':&nbsp;'.$GLOBALS['help']->getMessage(HELP_MSG_FIELDDATE));
			
			// add rules
			if($this->get_required() == 1) {
				$element->addRule('required',parent::lang('class.Field#element#rule#required.date'));
			}
			$element->addRule('callback',parent::lang('class.Field#element#rule#check.date'),array($this,'callback_check_date'));
			
			// add id to return
			$element_ids = $this->get_table().'-'.$this->get_id();
		} elseif($this->get_type() == 'checkbox') {
			
			// checkbox
			$element = HTML_QuickForm2_Factory::createElement('checkbox', $this->get_table().'-'.$this->get_id(),$options);
			$element->setLabel($this->get_name().':&nbsp;'.$GLOBALS['help']->getMessage(HELP_MSG_FIELDCHECKBOX));
			
			// add rules
			if($this->get_required() == 1) {
				$element->addRule('required',parent::lang('class.Field#element#rule#required.checkbox'));
			}
			
			// add id to return
			$element_ids = $this->get_table().'-'.$this->get_id();
		} elseif($this->get_type() == 'dbselect') {
			
			// read config
			$config = $this->get_config();
			
			// merge options
			$options = array_merge($options,$config['options']);
			
			// select
			$element = HTML_QuickForm2_Factory::createElement('select', $this->get_table().'-'.$this->get_id(),$options);
			$element->setLabel($this->get_name().':&nbsp;'.$GLOBALS['help']->getMessage(HELP_MSG_FIELDDBSELECT));
			
			// add rules
			if($this->get_required() == 1) {
				$element->addRule('required',parent::lang('class.Field#element#rule#required.checkbox'));
				$element->addRule('callback',parent::lang('class.Field#entry#rule#check.select'),array($this,'callback_check_select'));
			}
			
			// add id to return
			$element_ids = $this->get_table().'-'.$this->get_id();
			
			// get options from field-config
			$field_options = array('--');
			$this->dbselect_options($field_options);
			
			// load options
			$element->loadOptions($field_options);
		} elseif($this->get_type() == 'dbhierselect') {
			
			// select
			$element = HTML_QuickForm2_Factory::createElement('hierselect', $this->get_table().'-'.$this->get_id(),$options);
			$element->setLabel($this->get_name().':&nbsp;'.$GLOBALS['help']->getMessage(HELP_MSG_FIELDDBHIERSELECT));
			
			// add rules
			if($this->get_required() == 1) {
				$element->addRule('required',parent::lang('class.Field#element#rule#required.checkbox'));
				$element->addRule('callback',parent::lang('class.Field#entry#rule#check.hierselect'),array($this,'callback_check_hierselect'));
			}
			
			// add id to return
			$element_ids = $this->get_table().'-'.$this->get_id();
			
			// get options from field-config
			$field_options_first[0] = '--';
			$field_options_second[0][0] = '--';
			$this->dbhierselect_options($field_options_first,$field_options_second);
						
			// load options
			$element->loadOptions(array($field_options_first,$field_options_second));
		}
		
		// set
		$this->set_quickform($element);
		
		// return
		return $element_ids;
	}
	
	
	
	
	
	
	/**
	 * read_value reads the actual value from the db
	 * 
	 * @param array $data values (table, table_id, field_id) can override the instancevariables
	 * @return void
	 */
	public function read_value($data = null) {
		
		// prepare return
		$value = '';
		$defaults = '';
		
		// get db-object
		$db = Db::newDb();
		
		// check override
		if(is_null($data)) {
			$table = $this->get_table();
			$table_id = $this->get_table_id();
			$field_id = $this->get_id();
		} else {
			$table = $data['table'];
			$table_id = $data['table_id'];
			$field_id = $data['field_id'];
		}
		
		// prepare sql-statement
		$sql = "SELECT v.value,v.defaults
				FROM value AS v
				WHERE v.table_name = '$table'
				AND v.table_id = $table_id
				AND v.field_id = $field_id";
		
		// execute
		$result = $db->query($sql);
		
		// check if value is set
		if($result->num_rows != 0) {
			// fetch result
			list($value,$defaults) = $result->fetch_array(MYSQL_NUM);
		}
		
		// close db
		$db->close();
		
		// set
		$this->set_value($value);
		$this->set_defaults($defaults);
	}
	
	
	
	
	
	
	/**
	 * value sets the given value
	 * 
	 * @param mixed $value the value of the field
	 * @return void
	 */
	public function value($value) {
		
		// get db-object
		$db = Db::newDb();
		
		// check type
		$checked_value = '';
		$checked_default = 0;
		if($this->get_type() == 'text') {
			
			// check manual or default
			if($value['manual'] == '') {
				
				// get id and last-used or defaults
				$vtype = substr($value['defaults'],0,1);
				$vid = (int) substr($value['defaults'],1,strlen($value['defaults'])-1);
				
				if($vtype == 'd') {
					$checked_default = $vid;
				} else {
					
					// get last-used-value
					$result = $db->query("SELECT value FROM value WHERE id=$vid");
					list($lvalue) = $result->fetch_array(MYSQL_NUM);
					$checked_value = $lvalue;
				}
			} else {
				$checked_value = $value['manual'];
			}
		} elseif($this->get_type() == 'dbselect') {
			
			// check multiple
			if(is_array($value)) {
				
				// walk throug $value
				$checked_value = '';
				foreach($value as $v) {
					$checked_value .= $v.'|';
				}
				$checked_value = substr($checked_value,0,-1);
			} else {
				$checked_value = $value;
			}
		} elseif($this->get_type() == 'dbhierselect') {
			$checked_value = $value[0].'|'.$value[1];
		} else {
			$checked_value = $value;
		}
		
		// set classvariables
		$this->set_defaults($checked_default);
		$this->set_value($checked_value);
	}
	
	
	
	
	
	
	/**
	 * value_to_html returns the field and its $value as array
	 * 
	 * @return array array containing name and value of the field
	 */
	public function value_to_html() {
		
		// get values
		$value = $this->get_value();
		$defaults = $this->get_defaults();
		$name = $this->get_name();
		
		// check value
		$checked_value = '';
		if($this->get_type() == 'checkbox') {
			
			// check if not null
			if(isset($value) && $value == 1) {
				$checked_value = parent::lang('class.Field#value_to_html#checkbox.value#checked');
			} else {
				$checked_value = parent::lang('class.Field#value_to_html#checkbox.value#unchecked');
			}
			
			// return
			return array(
					'name' => $name,
					'value' => $checked_value
				);
		} elseif($this->get_type() == 'date') {
			$checked_value = date('d.m.Y',strtotime($value));
			
			// return
			return array(
					'name' => $name,
					'value' => $checked_value
				);
		} elseif($this->get_type() == 'text') {
			
			// check defaults
			if($value == '') {
				
				// get default-value
				if($defaults != '') {
					$checked_value = $this->return_defaults_value($defaults);
				} else {
					$checked_value = '';
				}
				
			} else {
				$checked_value = $value;
			}
			
			// return
			return array(
					'name' => $name,
					'value' => $checked_value
				);
		} elseif($this->get_type() == 'dbselect') {
			
			// get values
			$values = $this->dbselect_value();
			
			// check multiple
			if(isset($values[0])) {
				
				// walk through $value
				$checked_value = '';
				foreach($values as $v) {
					$checked_value .= $v['value'].', ';
				}
				$checked_value = substr($checked_value,0,-2);
			} else {
				$checked_value = $values['value'];
			}
			
			// return
			return array(
					'name' => $name,
					'value' => $checked_value
				);
		} elseif($this->get_type() == 'dbhierselect') {
			
			// get values
			$values = $this->dbhierselect_value();
			
			$checked_value = $values['value1'].'/'.$values['value2'];
			
			// return
			return array(
					'name' => $name,
					'value' => $checked_value
				);
		}
	}
	
	
	
	
	
	
	/**
	 * write_db writes the actual objectdata to the db
	 * 
	 * @param string $action insert inserts new values, update updates existing
	 * @return void
	 */
	public function write_db($action) {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql
		if($action == 'insert') {
			
			// insert
			$sql = "INSERT INTO value (id,table_name,table_id,field_id,value,defaults)
					VALUES (NULL,'".$this->get_table()."',".$this->get_table_id().",".$this->get_id().",'".$this->get_value()."',".$this->get_defaults().")";
		} else {
			
			// update
			$sql = "UPDATE value SET
					value='".$this->get_value()."',
					defaults=".$this->get_defaults()."
					WHERE field_id = ".$this->get_id()."
					AND table_id = ".$this->table_id."
					AND table_name = '".$this->get_table()."'";
		}
		
		// execute
		$db->query($sql);
	}
	
	
	
	
	
	
	/**
	 * delete_value deletes the value in db
	 * 
	 * @param int $table_id id of the value in $table
	 */
	public function delete_value() {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql
		$sql = "DELETE FROM value
				WHERE field_id = ".$this->get_id()."
				AND table_id = ".$this->get_table_id()."
				AND table_name = '".$this->get_table()."'";
		
		// execute
		$db->query($sql);
	}
	
	
	
	
	
	
	/**
	 * read_defaults adds the default-values and last-used-value to the
	 * given array
	 * 
	 * @param array $options array to add default- and last-used-values
	 */
	public function read_defaults(&$element) {
		
		// get db-object
		$db = Db::newDb();
		
		// get defaults
		// prepare sql
		$sql = "SELECT d.id,d.name
				FROM defaults AS d
				WHERE category='".$this->get_category()."'
				AND d.valid=1		
				ORDER BY d.name ASC";
		
		// execute
		$result = $db->query($sql);
		
		// add first option
		$element->addOption('--',0);
		
		// add default-optgroup
		$dOptgroup = $element->addOptgroup(parent::lang('class.Field#read_defaults#defaults#separator'));
		
		while(list($id,$name) = $result->fetch_array(MYSQL_NUM)) {
			
			// check name length
			$truncName = '';
			if(strlen($name) > 30) {
				$truncName = substr($name,0,27).'...';
			} else {
				$truncName = $name;
			}
			
			// add options
			$dOptgroup->addOption($truncName,'d'.$id,array('title' => $name));
		}
		
		// get last-used
		// get authorized calendar-ids
		$ids = Rights::get_authorized_entries($this->get_table());
		
		// prepare sql
		$sql = "SELECT v.id,v.table_id,v.value
				FROM value AS v,field AS f
				WHERE v.table_name='".$this->get_table()."'
				AND f.type='".$this->get_type()."'
				AND f.id=v.field_id
				ORDER BY v.id DESC
				LIMIT 30";
		
		// execute
		$result = $db->query($sql);
		
		// add last-optgroup
		$lOptgroup = $element->addOptgroup(parent::lang('class.Field#read_defaults#lastUsed#separator'));
		
		
		while(list($id,$table_id,$value) = $result->fetch_array(MYSQL_NUM)) {
			
			// check rights
			if(in_array((int) $table_id,$ids)) {
				
				// replace linebreak
				$value = str_replace(array("\r\n","\r","\n")," ",$value);
				
				// check value length
				$truncValue = '';
				if(strlen($value) > 30) {
					$truncValue = substr($value,0,27).'...';
				} else {
					$truncValue = $value;
				}
				
				// add options
				$lOptgroup->addOption($truncValue,'l'.$id);
			}
		}
	}
	
	
	
	
	
	
	/**
	 * return_defaults_value reads the value of the given defaults-id
	 * from db and returns it
	 * 
	 * @param int $defaults id of the defaults to get the value from
	 */
	public function return_defaults_value($defaults) {
		
		// get db-object
		$db = Db::newDb();
		
		$result = $db->query("SELECT value FROM defaults WHERE id=$defaults");
		list($value) = $result->fetch_array(MYSQL_NUM);
		
		// return
		return $value;
	}
	
	
	
	
	
	
	/**
	 * dbselect_options gets the options for the quickform from
	 * db using the field-config (select_list#select_value)
	 * 
	 * @param array $options array to add the options
	 */
	private function dbselect_options(&$options) {
		
		// get db-object
		$db = Db::newDb();
		
		// get config
		$config = $this->get_config();
		
		// execute query
		$result = $db->query($config['sql'][0]);
		
		// fetch result
		while(list($id,$name) = $result->fetch_array(MYSQL_NUM)) {
			$options[$id] = $name;
		}
		
	}
	
	
	
	
	
	
	/**
	 * dbselect_value the text value for this field from
	 * db using the field-config (select_list#select_value)
	 * 
	 * @return string value from db
	 */
	public function dbselect_value() {
		
		// get db-object
		$db = Db::newDb();
		
		// get config
		$config = $this->get_config();
		
		// check multiple (value contains "|")
		$field_values = array();
		if(strpos($this->get_value(),'|') !== false) {
			
			// separate by |
			$values = explode('|',$this->get_value());
			
			// walk through $values
			for($i=0;$i<count($values);$i++) {
				
				// execute single query
				$sql = str_replace('|',$values[$i],$config['sql'][1]);		
				$result = $db->query($sql);
				
				// fetch result
				$field_values[] = $result->fetch_array(MYSQL_ASSOC);
			}
		} else {
			
			// execute single query
			$value = str_replace('|',$this->get_value(),$config['sql'][1]);		
			$result = $db->query($value);
			
			// fetch result
			$field_values = $result->fetch_array(MYSQL_ASSOC);
		}
		
		// return
		return $field_values;
		
	}
	
	
	
	
	
	
	/**
	 * dbhierselect_options gets the options for the quickform from
	 * db using the field-config (select_list#select_value)
	 * 
	 * @param array $options_first array to add the options for first select
	 * @param array $options_second array to add the options for second select
	 */
	private function dbhierselect_options(&$options_first,&$options_second) {
		
		// get db-object
		$db = Db::newDb();
	
		// get config
		$config = $this->get_config();
		
		// execute query
		$result = $db->query($config['sql'][0]);
		
		// fetch result
		while(list($id,$second_id,$name) = $result->fetch_array(MYSQL_NUM)) {
			
			// set first options
			$options_first[$id] = $name;
			
			// set second option 0
			$options_second[$id][0] = '--';
			
			// set second options
			$second = str_replace('|',(int) $second_id,$config['sql'][1]);
			$result_second = $db->query($second);
			
			// fetch result
			while(list($id2,$name2) = $result_second->fetch_array(MYSQL_NUM)) {
				$options_second[$id][$id2] = $name2;				
			}
		}	
	}
	
	
	
	
	
	
	/**
	 * dbselect_value the text value for this field from
	 * db using the field-config (select_list#select_value)
	 * 
	 * @return string value from db
	 */
	public function dbhierselect_value() {
		
		// get db-object
		$db = Db::newDb();
			
		// get config
		$config = $this->get_config();
		$sql = explode('|',$config['sql'][2],3);
		
		// separate value
		list($v_first,$v_second) = explode('|',$this->get_value(),2);
		
		// execute query
		$value = $sql[0].$v_first.$sql[1].$v_second.$sql[2];
		$result = $db->query($value);
		
		// fetch result
		$field_values = $result->fetch_array(MYSQL_ASSOC);
		
		// return
		return $field_values;
		
	} 
}



?>
