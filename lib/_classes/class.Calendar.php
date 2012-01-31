<?php


/**
 * class calendar implements a date (i.e. event)
 */
class Calendar extends Object {
	
	/*
	 * class-variables
	 */
	private $id;
	private $name;
	private $shortname;
	private $date;
	private $type;
	private $content;
	private $ann_id;
	private $rights;
	private $valid;
	
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
	private function get_shortname(){
		return $this->shortname;
	}
	private function set_shortname($shortname) {
		$this->shortname = $shortname;
	}
	private function get_date(){
		return $this->date;
	}
	private function set_date($date) {
		$this->date = $date;
	}
	private function get_type(){
		return $this->type;
	}
	private function set_type($type) {
		$this->type = $type;
	}
	private function get_content(){
		return $this->content;
	}
	private function set_content($content) {
		$this->content = $content;
	}
	private function get_ann_id(){
		return $this->ann_id;
	}
	private function set_ann_id($ann_id) {
		$this->ann_id = $ann_id;
	}
	private function get_rights(){
		return $this->rights;
	}
	private function set_rights($rights) {
		$this->rights = $rights;
	}
	private function get_valid(){
		return $this->valid;
	}
	private function set_valid($valid) {
		$this->valid = $valid;
	}
	
	/*
	 * constructor/destructor
	 */
	public function __construct($arg) {
		
		// if $arg is array, create new entry, else get entry from db by given id
		if(is_array($arg)) {
			
			// prepare shortname
			$shortname = $arg['shortname'];
			if($shortname == '') {
				$shortname = strtoupper(substr($arg['name'],0,3));
			}
			
			// set variables to object
			$this->set_id(null);
			$this->set_name($arg['name']);
			$this->set_shortname($shortname);
			$this->set_date($arg['date']);
			$this->set_type($arg['type']);
			$this->set_content($arg['content']);
			$this->set_valid($arg['valid']);
			
			// set rights
			$this->set_rights(new Rights('calendar',$arg['rights']));
		} else {
			
			// get field for given id
			$this->get_from_db($arg);
			$this->set_rights(new Rights('calendar',$arg));
		}
	}
	
	/*
	 * methods
	 */
	/**
	 * get_from_db gets the calendar for the given calendarid
	 * 
	 * @param int $id id of the calendarentry
	 * @return void
	 */
	private function get_from_db($id) {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql-statement
		$stmt = $db->prepare(	'
						SELECT c.name,c.shortname,c.date,c.type,c.content,c.ann_id,c.valid
						FROM calendar AS c
						WHERE c.id = ?');
		
		// insert variables
		$stmt->bind_param('i',$id);
		
		// execute statement
		$stmt->execute();
		
		// bind variables to result
		$name = $shortname = $date = $type = $content = ''; $ann_id = $valid = 0;
		$stmt->bind_result($name,$shortname,$date,$type,$content,$ann_id,$valid);
		
		// fetch result
		$stmt->fetch();
		
		// set variables to object
		$this->set_id($id);
		$this->set_name($name);
		$this->set_shortname($shortname);
		$this->set_date($date);
		$this->set_type($type);
		$this->set_content($content);
		$this->set_ann_id($ann_id);
		$this->set_valid($valid);
		
		// close db
		$stmt->close();
		$db->close();
	}
	
	
	
	
	/**
	 * return_date returns the value of $date formatted with $format if given
	 * 
	 * @param string $format format to use with date()
	 * @return string value of $date
	 */
	public function return_date($format='') {
		
		// check if format given
		if($format != '') {
			return date($format,strtotime($this->get_date()));
		} else {
			return $this->get_date();
		}
	}
	
	
	
	
	/**
	 * return_id returns the calendar-id
	 * 
	 * @return int value of $id
	 */
	public function return_id() {
		return $this->get_id();
	}
	
	
	
	
	/**
	 * return_name returns the value of $name
	 * 
	 * @return string value of $name
	 */
	public function return_name() {
		return $this->get_name();
	}
	
	
	
	
	/**
	 * return_rights returns the value of $rights
	 * 
	 * @return object value of $rights
	 */
	public function return_rights() {
		return $this->get_rights();
	}
	
	
	
	
	/**
	 * return_valid returns the value of $valid
	 * 
	 * @return int value of $valid
	 */
	public function return_valid() {
		return $this->get_valid();
	}
	
	
	
	
	/**
	 * disable sets the calendar-entry invalid
	 * 
	 * @return void
	 */
	public function disable() {
		return $this->set_valid(0);
	}
	
	
	
	
	/**
	 * write_db writes the calendar-entry to db
	 * 
	 * @return void
	 */
	public function write_db($action='new') {
		
		// prepare timestamp
		$timestamp = date('Y-m-d',strtotime($this->get_date()));
		
		// get db-object
		$db = Db::newDb();
		
		// check action
		if($action == 'new') {
		
			// insert
			// prepare sql-statement
			$sql = 'INSERT INTO calendar (id,name,shortname,date,type,content,ann_id,valid)
					VALUES (null,"'
					.$this->get_name().'","'
					.$this->get_shortname().'","'
					.$timestamp.'","'
					.$this->get_type().'","'
					.$this->get_content().'",
					0,'
					.$this->get_valid().')';
			
			// execute
			$db->query($sql);
			
			// get insert_id
			$insert_id = $db->insert_id;
			
			// set id and ann_id
			$this->set_id($insert_id);
			$this->set_ann_id(0);
			
			// write rights
			try {
				$this->get_rights()->write_db($insert_id);
			} catch(Exception $e) {
				throw new Exception('DbActionUnknown',$e->getCode());
			}
		} elseif($action == 'update') {
			
			// update
			// prepare sql-statement
			$sql = 'UPDATE calendar
					SET
						name = "'.$this->get_name().'",
						shortname = "'.$this->get_shortname().'",
						date = "'.$timestamp.'",
						type = "'.$this->get_type().'",
						content = "'.$this->get_content().'",
						ann_id = "'.$this->get_ann_id().'",
						valid = '.$this->get_valid().'
					WHERE id = "'.$this->get_id().'"';
			
			// execute
			$db->query($sql);
		} else {
			
			// error
			$errno = $GLOBALS['Error']->error_raised('DbActionUnknown','write_calendar',$action);
			throw new Exception('DbActionUnknown',$errno);
		}
		
		// close db
		$db->close();
	}
	
	
	
	
	/**
	 * details_to_html returns the calendar-entry-details as html-string
	 * 
	 * @param object $template HTMLTemplate-objekt to parse the data
	 * @return string calendar-entry-details as html-string
	 */
	public function details_to_html($template) {
		
		// prepare rights
		$groups = $_SESSION['user']->return_all_groups('admin');
		$rights = $this->get_rights()->return_rights();
		$rights_string = '';

		foreach($rights as $right) {
			$rights_string .= $groups[(int) $right].', ';
		}
		$rights_string = substr($rights_string,0,-2);

		// prepare data
		$data = array(
					'name' => parent::lang('class.Calendar#details_to_html#data#name').$this->get_name(),
					'shortname' => parent::lang('class.Calendar#details_to_html#data#shortname').$this->get_shortname(),
					'date' => parent::lang('class.Calendar#details_to_html#data#date').$this->return_date('d.m.Y'),
					'type' => parent::lang('class.Calendar#details_to_html#data#type').$this->return_type('translated'),
					'content' => parent::lang('class.Calendar#details_to_html#data#content').$this->get_content(),
					'rights' => parent::lang('class.Calendar#details_to_html#data#rights').$rights_string
		);
		
		// return
		return $template->parse($data);
	}
	
	
	
	
	/**
	 * return_type returns the value of $type or the translated value
	 * 
	 * @param string $choice raw="actual value of $type", translated="translated value"
	 * @return mixed the value of $type or the translated value
	 */
	public function return_type($choice='raw') {
		
		// check choice
		if($choice == 'raw') {
			return $this->get_type();
		}
		if($choice == 'translated') {
			return parent::lang('class.Calendar#return_types#type#name.'.$this->get_type());
		}
	}
	
	
	
	
	/**
	 * return_types returns the list of available types
	 * 
	 * @return array list of available types
	 */
	public static function return_types() {
		
		// fill array
		$return = array(
					'event' => parent::lang('class.Calendar#return_types#type#name.event')
		);
		
		// return
		return $return;
	}
	
	
	
	
	/**
	 * check_id checks if the given id exists in db
	 * 
	 * @return bool true if id exists, false otherwise
	 */
	public static function check_id($id) {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql
		$sql = "SELECT id FROM calendar WHERE id=$id";
		
		// execute
		$result = $db->query($sql);
		
		if($result->num_rows == 0) {
			return false;
		} else {
			return true;
		}
	}
}



?>
