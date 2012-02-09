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
	
	/*
	 * constructor/destructor
	 */
	public function __construct($id,$table,$table_id) {
		
		// parent constructor
		parent::__construct();
		
		// get field for given id
		$this->get_from_db($id);
		
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
	 * @return void
	 */
	private function get_from_db($id) {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql-statement
		$sql = "SELECT f.name,f.type
				FROM field AS f
				WHERE f.id = $id";
		
		// execute
		$result = $db->query($sql);
		
		// fetch result
		list($name,$type) = $result->fetch_array(MYSQL_NUM);
		
		// set variables to object
		$this->set_id($id);
		$this->set_name($name);
		$this->set_type($type);
		
		// close db
		$db->close();
	}
	
	
	
	
	
	
	/**
	 * read_quickform set a HTML_Quickform2_Element-object to $quickform according
	 * to the $type
	 * 
	 * @return void
	 */
	public function read_quickform() {
		
		// prepare return
		$element = null;
		
		// check type
		if($this->get_type() == 'text') {
			
			// textarea
			$element = HTML_QuickForm2_Factory::createElement('textarea', $this->get_name().'-'.$this->get_id());
		} elseif($this->get_type() == 'date') {
			
			// date-group
			$element = HTML_QuickForm2_Factory::createElement('group', $this->get_name().'-'.$this->get_id());
			
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
	 * @return void
	 */
	public function read_value() {
		
		// prepare return
		$value = '';
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql-statement
		$sql = "SELECT v.value
				FROM value AS v
				WHERE v.table_name = ".$this->get_table()."
				AND v.table_id = ".$this->get_table_id()."
				AND v.field_id = ".$this->get_id();
		
		// execute
		$result = $db->query($sql);
		
		// fetch result
		list($value) = $result->fetch_array(MYSQL_NUM);
		
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
}



?>
