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
 * class TributeListallListing implements the data handling of listing the tributes from 
 * the database
 */
class TributeListallListing extends Listing implements ListingInterface {
	
	
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
		$ids = $this->getUser()->permittedItems('tribute', 'w');
		
		// check if empty result
		$mysqlData = implode(',', $ids);
		if(count($ids) == 0) {
			$mysqlData = 'SELECT FALSE';
		}
		
		// check dropdown selection
		$postSelect = $this->post('select');
		$postValue = $this->post('value');
		$postWhere = '';
		if($postSelect !== false && $postValue !== false) {
			
			// year
			if($postSelect == 'year') {
				$postWhere = 'AND `t`.`year`=\''.$postValue.'\'';
			} elseif($postSelect == 'testimonial') {
				$postWhere = 'AND `tm`.`id`=\''.$postValue.'\'';
			}
		}
		
		// check jTable and get data
		if(count($getData) > 0) {
			
			// check order by
			if($getData['orderBy'] == '') {
				$getData['orderBy'] = 'ORDER BY `name` ASC';
			}
			
			$sql = '
				SELECT `t`.`id`, `t`.`name`, `t`.`year`, `tm`.`name` AS `testimonial`, `t`.`planned_date`, `t`.`start_date`, `t`.`date`
				FROM `tribute` AS `t`, `testimonials` AS `tm`
				WHERE `t`.`id` IN (#?)
					AND `t`.`valid`=TRUE
					AND `t`.`testimonial_id`=`tm`.`id`
					'.$postWhere.'
				'.$getData['orderBy'].'
				'.$getData['limit'].'
			';
		} else {
			
			$sql = '
				SELECT `t`.`id`, `t`.`name`, `t`.`year`, `tm`.`name` AS `testimonial`, `t`.`planned_date`, `t`.`start_date`, `t`.`date`
				FROM `tribute` AS `t`, `testimonials` AS `tm`
				WHERE `t`.`id` IN (#?)
					AND `t`.`valid`=TRUE
					AND `t`.`testimonial_id`=`tm`.`id`
					'.$postWhere.'
				ORDER BY `t`.`name` ASC
			';
		}

		$result = Db::ArrayValue($sql,
		MYSQL_ASSOC,
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
		$ids = $this->getUser()->permittedItems('tribute', 'w');
		
		// check if empty result
		$mysqlData = implode(',', $ids);
		if(count($ids) == 0) {
			$mysqlData = 'SELECT FALSE';
		}
		
		// check dropdown selection
		$postSelect = $this->post('select');
		$postValue = $this->post('value');
		$postWhere = '';
		if($postSelect !== false && $postValue !== false) {
				
			// year
			if($postSelect == 'year') {
				$postWhere = 'AND `t`.`year`=\''.$postValue.'\'';
			} elseif($postSelect == 'testimonial') {
				$postWhere = 'AND `t`.`testimonial_id`=\''.$postValue.'\'';
			}
		}
		
		$countRows = Db::singleValue('
				SELECT COUNT(*)
				FROM `tribute` AS `t`
				WHERE `t`.`id` IN (#?)
					AND `t`.`valid`=TRUE
					'.$postWhere.'
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
			$smarty = new JudoIntranetSmarty();
				
			// add admin
			$adminArray = array();
			$admin = '';
			// if user is loggedin add admin-links
			if($this->getUser()->get_loggedin() === true) {
					
				// smarty
				// edit
				$adminArray[] = array(
						'href' => 'tribute.php?id=edit&tid='.$row['id'],
						'title' => _l('edit tribute'),
						'name' => array(
								'src' => 'img/tribute_edit.png',
								'alt' => _l('edit tribute'),
							),
					);
				// delete
				$adminArray[] = array(
						'href' => 'tribute.php?id=delete&tid='.$row['id'],
						'title' => _l('delete tribute'),
						'name' => array(
								'src' => 'img/tribute_delete.png',
								'alt' => _l('delete tribute'),
							),
					);
				// download
				$adminArray[] = array(
						'href' => 'api/filesystem/tribute/'.$row['id'],
						'title' => _l('download tribute as PDF'),
						'name' => array(
								'src' => 'img/tribute_pdf.png',
								'alt' => _l('download tribute as PDF'),
							),
					);
			}
			$smarty->assign('data', $adminArray);
			$admin = $smarty->fetch('smarty.a.img.tpl');
			
			// add to return array
			$return[] = array(
					'name' => $row['name'],
					'year' => $row['year'],
					'testimonial' => $row['testimonial'],
					'start_date' => date('d.m.Y', strtotime($row['start_date'])),
					'planned_date' => (is_null($row['planned_date']) ? '' : date('d.m.Y', strtotime($row['planned_date']))),
					'date' => (is_null($row['date']) ? '' : date('d.m.Y', strtotime($row['date']))),
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
		
		// prepare sql
		$sql = '
			SELECT `id`, `name`, `year`
			FROM `tribute`
			WHERE `valid`=TRUE
				AND `name` LIKE \'%#?%\'
			ORDER BY `name`
			LIMIT 10		
		';
		
		$result = Db::ArrayValue($sql,
		MYSQL_ASSOC,
		array($query,));
		if($result === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// prepare result
		$return = array();
		if(count($result) > 0) {
			foreach($result as $row) {
				$return[] = array(
						'label' => self::highlightApiSearch($query, $row['name']) .' ('.$row['year'].')',
						'value' => 'tribute.php?id=edit&tid='.$row['id'],
					);
			}
		} else {
			$return[] = array('label' => '- '._l('no results').' -', 'value' => 'tribute.php?id=listall');
		}
		
		// return
		return $return;
	}
	
	
	/**
	 * highlightApiSearch($query, $result) replaces $query with hightlighted version in $result
	 * 
	 * @param string $query the seach string that should be highlighted
	 * @param string $result the result string from database that contains $query
	 * @return string the highlighted result string
	 */
	private static function highlightApiSearch($query, $result) {
		
		// check if there are strings to replace
		if(stripos($result, $query) === false) {
			return $result;
		}
		
		// direct replacement
		$replace = str_replace($query, '<b>'.$query.'</b>', $result);
		if($result != $replace) {
			return $replace;
		}
		
		// ucfirst replacement
		$replace = str_replace(ucfirst($query), '<b>'.ucfirst($query).'</b>', $result);
		if($result != $replace) {
			return $replace;
		}
		
		// ucwords replacement
		$replace = str_replace(ucwords($query), '<b>'.ucwords($query).'</b>', $result);
		if($result != $replace) {
			return $replace;
		}
	}
}
?>
