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
 * class Result implements the representation of a result object
 */
class Result extends Page {
	
	/*
	 * class-variables
	 */
	private $calendar;
	private $city;
	private $club;
	private $resultStore;
	private $preset;
	private $desc;
	private $clubArray;
	private $isTeam;
	
	/*
	 * getter/setter
	 */
	public function getCalendar(){
		return $this->calendar;
	}
	public function setCalendar($calendar) {
		$this->calendar = $calendar;
	}
	public function getCity(){
		return $this->city;
	}
	public function setCity($city) {
		$this->city = $city;
	}
	public function getClub(){
		return $this->club;
	}
	public function setClub($club) {
		$this->club = $club;
	}
	public function getResultStore($data = false){
		
		// check $data
		if($data === false) {
			return $this->resultStore;
		} else {
			
			// check $data[0]
			if($data[0] == 'agegroups') {
				return $this->resultStore['agegroups'];
			} elseif($data[0] == 'weightclasses') {
				// check single or team
				if($this->getIsTeam() == 0) {
					return $this->resultStore['weightclasses'][$data[1]];
				}
			} elseif($data[0] == 'standings') {
				// check single or team
				if($this->getIsTeam() == 0) {
					return $this->resultStore['standings'][$data[1]][$data[2]];
				} else {
					return $this->resultStore['standings'][$data[1]];
				}
			} else {
				return false;
			}
		}
	}
	public function setResultStore($data, $reset = true) {
		
		// check reset
		if($reset === true) {
			$this->resultStore = $data;
		} else {
			
			// add agegroup
			if(!isset($this->resultStore['agegroups'][$data['agegroup']])) {
				$this->resultStore['agegroups'][$data['agegroup']] = 1;
			} else {
				$this->resultStore['agegroups'][$data['agegroup']]++;
			}
			
			// check single or team
			if($this->getIsTeam() == 0) {
				
				// single
				// add agegroup->weightclass
				if(!isset($this->resultStore['weightclasses'][$data['agegroup']][$data['weightclass']])) {
					$this->resultStore['weightclasses'][$data['agegroup']][$data['weightclass']] = 1;
				} else {
					$this->resultStore['weightclasses'][$data['agegroup']][$data['weightclass']]++;
				}
				
				// add standings
				$this->resultStore['standings'][$data['agegroup']][$data['weightclass']][] = array(
						'place' => $data['place'],
						'name' => $data['name'],
						'club_id' => $data['club_id'],
						'club_name' => (isset($this->clubArray[$data['club_id']]) ? $this->clubArray[$data['club_id']]['name'] : ''),
						'club_number' => (isset($this->clubArray[$data['club_id']]) ? $this->clubArray[$data['club_id']]['number'] : ''),
					);
			} else {
				
				// team
				// add standings
				$clubName = (isset($this->clubArray[$data['club_id']]) ? $this->clubArray[$data['club_id']]['name'] : '');
				$this->resultStore['standings'][$data['agegroup']][] = array(
						'place' => $data['place'],
						'name' => (is_null($data['name']) ? $clubName : ''),
						'club_id' => $data['club_id'],
						'club_name' => $clubName,
						'club_number' => (isset($this->clubArray[$data['club_id']]) ? $this->clubArray[$data['club_id']]['number'] : ''),
				);
			}
		}
		
		return true;
	}
	public function getPreset(){
		return $this->preset;
	}
	public function setPreset($preset) {
		$this->preset = $preset;
	}
	public function getDesc(){
		return $this->desc;
	}
	public function setDesc($desc) {
		$this->desc = $desc;
	}
	public function getClubArray(){
		return $this->clubArray;
	}
	public function setClubArray($clubArray) {
		$this->clubArray = $clubArray;
	}
	public function getIsTeam(){
		return $this->isTeam;
	}
	public function setIsTeam($isTeam) {
		$this->isTeam = $isTeam;
	}
	
	/*
	 * constructor/destructor
	 */
	public function __construct($id = 0, $calendarId = null) {
		
		// parent constructor
		parent::__construct();
		
		// read clubs
		$this->setClubArray(self::readClubs());
		
		// prepare $this->resultStore
		$resultStore = array(
				'agegroups' => array(),
				'weightclasses' => array(),
				'standings' => array(),
			);
		$this->setResultStore($resultStore);
		if(!is_null($calendarId) && $calendarId != 0) {
			$this->setCalendar(new Calendar($calendarId));
		}
		
		// get data from db
		if(!is_null($id) && $id != 0) {
			$this->getFromDb($id);
			$this->setId($id);
			$this->readAnnouncementValues();
		} else {
			$this->setId(0);
			$this->setCity('');
			$this->setPreset(0);
			$this->setValid(1);
			$this->setDesc('');
		}
	}
	
	/*
	 * methods
	 */
	/**
	 * addStandings($data) adds the given $data to the result
	 * @param array $data array containing the required informations for a standings
	 * 		agegroup => agegroup
	 * 		weightclass => weightclass
	 * 		place => place
	 * 		name => name
	 * 		club_id => club id
	 * @return bool true, if successful, false in case of error
	 */
	public function addStandings($data) {
		
		// check $data
		if(!is_array($data)) {return false;}
		if(count($data) != 5) {return false;}
		if(	!array_key_exists('agegroup', $data) ||
			!array_key_exists('weightclass', $data) ||
			!array_key_exists('place', $data) ||
			!array_key_exists('name', $data) ||
			!array_key_exists('club_id', $data)) {return false;}
		
		// set resultStore
		return $this->setResultStore($data, false);
	}
	
	
	/**
	 * getAgegroups() returns an array containing all agegroups in this result
	 * @return array array containing all agegroups in this result
	 */
	public function getAgegroups() {
		
		// return
		return $this->getResultStore(array('agegroups'));
	}
	
	
	/**
	 * getWeightclasses($agegroup) returns an array containing all weightclasses in $agegroup in this result
	 * 
	 * @param string $agegroup agegroup to get weightclasses for
	 * @return array array containing all weightclasses in $agegroup in this result
	 */
	public function getWeightclasses($agegroup) {
		
		// return
		return $this->getResultStore(array('weightclasses', $agegroup));
	}
	
	
	/**
	 * getStandings($agegroup, $weightclass) returns an array containing the standings in 
	 * $agegroup and $weightclass in this result
	 * 
	 * @param string $agegroup agegroup to get standings for
	 * @param string $weightclass weightclass in $agegroup to get standings for
	 * @return array array containing the standings in $agegroup and $weightclass in this result
	 */
	public function getStandings($agegroup, $weightclass) {
		
		// return
		return $this->getResultStore(array('standings', $agegroup, $weightclass));
	}
	
	
	/**
	 * getFromDb($id) retrieves the informations for the given $id from database
	 * 
	 * @param int $id the id of the entry to be retrieved from db
	 * @return void
	 */
	private function getFromDb($id) {
		
		// get result values from db
		$result = Db::ArrayValue('
			SELECT `calendar_id`, `preset_id`, `desc`, `is_team`, `last_modified`, `modified_by`, `valid`
			FROM `result`
			WHERE `id`=#?	
		',
		MYSQL_ASSOC,
		array($id,));
		if($result === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		// set variables
		$this->setCalendar(new Calendar($result[0]['calendar_id']));
		$this->setPreset($result[0]['preset_id']);
		$this->setDesc($result[0]['desc']);
		$this->setIsTeam($result[0]['is_team']);
		$this->setLastModified((strtotime($result[0]['last_modified']) < 0 ? 0 : strtotime($result[0]['last_modified'])));
		$this->setModifiedBy($result[0]['modified_by']);
		$this->setValid($result[0]['valid']);
		
		// get standings value from db
		$standings = Db::ArrayValue('
			SELECT `agegroup`, `weightclass`, `name`, `club_id`, `place`
			FROM `standings`
			WHERE `result_id`=#?	
		',
		MYSQL_ASSOC,
		array($id,));
		if($standings === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}

		// add standings
		foreach($standings as $standing) {
			$this->addStandings($standing);
		}
	}
	
	
	/**
	 * writeDb() writes $this object to database
	 * 
	 * @return mixed id of the new inserted database entry, or false in case of error
	 */
	public function writeDb() {
		
		// check if result has standings
		if(count($this->getAgegroups()) > 0
			&& $this->getPreset() > 0) {
			
			
			
			// write result
			if(!Db::executeQuery('
				INSERT INTO `result` (`id`, `calendar_id`, `preset_id`, `desc`, `is_team`, `last_modified`, `modified_by`, `valid`)
				VALUES (#?, #?, #?, \'#?\', #?, CURRENT_TIMESTAMP, #?, #?)
				ON DUPLICATE KEY UPDATE
					`calendar_id`=#?,
					`preset_id`=#?,
					`desc`=\'#?\',
					`is_team`=#?,
					`last_modified`=CURRENT_TIMESTAMP,
					`modified_by`=#?,
					`valid`=#?
			',
				array(// insert
					($this->getId() == 0 ? 'NULL' : $this->getId()),
					$this->getCalendar()->getId(),
					$this->getPreset(),
					$this->getDesc(),
					$this->getIsTeam(),
					$this->getUser()->get_id(), 
					$this->getValid(),
					// update
					$this->getCalendar()->getId(),
					$this->getPreset(),
					$this->getDesc(),
					$this->getIsTeam(),
					$this->getUser()->get_id(), 
					$this->getValid(),))) {
				$n = null;
				throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
			}
			
			// get insertid
			$newId = (isset(Db::$insertId) ? Db::$insertId : $this->getId());
			
			// write standings
			// remove existing standings
			if(!Db::executeQuery('
				DELETE FROM `standings`
				WHERE `result_id`=#?
			',
			array($newId,))) {
				$n = null;
				throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
			}
			// prepare sql
			$sqlParams = array();
			$sql = '
				INSERT INTO `standings` (`id`, `result_id`, `agegroup`, `weightclass`, `name`, `club_id`, `place`)
				VALUES 
				';
			
			// walk through agegroups
			foreach($this->getAgegroups() as $agegroup => $countAgegroups) {
				
				// check single or team
				if($this->getIsTeam() == 0) {
					
					// single
					// walk through weightclasses
					foreach($this->getWeightclasses($agegroup) as $weightclass => $countWeightclasses) {
						
						// walk though standings
						foreach($this->getStandings($agegroup, $weightclass) as $standing) {
							
							$sql .= '(NULL, #?, \'#?\', \'#?\', \'#?\', #?, #?),';
							array_push($sqlParams, $newId, $agegroup, $weightclass, $standing['name'], $standing['club_id'], $standing['place']);
						}
					}
				} else {
					
					// team
					// walk through standings
					foreach($this->getStandings($agegroup, null) as $standing) {
							
						$sql .= '(NULL, #?, \'#?\', #?, \'#?\', #?, #?),';
						array_push($sqlParams, $newId, $agegroup, 'NULL', $standing['name'], $standing['club_id'], $standing['place']);
					}
				} 
			}
			// remove last "," from $sql
			$sql = substr($sql, 0, -1);
			
			// execute query
			if(!Db::executeQuery(
			$sql,
			$sqlParams)) {
				$n = null;
				throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
			}
			
			// write task
			if(!Db::executeQuery('
				INSERT INTO `accounting_tasks` (`table_name`, `table_id`, `state`)
				VALUES (\'result\', #?, 0)
				ON DUPLICATE KEY UPDATE
					`state`=0
			',
			array($newId,))) {
				$n = null;
				throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
			}
			
			// set id
			$this->set_id($newId);
			// read announcement values
			$this->readAnnouncementValues();
			
			// create cached file
			$this->createCachedFile(File::idFromCache('result|'.$newId));
			
			// return
			return $newId;
		} else {
			return false;
		}
	}
	
	
	/**
	 * delete($rid) deletes the result and all corresponding standings with the given $rid
	 * from database
	 * 
	 * @param int $rid the result id of the result to be deleted from database
	 * @return void
	 */
	public static function delete($rid) {
		
		// delete result
		if(!Db::executeQuery('
			DELETE FROM `result` WHERE `id`=#?
				',
		array($rid,))) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// delete standings
		if(!Db::executeQuery('
			DELETE FROM `standings` WHERE `result_id`=#?
				',
		array($rid,))) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
	}
	
	
	/**
	 * readAnnouncementValues() reads the city of the calendar entry
	 * @return void
	 */
	private function readAnnouncementValues() {
		
		// prepare values
		$annFields = array(
				'city' => 'result.cityField',
				'club' => 'result.clubField',
			);
		$value = '';
		// get calendar id
		$presetId = $this->getCalendar()->get_preset_id();
		
		// check preset id
		if($presetId != 0) {

			// get preset
			$preset = new Preset($presetId, 'calendar', $this->getCalendar()->get_id());
			
			// get fields
			$fields = $preset->get_fields();
			
			// walk through fields
			foreach($annFields as $name => $config) {
			
				// get field config
				$fieldConfig = json_decode($this->getGc()->get_config($config), true);
				
				// check if field exists
				if(isset($fields[$fieldConfig['id']])) {
					
					// get value (using config)
					$actualField = $fields[$fieldConfig['id']];
					$actualField->readValue();
									
					// check field type
					if($actualField->get_type() == 'dbhierselect') {
						$actualValue = $actualField->dbhierselectValue();
						$value = $actualValue[$fieldConfig['value']];
					} else {
						$value = $actualField->get_value();
					}
				}
				
				// prepare setting value
				$setMethod = 'set'.ucfirst($name);
				// set value
				if(method_exists($this, $setMethod)) {
					call_user_method($setMethod, $this, $value);
				}
				
				// reset value
				$value = '';
			}
		}
	}
	
	
	/**
	 * addMarks($infos) the marks and values to the given array
	 * 
	 * @param array $infos array to fill with marks and values
	 * @return void
	 */
	public function addMarks(&$infos) {
		
		// get version
		$version = max(strtotime($infos['version']), (int)$this->getLastModified());
		$infos['version'] = date('d.m.Y', $version);
		
		// add fields
		$infos['result_object'] = $this;
	}
	
	
	/**
	 * getFilledTemplateAsString() returns the result as HTML string with all placeholders replaced
	 * 
	 * @param bool $includeFilename if true the filename is additionally returned
	 * @param bool $noPdf is directly assigned to the "nopdf" flag of the template
	 * @param string $type type of the output, used in filename and template file
	 * @return mixed filled template as HTML string or array including filename
	 */
	public function getFilledTemplateAsString($includeFilename = false, $noPdf = false) {
		
		// get preset
		$preset = new Preset($this->getPreset(), 'result', $this->getId());
		
		// prepare smarty
		$sR = new JudoIntranetSmarty();
		
		// prepare marker-array
		$infos = array(
				'version' => '01.01.1970 01:00'
			);
		
		// add result-fields to array
		$this->addMarks($infos, false);
		// add calendar-fields to array
		$this->getCalendar()->add_marks($infos, false);
		
		// smarty
		$sR->assign('r', $infos);
		// check marks in values
		foreach($infos as $k => $v) {
			
			if(preg_match('/\{\$r\..*\}/U', $v)) {
				$infos[$k] = $sR->fetch('string:'.$v);
			}
		}
		
		// smarty
		$sR->assign('r', $infos);
		$sR->assign('nopdf', $noPdf);
		$return = $sR->fetch('templates/results/'.$preset->get_path().'.tpl');
		
		// check $includeFilename
		if($includeFilename === true) {
			
			// prepare array
			$return = array();
			$return['filename'] = $this->replace_umlaute(html_entity_decode($sR->fetch('string:'.utf8_encode($preset->get_filename())), ENT_XHTML, 'UTF-8'));
			$return['html'] = $sR->fetch('templates/results/'.$preset->get_path().'.tpl');
		}
		
		return $return;
	}
	
	
	/**
	 * cacheFile() generates the cached file in database
	 * 
	 * @return void
	 */
	public function cacheFile() {
		
		// get HTML string
		$pdfOut = $this->getFilledTemplateAsString(true, false);			
		
		// get HTML2PDF-object
		$pdf = new HTML2PDF('P', 'A4', 'de', true, 'UTF-8', array(0, 0, 0, 0));
		
		// convert
		$pdf->writeHTML($pdfOut['html'], false);
		
		// output
		$pdfFilename = $pdfOut['filename'];
		
		// prepare file for File::factory
		return array(
				'name' => substr($pdfFilename, 0, -4),
				'filename' => $pdfFilename,
				'mimetype' => 'application/pdf',
				'content' => $pdf->Output($pdfFilename, 'S'),
				'cached' => 'result|'.$this->get_id(),
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
				'result' => true,
			);
		
		// return
		return $return;
		
	}
	
	
	/**
	 * getIdsForCalendar($cid) returns the result ids used in calender entry $cid
	 * 
	 * @param int $cid id of the calendar entry
	 * @return array result ids for $cid
	 */
	public static function getIdsForCalendar($cid) {
		
		// get result ids from database
		$result = Db::ArrayValue('
			SELECT `id`
			FROM `result`
			WHERE `calendar_id`=#?	
		',
		MYSQL_NUM,
		array($cid,));
		if($result === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// get single array from result
		$return = array();
		foreach($result as $array) {
			$return[] = $array[0];
		}
		
		// return
		return $return;
	}
	
	
	/**
	 * deleteEntry() calls the static delete() method
	 */
	public function deleteEntry() {
		
		// call static delete method
		self::delete($this->get_id());
		// delete task
		AccountingResultTask::delete($this->get_id());
		// delete files
		// delete cached file
		$fid = File::idFromCache('result|'.$this->getId());
		File::delete($fid);
		// delete attachements
		File::deleteAttachedFiles('result', $this->getId());
	}
}