<?php


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
		$sql = "SELECT f.name,f.type,f2p.required,f.category
				FROM field AS f,fields2presets AS f2p
				WHERE f.id = $id
				AND f2p.field_id = $id
				AND f2p.pres_id = $pid";
		
		// execute
		$result = $db->query($sql);
		
		// fetch result
		list($name,$type,$required,$category) = $result->fetch_array(MYSQL_NUM);
		
		// set variables to object
		$this->set_id($id);
		$this->set_name($name);
		$this->set_type($type);
		$this->set_required($required);
		$this->set_category($category);
		
		// close db
		$db->close();
	}
	
	
	
	
	
	
	/**
	 * read_quickform set a HTML_Quickform2_Element-object to $quickform according
	 * to the $type
	 * 
	 * @param array $options array containing parameters for the input-tag
	 * @param bool $defaults text-fields with default-values if true
	 * @return void
	 */
	public function read_quickform($options = array(),$defaults = false) {
		
		// prepare return
		$element = null;
		
		// check type
		if($this->get_type() == 'text') {
			
			// check defaults
			if($defaults === true) {
				
				// field-group
				$element = HTML_QuickForm2_Factory::createElement('group', $this->get_table().'-'.$this->get_id(),$options);
				$element->setLabel($this->get_name().':');
				
				// add select
				$select = $element->addElement('select','defaults',array());
				$select->setLabel(parent::lang('class.Field#element#label#textarea.defaults'));
				
				// add textarea
				$textarea = $element->addElement('textarea','manual',array());
				$textarea->setLabel(parent::lang('class.Field#element#label#textarea.manual'));
				
				// prepare options
				$options = array('--');
				
				// get defaults
				$this->read_defaults($options);
				
				// load options
				$select->loadOptions($options);
			} else {
				
				// textarea
				$element = HTML_QuickForm2_Factory::createElement('textarea', $this->get_table().'-'.$this->get_id(),$options);
				$element->setLabel($this->get_name().':');
				
				// add rules
				if($this->get_required() == 1) {
					$element->addRule('required',parent::lang('class.Field#element#rule#required.text'));
				}
				$element->addRule(
					'regex',
					parent::lang('class.Field#element#rule#regexp.allowedChars').' ['.$_SESSION['GC']->get_config('textarea.desc').']',
					$_SESSION['GC']->get_config('textarea.regexp'));
			}
		} elseif($this->get_type() == 'date') {
			
			// date-group
			$element = HTML_QuickForm2_Factory::createElement('group', $this->get_table().'-'.$this->get_id(),$options);
			$element->setLabel($this->get_name().':');
			
			$now_year = (int) date('Y');
			$year_min = $now_year;
			$year_max = $now_year + 3;
			
			// select day
			$options = array('--');
			for($i=1;$i<=31;$i++) {
				$options[$i] = $i;
			}
			$select_day = $element->addElement('select','day',array());
			$select_day->loadOptions($options);
			
			// select month
			$options = array('--');
			for($i=1;$i<=12;$i++) {
				$options[$i] = parent::lang('class.Field#read_quickform#date#month.'.$i);
			}
			$select_month = $element->addElement('select','month',array());
			$select_month->loadOptions($options);
			
			// select year
			$options = array('--');
			for($i=$year_min;$i<=$year_max;$i++) {
				$options[$i] = $i;
			}
			$select_year = $element->addElement('select','year',array());
			$select_year->loadOptions($options);
			
			// add rules
			if($this->get_required() == 1) {
				$element->addRule('required',parent::lang('class.Field#element#rule#required.date'));
			}
			$element->addRule('callback',parent::lang('class.Field#element#rule#check.date'),array($this,'callback_check_date'));
		} elseif($this->get_type() == 'checkbox') {
			
			// checkbox
			$element = HTML_QuickForm2_Factory::createElement('checkbox', $this->get_table().'-'.$this->get_id(),$options);
			$element->setLabel($this->get_name().':');
			
			// add rules
			if($this->get_required() == 1) {
				$element->addRule('required',parent::lang('class.Field#element#rule#required.checkbox'));
			}
		}
		
		// set
		$this->set_quickform($element);
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
		$checked_default = 'NULL';
		if($this->get_type() == 'date') {
			$checked_value = date('Y-m-d',strtotime($value['year'].'-'.$value['month'].'-'.$value['day']));
		} elseif($this->get_type() == 'text') {
			
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
		} else {
			$checked_value = $value;
		}
		
		// set classvariables
		$this->set_defaults($checked_default);
		$this->set_value($checked_value);
	}
	
	
	
	
	
// REMOVE PARAMS	
	/**
	 * value_to_html returns the field and its $value as html embedded in $template
	 * 
	 * @param object $template the HtmlTemplate-object to embed the field
	 * @param mixed $value the value of the field
	 * @return string the html-representation
	 */
	public function value_to_html($template,$value) {
		
		// get values
		$value = $this->get_value();
		$defaults = $this->get_defaults();
		
		// get templates
		// b
		try {
			$b = new HtmlTemplate('templates/b.tpl');
		} catch(Exception $e) {
			$GLOBALS['Error']->handle_error($e);
		}
		
		// check value
		$checked_value = '';
		if($this->get_type() == 'checkbox') {
			
			// check if not null
			if(isset($value) && $value == 1) {
				$checked_value = parent::lang('class.Field#value_to_html#checkbox.value#checked');
			} else {
				$checked_value = parent::lang('class.Field#value_to_html#checkbox.value#unchecked');
			}
		} elseif($this->get_type() == 'date') {
			$checked_value = date('d.m.Y',strtotime($value));
		} elseif($this->get_type() == 'text') {
			
			// check defaults
			if($value == '') {
				
				// get default-value
				// get db-object
				$db = Db::newDb();
				
				$result = $db->query("SELECT value FROM defaults WHERE id=$defaults");
				list($checked_value) = $result->fetch_array(MYSQL_NUM);
			} else {
				$checked_value = $value;
			}
		}
		
		// get fieldname bold
		$field_name = $b->parse(array(
					'b.parameters' => '',
					'b.content' => $this->get_name().': '
				));
		
		// check template
		if(!is_null($template)) {
			
			// prepare values for template-p
			$content = array(
						'params' => '',
						'text' => $field_name.$checked_value
					);
		
			// return
			return $template->parse($content);
		} else {
			return $field_name.$checked_value;
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
	public function read_defaults(&$options) {
		
		// get db-object
		$db = Db::newDb();
		
		// get defaults
		// prepare sql
		$sql = "SELECT d.id,d.name,d.value
				FROM defaults AS d
				WHERE category='".$this->get_category()."'
				ORDER BY d.name ASC";
		
		// execute
		$result = $db->query($sql);
		
		// fetch defaults
		$defaults = array();
		while(list($id,$name,$value) = $result->fetch_array(MYSQL_NUM)) {
			
			// replace linebreak
			$value = str_replace(array("\r\n","\r","\n")," ",$value);
			
			// check value length
			if(strlen($value) > 30) {
				$value = substr($value,0,27).'...';
			}
			
			$defaults['d'.$id] = $value;
		}
		
		// add separator and options
		$options[parent::lang('class.Field#read_defaults#defaults#separator')] = $defaults;
		
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
		
		// fetch last-used
		$last = array();
		while(list($id,$table_id,$value) = $result->fetch_array(MYSQL_NUM)) {
			
			// check rights
			if(in_array((int) $table_id,$ids)) {
				
				// replace linebreak
				$value = str_replace(array("\r\n","\r","\n")," ",$value);
				
				// check value length
				if(strlen($value) > 30) {
					$value = substr($value,0,27).'...';
				}
				
				$last['l'.$id] = $value;
			}
		}
		
		// reverse array
		array_reverse($last,true);
		
		
		// add separator and options
		$options[parent::lang('class.Field#read_defaults#lastUsed#separator')] = $last;
	}
}



?>
