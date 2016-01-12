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
	private function getData($postData = array()) {
		
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
		$postFilter = array();
		$postWhere = 'AND (';
		if($this->post('filter') !== false) {
			$postFilter = json_decode($this->post('filter'), true);
		
			$postBool = 'AND ';
			if($this->post('bool') !== false) {
				if($this->post('bool') == 'or') {
					$postBool = 'OR ';
				}
			}
			// year
			if($postFilter['year'] !== '') {
				$postWhere .= '`t`.`year`=\''.$postFilter['year'].'\' '.$postBool;
			}
			// testimonial category
			if($postFilter['category'] !== '') {
				$postWhere .= '`tm`.`category_id`=\''.$postFilter['category'].'\' '.$postBool;
			}
			// testimonial
			if($postFilter['testimonial'] !== '') {
				$postWhere .= '`t`.`testimonial_id`=\''.$postFilter['testimonial'].'\' '.$postBool;
			}
			// state
			if($postFilter['state'] !== '') {
				$postWhere .= '`t`.`state_id`=\''.$postFilter['state'].'\' '.$postBool;
			}
			// club
			if($postFilter['club'] !== '') {
				$postWhere .= '`t`.`club_id`=\''.$postFilter['club'].'\' '.$postBool;
			}
		}
		if($postWhere == 'AND (') {
			$postWhere = '';
		} else {
			$postWhere = substr($postWhere, 0, -strlen($postBool));
			$postWhere .= ')';
		}
		
		// prepare sql
		$sql = '
				SELECT
					`t`.`id`,
					`t`.`name`,
				    `c`.`name` AS `club`,
				    `t`.`year`,
					`tc`.`name` AS `category_id`,
				    `tm`.`name` AS `testimonial`,
				    `t`.`planned_date`,
				    `t`.`start_date`,
				    `t`.`date`,
				    `ts`.`name` AS `state`
				FROM
					`tribute` AS `t`
				JOIN `testimonials` AS `tm` ON `t`.`testimonial_id`=`tm`.`id`
				JOIN `testimonial_category` AS `tc` ON `tm`.`category_id`=`tc`.`id`
				JOIN `tribute_state` AS `ts` ON `t`.`state_id`=`ts`.`id`
				LEFT JOIN `club` AS `c` ON `c`.`id`=`t`.`club_id`
				WHERE `t`.`id` IN (#?)
					AND `t`.`valid`=TRUE
					'.$postWhere;
		
		// check jTable and get data
		$_SESSION['printTributeList']['timestamp'] = time() + $this->getGc()->get_config('tribute.printTimeout');
		$_SESSION['printTributeList']['timestampChecked'] = false;
		$_SESSION['printTributeList']['printDate'] = time();
		
		if(count($postData) > 0) {
			
			// check order by
			if($postData['orderBy'] == '') {
				$postData['orderBy'] = 'ORDER BY `name` ASC';
			}
			
			// add order by clause
			$sql .= $postData['orderBy'];
			
			// save sql w/o limit in session
			$_SESSION['printTributeList']['sql'] = $sql;
			
			// add limit clause
			$sql .= PHP_EOL . $postData['limit'];
		} else {
			
			// add order by clause
			$sql .= PHP_EOL . 'ORDER BY `t`.`name` ASC';

			// save sql in session
			$_SESSION['printTributeList']['sql'] = $sql;
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
		$postFilter = array();
		$postWhere = 'AND (';
		if($this->post('filter') !== false) {
			$postFilter = json_decode($this->post('filter'), true);
		
			$postBool = 'AND ';
			if($this->post('bool') !== false) {
				if($this->post('bool') == 'or') {
					$postBool = 'OR ';
				}
			}
			// year
			if($postFilter['year'] !== '') {
				$postWhere .= '`t`.`year`=\''.$postFilter['year'].'\' '.$postBool;
			}
			// testimonial category
			if($postFilter['category'] !== '') {
				$postWhere .= '`tm`.`category_id`=\''.$postFilter['category'].'\' '.$postBool;
			}
			// testimonial
			if($postFilter['testimonial'] !== '') {
				$postWhere .= '`t`.`testimonial_id`=\''.$postFilter['testimonial'].'\' '.$postBool;
			}
			// state
			if($postFilter['state'] !== '') {
				$postWhere .= '`t`.`state_id`=\''.$postFilter['state'].'\' '.$postBool;
			}
			// club
			if($postFilter['club'] !== '') {
				$postWhere .= '`t`.`club_id`=\''.$postFilter['club'].'\' '.$postBool;
			}
		}
		if($postWhere == 'AND (') {
			$postWhere = '';
		} else {
			$postWhere = substr($postWhere, 0, -strlen($postBool));
			$postWhere .= ')';
		}
		
		$countRows = Db::singleValue('
				SELECT COUNT(*)
				FROM `tribute` AS `t`
				JOIN `testimonials` AS `tm` ON `t`.`testimonial_id`=`tm`.`id`
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
		$index = $this->get('jtStartIndex') + 1;
		foreach($results as $row) {
			
			// add index
			$row['index'] = $index;
			$index++;
			
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
					'index'	=> $row['index'],
					'name' => $row['name'],
					'club' => $row['club'],
					'year' => $row['year'],
					'category_id' => $row['category_id'],
					'testimonial' => $row['testimonial'],
					'start_date' => date('d.m.Y', strtotime($row['start_date'])),
					'planned_date' => (is_null($row['planned_date']) ? '' : date('d.m.Y', strtotime($row['planned_date']))),
					'date' => (is_null($row['date']) ? '' : date('d.m.Y', strtotime($row['date']))),
					'state' => $row['state'],
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
		$ids = self::getUser()->permittedItems('tribute', 'w');
		
		// check if empty result
		$mysqlData = implode(',', $ids);
		if(count($ids) == 0) {
			$mysqlData = 'SELECT FALSE';
		}
		
		// prepare sql
		$sql = '
			SELECT `t`.`id`, `t`.`name`, `t`.`year`, `tm`.`name` AS `testimonial`, `c`.`name` AS `club`
			FROM `tribute` AS `t`
			LEFT JOIN `club` AS `c` ON `t`.`club_id` = `c`.`id`
			JOIN `testimonials` AS `tm` ON `t`.`testimonial_id` = `tm`.`id`
			WHERE `t`.`valid`=TRUE
				AND (`t`.`name` LIKE \'%#?%\'
					OR `c`.`name` LIKE \'%#?%\')
				AND `t`.`id` IN (#?)
			ORDER BY `t`.`name`
			LIMIT 10		
		';
		
		$result = Db::ArrayValue($sql,
		MYSQL_ASSOC,
		array(	$query,
				$query,
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
				$return[] = array(
						'label' => self::highlightApiSearch($query, $row['name'] .' ['.$row['club']).'|'.$row['testimonial'].'|'.$row['year'].']',
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
	 * addPrintListMarks() returns an array with the data of the actual list view
	 * 
	 * @return array array containing list view data
	 */
	public static function addPrintListMarks() {
		
		// get permitted ids
		$ids = self::getUser()->permittedItems('tribute', 'w');
		
		// check if empty result
		$mysqlData = implode(',', $ids);
		if(count($ids) == 0) {
			$mysqlData = 'SELECT FALSE';
		}
		
		// get sql and timestamp
		$sql = $_SESSION['printTributeList']['sql'];
		$printDate = $_SESSION['printTributeList']['printDate'];
		
		$result = Db::ArrayValue($sql,
		MYSQL_ASSOC,
		array($mysqlData,));
		if($result === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// prepare result
		$return = array();
		if(count($result) > 0) {
			foreach($result as $row) {
				$return['list'][] = array(
						'name' => $row['name'],
					    'club' => $row['club'],
					    'year' => $row['year'],
					    'testimonial' => $row['testimonial'],
					    'plannedDate_d_m_Y' => (is_null($row['planned_date']) ? '' : date('d.m.Y', strtotime($row['planned_date']))),
					    'startDate_d_m_Y' => (is_null($row['start_date']) ? '' : date('d.m.Y', strtotime($row['start_date']))),
					    'date_d_m_Y' => (is_null($row['date']) ? '' : date('d.m.Y', strtotime($row['date']))),
					    'plannedDate_dmY' => (is_null($row['planned_date']) ? '' : date('dmY', strtotime($row['planned_date']))),
					    'startDate_dmY' => (is_null($row['start_date']) ? '' : date('dmY', strtotime($row['start_date']))),
					    'date_dmY' => (is_null($row['date']) ? '' : date('dmY', strtotime($row['date']))),
					    'plannedDate_j_F_Y' => (is_null($row['planned_date']) ? '' : strftime('%e. %B %Y', strtotime($row['planned_date']))),
					    'startDate_j_F_Y' => (is_null($row['start_date']) ? '' : strftime('%e. %B %Y', strtotime($row['start_date']))),
					    'date_j_F_Y' => (is_null($row['date']) ? '' : strftime('%e. %B %Y', strtotime($row['date']))),
					    'state' => $row['state'],
					);
			}
		} else {
			$return['list'][] = array(
					'name' => _l('no results'),
					'club' => '',
					'year' => '',
					'testimonial' => '',
					'plannedDate_d_m_Y' => '',
					'startDate_d_m_Y' => '',
					'date_d_m_Y' => '',
					'plannedDate_dmY' => '',
					'startDate_dmY' => '',
					'date_dmY' => '',
					'plannedDate_j_F_Y' => '',
					'startDate_j_F_Y' => '',
					'date_j_F_Y' => '',
					'state' => '',
				);
		}
		
		$return['printDate_j_F_Y'] = strftime('%e. %B %Y', $printDate);
		$return['printDate_dmY'] = date('dmY', $printDate);
		$return['printDate_d_m_Y'] = date('d.m.Y', $printDate);
		$return['printDate_j_F_Y_HMS'] = strftime('%e. %B %Y, %H:%M:%S Uhr', $printDate);
		$return['printDate_dmY_His'] = date('dmY_His', $printDate);
		$return['printDate_d_m_Y_His'] = date('d.m.Y H:i:s', $printDate);
		
		// return
		return $return;
	}
}
?>
