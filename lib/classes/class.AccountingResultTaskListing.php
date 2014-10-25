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
 * class AccountingResultTaskListing implements the data handling of listing the result tasks
 * for accounting from the database
 */
class AccountingResultTaskListing extends Listing implements ListingInterface {
	
	
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
	 * @return array array of associative arrays (column name => value) to use with template
	 */
	public function listingAsArray() {
		
		// return
		return $this->getData();
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
				'',
				parent::lang('name', true),
				parent::lang('date', true),
				parent::lang('desc', true),
				parent::lang('last modified', true),
				parent::lang('actions', true),
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
	private function getData() {
		
		// prepare return
		$return = array();
		
		/*
		 * get data
		 * 
		 * state
		 * name
		 * date
		 * desc
		 * last_modified
		 * modified_by (not used in listing view)
		 * id (not used in listing view)
		 */
		$result = Db::ArrayValue('
			SELECT `at`.`state`, `c`.`name`, `c`.`date`, `r`.`desc`, `r`.`last_modified`, `r`.`modified_by`, `r`.`id`, `r`.`calendar_id`
			FROM `accounting_tasks` AS `at`, `calendar` AS `c`, `result` AS `r`
			WHERE `at`.`table_name`=\'result\'
				AND `at`.`table_id`=`r`.`id`
				AND `c`.`id`=`r`.`calendar_id`
				AND `r`.`valid`=1
				AND `c`.`valid`=1
				ORDER BY `r`.`last_modified` DESC
		',
		MYSQL_ASSOC,
		array());
		if($result === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// walk through data
		$calendarIds = array();
		foreach($result as $data) {
			
			// check calendar id
			if(!in_array($data['calendar_id'], $calendarIds)) {
				
				// save and unset result id
				$resultId = $data['id'];
				unset($data['id']);
				
				// prepare confirm task links
				if($data['state'] == 0) {
					$data['state'] = array(
							'href' => 'accounting.php?id=task&task=confirmresult&rid='.$resultId,
							'src' => 'unconfirmed',
							'title' => parent::lang('click to confirm', true),
							'alt' => parent::lang('unconfirmed', true),
					 	);
				} else {
					$data['state'] = array(
							'href' => 'accounting.php?id=task&task=unconfirmresult&rid='.$resultId,
							'src' => 'confirmed',
							'title' => parent::lang('click to unconfirm', true),
							'alt' => parent::lang('confirmed', true),
						);
				}
				
				// get username and unset modified by
				$user = new User(false);
				$user->change_user($data['modified_by'], false, 'id');
				$username = $user->get_userinfo('name');
				unset($data['modified_by']);
				
				// get formatted last modified date
				$lastModified = date('d.m.Y', strtotime($data['last_modified']));
				
				// set last modified
				$data['last_modified'] = array(
						'text' => $lastModified,
						'title' => parent::lang('modified by', true).' '.$username,
					);
				
				// get formatted date
				$data['date'] = date('d.m.Y', strtotime($data['date']));
				
				// add actions
				$data['actions'][] = array(
						'href' => 'result.php?id=accounting&action=billevent&cid='.$data['calendar_id'],
						'src' => '',
						'title' => parent::lang('bill as pdf', true),
						'alt' => parent::lang('bill as pdf', true),
					);
				
				// put in return array
				$return[] = $data;
				
				// add to calendar id array
				$calendarIds[] = $data['calendar_id'];
			}
		}
		
		// return
		return $return;
	}
	
}
?>
