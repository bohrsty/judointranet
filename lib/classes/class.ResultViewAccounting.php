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
 * class ResultViewAccounting implements the control of the id "accounting" result page
 */
class ResultViewAccounting extends ResultView {
	
	/*
	 * class-variables
	 */
	private $smarty;
	private $clubArray;
	
	/*
	 * getter/setter
	 */
	
	/*
	 * constructor/destructor
	 */
	public function __construct() {
		
		// setup parent
		parent::__construct();
		
		// create smarty object
		$this->smarty = new JudoIntranetSmarty();
		
		// create club array
		$this->clubArray = array();
	}
	
	
	/**
	 * show() generates the output of the page
	 * 
	 * @return string output for the page to be added to the template
	 */
	public function show() {
		
		// pagecaption
		$this->getTpl()->assign('pagecaption', _l('result accounting').'&nbsp;'.$this->helpButton(HELP_MSG_FILELISTALL));
		
		// check action
		switch($this->get('action')) {
			
			case 'billevent':
				$this->billEvent();
			break;
			
			default:
				throw new UnknownActionException($this);
			break;
		}
	}
	
	
	/**
	 * billEvent() handles the action "billevent" for result given in $_GET['rid']
	 */
	private function billEvent() {
		
		// get cid
		$cid = $this->get('cid');
		
		// check if calendar entry exists
		if(Page::exists('calendar', $cid)) {
			
			// get result ids
			$rids = Result::getIdsForCalendar($cid);
			
			// check rids
			if(count($rids) > 0) {
				
				// prepare variables
				$amounts = AccountingCosts::getAmountsAsArray();
				$i = 0;
				$results = array();
				$clubCount = array();
				$totalCount = 0;
				
				// walk through results
				$results = array();
				foreach($rids as $rid) {
					
					// create result
					$result = new Result($rid);
					$results[$i] = $result;
					
					// check if clubArray already set
					if(count($this->clubArray) == 0) {
						$this->clubArray = $result->getClubArray();
					}
					
					// get number of paricipants per club
					foreach($result->getAgegroups() as $agegroup => $countAgegroups) {
						
						// check single/team
						if($result->getIsTeam() == 0) {
							
							foreach($result->getWeightclasses($agegroup) as $weightclass => $countWeightclass) {
								foreach($result->getStandings($agegroup, $weightclass) as $standing) {
									
									// increment club count
									if(!isset($clubCount[$i][$agegroup][$standing['club_id']])) {
										$clubCount[$i][$agegroup][$standing['club_id']] = 1;
									} else {
										$clubCount[$i][$agegroup][$standing['club_id']]++;
									}
									// increment total count
									$totalCount++;
								}
							}
						} else {
							
							foreach($result->getStandings($agegroup, null) as $standing) {
									
								// increment club count
								if(!isset($clubCount[$i][$agegroup][$standing['club_id']])) {
									$clubCount[$i][$agegroup][$standing['club_id']] = 1;
								} else {
									$clubCount[$i][$agegroup][$standing['club_id']]++;
								}
								// increment total count
								$totalCount++;
							}
						}
						
						// sort club array
						uksort($clubCount[$i][$agegroup], array($this, 'callbackSortClubArray'));
					}
					
					// increment counter
					$i++;
				}
				
				// prepare marker-array
				$infos = array(
						'version' => '01.01.1970 01:00',
						'accountingIsSubevent' => $results[0]->getCalendar()->isLinked(),
					);
				
				// add calendar-fields to array
				$results[0]->getCalendar()->add_marks($infos, false);
				// add result object of first result
				$results[0]->addMarks($infos);
				
				// smarty
				$this->smarty->assign('r', $infos);
				// check marks in values
				foreach($infos as $k => $v) {
					
					if(preg_match('/\{\$r\..*\}/U', $v)) {
						$infos[$k] = $sR->fetch('string:'.$v);
					}
				}
				
				// prepare template
				$this->smarty->assign('isTeam', $result->getIsTeam());
				$this->smarty->assign('clubCount', $clubCount);
				$this->smarty->assign('totalCount', $totalCount);
				$this->smarty->assign('amounts', $amounts);
				$this->smarty->assign('r', $infos);
				
				// get preset of first result
				$preset = new Preset($results[0]->getPreset(), 'result', $results[0]->getId());
				
				// get HTML string
				$pdfOut = $this->smarty->fetch(JIPATH.'/templates/results/bill_'.$preset->get_path().'.tpl');			
				
				// get HTML2PDF-object
				$pdf = new HTML2PDF('P', 'A4', 'de', true, 'UTF-8', array(0, 0, 0, 0));
				$pdf->setTestTdInOnePage(false);
				// convert
				$pdf->writeHTML($pdfOut, false);
				
				// output
				$pdfFilename = $this->replace_umlaute(_l('bill').'_'.html_entity_decode($this->smarty->fetch('string:'.utf8_encode($preset->get_filename())), ENT_XHTML, 'UTF-8'));
				
				// output pdf and exit
				$pdf->Output($pdfFilename, 'D');
				exit;
			} else {
				throw new ResultIdNotExistsExeption($this);
			}
		} else {
			throw new CalendarIdNotExistsExeption($this, $cid);
		}
	}
	
	
	/**
	 * callbackSortClubArray($first, $second) compares two club array keys by number (for uksort)
	 * 
	 * @param int $first first array key
	 * @param int $second second array key
	 * @return int -1 if $first<$second, 0 if equal, 1 if $first>$second
	 */
	public function callbackSortClubArray($first, $second) {
		
		// check if clubArray is empty
		if(count($this->clubArray) == 0) {
			
			// compare key directly
			if($first < $second) {
				return -1;
			}
			if($first == $second) {
				return 0;
			}
			if($first > $second) {
				return 1;
			}
		} else {
			
			// get clubArray
			$cA = $this->clubArray;
			
			// check if $first and $second exists in array
			if(!isset($cA[$first]) && isset($cA[$second])) {
				return -1;
			}
			if(isset($cA[$first]) && !isset($cA[$second])) {
				return 1;
			}
			
			// compare key by club number
			if($cA[$first]['number'] < $cA[$second]['number']) {
				return -1;
			}
			if($cA[$first]['number'] == $cA[$second]['number']) {
				return 0;
			}
			if($cA[$first]['number'] > $cA[$second]['number']) {
				return 1;
			}
		}
	}
}

?>
