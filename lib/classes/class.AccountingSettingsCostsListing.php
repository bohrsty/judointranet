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
 * class AccountingSettingsCostsListing implements the data handling of listing the cost settings
 * for accounting from the database
 */
class AccountingSettingsCostsListing extends Listing implements ListingInterface {
	
	
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
				_l('identifier'),
				_l('name'),
				_l('type'),
				_l('value'),
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
	private function getData($postData = array()) {
		
		// prepare return
		$return = array();
		
		// check jTable and get data
		if(count($postData) > 0) {
			
			$sql = '
				SELECT `id`, `name`, `type`, `value`
				FROM `accounting_costs`
				WHERE `valid`=TRUE
				'.$postData['orderBy'].'
				'.$postData['limit'].'
			';
		} else {
			
			$sql = '
				SELECT `name`, `type`, `value`
				FROM `accounting_costs`
				WHERE `valid`=TRUE
				ORDER BY `name`
			';
		}
		$result = Db::ArrayValue($sql,
		MYSQLI_ASSOC,
		array());
		if($result === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// walk through data
		$calendarIds = array();
		foreach($result as $data) {
			
			// get translated name
			$data['name'] = _l('costs '.$data['name']);
			
			// put in return array
			$return[] = $data;
		}
		
		// return
		return $return;
	}
	
	
	/**
	 * totalRowCount() returns the total number of rows for this listing
	 * 
	 * @return int total number of rows in this listing
	 */
	public function totalRowCount() {
		
		$countRows = Db::singleValue('
				SELECT COUNT(*)
				FROM `accounting_costs`
				WHERE `valid`=TRUE
			'
		);
		if($countRows === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		} else {
			return $countRows;
		}
	}
	
	
	/**
	 * updateRow($postData) updates the row given by $postData['id'] with the given $postData
	 * 
	 * @param array $postData data to update row
	 */
	public function updateRow($postData) {
		
		// prepare data array
		$data = array(
				$postData['value'],
				$postData['id'],
			);
		
		// execute query
		if(!Db::executeQuery('
			UPDATE `accounting_costs`
			SET `value`=\'#?\'
			WHERE `id`=\'#?\'
		',
		$data)) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
	}
	
	
	/**
	 * singleRow($id) returns an array with the data of the row given by $id
	 * 
	 * @param int $id id of the row to be returned
	 * @return array array with the data of the row given by $id
	 */
	public function singleRow($id) {
		
		$singleRow = Db::arrayValue('
				SELECT `id`, `name`, `type`, `value`
				FROM `accounting_costs`
				WHERE `id`=#?
			',
			MYSQLI_ASSOC,
			array($id,)
		);
		if($singleRow === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		} else {
			return $singleRow[0];
		}
	}
	
}
?>
