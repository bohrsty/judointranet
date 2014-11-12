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
 * class AdministrationUsertableFieldListing implements the data handling of listing the results from the database
 */
class AdministrationUsertableFieldListing extends Listing implements ListingInterface {
	
	
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
		
		// return
		return '';
	}
	
	
	/**
	 * getData() retrieves the required data from db
	 * 
	 * @param array $getData data given from jTable for sorting and pageing
	 * @return array prepared data from db
	 */
	private function getData($getData = array()) {
		
		// get table config
		$tableConfig = $this->getTableConfig($this->get('table'));
		// get column names as array
		$colums = array_merge(array('id'), explode(',', $tableConfig['cols']));
		$select = '`'.implode('`,`',$colums).'`';
		
		// check jTable and get data
		if(count($getData) > 0) {
			
			// check order by
			if($getData['orderBy'] == '') {
				$getData['orderBy'] = $tableConfig['orderBy'];
			}
			
			$sql = '
				SELECT #?
				FROM `#?`
				'.$getData['orderBy'].'
				'.$getData['limit'].'
			';
		} else {
			
			$sql = '
				SELECT #?
				FROM `#?`
				#?
			';
		}
		$result = Db::ArrayValue($sql,
		MYSQL_ASSOC,
		array(
				$select,
				$this->get('table'),
				$tableConfig['orderBy'],
			));
		if($result === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// prepare valid
		$validToText = array(
			0 => 'false',
			1 => 'true',
			);
		$return = array();
		foreach($result as $row) {
			
			$row['valid'] = $validToText[$row['valid']];
			$return[] = $row;
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
				FROM `#?`
			',
		array($this->get('table'),)
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
		
		// check permissions
		if($this->getUser()->hasPermission('navi', 35, 'w') === false) {
			return JTABLE_NOT_AUTHORIZED;
		}
		// check if row exists
		if(Page::exists($this->get('table'), $postData['id']) === false) {
			return JTABLE_ROW_NOT_EXISTS;
		}
		
		// get table config
		$tableConfig = $this->getTableConfig($this->get('table'));
		
		// prepare text to valid
		$textToValid = array(
				'false' => 0,
				'true' => 1,
			);
					
		// prepare data array
		$set = '';
		$data = array($this->get('table'),);
		foreach(explode(',', $tableConfig['cols']) as $col) {
			
			// text to valid
			if($col == 'valid') {
				$postData[$col] = $textToValid[$postData[$col]];
			}
			$data[] = $postData[$col];
			$set .= '`'.$col.'`=\'#?\',';
		}
		$set = substr($set, 0, -1);
		$data[] = $postData['id'];
		
		// execute query
		if(!Db::executeQuery('
			UPDATE `#?`
			SET '.$set.'
			WHERE `id`=\'#?\'
		',
		$data)) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// return success
		return true;
	}
	
	
	/**
	 * singleRow($id) returns an array with the data of the row given by $id
	 * 
	 * @param int $id id of the row to be returned
	 * @return array array with the data of the row given by $id
	 */
	public function singleRow($id) {
		
		// prepare valid
		$validToText = array(
				0 => 'false',
				1 => 'true',
			);
		
		// get table config
		$tableConfig = $this->getTableConfig($this->get('table'));
		// get column names as array
		$colums = array_merge(array('id'), explode(',', $tableConfig['cols']));
		$select = '`'.implode('`,`', $colums).'`';
		
		$singleRow = Db::arrayValue('
				SELECT #?
				FROM `#?`
				WHERE `id`=#?
			',
			MYSQL_ASSOC,
			array(
					$select,
					$this->get('table'),
					$id,
				)
		);
		if($singleRow === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		} else {
			$singleRow[0]['valid'] = $validToText[$singleRow[0]['valid']];
			return $singleRow[0];
		}
	}
	
	
	/**
	 * deleteRow($id) deletes the row given by $id
	 * 
	 * @param int $id id of the row to be deleted
	 */
	public function deleteRow($id) {
		
		// check permissions
		if($this->getUser()->hasPermission('navi', 35, 'w') === false) {
			return JTABLE_NOT_AUTHORIZED;
		}
		// check if row exists
		if(Page::exists($this->get('table'), $id) === false) {
			return JTABLE_ROW_NOT_EXISTS;
		}
		
		// execute query
		if(!Db::executeQuery('
			DELETE FROM `#?`
			WHERE `id`=\'#?\'
		',
		array(
				$this->get('table'),
				$id,
			))) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// return success
		return true;
	}
	
	
	/**
	 * createRow($postData) creates a new row with the given $postData
	 * 
	 * @param array $postData data to create row
	 */
	public function createRow($postData) {
		
		// check permissions
		if($this->getUser()->hasPermission('navi', 35, 'w') === false) {
			return array(
				'return' => JTABLE_NOT_AUTHORIZED,
				'newId' => null,
			);
		}
		
		// get table config
		$tableConfig = $this->getTableConfig($this->get('table'));
		
		// prepare text to valid
		$textToValid = array(
				'false' => 0,
				'true' => 1,
			);
					
		// prepare data array
		$tableCols = '`id`,';
		$tableValues = 'NULL,';
		$data = array($this->get('table'),);
		foreach(explode(',', $tableConfig['cols']) as $col) {
			
			// text to valid
			if($col == 'valid') {
				$postData[$col] = $textToValid[$postData[$col]];
			}
			$data[] = $postData[$col];
			$tableCols .= '`'.$col.'`,';
			$tableValues .= '\'#?\',';
		}
		$tableCols = substr($tableCols, 0, -1);
		$tableValues = substr($tableValues, 0, -1);
		$data[] = $this->get('table');
		
		// execute query
		if(!Db::executeQuery('
			INSERT INTO `#?` ('.$tableCols.')
			VALUES ('.$tableValues.')
		',
		$data)) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// return new id
		return array(
				'return' => true,
				'newId' => Db::$insertId,
			);
	}
	
}
?>
