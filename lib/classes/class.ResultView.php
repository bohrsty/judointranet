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
 * class ResultView implements the control of the result page
 */
class ResultView extends PageView implements ViewInterface {
	
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
	
	/**
	 * init chooses the functionality by using $_GET['id']
	 * 
	 * @return void
	 */
	public function init() {
		
		// set pagename
		$this->getTpl()->assign('pagename',parent::lang('Results', true));
		
		// init helpmessages
		$this->initHelp();
		
		// switch $_GET['id'] if set
		if($this->get('id') !== false) {
			
			// check permissions
			$naviId = Navi::idFromFileParam(basename($_SERVER['SCRIPT_FILENAME']), $this->get('id'));
			if($this->getUser()->hasPermission('navi', $naviId)) {
				
				switch($this->get('id')) {
					
					case 'listall':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(parent::lang('Results: listall', true)));
						$listall = new ResultViewListall();
						$this->getTpl()->assign('main', $listall->show());
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', false);
						$this->getTpl()->assign('tinymce', false);
					break;
					
					case 'details':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(parent::lang('Results: details', true)));
						$details = new ResultViewDetails();
						$this->getTpl()->assign('main', $details->show());
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', false);
						$this->getTpl()->assign('tinymce', false);
					break;
					
					case 'delete':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(parent::lang('Results: delete', true)));
						$delete = new ResultViewDelete();
						$this->getTpl()->assign('main', $delete->show());
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
						$this->getTpl()->assign('tinymce', false);
					break;
					
					case 'new':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(parent::lang('Results: import', true)));
						$new = new ResultViewNew();
						$this->getTpl()->assign('main', $new->show());
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
						$this->getTpl()->assign('tinymce', false);
					break;
					
					case 'list':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(parent::lang('Results: list', true)));
						$list = new ResultViewList();
						$this->getTpl()->assign('main', $list->show());
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', false);
						$this->getTpl()->assign('tinymce', false);
					break;
					
					case 'accounting':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(parent::lang('Results: accounting', true)));
						$accounting = new ResultViewAccounting();
						$this->getTpl()->assign('main', $accounting->show());
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', false);
						$this->getTpl()->assign('tinymce', false);
					break;
					
					default:
						
						// id set, but no functionality
						// smarty
						$this->getTpl()->assign('title', '');
						$this->getTpl()->assign('main', '');
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', false);
						$this->getTpl()->assign('tinymce', false);
						
						// throw exception
						throw new GetUnknownIdException($this);
					break;
				}
			} else {
				
				// smarty
				$this->getTpl()->assign('title', '');
				$this->getTpl()->assign('main', '');
				$this->getTpl()->assign('jquery', true);
				$this->getTpl()->assign('zebraform', false);
				$this->getTpl()->assign('tinymce', false);
				
				// throw exception
				throw new NotAuthorizedException($this);
			}
		} else {
			
			// id not set
			// smarty-title
			$this->getTpl()->assign('title', $this->title(parent::lang('Results', true))); 
			// smarty-main
			$this->getTpl()->assign('main', $this->defaultContent());
			// smarty-jquery
			$this->getTpl()->assign('jquery', true);
			// smarty-hierselect
			$this->getTpl()->assign('zebraform', false);
			// smarty-tiny_mce
			$this->getTpl()->assign('tinymce', false);
		}
		
		// global smarty
		$this->showPage('smarty.main.tpl');
	}
	
	
	/**
	 * readAllEntries() get all result entries from db for that the actual
	 * user has sufficient rights. returns an array with result objects
	 * 
	 * @return array all entries as result objects
	 */
	protected function readAllEntries() {
		
		// prepare return
		$results = array();
		
		// get result objects
		$resultEntries = self::getUser()->permittedItems('result', 'w');
		foreach($resultEntries as $resultId) {
			$results[] = new Result($resultId);
		}
		
		// sort result entries
		usort($results, array($this, 'callbackCompareResults'));
		
		// return result objects
		return $results;
	}
	
	
	/**
	 * callbackCompareResults($first, $second) compares two result objects by name (for usort)
	 * 
	 * @param object $first first result objects
	 * @param object $second second result object
	 * @return int -1 if $first<$second, 0 if equal, 1 if $first>$second
	 */
	protected function callbackCompareResults($first, $second) {
	
		// compare dates
		if(strtotime($first->getCalendar()->get_date()) < strtotime($second->getCalendar()->get_date())) {
			return -1;
		}
		if(strtotime($first->getCalendar()->get_date()) == strtotime($second->getCalendar()->get_date())) {
			return 0;
		}
		if(strtotime($first->getCalendar()->get_date()) > strtotime($second->getCalendar()->get_date())) {
			return 1;
		}
	}
	
	
	/**
	 * prepareResults($results) prepares the result objects as array to use with smarty template
	 * 
	 * @param array $results array containing result objects to prepare as list for smarty
	 * @param int $cid id of the calendar entry the results to be listed
	 * @return array prepared array to use with smarty template
	 */
	protected function prepareResults($results, $cid = 0) {
		
		$counter = 0;
		// smarty
		$sList = array();
		foreach($results as $entry) {
			
			// save calendar temporaryly
			$calendar = $entry->getCalendar();
			
			// check $cid
			if($cid == 0 || $cid == $calendar->get_id()) {
				 
				// check if valid
				if($calendar->get_valid() == 1 && $entry->getValid() == 1) {
					
					// smarty
					$sList[$counter] = array(
							'desc' => $entry->getDesc(),
							'name' => array(
									'href' => 'calendar.php?id=details&cid='.$calendar->get_id(),
									'title' => parent::lang('calendar details', true),
									'name' => $calendar->get_name(),
								),
							'date' => date('d.m.Y', strtotime($calendar->get_date())),
							'city' => $entry->getCity(),
						);
					// show
					$sList[$counter]['show'][] = array(
								'href' => 'result.php?id=details&rid='.$entry->getId(),
								'title' => parent::lang('result details', true),
								'src' => 'img/res_details.png',
								'alt' => parent::lang('result details', true),
							);
					$sList[$counter]['show'][] = array(
								'href' => 'file.php?id=cached&table=result&tid='.$entry->getId(),
								'title' => parent::lang('result pdf', true),
								'src' => 'img/res_pdf.png',
								'alt' => parent::lang('result pdf', true),
							);
						
					// add admin
					
					// if user is loggedin add admin-links
					if($this->getUser()->get_loggedin() === true) {
						
						// edit
	//					$sList[$counter]['admin'][] = array(
	//							'href' => '',
	//							'title' => '',
	//							'src' => 'img/res_edit.png',
	//							'alt' => '',
	//						);
						// delete
						$sList[$counter]['admin'][] = array(
								'href' => 'result.php?id=delete&rid='.$entry->getId(),
								'title' => parent::lang('result delete', true),
								'src' => 'img/res_delete.png',
								'alt' => parent::lang('result delete', true)
							);
					} else {
						
						// smarty
						$sList[$counter]['admin'][] = array(
								'href' => '',
								'title' => '',
								'src' => '',
								'alt' => ''
							);
					}
					
					// increment counter
					$counter++;
	
				} else {
					
					// deleted items
				}
			}
		}
			
		// return
		return $sList;
	}
}

?>
