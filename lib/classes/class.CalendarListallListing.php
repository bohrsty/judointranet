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
 * class CalendarListallListing implements the data handling of listing the calendars from 
 * the database
 */
class CalendarListallListing extends CalendarListing implements ListingInterface {
	
	
	/*
	 * class-variables
	 */
	
	
	/*
	 * getter/setter
	 */
	
	/*
	 * constructor/destructor
	 */
	public function __construct() {
		
		// setup parent
		parent::__construct();
	}
	
	/*
	 * methods
	 */
	/**
	 * listingAsArray() returns the listing data as array of associative
	 * arrays (column name => value)
	 * 
	 * @param array $postData POST data from jTable api call
	 * @return array array of associative arrays (column name => value) to use with template
	 */
	public function listingAsArray($postData = array()) {
		
		// return
		return $this->getData($postData);
	}
	
	
	/**
	 * listingAsHtml($templatefile, $assign) returns the listing data as HTML string
	 * generated from $templatefile; with the use of the required $assign fields
	 * 
	 * @param string $templatefile filename of the template to generate the listing
	 * @param array $assign array of fields required to be assigned in $templatefile
	 * @return string generated HTML string from $template
	 */
	public function listingAsHtml($templatefile = '', $assign = array()) {
		
		// check for template file
		if($templatefile == '') {
			return '';
		}
		
		// get data
		$data = $this->getData();
		
		// prepare template
		$tpl = new JudoIntranetSmarty();
		
		// assign all required fields from $assign with empty string
		foreach($assign as $field) {
			$tpl->assign($field, '');
		}
		
		// assign table header
		$th = array(
				'date' => _l('date'),
				'name' => _l('event'),
				'city' => _l('city'),
				'show' => _l('show'),
				'admin' => _l('tasks'),
			);
		$tpl->assign('ths', $th);
		
		// assign data
		$tpl->assign('data', $data);
		
		// return HTML string from $templatefile
		return $tpl->fetch($templatefile);
	}
	
	
	/**
	 * getData() retrieves the required data from db
	 * 
	 * @return array prepared data from db
	 */
	private function getData($getData = array()) {
		
		// prepare return
		$return = array();
		
		// get permitted and filtered ids
		if($this->get('filter') == '') {
			$filterId = false;
		}
		$ids = Filter::filterItemIdsAsArray($filterId, 'calendar', $this->get('from'), $this->get('to'));
		
		// check if empty result
		$mysqlData = implode(',', $ids);
		if(count($ids) == 0) {
			$mysqlData = 'SELECT FALSE';
		}
		
		// check jTable and get data
		if(count($getData) > 0) {
			
			// check order by
			if($getData['orderBy'] == '') {
				$getData['orderBy'] = 'ORDER BY `date` DESC';
			}
			
			$sql = '
				SELECT `c`.`id`,`c`.`preset_id`,`c`.`date`,`c`.`end_date`,`c`.`name` AS `event`,`c`.`city`,`c`.`color`,`c`.`is_external`,IFNULL(IF(`c`.`preset_id`<>0,(SELECT `value`.`value` FROM `value` WHERE `value`.`table_name`=\'calendar\' AND `value`.`table_id`=`c`.`id` AND `value`.`field_id`=-1 LIMIT 1),0),0) AS `draftvalue`, IF((SELECT COUNT(*) FROM `value` WHERE `value`.`table_name`=\'calendar\' AND `value`.`table_id`=`c`.`id`)>0,1,0) AS `has_ann_value`,(SELECT COUNT(*) FROM `result` AS `r` WHERE `r`.`calendar_id`=`c`.`id`) AS `results`,(SELECT COUNT(*) FROM `files_attached` AS `fa` WHERE `fa`.`table_name`=\'calendar\' AND `fa`.`table_id`=`c`.`id`) AS `files`
				FROM `calendar` AS `c`
				WHERE `c`.`id` IN (#?)
					AND `c`.`valid`=TRUE
				'.$getData['orderBy'].'
				'.$getData['limit'].'
			';
		} else {
			
			$sql = '
				SELECT `c`.`id`,`c`.`preset_id`,`c`.`date`,`c`.`end_date`,`c`.`name` AS `event`,`c`.`city`,`c`.`color`,`c`.`is_external`,IFNULL(IF(`c`.`preset_id`<>0,(SELECT `value`.`value` FROM `value` WHERE `value`.`table_name`=\'calendar\' AND `value`.`table_id`=`c`.`id` AND `value`.`field_id`=-1 LIMIT 1),0),0) AS `draftvalue`, IF((SELECT COUNT(*) FROM `value` WHERE `value`.`table_name`=\'calendar\' AND `value`.`table_id`=`c`.`id`)>0,1,0) AS `has_ann_value`,(SELECT COUNT(*) FROM `result` AS `r` WHERE `r`.`calendar_id`=`c`.`id`) AS `results`,(SELECT COUNT(*) FROM `files_attached` AS `fa` WHERE `fa`.`table_name`=\'calendar\' AND `fa`.`table_id`=`c`.`id`) AS `files`
				FROM `calendar` AS `c`
				WHERE `c`.`id` IN (#?)
					AND `c`.`valid`=TRUE
				ORDER BY `date` DESC
			';
		}

		$result = Db::ArrayValue($sql,
		MYSQL_ASSOC,
		array($mysqlData,));
		if($result === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// return
		return $this->prepareResults($result);
	}
	
	
	/**
	 * totalRowCount() returns the total number of rows for this listing
	 * 
	 * @return int total number of rows in this listing
	 */
	public function totalRowCount() {
		
		// get permitted and filtered ids
		if($this->get('filter') == '') {
			$filterId = false;
		}
		$ids = Filter::filterItemIdsAsArray($filterId, 'calendar', $this->get('from'), $this->get('to'));
		
		// check if empty result
		$mysqlData = implode(',', $ids);
		if(count($ids) == 0) {
			$mysqlData = 'SELECT FALSE';
		}
		
		$countRows = Db::singleValue('
				SELECT COUNT(*)
				FROM `calendar` AS `c`
				WHERE `c`.`id` IN (#?)
					AND `c`.`valid`=TRUE
			',
			array($mysqlData,)
		);
		if($countRows === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		} else {
			return $countRows;
		}
	}
}
?>
