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
 * class FileListallListing implements the data handling of listing the files from 
 * the database
 */
class FileListallListing extends Listing implements ListingInterface {
	
	
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
		
		// get permitted ids
		$ids = $this->getUser()->permittedItems('file', 'w');
		
		// check if empty result
		$mysqlData = implode(',', $ids);
		if(count($ids) == 0) {
			$mysqlData = 'SELECT FALSE';
		}
		
		// check jTable and get data
		if(count($getData) > 0) {
			
			// check order by
			if($getData['orderBy'] == '') {
				$getData['orderBy'] = 'ORDER BY `name` ASC';
			}
			
			$sql = '
				SELECT `f`.`id`, `f`.`name`, `ft`.`name` AS `filetype`, `f`.`filename`
				FROM `file` AS `f`, `file_type` AS `ft`
				WHERE `f`.`id` IN (#?)
					AND ISNULL(`f`.`cached`)
					AND `f`.`valid`=TRUE
					AND `f`.`file_type`=`ft`.`id`
				'.$getData['orderBy'].'
				'.$getData['limit'].'
			';
		} else {
			
			$sql = '
				SELECT `f`.`id`, `f`.`name`, `ft`.`name` AS `filetype`, `f`.`filename`
				FROM `file` AS `f`, `file_type` AS `ft`
				WHERE `f`.`id` IN (#?)
					AND ISNULL(`f`.`cached`)
					AND `f`.`valid`=TRUE
					AND `f`.`file_type`=`ft`.`id`
				ORDER BY `name` ASC
			';
		}

		$result = Db::ArrayValue($sql,
		MYSQLI_ASSOC,
		array(	$mysqlData,));
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
		
		// get permitted ids
		$ids = $this->getUser()->permittedItems('file', 'w');
		
		// check if empty result
		$mysqlData = implode(',', $ids);
		if(count($ids) == 0) {
			$mysqlData = 'SELECT FALSE';
		}
		
		$countRows = Db::singleValue('
				SELECT COUNT(*)
				FROM `file`
				WHERE `id` IN (#?)
					AND ISNULL(`cached`)
					AND `valid`=TRUE
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
	
	
	/**
	 * prepareResults($results) prepares the result array to use with jtable
	 * 
	 * @param array $results array containing results from database
	 * @return array prepared array to use with smarty template
	 */
	private function prepareResults($results) {
		
		// walk through data
		$return = array();
		foreach($results as $row) {
			
			// prepare smarty templates for links and images
			// name
			$smarty = new JudoIntranetSmarty();
			$nameLinkArray = array(
					array(
							'href' => 'file.php?id=details&fid='.$row['id'],
							'title' => _l('details'),
							'name' => $row['name'],
						),
				);
			$smarty->assign('data', $nameLinkArray);
			$nameLink = $smarty->fetch('smarty.a.img.tpl');
			
			// show
			$showArray = array();
			$showArray[] = array(
					'href' => 'file.php?id=download&fid='.$row['id'],
					'title' => $row['name']._l(' download'),
					'name' => array(
							'src' => 'img/file_download.png',
							'alt' => $row['name']._l(' download'),
						),
				);
			
			$smarty->assign('data', $showArray);
			$smarty->assign('spacer', true);
			$show = $smarty->fetch('smarty.a.img.tpl');
				
			// add admin
			$adminArray = array();
			$admin = '';
			// if user is loggedin add admin-links
			if($this->getUser()->get_loggedin() === true) {
					
				// smarty
				// edit
				$adminArray[] = array(
						'href' => 'file.php?id=edit&fid='.$row['id'],
						'title' => _l('edit file'),
						'name' => array(
								'src' => 'img/file_edit.png',
								'alt' => _l('edit file'),
							),
					);
				// delete
				$adminArray[] = array(
						'href' => 'file.php?id=delete&fid='.$row['id'],
						'title' => _l('delete file'),
						'name' => array(
								'src' => 'img/file_delete.png',
								'alt' => _l('delete file'),
							),
					);
			}
			$smarty->assign('data', $adminArray);
			$admin = $smarty->fetch('smarty.a.img.tpl');
			
			// add to return array
			$return[] = array(
					'name' => $nameLink,
					'filetype' => $row['filetype'],
					'filename' => $row['filename'],
					'show' => $show,
					'admin' => $admin
				);
		}
		
		// return
		return $return;
	}
	
	
	/**
	 * apiSearch($query) searches for $query in the database and returns an array to be used
	 * with jquery ui autocomplete
	 * 
	 * @param string $query string to be seached for in database
	 * @return array array containing 'label' and 'value' of the seach results
	 */
	public static function apiSearch($query) {
		
		// get permitted ids
		$ids = self::staticGetUser()->permittedItems('file', 'r');
		
		// check if empty result
		$mysqlData = implode(',', $ids);
		if(count($ids) == 0) {
			$mysqlData = 'SELECT FALSE';
		}
		
		// prepare query
		$sqlQuery = $query;
		if(strpos($query, ' ')) {
			$sqlQuery = str_replace(' ', '%', $query);
		}
		
		// prepare sql
		$sql = '
			SELECT `f`.`id`, `f`.`name`, `f`.`filename`, `f`.`cached`
			FROM `file` AS `f`
			WHERE `f`.`valid`=TRUE
				AND (`f`.`name` LIKE \'%#?%\'
					OR `f`.`filename` LIKE \'%#?%\')
				AND `f`.`id` IN (#?)
			ORDER BY `f`.`name`
			LIMIT 10		
		';
		
		$result = Db::ArrayValue($sql,
		MYSQLI_ASSOC,
		array(	$sqlQuery,
				$sqlQuery,
				$mysqlData,
			));
		if($result === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// prepare result
		$return = array();
		if(count($result) > 0) {
			foreach($result as $row) {
				
				// prepare and translate table name
				$tableName = _l('table name uploaded');
				if(!is_null($row['cached'])) {
					$tableName = _l('table name '. explode('|', $row['cached'])[0]);
				}
				
				$return[] = array(
						'label' => self::highlightApiSearch($query, $row['name'] .' ('.$row['filename']).') ['.$tableName.']',
						'value' => 'file.php?id=download&fid='.$row['id'],
					);
			}
		} else {
			$return[] = array('label' => '- '._l('no results').' -', 'value' => 'file.php?id=listall');
		}
		
		// return
		return $return;
	}
}
?>
