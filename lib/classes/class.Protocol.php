<?php

// secure against direct execution
if(!defined("JUDOINTRANET")) {die("Cannot be executed directly! Please use index.php.");}


/**
 * class Protocol implements the representation of a protocol object
 */
class Protocol extends Page {
	
	/*
	 * class-variables
	 */
	private $id;
	private $date;
	private $type;
	private $location;
	private $protocol;
	private $preset;
	private $valid;
	private $member;
	private $decisions;
	private $owner;
	private $correctable;
	private $recorder;
	
	/*
	 * getter/setter
	 */
	public function get_id(){
		return $this->id;
	}
	public function set_id($id) {
		$this->id = $id;
	}
	public function get_date($format=''){
		
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
	public function get_type($param='n'){
		if($param == 'n') {
			return $this->type['n'];
		} else {
			return $this->type['i'];
		}
	}
	public function set_type($typeId,$typeName) {
		$this->type['i'] = $typeId;
		if($typeName === false) {
			$this->type['n'] = Db::returnValueById($typeId,'protocol_types','name');
		} else {
			$this->type['n'] = $typeName;
		}
	}
	public function get_location(){
		return $this->location;
	}
	public function set_location($location) {
		$this->location = $location;
	}
	public function get_protocol(){
		return $this->protocol;
	}
	public function set_protocol($protocol) {
		$this->protocol = $protocol;
	}
	public function get_preset(){
		return $this->preset;
	}
	public function set_preset($preset) {
		$this->preset = $preset;
	}
	public function get_valid(){
		return $this->valid;
	}
	public function set_valid($valid) {
		$this->valid = $valid;
	}
	public function get_member($sep=''){
		
		// check separator
		if($sep == '') {
			return $this->member;
		} else {
			return implode($sep,$this->member);
		}
	}
	public function set_member($member) {
		
		// check member
		if(is_array($member)) {
			$this->member = $member;
		} else {
			
			// check \r\n, \n\r or \n
			if(strpos($member,"\n\r") !== false) {
				$this->member = explode("\n\r",$member);
			} elseif(strpos($member,"\r\n") !== false) {
				$this->member = explode("\r\n",$member);
			} elseif(strpos($member,"\n") !== false) {
				$this->member = explode("\n",$member);
			} elseif(strpos($member,"|") !== false) {
				$this->member = explode("|",$member);
			} else {
				$this->member = array($member);
			}
		}
	}
	public function get_decisions($type='array'){
		
		// check type
		if($type == 'array') {
			return $this->decisions;
		} else {
			return implode("\n",$this->decisions);
		}
	}
	public function set_decisions($decisions) {
		$this->decisions = $decisions;
	}
	public function get_owner(){
		return $this->owner;
	}
	public function set_owner($owner) {
		$this->owner = $owner;
	}
	public function get_correctable(){
		return $this->correctable;
	}
	public function set_correctable($correctable) {
		$this->correctable = $correctable;
	}
	public function get_recorder(){
		return $this->recorder;
	}
	public function set_recorder($recorder) {
		$this->recorder = $recorder;
	}
	
	/*
	 * constructor/destructor
	 */
	public function __construct($arg) {
		
		// parent constructor
		parent::__construct();
		
		// if $arg is array, create new entry, else get entry from db by given id
		if(is_array($arg)) {
			
			$this->set_id(null);
			$this->set_date($arg['date']);
			$this->set_type($arg['type'],false);
			$this->set_location($arg['location']);
			$this->set_member($arg['member']);
			$this->set_protocol($arg['protocol']);
			$this->set_decisions($this->readDecisions());
			$this->set_preset(new Preset($arg['preset'],'protocol',0));
			$this->set_valid($arg['valid']);
			$this->set_owner($arg['owner']);
			$this->set_correctable($arg['correctable']);
			$this->set_recorder($arg['recorder']);
			
			// set rights
			$this->set_rights(new Rights('protocol',$arg['rights']));
		} else {
		
			// get field for given id
			$this->get_from_db($arg);
			$this->set_rights(new Rights('protocol',$arg));
		}
	}
	
	/*
	 * methods
	 */
	/**
	 * get_from_db gets the protocol for the given protocolid
	 * 
	 * @param int $id id of the protocolentry
	 * @return void
	 */
	private function get_from_db($id) {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql-statement
		$sql = "SELECT p.date,p.type,pt.name,p.location,p.member,p.protocol,p.preset_id,p.valid,u.name,p.correctable,p.recorder
				FROM protocol AS p,protocol_types AS pt,user AS u
				WHERE p.id = $id
				AND p.type=pt.id
				AND p.owner=u.id";
		
		// execute
		$result = $db->query($sql);
		
		// fetch result
		list($date,$typeId,$typeName,$location,$member,$protocol,$preset_id,$valid,$owner,$correctable,$recorder) = $result->fetch_array(MYSQL_NUM);
		
		// set variables to object
		$this->set_id($id);
		$this->set_date($date);
		$this->set_type($typeId,$typeName);
		$this->set_location($location);
		$this->set_member($member);
		$this->set_protocol($protocol);
		$this->set_decisions($this->readDecisions());
		$this->set_preset(new Preset($preset_id,strtolower(get_class($this)),$id));
		$this->set_valid($valid);
		$this->set_owner($owner);
		$this->set_correctable($correctable);
		$this->set_recorder($recorder);
		
		
		
		// close db
		$db->close();
	}
	
	
	
	
	
	
	/**
	 * return_protocol returns an array containing all protocols the
	 * user has rights to
	 * 
	 * @return array array containing the protocol_ids the user has rights to
	 */
	public static function return_protocols() {
		
		// get ids
		$return = Rights::get_authorized_entries('protocol');
		
		// return
		return $return;
	}
	
	
	
	
	
	
	/**
	 * readDecisions extracts the decisions from protocol
	 * 
	 * @return array array containing the extracted decisions
	 */
	private function readDecisions() {
		
		// prepare
		$return = array();
		
		
		// return
		return $return;
	}
	
	
	
	
	/**
	 * details returns the protocol-entry-details as array
	 * 
	 * @return array protocol-entry-details as array
	 */
	public function details() {
		
//		// prepare rights
//		$groups = $_SESSION['user']->return_all_groups('admin');
//		$rights = $this->get_rights()->get_rights();
//		$rights_string = '';
//
//		foreach($rights as $right) {
//			$rights_string .= $groups[(int) $right].', ';
//		}
//		$rights_string = substr($rights_string,0,-2);
		
		// prepare data
		$data = array(
					'date' => parent::lang('class.Protocol#details#data#date').$this->get_date('d.m.Y'),
					'location' => parent::lang('class.Protocol#details#data#location').$this->get_location(),
					'member' => parent::lang('class.Protocol#details#data#member').$this->get_member(', '),
					'recorder' => parent::lang('class.Protocol#details#data#recorder').$this->get_recorder()
		);
		if(is_numeric($this->get_type())) {
			$data['type'] = parent::lang('class.Protocol#details#data#type').DB::returnValueById($this->get_type(),'protocol_types','name');
		} else {
			$data['type'] = parent::lang('class.Protocol#details#data#type').$this->get_type();
		}
		if(is_numeric($this->get_owner())) {
			$data['owner'] = parent::lang('class.Protocol#details#data#owner').DB::returnValueById($this->get_owner(),'user','name');
		} else {
			$data['owner'] = parent::lang('class.Protocol#details#data#owner').$this->get_owner();
		}
		
		// return
		return $data;
	}
	
	
	
	
	
	
	/**
	 * return_types returns the list of available types
	 * 
	 * @return array list of available types
	 */
	public static function return_types() {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql
		$sql = "SELECT id,name FROM protocol_types";
		
		// execute
		$result = $db->query($sql);
		
		// fill array
		$return = array();
		while($row = $result->fetch_array(MYSQL_ASSOC)) {
			$return[$row['id']] = $row['name'];
		}
		
		// return
		return $return;
	}
	
	
	
	
	
	
	
	/**
	 * writeDb writes the protocol-entry to db
	 * 
	 * @return void
	 */
	public function writeDb($action='new') {
		
		// prepare timestamp
		$timestamp = date('Y-m-d',strtotime($this->get_date()));
		
		// get db-object
		$db = Db::newDb();
		
		// check action
		if($action == 'new') {
		
			// insert
			// prepare sql-statement
			$sql = "INSERT INTO protocol
						(id,
						date,
						type,
						location,
						protocol,
						preset_id,
						valid,
						member,
						owner,
						correctable,
						recorder)
					VALUES (null,'"
						.$db->real_escape_string($timestamp)."',"
						.$db->real_escape_string($this->get_type('i')).",'"
						.$db->real_escape_string($this->get_location())."','"
						.$db->real_escape_string($this->get_protocol())."',"
						.$db->real_escape_string($this->get_preset()->get_id()).","
						.$db->real_escape_string($this->get_valid()).",'"
						.$db->real_escape_string($this->get_member("|"))."',"
						.$db->real_escape_string($this->get_owner()).","
						.$db->real_escape_string($this->get_correctable()).",'"
						.$db->real_escape_string($this->get_recorder())."')";
			
			// execute;
			$db->query($sql);
			
			// get insert_id
			$insert_id = $db->insert_id;
			
			// set id and preset_id
			$this->set_id($insert_id);
			
			// write rights
			try {
				$this->get_rights()->write_db($insert_id);
			} catch(Exception $e) {
				throw new Exception('DbActionUnknown',$e->getCode());
			}
		} elseif($action == 'update') {
			
			// update
			// prepare sql-statement
			$sql = "UPDATE protocol
					SET
						date='".$db->real_escape_string($timestamp)."',
						type=".$db->real_escape_string($this->get_type('i')).",
						location='".$db->real_escape_string($this->get_location())."',
						protocol='".$db->real_escape_string($this->get_protocol())."',
						preset_id=".$db->real_escape_string($this->get_preset()->get_id()).",
						valid=".$db->real_escape_string($this->get_valid()).",
						member='".$db->real_escape_string($this->get_member("|"))."',
						correctable=".$db->real_escape_string($this->get_correctable()).",
						recorder='".$db->real_escape_string($this->get_recorder())."'
					WHERE id = ".$db->real_escape_string($this->get_id());
			
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
			$errno = $GLOBALS['Error']->error_raised('DbActionUnknown','write_calendar',$action);
			throw new Exception('DbActionUnknown',$errno);
		}
		
		// close db
		$db->close();
	}
	
	
	
	
	
	
	
	/**
	 * addMarks the marks and values to the given array
	 * 
	 * @param array $infos array to fill with marks and values
	 * @param boolean $html convert special chars for html if true, does not if false
	 * @return void
	 */
	public function addMarks(&$infos,$html=true) {
		
		// add fields
		// check html
		if($html === true) {
			$infos['date'] = nl2br(htmlentities($this->get_date(),ENT_QUOTES,'UTF-8'));
			$infos['type'] = nl2br(htmlentities($this->get_type(),ENT_QUOTES,'UTF-8'));
			$infos['location'] = nl2br(htmlentities($this->get_location(),ENT_QUOTES,'UTF-8'));
			$infos['date_d_m_Y'] = nl2br(htmlentities($this->get_date('d.m.Y'),ENT_QUOTES,'UTF-8'));
			$infos['date_dmY'] = nl2br(htmlentities($this->get_date('dmY'),ENT_QUOTES,'UTF-8'));
			$infos['date_j_F_Y'] = nl2br(htmlentities(strftime('%e. %B %Y',$this->get_date('U')),ENT_QUOTES,'UTF-8'));
			$infos['member'] = nl2br(htmlentities($this->get_member(', '),ENT_QUOTES,'UTF-8'));
			$infos['protocol'] = nl2br(htmlentities($this->get_protocol(),ENT_QUOTES,'UTF-8'));
			$infos['decisions'] = nl2br(htmlentities($this->get_decisions('string'),ENT_QUOTES,'UTF-8'));
			$infos['recorder'] = nl2br(htmlentities($this->get_recorder(),ENT_QUOTES,'UTF-8'));
		} else {
			$infos['date'] = $this->get_date();
			$infos['type'] = $this->get_type();
			$infos['location'] = $this->get_location();
			$infos['date_d_m_Y'] = $this->get_date('d.m.Y');
			$infos['date_dmY'] = $this->get_date('dmY');
			$infos['date_j_F_Y'] = strftime('%e. %B %Y',$this->get_date('U'));
			$infos['member'] = $this->get_member(', ');
			$infos['protocol'] = $this->get_protocol();
			$infos['decisions'] = $this->get_decisions('string');
			$infos['recorder'] = $this->get_recorder();
		}
	}
	
	
	
	
	
	
	/**
	 * update sets the values from given array to this
	 * 
	 * @param array $protocol array containing the new values
	 * @return void
	 */
	public function update($protocol) {
		
		// walk through array
		foreach($protocol as $name => $value) {
			
			// check $name
			if($name == 'date') {
				$this->set_date($value);
			} elseif($name == 'type') {
				$this->set_type($value,false);
			} elseif($name == 'location') {
				$this->set_location($value);
			} elseif($name == 'protocol') {
				$this->set_protocol($value);
			} elseif($name == 'preset') {
				$this->set_preset($value);
			} elseif($name == 'rights') {
				$this->get_rights()->update($this->get_id(),$value);
			} elseif($name == 'member') {
				$this->set_member($value);
			} elseif($name == 'decisions') {
				$this->set_decisions($value);
			} elseif($name == 'owner') {
				$this->set_owner($value);
			} elseif($name == 'correctable') {
				$this->set_correctable($value);
			} elseif($name == 'recorder') {
				$this->set_recorder($value);
			} elseif($name == 'valid') {
				$this->set_valid($value);
			}
		}
	}
}



?>
