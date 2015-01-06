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
 * class ProtocolListallListing implements the data handling of listing the protocols from 
 * the database
 */
class ProtocolListallListing extends Listing implements ListingInterface {
	
	
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
				SELECT `p`.`id`, `p`.`date`, `pt`.`name` AS `type`, `p`.`location`, `p`.`owner`, `p`.`correctable`, (SELECT COUNT(*) FROM `files_attached` AS `fa` WHERE `fa`.`table_name`=\'protocol\' AND `fa`.`table_id`=`p`.`id`) AS `files`, (SELECT `pc`.`finished` FROM `protocol_correction` AS `pc` WHERE `pc`.pid = `p`.`id` AND `pc`.`uid`=#?) AS `correction_finished`, (SELECT COUNT(*) FROM `protocol_correction` AS `pc` WHERE `pc`.`pid`=`p`.`id`) AS `has_corrections`
				FROM `protocol` AS `p`, `protocol_types` AS `pt`
				WHERE `p`.`id` IN (#?)
					AND `p`.`valid`=TRUE
					AND `p`.`type`=`pt`.`id`
				'.$getData['orderBy'].'
				'.$getData['limit'].'
			';
		} else {
			
			$sql = '
				SELECT `p`.`id`, `p`.`date`, `pt`.`name` AS `type`, `p`.`location`, `p`.`owner`, `p`.`correctable`, (SELECT COUNT(*) FROM `files_attached` AS `fa` WHERE `fa`.`table_name`=\'protocol\' AND `fa`.`table_id`=`p`.`id`) AS `files`, (SELECT `pc`.`finished` FROM `protocol_correction` AS `pc` WHERE `pc`.pid = `p`.`id` AND `pc`.`uid`=#?) AS `correction_finished`, (SELECT COUNT(*) FROM `protocol_correction` AS `pc` WHERE `pc`.`pid`=`p`.`id`) AS `has_corrections`
				FROM `protocol` AS `p`, `protocol_types` AS `pt`
				WHERE `p`.`id` IN (#?)
					AND `p`.`valid`=TRUE
					AND `p`.`type`=`pt`.`id`
				ORDER BY `date` ASC
			';
		}

		$result = Db::ArrayValue($sql,
		MYSQL_ASSOC,
		array(	$this->getUser()->get_id(),
				$mysqlData,));
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
		$ids = $this->getUser()->permittedItems('protocol', 'w');
		
		// check if empty result
		$mysqlData = implode(',', $ids);
		if(count($ids) == 0) {
			$mysqlData = 'SELECT FALSE';
		}
		
		$countRows = Db::singleValue('
				SELECT COUNT(*)
				FROM `protocol`
				WHERE `id` IN (#?)
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
			
			// prepare correctable
			$correctableStatus = null;
			$corretors = array();
			// split value
			$explode = explode('|', $row['correctable']);
			$correctableStatus = $explode[0];
			// correctors
			if(isset($explode[1])) {
				$correctors = explode(',', $explode[1]);
			}
			
			// prepare smarty templates for links and images
			// date
			$smarty = new JudoIntranetSmarty();
			$dateLinkArray = array(
					array(
							'href' => 'protocol.php?id=details&pid='.$row['id'],
							'title' => _l('date'),
							'name' => date('d.m.Y', strtotime($row['date'])),
						),
				);
			$smarty->assign('data', $dateLinkArray);
			$dateLink = $smarty->fetch('smarty.a.img.tpl');
			
			// check status of protocol
			$showArray = array();
			if($correctableStatus == 2 || $this->getUser()->get_id() == $row['owner']) {
				
				// show
				$showArray[] = array(
						'href' => 'protocol.php?id=show&pid='.$row['id'],
						'title' => _l('show protocol'),
						'name' => array(
								'src' => 'img/prot_details.png',
								'alt' => _l('show protocol'),
							),
					);
				$showArray[] = array(
						'href' => 'file.php?id=cached&table=protocol&tid='.$row['id'],
						'title' => _l('protocol as PDF'),
						'name' => array(
								'src' => 'img/prot_pdf.png',
								'alt' => _l('protocol as PDF'),
							),
					);
			} else {
				$showArray[] = '';
				$showArray[] = '';
			}
			
			// add attached file info
			if($row['files'] > 0) {
				
				$showArray[] = array(
						'href' => 'protocol.php?id=details&pid='.$row['id'],
						'title' => _l('existing attachments'),
						'name' => array(
								'src' => 'img/attachment_info.png',
								'alt' => _l('existing attachments'),
							),
					);
			} else {
				$showArray[] = '';
			}
			
			$smarty->assign('data', $showArray);
			$smarty->assign('spacer', true);
			$show = $smarty->fetch('smarty.a.img.tpl');
				
			// add admin
			$adminArray = array();
			$admin = '';
			// if user is loggedin add admin-links
			if($this->getUser()->get_loggedin() === true) {
				
				// edit and delete only for author or admin
				if($this->getUser()->get_id() == $row['owner']
					|| $this->getUser()->isAdmin()) {
					
					// smarty
					// edit
					$adminArray[] = array(
							'href' => 'protocol.php?id=edit&pid='.$row['id'],
							'title' => _l('edit protocol'),
							'name' => array(
									'src' => 'img/prot_edit.png',
									'alt' => _l('edit protocol'),
								),
						);
					// delete
					$adminArray[] = array(
							'href' => 'protocol.php?id=delete&pid='.$row['id'],
							'title' => _l('delete protocol'),
							'name' => array(
									'src' => 'img/prot_delete.png',
									'alt' => _l('delete protocol'),
								),
						);
					// attachment
					$adminArray[] = array(
							'href' => 'file.php?id=attach&table=protocol&tid='.$row['id'],
							'title' => _l('attach file(s)'),
							'name' => array(
									'src' => 'img/attachment.png',
									'alt' => _l('attach file(s)'),
								),
						);
				}
				
				// correction
				if(	$correctableStatus == 1
					&& in_array($this->getUser()->get_id(), $correctors)
					&& $this->getUser()->get_id() != $row['owner']) {
					
					// check if correction is finished
					if($row['correction_finished'] == 1) {
						$adminArray[] = array(
								'href' => false,
								'title' => _l('finished correction'),
								'name' => array(
										'src' => 'img/done.png',
										'alt' => _l('finished correction'),
									),
							);
					} else {
						$adminArray[] = array(
								'href' => 'protocol.php?id=correct&pid='.$row['id'],
								'title' => _l('correct protocol'),
								'name' => array(
										'src' => 'img/prot_correct.png',
										'alt' => _l('correct protocol'),
									),
							);
					}
				}
				
				// corrected
				if(	$correctableStatus == 1
					&& $this->getUser()->get_id() == $row['owner']
					&& $row['has_corrections'] > 0) {
					
					$adminArray[] = array(
								'href' => 'protocol.php?id=correct&pid='.$row['id'].'&action=diff',
								'title' => _l('existing corrections, please check'),
								'name' => array(
										'src' => 'img/prot_corrected.png',
										'alt' => _l('existing corrections, please check'),
									),
							);
				}
				
			}
			$smarty->assign('data', $adminArray);
			$admin = $smarty->fetch('smarty.a.img.tpl');
			
			// add to return array
			$return[] = array(
					'date' => $dateLink,
					'type' => $row['type'],
					'location' => $row['location'],
					'show' => $show,
					'admin' => $admin
				);
		}
		
		// return
		return $return;
	}
}
?>
