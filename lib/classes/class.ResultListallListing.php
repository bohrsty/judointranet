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
 * class ResultListallListing implements the data handling of listing the results from the database
 */
class ResultListallListing extends ResultListing implements ListingInterface {
	
	
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
				'desc' => _l('result desc'),
				'name' => _l('event name'),
				'date' => _l('event date'),
				'city' => _l('event city'),
				'show' => _l('show'),
				'admin' => _l('admin'),
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
	 * @param array $getData data given from jTable for sorting and pageing
	 * @return array prepared data from db
	 */
	private function getData($getData = array()) {
		
		// get permitted result ids
		$resultIds = $this->getUser()->permittedItems('result', 'w');
		
		// check if empty result
		$mysqlData = implode(',', $resultIds);
		if(count($resultIds) == 0) {
			$mysqlData = 'SELECT FALSE';
		}
		
		// check jTable and get data
		if(count($getData) > 0) {
			
			$sql = '
				SELECT `r`.`desc`,`c`.`id` AS `calendar_id`,`c`.`name`,`c`.`date`,`c`.`city`,`r`.`id`
				FROM `calendar` AS `c`, `result` AS `r`
				WHERE `r`.`id` IN (#?)
				AND `r`.`valid`=TRUE
				AND `r`.`calendar_id`=`c`.`id`
				AND `c`.`valid`=TRUE
				'.$getData['orderBy'].'
				'.$getData['limit'].'
			';
		} else {
			
			$sql = '
				SELECT `r`.`desc`,`c`.`id` AS `calendar_id`,`c`.`name`,`c`.`date`,`c`.`city`,`r`.`id`
				FROM `calendar` AS `c`, `result` AS `r`
				WHERE `r`.`id` IN (#?)
				AND `r`.`valid`=TRUE
				AND `r`.`calendar_id`=`c`.`id`
				AND `c`.`valid`=TRUE
				ORDER BY `c`.`date` DESC
			';
		}
		$result = Db::ArrayValue($sql,
		MYSQLI_ASSOC,
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
		
		// get permitted result ids
		$resultIds = $this->getUser()->permittedItems('result', 'w');
		
		$countRows = Db::singleValue('
				SELECT COUNT(*)
				FROM `calendar` AS `c`, `result` AS `r`
				WHERE `r`.`id` IN (#?)
				AND `r`.`valid`=TRUE
				AND `r`.`calendar_id`=`c`.`id`
				AND `c`.`valid`=TRUE
			',
		array(implode(',', $resultIds),)
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
