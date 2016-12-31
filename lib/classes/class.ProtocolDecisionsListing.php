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
 * class ProtocolDecisionsListing implements the data handling of listing the protocol decisions from 
 * the database
 */
class ProtocolDecisionsListing extends Listing implements ListingInterface {
	
	
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
		
		// check if child table
		if($this->get('pid') === false) {
			
			// get permitted ids
			$ids = $this->getUser()->permittedItems('protocol', 'w');
		
			// check if empty result
			$mysqlData = implode(',', $ids);
			if(count($ids) == 0) {
				$mysqlData = 'SELECT FALSE';
			}
			
			// check jTable and get data
			if(count($getData) > 0) {
				
				// check order by
				if($getData['orderBy'] == '') {
					$getData['orderBy'] = 'ORDER BY `date` ASC';
				}
				
				$sql = '
					SELECT `p`.`id` AS `pid`, `p`.`date`, `pt`.`name` AS `type`, `p`.`location`, `p`.`protocol`
					FROM `protocol` AS `p`, `protocol_types` AS `pt`
					WHERE `p`.`id` IN (#?)
						AND `p`.`valid`=TRUE
						AND `p`.`type`=`pt`.`id`
					'.$getData['orderBy'].'
					'.$getData['limit'].'
				';
			} else {
				
				$sql = '
					SELECT `p`.`id` AS `pid`, `p`.`date`, `pt`.`name` AS `type`, `p`.`location`, `p`.`protocol`
					FROM `protocol` AS `p`, `protocol_types` AS `pt`
					WHERE `p`.`id` IN (#?)
						AND `p`.`valid`=TRUE
						AND `p`.`type`=`pt`.`id`
					ORDER BY `date` ASC
				';
			}
	
			$result = Db::ArrayValue($sql,
			MYSQLI_ASSOC,
			array($mysqlData,));
			if($result === false) {
				$n = null;
				throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
			}
			
			// prepare return
			$return = $this->prepareResults($result);
		} else {
			
			$sql = '
				SELECT `p`.`protocol`
				FROM `protocol` AS `p`
				WHERE `p`.`id`=#?
			';
			
			$result = Db::singleValue($sql,	array($this->get('pid'),));
			if($result === false) {
				$n = null;
				throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
			}
			
			// prepare return
			foreach($this->parseHtml($result, '<p class="tmceDecision">|</p>') as $decision) {
				$return[] = array('decision' => $decision);
			} 
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
		
		// check if child table
		if($this->get('pid') === false) {
		
			// get permitted ids
			$ids = $this->getUser()->permittedItems('protocol', 'w');
			
			// check if empty result
			$mysqlData = implode(',', $ids);
			if(count($ids) == 0) {
				$mysqlData = 'SELECT FALSE';
			}
			
			$rows = Db::arrayValue('
					SELECT `protocol`
					FROM `protocol`
					WHERE `id` IN (#?)
						AND `valid`=TRUE
				',
				MYSQLI_ASSOC,
				array($mysqlData,)
			);
			if($rows === false) {
				$n = null;
				throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
			} else {
				
				// check if $row contains decision
				$return = 0;
				foreach($rows as $row) {
					if(strpos($row['protocol'], '<p class="tmceDecision">') !== false) {
						$return++;
					}
				}
				
				// return
				return $return;
			}
		} else {
			
			$sql = '
				SELECT `p`.`protocol`
				FROM `protocol` AS `p`
				WHERE `p`.`id`=#?
			';
			
			$result = Db::singleValue($sql,	array($this->get('pid'),));
			if($result === false) {
				$n = null;
				throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
			} else {
			
				// prepare return
				$decisions = $this->parseHtml($result, '<p class="tmceDecision">|</p>');
				return count($decisions);
			}
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
			
			// prepare smarty templates for links and images if $row contains decision
			if(strpos($row['protocol'], '<p class="tmceDecision">') !== false) {
				
				// date
				$smarty = new JudoIntranetSmarty();
				$dateLinkArray = array(
						array(
								'href' => 'protocol.php?id=details&pid='.$row['pid'],
								'title' => _l('date'),
								'name' => date('d.m.Y', strtotime($row['date'])),
							),
					);
				$smarty->assign('data', $dateLinkArray);
				$dateLink = $smarty->fetch('smarty.a.img.tpl');
				
				// add to return array
				$return[] = array(
						'pid' => $row['pid'],
						'date' => $dateLink,
						'type' => $row['type'],
						'location' => $row['location'],
					);
			}
		}
		
		// return
		return $return;
	}
	
	
	/**
	 * parseHtml($text, $tag) parses $text and returns an array containing the text between $tag
	 * 
	 * @param string $text the HTML text to be parsed
	 * @param string $tag the complete HTML tag (open and close, devided by |)
	 * @return array array containing the text between the given HTML tags
	 */
	private function parseHtml($text, $tag) {
	
		// split tag
		list($open, $close) = explode('|', $tag);
		
		// match text
		$matches = array();
		$preg = '|'.$open.'(.*)'.$close.'|U';
		$result = preg_match_all($preg, $text, $matches);
		
		// return
		return $matches[1];
	}
}
?>
