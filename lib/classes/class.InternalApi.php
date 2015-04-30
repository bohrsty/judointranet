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
 * class InternalApi implements the data handling of the internal ajax requests
 */
class InternalApi extends Object {
	
	
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
	 * handle() handles the internal AJAX calls
	 */
	public final function handle() {
		
		// get random id
		$randomId = $this->get('id');
		
		// get $_SESSION data
		$api = array(
				'apiClass' => (isset($_SESSION['api'][$randomId]['apiClass']) ? $_SESSION['api'][$randomId]['apiClass'] : ''),
				'apiBase' => (isset($_SESSION['api'][$randomId]['apiBase']) ? $_SESSION['api'][$randomId]['apiBase'] : ''),
				'randomId' => (isset($_SESSION['api'][$randomId]['randomId']) ? $_SESSION['api'][$randomId]['randomId'] : ''),
			);
		// check signature
		$signedError = false;
		$timeoutError = false;
		if($this->checkApiSignature($randomId) === false) {
			$signedError = true;
		} elseif($_SESSION['api'][$randomId]['time'] + $this->getGc()->get_config('internalApi.timeout') < time()) {
			$timeoutError = true;
		} else {
			// reset timeout
			$_SESSION['api'][$randomId]['time'] = time();
		}
			
		// switch by 'apiClass'
		switch($api['apiClass']) {
			
			case 'JTable':
				
				// check error
				if($signedError === true) {
				// signature error
					echo json_encode(array(
						'Result' => 'ERROR',
						'Message' => _l('API call failed [not signed]')
					));
				} elseif($timeoutError === true) {
				// timeout error
					
					// get link
					$sLink = new JudoIntranetSmarty();
					$data = array(
							'params' => 'class="link" onclick="location.reload();"',
							'title' => _l('reload page'),
							'content' => _l('reload page'),
						);
					$sLink->assign('span', $data);
					
					echo json_encode(array(
						'Result' => 'ERROR',
						'Message' => _l('API call failed [timeout] #?reloadLink', array('reloadLink' => $sLink->fetch('smarty.span.tpl')))
					));
				} else {
				// get api object
					$jtable = new InternalApiJtable();
					echo json_encode($jtable->result());
				}
			break;
			
			case 'PresetForm':
				echo json_encode($this->calendarSetPreset());
			break;
			
			case 'Fullcalendar':
				
				// check error
				if($signedError === true) {
					// signature error
					echo json_encode(array(
							'Result' => 'ERROR',
							'Message' => _l('API call failed [not signed]')
					));
				} elseif($timeoutError === true) {
					// timeout error
					echo json_encode(array(
							'Result' => 'ERROR',
							'Message' => _l('API call failed [timeout]')
					));
				} else {
					
					// get object
					$fullcalendar = new CalendarFullcalendar();
					echo json_encode($fullcalendar->getEvents());
				}
			break;
			
			case 'TributeSearch':
				
				// check error
				if($signedError === true) {
					// signature error
					echo json_encode(array(
							'label' => _l('ERROR').': '._l('API call failed [not signed]'),
							'value' => 'tribute.php?id=listall',
					));
				} elseif($timeoutError === true) {
					// timeout error
					echo json_encode(array(
							'label' => _l('ERROR').': '._l('API call failed [timeout]'),
							'value' => 'tribute.php?id=listall',
					));
				} else {
					
					// get object
					echo json_encode(TributeListallListing::apiSearch($this->get('term')));
				}
			break;
			
			case 'TributeHistoryEntry':
				
				// get template
				$template = new JudoIntranetSmarty();
				
				// check error
				if($signedError === true) {
					
					// signature error
					$template->assign('error', true);
					$template->assign('errorMessage', _l('ERROR').': '._l('API call failed [not signed]'));
				} elseif($timeoutError === true) {
					
					// timeout error
					$template->assign('error', true);
					$template->assign('errorMessage', _l('ERROR').': '._l('API call failed [timeout]'));
				} else {
					
					// insert history entry and check result
					$historyData = array(
							'tributeId' => $this->post('tributeId'),
							'type' => $this->post('historyType'),
							'subject' => $this->post('historySubject'),
							'content' => $this->post('historyContent'),
							'valid' => '1',
						);
					if($this->post('historyDate') !== false && $this->post('historyDate') != '') {
						if(date('U', strtotime($this->post('historyDate'))) < strtotime('today 00:00')) {
							$firstTimestamp = TributeHistory::getFirstEntryFor($this->post('tributeId'));
							if(date('U', strtotime($this->post('historyDate'))) > strtotime($firstTimestamp)) {
								$historyData['lastModified'] = date('Y-m-d', strtotime($this->post('historyDate')));
							} else {
								$historyData['lastModified'] = $firstTimestamp;
							}
						}
					}
					$historyFactory = TributeHistory::factoryInsert($historyData);
					if($historyFactory['result'] == 'OK') {
						
						// assign object
						$template->assign('error', false);
						$template->assign('entry', $historyFactory['data']);
						// assign api indication for javascript
						$template->assign('isApi', true);
					} else {
						
						// error on insert
						$template->assign('error', true);
						$template->assign('errorMessage', $historyFactory['data']);
					}
				}
				
				// echo template
				echo $template->fetch('smarty.tributeHistoryEntry.tpl');
			break;
			
			case 'ResultLinkForm':
				
				// check error
				if($signedError === true) {
					// signature error
					echo json_encode(array(
							'result' => 'ERROR',
							'message' => _l('API call failed [not signed]'),
					));
				} elseif($timeoutError === true) {
					// timeout error
					echo json_encode(array(
							'result' => 'ERROR',
							'message' => _l('API call failed [timeout]'),
					));
				} else {
					
					// get object
					echo json_encode(Calendar::linkTo($this->post('cid'), $this->post('lcid')));
				}
			break;
			
			case 'TributeFileupload':
				
				// check error
				if($signedError === true) {
					// signature error
					echo json_encode(array(
							'result' => 'ERROR',
							'message' => _l('API call failed [not signed]'),
					));
				} elseif($timeoutError === true) {
					// timeout error
					echo json_encode(array(
							'result' => 'ERROR',
							'message' => _l('API call failed [timeout]'),
					));
				} else {
					
					// generate file
					$tributeFile = TributeFile::factoryFile();
					// echo result
					echo json_encode($tributeFile->getError());
				}
			break;
			
			default:
				echo 'ERROR: '._l('API call failed [unknown apiClass]');
			break;
		}
	}
	
	
	/**
	 * checkApiSignature() checks if the submitted data is signed correctly
	 * 
	 * @param string $randomId random id to determine api call
	 * @return bool false if signature is wrong, true if it is correct
	 */
	private final function checkApiSignature($randomId) {
		
		// get $_GET data and decode
		$api = array(
				'apiClass' => (isset($_SESSION['api'][$randomId]['apiClass']) ? $_SESSION['api'][$randomId]['apiClass'] : ''),
				'apiBase' => (isset($_SESSION['api'][$randomId]['apiBase']) ? $_SESSION['api'][$randomId]['apiBase'] : ''),
				'randomId' => (isset($_SESSION['api'][$randomId]['randomId']) ? $_SESSION['api'][$randomId]['randomId'] : ''),
			);
		$signedApi = $this->get('signedApi');
		// get api key
		$apiKey = $this->getGc()->get_config('global.apikey');
	
		// check signature
		return base64_encode(hash_hmac('sha256', json_encode($api), $apiKey)) == $signedApi;
	}
	
	
	/**
	 * calendarSetPreset() updates a calendar entry with the data taken from $_GET and returns
	 * an array containing the status information
	 * 
	 * @return array array containing the result of the action and a message if necessary
	 */
	private function calendarSetPreset() {
		
		// prepare error message
		$errorMessage = _l('saving failed, please contact the system administrator');
		
		// check data from $_GET
		$cid = $this->get('cid');
		$pid = $this->get('pid');
		// data given?
		if($cid === false || $pid === false) {
			return array(
					'result' => 'ERROR',
					'message' => $errorMessage.' [data missing]',
				);
		}
		// cid exists?
		if(Page::exists('calendar', $cid) === false) {
			return array(
					'result' => 'ERROR',
					'message' => $errorMessage.' [cid not exists]',
				);
		}
		// pid exists?
		if(Preset::check_preset($pid, 'calendar') === false) {
			return array(
					'result' => 'ERROR',
					'message' => $errorMessage.' [pid not exists]',
				);
		}
		
		// get calendar object
		$calendar = new Calendar($cid);
		
		// insert preset_id in calendar entry
		$update = array('preset_id' => $pid);
		$calendar->update($update);
		$calendar->write_db('update');
		
		return array(
				'result' => 'OK',
				'message' => '',
			);
	}
}

?>
