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
	private function get_type(){
		return $this->type;
	}
	private function set_type($type) {
		$this->type = $type;
	}
	private function get_quickform(){
		return $this->quickform;
	}
	private function set_quickform($quickform) {
		$this->quickform = $quickform;
	}
	private function get_value(){
		return $this->value;
	}
	private function set_value($value) {
		$this->value = $value;
	}
	private function get_table(){
		return $this->table;
	}
	private function set_table($table) {
		$this->table = $table;
	}
	private function get_table_id(){
		return $this->table_id;
	}
	private function set_table_id($table_id) {
		$this->table_id = $table_id;
	}
	private function get_required(){
		return $this->required;
	}
	private function set_required($required) {
		$this->required = $required;
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
		$sql = "SELECT f.name,f.type,f2p.required
				FROM field AS f,fields2presets AS f2p
				WHERE f.id = $id
				AND f2p.field_id = $id
				AND f2p.pres_id = $pid";
		
		// execute
		$result = $db->query($sql);
		
		// fetch result
		list($name,$type,$required) = $result->fetch_array(MYSQL_NUM);
		
		// set variables to object
		$this->set_id($id);
		$this->set_name($name);
		$this->set_type($type);
		$this->set_required($required);
		
		// close db
		$db->close();
	}
	
	
	
	
	
	
	/**
	 * read_quickform set a HTML_Quickform2_Element-object to $quickform according
	 * to the $type
	 * 
	 * @return void
	 */
	public function read_quickform($options = array()) {
		
		// prepare return
		$element = null;
		
		// check type
		if($this->get_type() == 'text') {
			
			// textarea
			$element = HTML_QuickForm2_Factory::createElement('textarea', $this->get_table().'-'.$this->get_id(),$options);
			$element->setLabel($this->get_name().':');
			
			// add rules
			if($this->get_required() == 1) {
				$element->addRule('required',parent::lang('class.Field#element#rule#required.text'));
			}
			$element->addRule(
				'regex',
				parent::lang('class.Field#element#rule#regexp.allowedChars').' ['.$_SESSION['GC']->return_config('textarea.desc').']',
				$_SESSION['GC']->return_config('textarea.regexp'));
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
	 * return_quickform returns the value of $quickform
	 * 
	 * @return object the value of $quickform
	 */
	public function return_quickform() {
		
		return $this->get_quickform();
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
		$sql = "SELECT v.value
				FROM value AS v
				WHERE v.table_name = '$table'
				AND v.table_id = $table_id
				AND v.field_id = $field_id";
		
		// execute
		$result = $db->query($sql);
		
		// check if value is set
		$value = '';
		if($result->num_rows != 0) {
			// fetch result
			list($value) = $result->fetch_array(MYSQL_NUM);
		}
		
		// close db
		$db->close();
		
		// set
		$this->set_value($value);
	}
	
	
	
	
	
	
	/**
	 * return_value returns the value of $value
	 * 
	 * @return string the value of $value
	 */
	public function return_value() {
		
		return $this->get_value();
	}
	
	
	
	
	
	
	/**
	 * return_name returns the value of $name
	 * 
	 * @return string the value of $name
	 */
	public function return_name() {
		
		return $this->get_name();
	}
	
	
	
	
	
	
	/**
	 * return_id returns the value of $id
	 * 
	 * @return string the value of $id
	 */
	public function return_id() {
		
		return $this->get_id();
	}
	
	
	
	
	
	
	/**
	 * return_type returns the value of $type
	 * 
	 * @return string the value of $type
	 */
	public function return_type() {
		
		return $this->get_type();
	}
	
	
	
	
	
	
	/**
	 * value_to_html returns the field and its $value as html embedded in $template
	 * 
	 * @param object $template the HtmlTemplate-object to embed the field
	 * @param mixed $value the value of the field
	 * @return string the html-representation
	 */
	public function value_to_html($template,$value) {
		
		// check value
		$checked_value = '';
		if($this->get_type() == 'checkbox') {
			
			// check if not null
			if(isset($value) && $value == 1) {
				$checked_value = parent::lang('class.Field#value_to_html#checkbox.value#checked');
			} else {
				$checked_value = parent::lang('class.Field#value_to_html#checkbox.value#unchecked');
			}
		} else {
			$checked_value = $value;
		}
		
		// check template
		if(!is_null($template)) {
			
			// prepare values for template-p
			$content = array(
						'params' => '',
						'text' => $this->get_name().': '.$checked_value
					);
		
			// return
			return $template->parse($content);
		} else {
			return $this->get_name().': '.$checked_value;
		}
		
	}
}



?>
