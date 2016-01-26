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
	private $form;
	private $value;
	private $table;
	private $table_id;
	private $required;
	private $category;
	private $defaults;
	private $config;
	private $lastModified;
	private $modifiedBy;
	private $view;
	
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
	public function getForm(){
		return $this->form;
	}
	public function setForm(&$form) {
		$this->form = $form;
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
	public function getModifiedBy(){
		return $this->modifiedBy;
	}
	public function setModifiedBy($modifiedBy) {
		$this->modifiedBy = $modifiedBy;
	}
	public function getLastModified(){
		return $this->lastModified;
	}
	public function setLastModified($lastModified) {
		$this->lastModified = $lastModified;
	}
	public function getView(){
		return $this->view;
	}
	public function setView(&$view) {
		$this->view = $view;
	}
	
	/*
	 * constructor/destructor
	 */
	public function __construct($id, $table, $table_id, $pid, &$view) {
		
		// parent constructor
		parent::__construct();
		
		// set view
		$this->setView($view);
		
		// set class variables
		$this->set_table($table);
		$this->set_table_id($table_id);
		
		// get field for given id
		$this->getFromDb($id,$pid);
	}
	
	/*
	 * methods
	 */
	/**
	 * getFromDb($id, $pid) gets the fieldconfig for the given field id
	 * 
	 * @param int $id id of the fieldentry
	 * @param int $pid id of the preset
	 * @return void
	 */
	private function getFromDb($id,$pid) {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql-statement
		$sql = 'SELECT f.name,f.type,f2p.required,f.category,f.config
				FROM field AS f,fields2presets AS f2p
				WHERE f.id=\''.$db->real_escape_string($id).'\'
				AND f2p.field_id=\''.$db->real_escape_string($id).'\'
				AND f2p.pres_id=\''.$db->real_escape_string($pid).'\'';
						
		// execute
		$result = $db->query($sql);
		
		// get data
		if($result) {
			list($name, $type, $required, $category, $config) = $result->fetch_array(MYSQL_NUM);
		} else {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// execute
		$result = $db->query($sql);
		
		// get last modified (if set)
		$sql = 'SELECT v.last_modified
				FROM value AS v
				WHERE v.table_name=\''.$db->real_escape_string($this->get_table()).'\'
				AND v.table_id=\''.$db->real_escape_string($this->get_table_id()).'\'
				AND v.field_id=\''.$db->real_escape_string($id).'\'';
		
		// get data
		$lastModified = 0;
		if($result) {
			if($result->num_rows == 1) {
				list($lastModified) = $result->fetch_array(MYSQL_NUM);
			}
		} else {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// set variables to object
		$this->set_id($id);
		$this->set_name($name);
		$this->set_type($type);
		$this->set_required($required);
		$this->set_category($category);
		$this->set_config(json_decode($config, true));
		$this->setLastModified((strtotime($lastModified) < 0 ? 0 : strtotime($lastModified)));
		
		// close db
		$db->close();
	}
	
	
	/**
	 * addFormElement($options, $defaults, &$formIds) adds an element to $this->form according 
	 * to $this->type
	 * 
	 * @param array $options array containing parameters for the input-tag
	 * @param bool $defaults text-fields with default-values if true (overridden by field config)
	 * @param array $formIds array containing the form ids for later getting the values
	 */
	public function addFormElement($options = array(),$defaults = true, &$formIds) {
		
		// load values
		$this->readValue();
		
		// get config
		$config = $this->get_config();
		
		// override $defaults if $config['defaults'] is set
		$defaults = (isset($config['defaults']) ? $config['defaults'] : $defaults);
				
		// get and simplify $this->form
		$form = &$this->getForm();
		// simplify id
		$elementId = $this->get_table().'-'.$this->get_id();
		
		// check type
		if($this->get_type() == 'text') {
			
			// set form id
			$formIds[$elementId] = array('valueType' => 'string', 'type' => 'fieldtext',);
			
			// add elements
			$this->fieldText($form, $defaults);
		} elseif($this->get_type() == 'date') {
			
			// prepare value
			$date = date('Y-m-d');
			if($this->get_value() != '') {
				$date = $this->get_value();
			}
						
			// set form id
			$formIds[$elementId] = array('valueType' => 'string', 'type' => 'date',);
			
			// add label
			$form->add(
				'label',			// type
				'label'.ucfirst($elementId),	// id/name
				$elementId,			// for
				$this->get_name().':'	// label text
			);
			
			// add date
			$element = $form->add(
					$formIds[$elementId]['type'],			// type
					$elementId,			// id/name
					date('d.m.Y', strtotime($date))	// default
				);
			// format/position
			$element->format('d.m.Y');
			$element->inside(false);
			
			// define regexp rule for the textarea
			$rules['date'] = array(
					'error',
					_l('check date'),
				);
			// define custom required rule
			if($this->get_required() == 1) {
				$rules['required'] = array(
						'error',
						_l('required date'),
					);
			}
			
			// add rules for textarea
			$element->set_rule($rules);
			
			// add note
			$form->add(
					'note',			// type
					'note'.ucfirst($elementId),	// id/name
					$elementId,		// for
					_l('help').'&nbsp;'.$this->getView()->helpButton(HELP_MSG_FIELDDATE)	// note text
				);
		} elseif($this->get_type() == 'checkbox') {
			
			// check options and value
			if($this->get_value() != '' && !array_key_exists('checked', $options)) {
				$options = array_merge($options, array('checked' => 'checked'));
			}
			
			// set form id
			$formIds[$elementId] = array('valueType' => 'string', 'type' => 'checkbox',);
			
			// add label
			$form->add(
				'label',			// type
				'label'.ucfirst($elementId),	// id/name
				$elementId,			// for
				$this->get_name().':'	// label text
			);
			
			// add checkbox
			$element = $form->add(
				$formIds[$elementId]['type'],		// type
				$elementId,						// id/name
				'1',							// value
				(count($options) > 0 ? $options : null)
			);
			
			// define custom required rule
			if($this->get_required() == 1) {
				$rules['required'] = array(
						'error',
						_l('required checkbox'),
					);
			
				// add rules
				$element->set_rule($rules);
			}
			
			// add note
			$form->add(
					'note',			// type
					'note'.ucfirst($elementId),	// id/name
					$elementId,		// for
					_l('help').'&nbsp;'.$this->getView()->helpButton(HELP_MSG_FIELDCHECKBOX)	// note text
				);
		} elseif($this->get_type() == 'dbselect') {
			
			// prepare value
			$value = null;
			if($this->get_value() != '') {
				
				$value = explode('|', $this->get_value());
				
				// check length
				if(count($value) == 1) {
					$value = $value[0];
				}
			}
			
			// set form id
			$formIds[$elementId] = array('valueType' => 'string', 'type' => 'select',);
			
			// read config
			$config = $this->get_config();
			
			// merge options
			$options = array_merge($options,$config['options']);
			
			// add label
			$form->add(
				'label',			// type
				'label'.ucfirst($elementId),	// id/name
				$elementId,			// for
				$this->get_name().':'	// label text
			);
			
			// add element
			$element = $form->add(
				'select',	// type
				$elementId.(array_key_exists('multiple', $options) ? '[]' : ''),		// id/name
				$value,		// default
				$options	// attributes
			);
			
			// define custom required rule
			if($this->get_required() == 1) {
				$rules['required'] = array(
						'error',
						_l('required select'),
					);
				
				// add rules
				$element->set_rule($rules);
			}
			
			// add select options
			$element->add_options($this->dbselectOptions());
			
			// add note
			$form->add(
					'note',			// type
					'note'.ucfirst($elementId),	// id/name
					$elementId,		// for
					_l('help').'&nbsp;'.$this->getView()->helpButton(HELP_MSG_FIELDDBSELECT)	// note text
				);
		} elseif($this->get_type() == 'dbhierselect') {
			
			// set form id
			$formIds[$elementId] = array('valueType' => 'string', 'type' => 'hierselect',);
			
			// get options from field-config
			$optionsFirst = array();
			$optionsSecond = array();
			$this->dbhierselectOptions($optionsFirst,$optionsSecond);
						
			// add hierselect
			$this->hierselect($form, $optionsFirst, $optionsSecond, $elementId.'-1', $elementId.'-2');
		}
	}
	
	
	
	
	
	
	/**
	 * readValue($data) reads the actual value from the db
	 * 
	 * @param array $data values (table, table_id, field_id) can override the instancevariables
	 * @return void
	 */
	public function readValue($data = null) {
		
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
		$sql = 'SELECT v.value,v.defaults,v.last_modified,v.modified_by
				FROM value AS v
				WHERE v.table_name = \''.$db->real_escape_string($table).'\'
				AND v.table_id = '.$db->real_escape_string($table_id).'
				AND v.field_id = '.$db->real_escape_string($field_id);
		
		// execute
		$result = $db->query($sql);
		
		// check result
		$value = '';
		$defaults = 0;
		$lastModified = 0;
		$modifiedBy = 0;
		if($result) {
			// check if value is set
			if($result->num_rows != 0) {
				// fetch result
				list($value,$defaults,$lastModified,$modifiedBy) = $result->fetch_array(MYSQL_NUM);
			}
		} else {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// close db
		$db->close();
		
		// set
		$this->set_value($value);
		$this->set_defaults($defaults);
		$this->setLastModified((strtotime($lastModified) < 0 ? 0 :strtotime($lastModified)));
		$this->setModifiedBy($modifiedBy);
	}
	
	
	
	
	
	
	/**
	 * value sets the given value
	 * 
	 * @param mixed $value the value of the field
	 * @return void
	 */
	public function value($value) {
		
		// get config
		$config = $this->get_config();
		
		// get db-object
		$db = Db::newDb();
		
		// check type
		$checked_value = '';
		$checked_default = 0;
		if($this->get_type() == 'text' && is_array($value)) {
			
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
				
				// walk through $value
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
	 * valueToHtml() returns the field and its $value as array
	 * 
	 * @return array array containing name and value of the field
	 */
	public function valueToHtml() {
		
		// get values
		$value = $this->get_value();
		$defaults = $this->get_defaults();
		$name = $this->get_name();
		
		// check value
		$checked_value = '';
		if($this->get_type() == 'checkbox') {
			
			// check if not null
			if(isset($value) && $value == 1) {
				$checked_value = _l('yes');
			} else {
				$checked_value = _l('no');
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
					$checked_value = $this->returnDefaultsValue($defaults);
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
			$values = $this->dbselectValue();
			
			// check multiple
			if(count($values) == 0) {
				$checked_value = '';
			} elseif(isset($values[0])) {
				
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
			$values = $this->dbhierselectValue();
			
			$checked_value = $values['value1'].'/'.$values['value2'];
			
			// return
			return array(
					'name' => $name,
					'value' => $checked_value
				);
		}
	}
	
	
	
	
	
	
	/**
	 * writeDb() writes the actual objectdata to the db
	 * 
	 * @param string $action insert inserts new values, update updates existing
	 * @return void
	 */
	public function writeDb($action) {
		
		// prepare sql
		if($action == 'insert') {
			
			// insert
			$sql = 'INSERT INTO value (`id`,`table_name`,`table_id`,`field_id`,`value`,`defaults`,`modified_by`)
					VALUES (#?, \'#?\', #?, #?, \'#?\', #?, #?)';
			$data = array(
					'NULL',
					$this->get_table(),
					$this->get_table_id(),
					$this->get_id(),
					$this->get_value(),
					$this->get_defaults(),
					$this->getUser()->get_id(),
				);
		} else {
			
			// update
			$sql = 'UPDATE value SET
					`value`=\'#?\',
					`defaults`=#?,
					`modified_by`=#?
					WHERE `field_id`=#?
					AND `table_id`=#?
					AND `table_name`=\'#?\'';
			$data = array(
					$this->get_value(),
					$this->get_defaults(),
					$this->getUser()->get_id(),
					$this->get_id(),
					$this->get_table_id(),
					$this->get_table(),
				);
		}
		
		// insert into database
		if(!Db::executeQuery(
			$sql,
			$data)) {
				$n = null;
				throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
			}
		
		// run updates
		$this->runUpdates();
	}
	
	
	
	
	
	
	/**
	 * deleteValue() deletes the value in db
	 * 
	 * @param int $table_id id of the value in $table
	 */
	public function deleteValue() {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql
		$sql = 'DELETE FROM value
				WHERE field_id = '.$db->real_escape_string($this->get_id()).'
				AND table_id='.$db->real_escape_string($this->get_table_id()).'
				AND table_name=\''.$db->real_escape_string($this->get_table()).'\'';
		
		// execute
		$result = $db->query($sql);
		
		// check result
		if(!$result) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// close db
		$db->close();
	}
	
	
	
	
	
	
	/**
	 * getOptions() returns an array containing the default values and last-used values
	 * for the defaults select element
	 * 
	 * @return array array containig the optgroups for the defaults select element
	 */
	public function getOptions() {
		
		// prepare optgroups
		$optionDefaults = array();
		$optionLastUsed = array();
		
		// get db-object
		$db = Db::newDb();
		
		// get defaults
		$defaultsResult = Db::ArrayValue('
				SELECT `d`.`id`, `d`.`name`
				FROM `defaults` AS `d`
				WHERE `category`=\'#?\'
					AND `d`.`valid`=1		
				ORDER BY `d`.`name` ASC
			',
				MYSQL_ASSOC,
				array($this->get_category()));
		if($defaultsResult === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		} else {
			
			foreach($defaultsResult as $row) {
				
				// check name length
				$truncName = $row['name'];
				if(strlen($truncName) > 30) {
					$truncName = substr($truncName, 0, 27).'...';
				}

				// add to options
				$optionDefaults['d'.$row['id']] = $truncName;
			}
		}
		
		// get last used
		$lastUsedResult = Db::ArrayValue('
				SELECT `v`.`id`, `v`.`value`
				FROM `value` AS `v`, `field` AS `f`
				WHERE `v`.`table_name`=\'#?\'
					AND `f`.`type`=\'#?\'
					AND `f`.`category`=\'#?\'
					AND `f`.`id`=`v`.`field_id`
				ORDER BY `v`.`id` DESC
			',
				MYSQL_ASSOC,
				array(
					$this->get_table(),
					$this->get_type(),
					$this->get_category(),
				));
		if($lastUsedResult === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		} else {
			
			// determine max
			$max = (count($lastUsedResult) >= 30 ? 30 : count($lastUsedResult));
			for($i=0; $i < $max; $i++) {
				
				// replace linebreak
				$value = str_replace(array("\r\n", "\r", "\n",), " ", $lastUsedResult[$i]['value']);
				
				// check value length
				$truncValue = $value;
				if(strlen($truncValue) > 30) {
					$truncValue = substr($truncValue, 0, 27).'...';
				}
				
				// check if truncated value has already been added
				foreach($optionLastUsed as $entry) {
					$foundKey = array_search($truncValue, $optionLastUsed);
					if($foundKey !== false) {
						continue 2;
					}
				}
				
				// add to options
				$optionLastUsed['l'.$lastUsedResult[$i]['id']] = $truncValue;
			}
		}
		
		// return optgroups
		return array(
			_l('preset') => $optionDefaults,
			_l('last used') => $optionLastUsed,
		);
	}
	
	
	
	
	
	
	/**
	 * returnDefaultsValue($defaults) reads the value of the given defaults-id
	 * from db and returns it
	 * 
	 * @param int $defaults id of the defaults to get the value from
	 */
	public function returnDefaultsValue($defaults) {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare statement
		$sql = 'SELECT value
				FROM defaults
				WHERE id='.$defaults;
		
		$result = $db->query($sql);
		
		// check result
		if($result) {
			list($value) = $result->fetch_array(MYSQL_NUM);
		} else {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// close db
		$db->close();
		
		// return
		return $value;
	}
	
	
	
	
	
	
	/**
	 * dbselectOptions returns the options for the select dbelement
	 * db using the field-config
	 * 
	 * @return array array containing the options
	 */
	private function dbselectOptions() {
		
		// prepare return
		$options = array();
		
		// get db-object
		$db = Db::newDb();
		
		// get config
		$config = $this->get_config();
		
		// execute query
		$result = $db->query($config['sql'][0]);
		
		// fetch result
		if($result) {
			while(list($id,$name) = $result->fetch_array(MYSQL_NUM)) {
				$options[$id] = $name;
			}
		} else {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// return
		return $options;
		
	}
	
	
	
	
	
	
	/**
	 * dbselectValue() the text value for this field from
	 * db using the field-config (select_list#select_value)
	 * 
	 * @return string value from db
	 */
	public function dbselectValue() {
		
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
				$sql = str_replace('|',$db->real_escape_string($values[$i]),$config['sql'][1]);		
				$result = $db->query($sql);
				
				// check result
				if($result) {
					// fetch result
					$field_values[] = $result->fetch_array(MYSQL_ASSOC);
				} else {
					$n = null;
					throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
				}
			}
		} elseif($this->get_value() != '') {
			
			// execute single query
			$value = str_replace('|',$db->real_escape_string($this->get_value()),$config['sql'][1]);		
			$result = $db->query($value);
			
			// check result
			if($result) {
				// fetch result
				$field_values = $result->fetch_array(MYSQL_ASSOC);
			} else {
					$n = null;
					throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
				}
		}
		
		// close db
		$db->close();
		
		// return
		return $field_values;
		
	}
	
	
	
	
	
	
	/**
	 * dbhierselectOptions($optionsFirst, $optionsSecond) adds the options for the 
	 * hierselect element to the given array using the field-config
	 * 
	 * @param array $optionsFirst array to add the options for first select
	 * @param array $optionsSecond array to add the options for second select
	 */
	private function dbhierselectOptions(&$optionsFirst,&$optionsSecond) {
		
		// get db-object
		$db = Db::newDb();
	
		// get config
		$config = $this->get_config();
		
		// execute query
		$result = $db->query($config['sql'][0]);
		
		// fetch result
		if($result) {
			while(list($id,$secondId,$name) = $result->fetch_array(MYSQL_NUM)) {
				
				// set first options
				$optionsFirst[$id] = $name;
				
				// set second options
				$second = str_replace('|',(int) $secondId,$config['sql'][1]);
				$resultSecond = $db->query($second);
				
				// fetch result
				if($resultSecond) {
					while(list($id2,$name2) = $resultSecond->fetch_array(MYSQL_NUM)) {
						$optionsSecond[$id][$id2] = $name2;				
					}
				} else {
					$n = null;
					throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
				}
			}
		} else {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}	
	}
	
	
	
	
	
	
	/**
	 * dbselectValue() the text value for this field from
	 * db using the field-config (select_list#select_value)
	 * 
	 * @return string value from db
	 */
	public function dbhierselectValue() {
		
		// get db-object
		$db = Db::newDb();
			
		// get config
		$config = $this->get_config();
		$sql = explode('|',$config['sql'][2],3);
		
		// separate value
		list($v_first,$v_second) = explode('|',$this->get_value(),2);
		$v_first = ($v_first != '' ? $v_first : 0);
		$v_second = ($v_second != '' ? $v_second : 0);
		
		// execute query
		$value = $sql[0].$db->real_escape_string($v_first).$sql[1].$db->real_escape_string($v_second).$sql[2];
		$result = $db->query($value);
		
		// check result
		if($result) {
			// fetch result
			$field_values = $result->fetch_array(MYSQL_ASSOC);
		} else {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// close db
		$db->close();
		
		// return
		return $field_values;
		
	}
	
	/**
	 * hierselect(&$form, $select1Array, $select2Array, $select1Id, $select2Id) adds the
	 * hierselect element to the given $form
	 * 
	 * @param object $form the zebra_form object to add the hierselect element to
	 * @param array $select1Array array containing the options of the first select element
	 * @param array $select2Array array containing the options of the second select element according to the first one
	 * @param string $select1Id DOM id of the first select element
	 * @param string $select2Id DOM id of the second select element
	 */
	private function hierselect(&$form, $select1Array, $select2Array, $select1Id, $select2Id) {
		
		// prepare value
		$value1 = null;
		$value2 = 0;
		if($this->get_value() != '') {
			
			$value = explode('|', $this->get_value(), 2);
			$value1 = $value[0];
			$value2 = $value[1];
		}
		
		// add label and selects
		$form->add(
				'label',			// type
				'label'.ucfirst($select1Id),	// id/name
				$select1Id,			// for
				$this->get_name().':'	// label text
			);
		$select1 = $form->add(
				'select',	// type
				$select1Id,		// id/name
				$value1		// default
			);
		$select2 = $form->add(
				'select',	// type
				$select2Id		// id/name
			);
		// add dummy select (hidden and disabled) to keep odd/even in default template
		$dummySelectId = 'dummyHierselect'; 
		$form->add(
				'select',	// type
				$dummySelectId		// id/name
			);
		
		// add note
		$form->add(
				'note',			// type
				'note'.ucfirst($select2Id),	// id/name
				$select2Id,		// for
				_l('help').'&nbsp;'.$this->getView()->helpButton(HELP_MSG_FIELDDBHIERSELECT)	// note text
			);
		
		// add required rule if field is required
		if($this->get_required() == 1) {
			$rules['required'] = array(
					'error',
					_l('required select'),
				);
			
			// add rules
			$select1->set_rule($rules);
		}
		
		// add rule to check second select to be selected if first is
		$select2->set_rule(
				array(
						'custom' => array(array(
								array($this, 'callbackCheckHierselect'),
								$select1Id,
								'error',
								_l('required fields'),
							),),
					)
			);
		
		// disable spamfilter on hierselects, because of changeing the option per jquery
		$select1->disable_spam_filter();
		$select2->disable_spam_filter();
		
		// add options to first select
		$select1->add_options($select1Array);
		
		// get option array for second select as json array
		$select2Json = json_encode($select2Array);
		
		// create smarty template and add variables
		$sJsHierselect = new JudoIntranetSmarty();
		$sJsHierselect->assign('select1', $select1Id);
		$sJsHierselect->assign('select2', $select2Id);
		$sJsHierselect->assign('select2Value', $value2);
		$sJsHierselect->assign('dummySelect', $dummySelectId);
		$sJsHierselect->assign('select2Array', $select2Json);
		
		// add completed javascript to jquery
		$this->getView()->add_jquery($sJsHierselect->fetch('smarty.js-zebraHierselect.tpl'));
	}
	
	
	/**
	 * fieldText(&$form, $defaults) adds the text element and default select to the
	 * given $form
	 * 
	 * @param object $form the form object to add the element to
	 * @param bool $defaults whether to add the defaults select element or not
	 */
	private function fieldText(&$form, $defaults) {
		
		// prepare value
		$defaultsValue = null;
		$manualValue = null;
		if($this->get_defaults() == 0) {
			if($this->get_value() != '') {
				$manualValue = $this->get_value();
			}
		} else {
			$defaultsValue = $this->get_defaults();
		}
		
		// set $elementId
		$elementId = $this->get_table().'-'.$this->get_id();
		
		// add label
		$form->add(
			'label',			// type
			'label'.ucfirst($elementId),	// id/name
			$elementId.'-manual',			// for
			$this->get_name().':'.($this->get_required() == 1 ? '<span class="required">*</span>' : '')	// label text
		);
		
		// check defaults
		if($defaults === true) {
			
			// add select
			$select = $form->add(
					'select',	// type
					$elementId.'-defaults',	// id/name
					'd'.$defaultsValue	// default
				);
			// add options
			$select->add_options($this->getOptions());
		}	
		
		// add textarea
		$textarea = $form->add(
			'textarea',		// type
			$elementId.'-manual',	// id/name
			$manualValue
		);
		
		// define regexp rule for the textarea
		$rules['regexp'] = array(
				$this->getGc()->get_config('textarea.regexp.zebra'),
				'error',
				_l('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
			);
		// define custom required rule
		if($this->get_required() == 1) {
			$rules['custom'] = array(
					array(
							array($this, 'callbackCheckRequired'),
							$elementId.'-defaults',
							'error',
							_l('required text'),
						),
				);
		}
		
		// add rules for textarea
		$textarea->set_rule($rules);	
		
		// add dummy element (hidden and disabled) to keep odd/even in default template
		$dummyId = 'dummy'; 
		$form->add(
				'select',	// type
				$dummyId	// id/name
			);
			
		// add note
		$form->add(
				'note',			// type
				'note'.ucfirst($elementId.'-defaults'),	// id/name
				$elementId.'-defaults',		// for
				_l('help').'&nbsp;'.$this->getView()->helpButton(HELP_MSG_FIELDTEXT)	// note text
			);
		
		// create smarty template and add variables
		$sJsFieldText = new JudoIntranetSmarty();
		$sJsFieldText->assign('manual', $elementId.'-manual');
		$sJsFieldText->assign('defaults', $elementId.'-defaults');
		$sJsFieldText->assign('dummy', $dummyId);
		
		// add completed javascript to jquery
		$this->getView()->add_jquery($sJsFieldText->fetch('smarty.js-zebraFieldText.tpl'));
	} 
	
	
	/**
	 * runUpdates() checks if some database fields depend on updates of this field
	 * 
	 * @return void
	 */
	private function runUpdates() {
		
		// check config
		if(isset($this->get_config()['update'])) {
			
			// get update config
			$update = $this->get_config()['update'];
			
			// check if user function exists
			if(method_exists($update['callback'][0], $update['callback'][1])) {
				
				// get value
				$value = null;
				if(isset($update['field']['dbhierselect'])) {
					$value = $this->dbhierselectValue()[$update['field']['dbhierselect']];
				} else {
					$value = $this->valueToHtml()['value'];
				}
				
				// call function to update with value/defaults value
				call_user_func($update['callback'], $this->get_table_id(), $value);
			}
		}
	}
}



?>
