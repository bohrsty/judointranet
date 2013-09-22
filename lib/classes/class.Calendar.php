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
 * class calendar implements a date (i.e. event)
 */
class Calendar extends Page {
	
	/*
	 * class-variables
	 */
	private $name;
	private $shortname;
	private $date;
	private $type;
	private $content;
	private $preset_id;
	private $valid;
	private $lastModified;
	private $modifiedBy;
	
	/*
	 * getter/setter
	 */
	public function get_name(){
		return $this->name;
	}
	public function set_name($name) {
		$this->name = $name;
	}
	public function get_shortname(){
		return $this->shortname;
	}
	public function set_shortname($shortname) {
		$this->shortname = $shortname;
	}
	public function get_date($format = ''){
		
		// check if format given
		if($format != '') {
			return date($format,strtotime($this->date));
		} else {
			return $this->date;
		}
	}
	public function set_date($date) {
		$this->date = $date;
	}
	public function get_type(){
		return $this->type;
	}
	public function set_type($type) {
		$this->type = $type;
	}
	public function get_content(){
		return $this->content;
	}
	public function set_content($content) {
		$this->content = $content;
	}
	public function get_preset_id(){
		return $this->preset_id;
	}
	public function set_preset_id($preset_id) {
		$this->preset_id = $preset_id;
	}
	public function get_valid(){
		return $this->valid;
	}
	public function set_valid($valid) {
		$this->valid = $valid;
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
	
	/*
	 * constructor/destructor
	 */
	public function __construct($arg) {
		
		// parent constructor
		parent::__construct();
		
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
		$sql = "
			SELECT c.name,c.shortname,c.date,c.type,c.content,c.preset_id,c.valid,c.last_modified,c.modified_by
			FROM calendar AS c
			WHERE c.id = $id";
		
		// execute
		$result = $db->query($sql);
		
		// fetch result
		list($name,$shortname,$date,$type,$content,$preset_id,$valid,$lastModified,$modifiedBy) = $result->fetch_array(MYSQL_NUM);
		
		// set variables to object
		$this->set_id($id);
		$this->set_name($name);
		$this->set_shortname($shortname);
		$this->set_date($date);
		$this->set_type($type);
		$this->set_content($content);
		$this->set_preset_id($preset_id);
		$this->set_valid($valid);
		$this->setLastModified((strtotime($lastModified) < 0 ? 0 :strtotime($lastModified)));
		$this->setModifiedBy($modifiedBy);
		
		// close db
		$db->close();
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
			$sql = 'INSERT INTO calendar (id,name,shortname,date,type,content,preset_id,valid,modified_by)
					VALUES (null,"'
					.$this->get_name().'","'
					.$this->get_shortname().'","'
					.$timestamp.'","'
					.$this->get_type().'","'
					.$this->get_content().'",
					0,'
					.$this->get_valid().','.
					(int)$this->getUser()->get_id().')';
			
			// execute
			$db->query($sql);
			
			// get insert_id
			$insert_id = $db->insert_id;
			
			// set id and preset_id
			$this->set_id($insert_id);
			$this->set_preset_id(0);
			
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
						preset_id = "'.$this->get_preset_id().'",
						valid = '.$this->get_valid().',
						modified_by = '.$this->getUser()->get_id().'
					WHERE id = "'.$this->get_id().'"';
			
			// execute
			$db->query($sql);
			
			// write rights
			try {
				$this->get_rights()->write_db($this->get_id());
			} catch(Exception $e) {
				throw new Exception('DbActionUnknown',$e->getCode());
			}
		} else {
			
			// error
			$errno = $this->getError()->error_raised('DbActionUnknown','write_calendar',$action);
			throw new Exception('DbActionUnknown',$errno);
		}
		
		// close db
		$db->close();
	}
	
	
	
	
	/**
	 * details_to_html returns the calendar-entry-details as array
	 * 
	 * @return array calendar-entry-details as array
	 */
	public function details_to_html() {
		
		// prepare rights
		$groups = $this->getUser()->return_all_groups('admin');
		$rights = $this->get_rights()->get_rights();
		$rights_string = '';

		foreach($rights as $right) {
			$rights_string .= $groups[(int) $right].', ';
		}
		$rights_string = substr($rights_string,0,-2);

		// prepare data
		$data = array(
					'name' => parent::lang('class.Calendar#details_to_html#data#name').$this->get_name(),
					'shortname' => parent::lang('class.Calendar#details_to_html#data#shortname').$this->get_shortname(),
					'date' => parent::lang('class.Calendar#details_to_html#data#date').$this->get_date('d.m.Y'),
					'type' => parent::lang('class.Calendar#details_to_html#data#type').$this->return_type('translated'),
					'content' => parent::lang('class.Calendar#details_to_html#data#content').nl2br($this->get_content()),
					'rights' => parent::lang('class.Calendar#details_to_html#data#rights').$rights_string
		);
		
		// return
		return $data;
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
					'event' => parent::lang('class.Calendar#return_types#type#name.event'),
					'training' => parent::lang('class.Calendar#return_types#type#name.training')
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
	
	
	
	
	/**
	 * update sets the values from given array to this
	 * 
	 * @param array $calendar array containing the new values
	 * @return void
	 */
	public function update($calendar) {
		
		// walk through array
		foreach($calendar as $name => $value) {
			
			// check $name
			if($name == 'date') {
				$this->set_date($value);
			} elseif($name == 'name') {
				$this->set_name($value);
			} elseif($name == 'shortname') {
				$this->set_shortname($value);
			} elseif($name == 'type') {
				$this->set_type($value);
			} elseif($name == 'content') {
				$this->set_content($value);
			} elseif($name == 'rights') {
				$this->get_rights()->update($this->get_id(),$value);
			} elseif($name == 'valid') {
				$this->set_valid($value);
			} elseif($name == 'preset_id') {
				$this->set_preset_id($value);
			}
		}
	}
	
	
	
	
	/**
	 * return_calendars returns an array containing all calendar-id the
	 * user has rights to
	 * 
	 * @return array array containing the calendar_ids the user has rights to
	 */
	public static function return_calendars() {
		
		// get ids
		$return = Rights::get_authorized_entries('calendar');
		
		// return
		return $return;
	}
	
	
	
	
	/**
	 * check_ann_value checks if the given calendar-entry has values on the
	 * given preset-id
	 * 
	 * @param int $cid id of the calendar-entry
	 * @return bool true if calendar-entry and preset has values, false otherwise
	 */
	public static function check_ann_value($cid) {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql-statement
		$sql = "
			SELECT v.id
			FROM value AS v
			WHERE v.table_name = 'calendar'
			AND v.table_id = $cid";
		
		// execute
		$result = $db->query($sql);
		
		// check result
		if($result->num_rows > 0) {
			return true;
		} else {
			return false;
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
		
		// get version
		$version = max(strtotime($announcement['version']), (int)$this->getLastModified());
		$announcement['version'] = date('dmy', $version);

		// add fields
		// check html
		if($html === true) {
			$announcement['calendar_name'] = nl2br(htmlentities($this->get_name(),ENT_QUOTES,'UTF-8'));
			$announcement['calendar_shortname'] = nl2br(htmlentities($this->get_shortname(),ENT_QUOTES,'UTF-8'));
			$announcement['calendar_date'] = nl2br(htmlentities($this->get_date(),ENT_QUOTES,'UTF-8'));
			$announcement['calendar_date_d_m_Y'] = nl2br(htmlentities($this->get_date('d.m.Y'),ENT_QUOTES,'UTF-8'));
			$announcement['calendar_date_dmY'] = nl2br(htmlentities($this->get_date('dmY'),ENT_QUOTES,'UTF-8'));
			$announcement['calendar_date_j_F_Y'] = nl2br(htmlentities(strftime('%e. %B %Y',$this->get_date('U')),ENT_QUOTES,'UTF-8'));
			$announcement['calendar_type'] = nl2br(htmlentities($this->get_type(),ENT_QUOTES,'UTF-8'));
			$announcement['calendar_content'] = nl2br(htmlentities($this->get_content(),ENT_QUOTES,'UTF-8'));
		} else {
			$announcement['calendar_name'] = $this->get_name();
			$announcement['calendar_shortname'] = $this->get_shortname();
			$announcement['calendar_date'] = $this->get_date();
			$announcement['calendar_date_d_m_Y'] = $this->get_date('d.m.Y');
			$announcement['calendar_date_dmY'] = $this->get_date('dmY');
			$announcement['calendar_date_j_F_Y'] = strftime('%e. %B %Y',$this->get_date('U'));
			$announcement['calendar_type'] = $this->get_type();
			$announcement['calendar_content'] = $this->get_content();
		}
	}
}



?>
