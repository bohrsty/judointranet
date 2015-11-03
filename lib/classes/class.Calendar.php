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
	private $filter;
	private $additionalFields;
	private $city;
	private $endDate;
	private $color;
	private $isExternal;
	
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
	public function getFilter(){
		return $this->filter;
	}
	public function setFilter($filter) {
		$this->filter = $filter;
	}
	public function getAdditionalFields(){
		return $this->additionalFields;
	}
	public function setAdditionalFields($additionalFields) {
		$this->additionalFields = $additionalFields;
	}
	public function getCity(){
		return $this->city;
	}
	public function setCity($city) {
		$this->city = $city;
	}
	public function getEndDate($format = ''){
		
		// check if set
		if(is_null($this->endDate)) {
			return null;
		}
		
		// check if format given
		if($format != '') {
			return date($format, strtotime($this->endDate));
		} else {
			return $this->endDate;
		}
	}
	public function setEndDate($endDate) {
		$this->endDate = $endDate;
	}
	public function getColor(){
		return $this->color;
	}
	public function setColor($color) {
		$this->color = $color;
	}
	public function getIsExternal(){
		return $this->isExternal;
	}
	public function setIsExternal($isExternal) {
		$this->isExternal = $isExternal;
	}
	
	/*
	 * constructor/destructor
	 */
	public function __construct($arg) {
		
		// parent constructor
		parent::__construct();
		
		// if $arg is array, create new entry, else get entry from db by given id
		if(is_array($arg)) {
			
			// prepare shortname and filter
			$shortname = $arg['shortname'];
			$filter = array();
			if($arg['isExternal'] === true) {
				$shortname = '';
			} else {
				
				// set shortname
				if($shortname == '') {
					$shortname = strtoupper(substr($arg['name'],0,3));
				}
				
				// get filter objects
				foreach($arg['filter'] as $filterId) {
					$filter[$filterId] = new Filter($filterId);
				}
			}
			
			// set variables to object
			$this->set_id(null);
			$this->set_name($arg['name']);
			$this->set_shortname($shortname);
			$this->set_date($arg['date']);
			$this->setEndDate((isset($arg['endDate']) ? $arg['endDate'] : null));
			$this->set_type($arg['type']);
			$this->set_content($arg['content']);
			$this->setCity($arg['city']);
			$this->set_preset_id(0);
			$this->setColor($arg['color']);
			$this->setIsExternal($arg['isExternal']);
			$this->set_valid($arg['valid']);
			$this->setFilter($filter);
		} else {
			
			// get field for given id
			$this->get_from_db($arg);
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
		
		// get values from db
		$result = Db::ArrayValue('
			SELECT `c`.`name`,
				`c`.`shortname`,
				`c`.`date`,
				`c`.`end_date`,
				`c`.`type`,
				`c`.`content`,
				`c`.`city`,
				`c`.`preset_id`,
				`c`.`color`,
				`c`.`is_external`,
				`c`.`valid`,
				`c`.`last_modified`,
				`c`.`modified_by`,
				(SELECT COUNT(`r`.`id`)
					FROM `result` AS `r`
					WHERE `r`.`calendar_id`=`c`.`id`) `results`,
				(SELECT COUNT(`fa`.`file_id`)
					FROM `files_attached` AS `fa`
					WHERE `fa`.`table_name`=\'calendar\'
						AND `fa`.`table_id`=`c`.`id`) `files`
			FROM `calendar` AS `c`
			WHERE `c`.`id`=#?	
		',
		MYSQL_ASSOC,
		array($id,));
		if($result === false) {
			throw new MysqlErrorException($this, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
			
		// set variables to object
		$this->set_id($id);
		$this->set_name($result[0]['name']);
		$this->set_shortname($result[0]['shortname']);
		$this->set_date($result[0]['date']);
		$this->setEndDate((!is_null($result[0]['end_date']) ? $result[0]['end_date'] : null));
		$this->set_type($result[0]['type']);
		$this->set_content($result[0]['content']);
		$this->setCity($result[0]['city']);
		$this->set_preset_id($result[0]['preset_id']);
		$this->setColor($result[0]['color']);
		$this->setIsExternal($result[0]['is_external'] == 1);
		$this->set_valid($result[0]['valid']);
		$this->setLastModified((strtotime($result[0]['last_modified']) < 0 ? 0 : strtotime($result[0]['last_modified'])));
		$this->setModifiedBy($result[0]['modified_by']);
		$this->setFilter(Filter::allFilterOf('calendar', $id));
		$this->setAdditionalFields(
			array(
					'files' => $result[0]['files'],
					'results' => $result[0]['results'],
					'webservices' => $this->getWebserviceResults(),
				)
		);
	}
	
	
	
	
	/**
	 * write_db writes the calendar-entry to db
	 * 
	 * @return void
	 */
	public function write_db($action='new') {
		
		// prepare timestamp
		$timestamp = date('Y-m-d',strtotime($this->get_date()));
		$endTimestamp = (is_null($this->getEndDate()) ? null : date('Y-m-d',strtotime($this->getEndDate())));
		
		// insert into database
		if(!Db::executeQuery('
				INSERT INTO calendar (`id`,`name`,`shortname`,`date`,`end_date`,`type`,`content`,`city`,`preset_id`,`color`,`is_external`,`valid`,`last_modified`,`modified_by`)
				VALUES (#?, \'#?\', \'#?\', \'#?\', '.(is_null($endTimestamp) ? '#?' : '\'#?\'').', \'#?\', \'#?\', \'#?\', #?, \'#?\',\'#?\', #?, CURRENT_TIMESTAMP, #?)
				ON DUPLICATE KEY UPDATE
					`name`=\'#?\',
					`shortname`=\'#?\',
					`date`=\'#?\',
					`end_date`='.(is_null($endTimestamp) ? '#?' : '\'#?\'').',
					`type`=\'#?\',
					`content`=\'#?\',
					`city`=\'#?\',
					`preset_id`=#?,
					`color`=\'#?\',
					`is_external`=\'#?\',
					`valid`=#?,
					`last_modified`=CURRENT_TIMESTAMP,
					`modified_by`=#?
			',
				array(// insert
					(is_null($this->getId()) ? 'NULL' : $this->getId()),
					$this->get_name(),
					$this->get_shortname(),
					$timestamp,
					(is_null($endTimestamp) ? 'NULL' : $endTimestamp),
					$this->get_type(),
					$this->get_content(),
					$this->getCity(),
					$this->get_preset_id(),
					$this->getColor(),
					$this->getIsExternal(),
					$this->get_valid(),
					(int)$this->getUser()->get_id(),
					// update
					$this->get_name(),
					$this->get_shortname(),
					$timestamp,
					(is_null($endTimestamp) ? 'NULL' : $endTimestamp),
					$this->get_type(),
					$this->get_content(),
					$this->getCity(),
					$this->get_preset_id(),
					$this->getColor(),
					$this->getIsExternal(),
					$this->get_valid(),
					(int)$this->getUser()->get_id(),))) {
				$n = null;
				throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
			}
		
		// set id if insert
		if(isset(Db::$insertId)) {
			$this->setId(Db::$insertId);
		}
		
		// write filter
		Filter::dbRemove('calendar', $this->getId());
		foreach($this->getFilter() as $filter) {
			$filter->dbWrite('calendar', $this->getId());
		}
	}
	
	
	
	
	/**
	 * detailsToHtml() returns the calendar-entry-details as array
	 * 
	 * @return array calendar entry details as array
	 */
	public function detailsToHtml() {
		
		// prepare filter
		$ownFilter = $this->getFilter();
		$filterNames = '';

		foreach($ownFilter as $filter) {
			$filterNames .= $filter->getName().' ,';
		}
		$filterNames = substr($filterNames,0,-2);

		// prepare data
		$data = array(
					'name' => _l('event<br />').$this->get_name(),
					'shortname' => _l('shortname<br />').$this->get_shortname(),
					'date' => _l('start date<br />').$this->get_date('d.m.Y'),
					'endDate' => _l('end date<br />').$this->getEndDate('d.m.Y'),
					'type' => _l('type<br />').$this->return_type('translated'),
					'content' => _l('description<br />').nl2br($this->get_content()),
					'city' => _l('city<br />').$this->getCity(),
					'color' => _l('color<br />').'<div class="color" style="background-color: '.$this->getColor().';">&nbsp;</div>',
					'isExternal' => _l('is external<br />').($this->getIsExternal() === true ? _l('yes') : _l('no')),
					'filter' => _l('filter<br />').$filterNames,
					'public' => _l('public access<br />').($this->isPermittedFor(0) ? _l('yes') : _l('no')),
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
		
		// get types
		$types = self::return_types();
		
		// check choice
		if($choice == 'raw') {
			return $this->get_type();
		}
		if($choice == 'translated') {
			return $types[$this->get_type()];
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
					'event' => _l('competition/championship'),
					'training' => _l('course'),
					'external' => _l('is external'),
		);
		asort($return, SORT_NATURAL | SORT_FLAG_CASE);
		
		// return
		return $return;
	}
	
	
	
	
	/**
	 * check_id checks if the given id exists in db
	 * 
	 * @return bool true if id exists, false otherwise
	 * @deprecated - 26.01.2014 use Page::exists() instead
	 */
	public static function check_id($id) {
		
		return self::exists('calendar', $id);
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
			} elseif($name == 'endDate') {
				$this->setEndDate($value);
			} elseif($name == 'name') {
				$this->set_name($value);
			} elseif($name == 'shortname') {
				$this->set_shortname($value);
			} elseif($name == 'type') {
				$this->set_type($value);
			} elseif($name == 'content') {
				$this->set_content($value);
			} elseif($name == 'city') {
				$this->setCity($value);
			} elseif($name == 'color') {
				$this->setColor($value);
			} elseif($name == 'isExternal') {
				$this->setIsExternal($value);
			} elseif($name == 'filter') {
				
				// get filter objects
				$filter = array();
				foreach($value as $filterId) {
					$filter[$filterId] = new Filter($filterId);
				}
				$this->setFilter($filter);
			} elseif($name == 'valid') {
				$this->set_valid($value);
			} elseif($name == 'preset_id') {
				$this->set_preset_id($value);
			}
		}
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
		$announcement['version'] = date('d.m.Y', $version);

		// add fields
		// check html
		if($html === true) {
			$announcement['calendar_name'] = nl2br(htmlentities($this->get_name(),ENT_QUOTES,'UTF-8'));
			$announcement['calendar_shortname'] = nl2br(htmlentities($this->get_shortname(),ENT_QUOTES,'UTF-8'));
			$announcement['calendar_date'] = nl2br(htmlentities($this->get_date(),ENT_QUOTES,'UTF-8'));
			$announcement['calendar_date_d_m_Y'] = nl2br(htmlentities($this->get_date('d.m.Y'),ENT_QUOTES,'UTF-8'));
			$announcement['calendar_date_dmY'] = nl2br(htmlentities($this->get_date('dmY'),ENT_QUOTES,'UTF-8'));
			$announcement['calendar_date_j_F_Y'] = nl2br(htmlentities(strftime('%e. %B %Y',$this->get_date('U')),ENT_QUOTES,'UTF-8'));
			$announcement['calendar_date_Y-m-d'] = nl2br(htmlentities($this->get_date('Y-m-d'),ENT_QUOTES,'UTF-8'));
			if(!is_null($this->getEndDate())) {
				$announcement['calendar_enddate'] = nl2br(htmlentities($this->getEndDate(),ENT_QUOTES,'UTF-8'));
				$announcement['calendar_enddate_d_m_Y'] = nl2br(htmlentities($this->getEndDate('d.m.Y'),ENT_QUOTES,'UTF-8'));
				$announcement['calendar_enddate_dmY'] = nl2br(htmlentities($this->getEndDate('dmY'),ENT_QUOTES,'UTF-8'));
				$announcement['calendar_enddate_j_F_Y'] = nl2br(htmlentities(strftime('%e. %B %Y',$this->getEndDate('U')),ENT_QUOTES,'UTF-8'));
				$announcement['calendar_enddate_Y-m-d'] = nl2br(htmlentities($this->getEndDate('Y-m-d'),ENT_QUOTES,'UTF-8'));
			} else {
				$announcement['calendar_enddate'] = '';
				$announcement['calendar_enddate_d_m_Y'] = '';
				$announcement['calendar_enddate_dmY'] = '';
				$announcement['calendar_enddate_j_F_Y'] = '';
				$announcement['calendar_enddate_Y-m-d'] = '';
			}
			$announcement['calendar_type'] = nl2br(htmlentities($this->get_type(),ENT_QUOTES,'UTF-8'));
			$announcement['calendar_content'] = nl2br(htmlentities($this->get_content(),ENT_QUOTES,'UTF-8'));
			$announcement['calendar_date_complete_d_m_y'] = nl2br(htmlentities($this->getCompleteDate('%d.', '%m.', '%Y'),ENT_QUOTES,'UTF-8'));
			$announcement['calendar_date_complete_dmy'] = nl2br(htmlentities($this->getCompleteDate('%d', '%m', '%Y'),ENT_QUOTES,'UTF-8'));
			$announcement['calendar_date_complete_j_F_Y'] = nl2br(htmlentities($this->getCompleteDate('%e.', ' %B', ' %Y'),ENT_QUOTES,'UTF-8'));
			$announcement['calendar_date_complete_Y-m-d|Y-m-d'] = nl2br(htmlentities($this->get_date('Y-m-d').'|'.(!is_null($this->getEndDate()) ? $this->getEndDate('Y-m-d') : $this->get_date('Y-m-d')),ENT_QUOTES,'UTF-8'));
		} else {
			$announcement['calendar_name'] = $this->get_name();
			$announcement['calendar_shortname'] = $this->get_shortname();
			$announcement['calendar_date'] = $this->get_date();
			$announcement['calendar_date_d_m_Y'] = $this->get_date('d.m.Y');
			$announcement['calendar_date_dmY'] = $this->get_date('dmY');
			$announcement['calendar_date_j_F_Y'] = strftime('%e. %B %Y',$this->get_date('U'));
			$announcement['calendar_date_Y-m-d'] = $this->get_date('Y-m-d');
			if(!is_null($this->getEndDate())) {
				$announcement['calendar_enddate'] = $this->getEndDate();
				$announcement['calendar_enddate_d_m_Y'] = $this->getEndDate('d.m.Y');
				$announcement['calendar_enddate_dmY'] = $this->getEndDate('dmY');
				$announcement['calendar_enddate_j_F_Y'] = strftime('%e. %B %Y',$this->getEndDate('U'));
				$announcement['calendar_enddate_Y-m-d'] = $this->getEndDate('Y-m-d');
			} else {
				$announcement['calendar_enddate'] = '';
				$announcement['calendar_enddate_d_m_Y'] = '';
				$announcement['calendar_enddate_dmY'] = '';
				$announcement['calendar_enddate_j_F_Y'] = '';
				$announcement['calendar_enddate_Y-m-d'] = '';
			}
			$announcement['calendar_type'] = $this->get_type();
			$announcement['calendar_content'] = $this->get_content();
			$announcement['calendar_date_complete_d_m_y'] = $this->getCompleteDate('%d.', '%m.', '%Y');
			$announcement['calendar_date_complete_dmy'] = $this->getCompleteDate('%d', '%m', '%Y');
			$announcement['calendar_date_complete_j_F_Y'] = $this->getCompleteDate('%e.', ' %B', ' %Y');
		}
		
		// add webservice results
		if(count($this->getAdditionalFields()['webservices']) > 0) {
			foreach($this->getAdditionalFields()['webservices'] as $wsName => $wsResult) {
				$class = 'WebserviceJob'.ucfirst(strtolower($wsName));
				$announcement['calendar_ws_'.strtolower($wsName)] = $class::addMarks($wsResult, $html);
			}
		}
	}
	
	
	/**
	 * cacheFile() generates the cached file in database
	 * 
	 * @return void
	 */
	public function cacheFile() {
		
		// get preset
		$preset = new Preset($this->get_preset_id(), 'calendar', $this->get_id());
		
		// smarty
		$sA = new JudoIntranetSmarty();
		
		// generate marker-array
		$announcement = $this->generateAllMarks($preset);
		
		// smarty
		$sA->assign('a', $announcement);
		$pdfOut = $sA->fetch($preset->get_path());			
		
		// get HTML2PDF-object
		$pdf = new HTML2PDF('P', 'A4', 'de', true, 'UTF-8', array(0, 0, 0, 0));
		$pdf->writeHTML($pdfOut, false);
		
		// output (D=download; F=save on filesystem; S=string)
		// get filename
		$pdfFilename = $this->replace_umlaute(html_entity_decode($sA->fetch('string:'.$preset->get_filename()), ENT_XHTML, 'UTF-8'));
		
		// prepare file for File::factory
		return array(
				'name' => substr($pdfFilename, 0, -4),
				'filename' => $pdfFilename,
				'mimetype' => 'application/pdf',
				'content' => $pdf->Output($pdfFilename, 'S'),
				'cached' => 'calendar|'.$this->get_id(),
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
			
		// add check announcement value check
		$return[0] = array(
				'result' => self::check_ann_value($this->get('tid')),
				'error' => 'AnnNotExists',
				'errorMessage' => 'entry:'.$this->get_id().'|'.$this->get_preset_id(),
				'errorEntry' => $this->get_id().'|'.$this->get_preset_id(),
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
		return $this->get_name().' '.$this->get_date('d.m.Y').(!is_null($this->getEndDate()) ? '-'.$this->getEndDate('d.m.Y') : '');
	}
	
	
	/**
	 * getDraftValue($pid, $cid) returns the value of the draft field
	 * 
	 * @param int $pid id of the used preset
	 * @param int $cid id of the calender entry
	 * @return int the value of the draft field
	 */
	public static function getDraftValue($pid, $cid) {
		
		// get preset
		$preset = new Preset($pid, 'calendar', $cid);
		// get draft field and read value
		if($preset->getUseDraft() == 1) {
			
			$draftField = $preset->fieldById(-1);
			$draftField->readValue();
			// return value
			return $draftField->get_value();
		} else {
			return 0;
		}
	}
	
	
	/**
	 * updateCity($city) updates the city field in database
	 * 
	 * @param int $tid table id of the entry to be updated
	 * @param string $city city value to be set in database
	 * @return void
	 */
	public static function updateCity($tid, $city) {
		
		// check empty city from callback
		if($city != '') {
			// update city value
			if(!Db::executeQuery('
					UPDATE `calendar`
					SET `city` = \'#?\'
					WHERE `id` = #?
				',
				array(
					$city,
					$tid,
				))) {
					$n = null;
					throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
				}
		}
	}
	
	
	/**
	 * getCompleteDate($dFormat, $mFormat, $yFormat) determines the date range and returns it in
	 * a compressed human readable string formatted by the format parameter (strftime())
	 * 
	 * @param string $dFormat format (incl. the separator) of the day in strftime()
	 * @param string $mFormat format (incl. the separator) of the month in strftime()
	 * @param string $yFormat format (incl. the separator) of the year in strftime()
	 * @return string compressed and human readable date range
	 */
	public function getCompleteDate($dFormat, $mFormat, $yFormat) {
		
		// get dates
		$startDate = $this->get_date('U');
		$endDate = $this->getEndDate('U');
		
		// format start dates
		$day = strftime($dFormat, $startDate);
		$month = strftime($mFormat, $startDate);
		$year = strftime($yFormat, $startDate);
		
		// check if end date set
		if(is_null($endDate)) {
			return $day.$month.$year;
		}
		
		// format dates
		// start date numeric
		$numMonth = strftime('%m', $startDate);
		$numYear = strftime('%Y', $startDate);
		// end date
		$endDay = strftime($dFormat, $endDate);
		$endMonth = strftime($mFormat, $endDate);
		$endYear = strftime($yFormat, $endDate);
		// end date numeric
		$numEndMonth = strftime('%m', $endDate);
		$numEndYear = strftime('%Y', $endDate);
		
		// check if same year
		if($numYear != $numEndYear) {
			return $day.$month.$year.' - '.$endDay.$endMonth.$endYear;
		}
		
		// check same month
		if($numMonth != $numEndMonth) {
			return $day.$month.' - '.$endDay.$endMonth.$endYear;
		}
		
		// is same month
		return $day.' - '.$endDay.$endMonth.$endYear;
	}
	
	
	/**
	 * linkTo($linkingId, $linkedId) links the $linkedId object to the $linkingId object
	 * 
	 * @param int $linkedId id of the object that will be linked
	 * @param int $linkingId id of the object that is linked to
	 * @return array array containing the result of the linkage
	 */
	public static function linkTo($linkedId, $linkingId) {
		
		// check $linkingId
		if($linkingId == '') {
		
			// delete existing links
			if(!Db::executeQuery('
				DELETE FROM `accounting_settings`
				WHERE `table` = \'calendar\'
					AND `type`=\'subitemof\'
					AND `id1`=#?
			',
			array($linkedId,)
			)) {
				$n = null;
				throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
			}
		} else {
		
			// insert link
			if(!Db::executeQuery('
				INSERT IGNORE INTO `accounting_settings`
					(`id1`, `id2`, `type`, `table`)
					VALUES (#?, #?, \'subitemof\', \'calendar\')
			',
			array(
				$linkedId,
				$linkingId,
			))) {
					$n = null;
					throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
			}
		}
		
		// return
		return array(
				'result' => 'OK',
				'message' => 'linked',
			);
	}
	
	
	/**
	 * isLinked() returns true, if is subitem of another calendar entry, false otherwise
	 * 
	 * @return bool true, if is subitem of another calendar entry, false otherwise
	 */
	public function isLinked() {
		
		// get value from database
		$linkedValue = Db::singleValue('
				SELECT COUNT(*)
				FROM `accounting_settings`
				WHERE `id1`=#?
					AND `table`=\'calendar\'
					AND `type`=\'subitemof\'
			',
			array($this->getId(),));
		if($linkedValue === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		} else {
			return $linkedValue > 0;
		}
	}
	
	
	/**
	 * generateAllMarks() adds calendar and field marks to an array and returns it
	 * 
	 * @param object $preset the preset for this calendar entry to get the fields from 
	 * @return array array containing all marks
	 */
	public function generateAllMarks($preset) {
		
		// smarty
		$sA = new JudoIntranetSmarty();
		
		// prepare marker-array
		$announcement = array(
				'version' => '01.01.70 01:00',
		);
		
		// add calendar-fields to array
		$this->add_marks($announcement);
		
		// add field-names and -values to array
		$preset->add_marks($announcement);
		
		// smarty
		$sA->assign('a', $announcement);
		// check marks in values
		foreach($announcement as $k => $v) {
				
			if(preg_match('/\{\$a\..*\}/U', $v)) {
				$announcement[$k] = $sA->fetch('string:'.$v);
			}
		}
		
		// return
		return $announcement;
	}
	
	
	/**
	 * getWebserviceResults() gets the values of the webservice results for this object and
	 * returns them as array
	 * 
	 * @return array array containing the results of the webservice calls for this object
	 */
	private function getWebserviceResults() {
		
		// get values from db
		$result = Db::ArrayValue('
			SELECT `webservice`, `value`
			FROM `webservice_results`
			WHERE `table`=\'calendar\'
				AND `table_id`=#?
		',
				MYSQL_ASSOC,
				array($this->get_id(),));
		if($result === false) {
			throw new MysqlErrorException($this, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// walk through result
		$webservices = array();
		foreach($result as $row) {
			$webservices[strtolower($row['webservice'])][] = json_decode($row['value'], true);
		}
		
		// return
		return $webservices;
	}
}



?>
