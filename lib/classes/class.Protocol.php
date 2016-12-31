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
 * class Protocol implements the representation of a protocol object
 */
class Protocol extends Page {
	
	/*
	 * class-variables
	 */
	private $date;
	private $type;
	private $location;
	private $protocol;
	private $preset;
	private $valid;
	private $member;
	private $owner;
	private $correctable;
	private $recorder;
	private $lastModified;
	
	/*
	 * getter/setter
	 */
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
	public function get_member($str=false,$sep=''){
		
		// check $str
		if($str === false) {			
			// check $sep
			if($sep === '') {
				return $this->member;
			} else {
				return $this->member[$sep];
			}
		} else {
			
			$member = implode($sep,$this->member);
			// check if string contains "|" twice, or add it
			if(substr_count($member, '|') == 0){
				return $member.'||';
			} elseif(substr_count($member, '|') == 1) {
				return $member.'|';
			}
			return $member;
		}
	}
	public function set_member($member) {
		
		// check member
		if(is_array($member)) {
			$this->member = $member;
		} else {
			$this->member = explode("|",$member);
		}
	}
	public function get_owner($info = 'id'){
		
		// check info
		if($info == 'username') {
			return $this->owner->get_userinfo('username');
		} elseif($info == 'name') {
			return $this->owner->get_userinfo('name');
		}
		return $this->owner->get_id();
	}
	public function set_owner($owner) {
		$this->owner = new User(false);
		$this->owner->change_user($owner, false, 'id');
	}
	public function get_correctable($string=true){
		
		// check string
		if($string === true) {
			
			// put corrector together
			$correctors = implode(",",$this->correctable['correctors']);
			// put status and return
			return $this->correctable['status']."|".$correctors;
		} else {
			return $this->correctable;
		}
	}
	public function set_correctable($correctable) {
		
		// check $correctable
		if(is_array($correctable)) {
			$this->correctable = $correctable;
		} else {
			
			$array = array();
			// split value
			// status
			$status = explode("|",$correctable);
			$array['status'] = $status[0];
			// correctors
			if(isset($status[1])) {
				
				$correctors = explode(",",$status[1]);
				$array['correctors'] = $correctors;
			} else {
				$array['correctors'] = array();
			}
			
			// set
			$this->correctable = $array;
		}
	}
	public function get_recorder(){
		return $this->recorder;
	}
	public function set_recorder($recorder) {
		$this->recorder = $recorder;
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
			
			$this->set_id(null);
			$this->set_date($arg['date']);
			$this->set_type($arg['type'],false);
			$this->set_location($arg['location']);
			$this->set_member($arg['member']);
			$this->set_protocol($arg['protocol']);
			$this->set_preset(new Preset($arg['preset'],'protocol',0), null);
			$this->set_valid($arg['valid']);
			$this->set_owner($arg['owner']);
			$this->set_correctable($arg['correctable']);
			$this->set_recorder($arg['recorder']);
		} else {
		
			// get field for given id
			$this->get_from_db($arg);
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
		$sql = "SELECT p.date,p.type,pt.name,p.location,p.member,p.protocol,p.preset_id,p.valid,u.id,p.correctable,p.recorder,p.last_modified
				FROM protocol AS p,protocol_types AS pt,user AS u
				WHERE p.id = $id
				AND p.type=pt.id
				AND p.owner=u.id";
		
		// execute
		$result = $db->query($sql);
		
		// fetch result
		list($date,$typeId,$typeName,$location,$member,$protocol,$preset_id,$valid,$owner,$correctable,$recorder,$lastModified) = $result->fetch_array(MYSQLI_NUM);
		
		// set variables to object
		$this->set_id($id);
		$this->set_date($date);
		$this->set_type($typeId,$typeName);
		$this->set_location($location);
		$this->set_member($member);
		$this->set_protocol($protocol);
		$this->set_preset(new Preset($preset_id,strtolower(get_class($this)),$id), null);
		$this->set_valid($valid);
		$this->set_owner($owner);
		$this->set_correctable($correctable);
		$this->set_recorder($recorder);
		$this->setLastModified((strtotime($lastModified) < 0 ? 0 : strtotime($lastModified)));
		
		
		
		// close db
		$db->close();
	}
	
	
	
	
	/**
	 * details returns the protocol-entry-details as array
	 * 
	 * @return array protocol-entry-details as array
	 */
	public function details() {
		
		// prepare translation
		$stateTranslation[0] = _l('in progress');
		$stateTranslation[1] = _l('in progress');
		$stateTranslation[2] = _l('published');
		
		// prepare data
		$correctable = $this->get_correctable(false);
		$data = array(
					'status' => _l('<span>state:</span><br />').$stateTranslation[$correctable['status']],
					'date' => _l('<span>date:</span><br />').$this->get_date('d.m.Y'),
					'location' => _l('<span>location:</span><br />').$this->get_location(),
					'member0' => _l('<span>participants (attendant):</span><br />').$this->get_member(false,0),
					'member1' => _l('<span>participants (excused):</span><br />').$this->get_member(false,1),
					'member2' => _l('<span>participants (without excuse):</span><br />').$this->get_member(false,2),
					'recorder' => _l('<span>recorder:</span><br />').$this->get_recorder()
		);
		if(is_numeric($this->get_type())) {
			$data['type'] = _l('<span>kind:</span><br />').DB::returnValueById($this->get_type(),'protocol_types','name');
		} else {
			$data['type'] = _l('<span>kind:</span><br />').$this->get_type();
		}
		$data['owner'] = _l('<span>owner:</span><br />').$this->get_owner('name');
		
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
		while($row = $result->fetch_array(MYSQLI_ASSOC)) {
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
						.$db->real_escape_string($this->get_member(true,"|"))."',"
						.$db->real_escape_string($this->get_owner()).",'"
						.$db->real_escape_string($this->get_correctable())."','"
						.$db->real_escape_string($this->get_recorder())."')";
			
			// execute;
			$db->query($sql);
			
			// get insert_id
			$insert_id = $db->insert_id;
			
			// set id and preset_id
			$this->set_id($insert_id);
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
						member='".$db->real_escape_string($this->get_member(true,"|"))."',
						owner='".$db->real_escape_string($this->get_owner())."',
						correctable='".$db->real_escape_string($this->get_correctable())."',
						recorder='".$db->real_escape_string($this->get_recorder())."'
					WHERE id = ".$db->real_escape_string($this->get_id());
			
			// execute
			$db->query($sql);
		} else {
			throw new DbActionUnknownException($this, 'write_protocol: '.$action);
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
		
		// get version
		$version = max(strtotime($infos['version']), (int)$this->getLastModified());
		$infos['version'] = date('d.m.Y', $version);
		
		// add fields
		// check html
		if($html === true) {
			$infos['date'] = nl2br(htmlentities($this->get_date(),ENT_QUOTES,'UTF-8'));
			$infos['type'] = nl2br(htmlentities($this->get_type(),ENT_QUOTES,'UTF-8'));
			$infos['location'] = nl2br(htmlentities($this->get_location(),ENT_QUOTES,'UTF-8'));
			$infos['date_d_m_Y'] = nl2br(htmlentities($this->get_date('d.m.Y'),ENT_QUOTES,'UTF-8'));
			$infos['date_dmY'] = nl2br(htmlentities($this->get_date('dmY'),ENT_QUOTES,'UTF-8'));
			$infos['date_j_F_Y'] = nl2br(htmlentities(utf8_encode(strftime('%e. %B %Y',$this->get_date('U'))),ENT_QUOTES,'UTF-8'));
			$infos['member0'] = nl2br(htmlentities($this->get_member(false,0),ENT_QUOTES,'UTF-8'));
			$infos['member1'] = nl2br(htmlentities($this->get_member(false,1),ENT_QUOTES,'UTF-8'));
			$infos['member2'] = nl2br(htmlentities($this->get_member(false,2),ENT_QUOTES,'UTF-8'));
			$infos['protocol'] = nl2br(htmlentities($this->get_protocol(),ENT_QUOTES,'UTF-8'));
			$infos['recorder'] = nl2br(htmlentities($this->get_recorder(),ENT_QUOTES,'UTF-8'));
		} else {
			$infos['date'] = $this->get_date();
			$infos['type'] = $this->get_type();
			$infos['location'] = $this->get_location();
			$infos['date_d_m_Y'] = $this->get_date('d.m.Y');
			$infos['date_dmY'] = $this->get_date('dmY');
			$infos['date_j_F_Y'] = utf8_encode(strftime('%e. %B %Y',$this->get_date('U')));
			$infos['member0'] = $this->get_member(false,0);
			$infos['member1'] = $this->get_member(false,1);
			$infos['member2'] = $this->get_member(false,2);
			$infos['protocol'] = $this->get_protocol();
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
			} elseif($name == 'member') {
				$this->set_member($value);
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
	
	
	
	
	
	
	/**
	 * hasDecisions counts the number of decisions in protocol text and returns it
	 * 
	 * @return int number of decisions in protocol text
	 */
	public function hasDecisions() {
		
		// match HTML tag
		$number = preg_match('|<p class="tmceDecision">(.*)</p>|U',$this->get_protocol());
		
		// return
		return $number;
	}
	
	
	
	
	
	
	/**
	 * hasCorrections returns if there are corrections for this protocol
	 * 
	 * @return bool true if there are corrections, false otherwise
	 */
	public function hasCorrections() {
		
		// get corrections list
		$corrections = ProtocolCorrection::listCorrections($this->get_id());
		
		// check list
		if(count($corrections) > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	
	/**
	 * cacheFile() generates the cached file in database
	 * 
	 * @return void
	 */
	public function cacheFile() {
		
		// smarty
		$sP = new JudoIntranetSmarty();
		
		// prepare marker-array
		$infos = array(
				'version' => '01.01.70 01:00',
			);
		
		// add calendar-fields to array
		$this->addMarks($infos, false);
		
		// add tmce-css
		$fh = fopen('templates/protocols/tmce_'.$this->get_preset()->get_path().'.css','r');
		$css = fread($fh,filesize('templates/protocols/tmce_'.$this->get_preset()->get_path().'.css'));
		fclose($fh);
		$infos['tmceStyles'] = $css;
		
		// smarty
		$sP->assign('p', $infos);
		// check marks in values
		foreach($infos as $k => $v) {
			
			if(preg_match('/\{\$p\..*\}/U', $v)) {
				$infos[$k] = $sP->fetch('string:'.$v);
			}
		}
		
		// smarty
		$sP->assign('p', $infos);
		$pdfOut = $sP->fetch(JIPATH.'/templates/protocols/'.$this->get_preset()->get_path().'.tpl');			
		
		// replace <p></p> to <div></div> for css use with HTML2PDF
		$pdfOut = preg_replace('/<p class="tmceItem">(.*)<\/p>/U','<div class="tmceItem">$1</div>', $pdfOut);
		$pdfOut = preg_replace('/<p class="tmceDecision">(.*)<\/p>/U','<div class="tmceDecision">$1</div>', $pdfOut);
		
		// get HTML2PDF-object
		$pdf = new HTML2PDF('P', 'A4', 'de', true, 'UTF-8', array(0, 0, 0, 0));
		$pdf->setTestTdInOnePage(false);
		// convert
		$pdf->writeHTML($pdfOut, false);
		
		// output
		$pdfFilename = $this->replace_umlaute(html_entity_decode($sP->fetch('string:'.utf8_encode($this->get_preset()->get_filename())), ENT_XHTML, 'UTF-8'));
		
		// prepare file for File::factory
		return array(
				'name' => substr($pdfFilename, 0, -4),
				'filename' => $pdfFilename,
				'mimetype' => 'application/pdf',
				'content' => $pdf->Output($pdfFilename, 'S'),
				'cached' => 'protocol|'.$this->get_id(),
				'valid' => true,
			);
	}
	
	
	/**
	 * additionalChecksPassed() returns an array containing the check result and the error message
	 * for any additional checks for this object
	 * 
	 * @return array array containing the check result and the error message
	 */
	public function additionalChecksPassed() {
		
		// add additional permissions check
		$return['permissions'] = array(
				'result' => ($this->get_correctable(false)['status'] == 2 || $this->getUser()->get_id() == $this->get_owner()),
			);
		
		// return
		return $return;
	}
	
	
	/**
	 * getName() returns a name for this object
	 * 
	 * @return string name of this object
	 */
	public function getName() {
		return $this->get_type('n').' '.$this->get_location().' '.$this->get_date('d.m.Y');
	}
}



?>
