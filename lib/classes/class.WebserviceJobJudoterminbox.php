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
 * class WebserviceJobJudoterminbox implements the representation of an object to handle
 * webservice calls to Judoterminbox (www.judoterminbox.de)
 */
class WebserviceJobJudoterminbox extends WebserviceJob implements WebserviceJobInterface {
	
	/*
	 * class-variables
	 */
	
	
	/*
	 * getter/setter
	 */
	
	
	/*
	 * constructor/destructor
	 */
	public function __construct($type) {
		
		// parent constructor
		parent::__construct($type);
	}
	
	/*
	 * methods
	 *//**
	 * runJob() executes the steps according to the job config
	 * 
	 * @return array the result of running the job as array for the AJAX call
	 */
	public function runJob() {
		
		// get calendar
		$calendar = new Calendar($this->getJobConfig()['calendarId']);
		
		// get timeout for HTTP request
		$wsTimeout = (float)max(array(5, $this->getGc()->get_config('webservice.interval')/1000-1));
		// create stream context
		$wsContext = stream_context_create(array(
				'http' => array(
						'timeout' => $wsTimeout,
					),
			));
		
		// try SOAP call
		try {
			
			// get SOAP client object
			$soap = new SoapClient($this->getConfig()['soap']['wsdl'], array('stream_context' => $wsContext,));
			// set server explicit, if given
			if($this->getConfig()['soap']['server'] != '') {
				$soap->__setLocation($this->getConfig()['soap']['server']);
			}
			
			// check action
			if($this->getJobConfig()['action'] == 'update') {
				
				// check new or update
				if(!array_key_exists('judoterminbox', $calendar->getAdditionalFields()['webservices'])) {
					return $this->jtbAddEvent($soap, $calendar);
				} else {
					return $this->jtbUpdateEvent($soap, $calendar);
				}
			} elseif($this->getJobConfig()['action'] == 'delete') {
				
				// check new or update
				if(array_key_exists('judoterminbox', $calendar->getAdditionalFields()['webservices'])) {
					return $this->jtbDeleteEvent($soap, $calendar);
				}
			}
		} catch(SoapFault $exception) {
			
			// log job
			$this->logJob('ERROR', $exception->getMessage());
			
			// return result
			return array(
					'result' => 'ERROR',
					'data' => array(
							'message' => $exception->getMessage(),
							'title' => 'Webservice Judoterminbox: '._l('error'),
					),
			);
		}
	}
	
	
	/**
	 * newJob() creates a new job and saves it to database
	 */
	public function newJob($args) {
		
		// required field
		if((array_key_exists('calendarId', $args) &&
				array_key_exists('fieldChecked', $args) &&
				array_key_exists('action', $args)) &&
				$args['fieldChecked'] == true || $this->getConfig()['ji']['fieldRequired'] === false) {
		
			// prepare config
			$config = array(
					'type' => 'judoterminbox',
					'config' => array(
							'calendarId' => $args['calendarId'],
							'action' => $args['action'],
						),
				);
			
			// insert job into database
			$this->insertJob($config);
		}
	}
	
	
	/**
	 * resultToHtml($result) puts the result in smarty template and returns the
	 * HTML string
	 * 
	 * @param array $result the result array
	 * @return string the result as HTML
	 */
	public static function resultToHtml($result) {
		
		// prepare smarty
		$sWsResult = new JudoIntranetSmarty();
		
		// assign result array
		$sWsResult->assign('resultArray', $result);
		
		// return HTML
		return $sWsResult->fetch('smarty.webserviceToHtml.judoterminbox.tpl');
	}
	
	
	/**
	 * addMarks($html) returns the result as (HTML) text to be used as mark in templates according
	 * to $html
	 * 
	 * @param array $result the result array
	 * @param bool $html returned string is formatted HTML if true, plain text otherwise
	 * @return string the result as "mark"
	 */
	public static function addMarks($result, $html) {
		
		// check $html
		if($html) {
		
			// prepare smarty
			$sWsResult = new JudoIntranetSmarty();
			
			// assign result array
			$sWsResult->assign('resultArray', $result);
			
			// return HTML
			return substr(trim($sWsResult->fetch('smarty.webserviceMarks.judoterminbox.tpl')), 0, -strlen('<br />'));
		} else {
			
			// walk through results
			$return = '';
			foreach($result as $entry) {
				$return .= ($entry['sex'] == 'm' ? _l('male') : _l('female')).': ';
				$return .= $result['urlBase'].$result['jtbId'].PHP_EOL;
			}
			
			// return text
			return html_entity_decode(substr($return, 0, -strlen(PHP_EOL)), ENT_XHTML, 'utf-8');
		}
	}
	
	
	/**
	 * callback functions to generate the required output for the webservice job
	 * 
	 * @param string the value of the input string
	 * @return string the generated output string
	 */
	// calendar type
	private function callbackCalendarType($calendarType) {
		
		// JTB types
		$types = array(
				'event' => 't',
				'training' => 'l'
			);
		
		return $types[$calendarType];
	}
	
	// year from
	private function callbackYearFrom($ageGroups) {
		
		// split $ageGroups
		$years = explode(',', $ageGroups);
		
		// trim and return first
		return trim($years[0]);
	}
	
	// year to
	private function callbackYearTo($ageGroups) {
		
		// split $ageGroups
		$years = explode(',', $ageGroups);
		
		// trim and return first
		return trim($years[(count($years) - 1)]);
	}
	
	// date from
	private function callbackDateFrom($completeDate) {
		
		// split $completeDate
		$dates = explode('|', $completeDate);
		
		// trim and return first
		return trim($dates[0]);
	}
	
	// date to
	private function callbackDateTo($completeDate) {

		// split $completeDate
		$dates = explode('|', $completeDate);
		
		// trim and return first
		return trim($dates[1]);
	}
	
	// sex
	private function callbackSex($sex) {

		// check $sex for '', m, w, g
		if($sex == '') {
			return array('m', 'w');
		}
		if($sex == 'm') {
			return array('m');
		}
		if($sex == 'w') {
			return array('w');
		}
		if($sex == 'g') {
			return array('g');
		}
	}
	
	// weightclass
	private function callbackWeightclass($weightclass) {
		
		// prepare regexp
		$regex = '/.*(?:'.html_entity_decode(_l('female'), ENT_QUOTES, 'UTF-8').':.*(?<w>(?:\-\d\d,)+\+\d\d)kg).*(?:'.html_entity_decode(_l('male'), ENT_QUOTES, 'UTF-8').':.*(?<m>(?:\-\d\d,)+\+\d\d)kg).*/Ums';
		
		// match regexp
		$matched = preg_match($regex, $weightclass, $matches);
		
		// check if matched
		if($matched === 0 || $matched === false) {
			return array(
					'm' => '',
					'w' => '',
				);
		} else {
			$return = array(
					'm' => $matches['m'],
					'w' => $matches['w'],
				);
			return $return;
		}
	}
	
	
	/**
	 * jtbAddEvent($soap) executes the SOAP calls for adding a new event
	 * 
	 * @param object $soap the SOAP object to use
	 * @param object $calendar calendar object to get the data from
	 * @return array array containing the output for AJAX call
	 */
	private function jtbAddEvent($soap, $calendar) {
		
		// get config
		$config = $this->getConfig();
		$jobConfig = $this->getJobConfig();
		
		// get preset
		$preset = new Preset($calendar->get_preset_id(), 'calendar', $calendar->get_id());
		
		// generate marks from calendar and fields
		$marks = $calendar->generateAllMarks($preset);
		
		// get sex value
		$sexFieldValue = $this->getFieldValue($marks, $config['fields']['sex']);
		
		// prepare return message
		$htmlMessage = new JudoIntranetSmarty();
		$htmlMessage->assign('statusMessage', _l('Successfully saved event to Judoterminbox.'));
		
		// walk through $sexFieldValue
		$jtbIds = array();
		foreach($sexFieldValue as $sex) {
			
			// walk through fields and generate values
			$soapParameter = array(
					$config['soap']['user'],
					$config['soap']['password'],
			);
			foreach($config['fields'] as $fieldName => $field) {
					
				// check sex
				if($fieldName == 'sex') {
					$soapParameter[] = $sex;
				} elseif($fieldName == 'eventName') {
					$soapParameter[] = $this->getFieldValue($marks, $field). ' '.($sex == 'm' ? html_entity_decode(_l('male'), ENT_QUOTES, 'UTF-8') : html_entity_decode(_l('female'), ENT_QUOTES, 'UTF-8'));
				} elseif($fieldName == 'weightclass') {
					$soapParameter[] = $this->getFieldValue($marks, $field)[$sex];
				} else {
					$soapParameter[] = $this->getFieldValue($marks, $field);
				}
			}
			
			// call SOAP function
			$jtbId = call_user_func_array(array($soap, 'JTBAddEvent'), $soapParameter);
			
			// save entry
			$jobResult = array(
					'sex' => $sex,
					'jtbId' => $jtbId,
					'urlBase' => $config['ji']['linkTemplate'],
				);
			$this->saveJobResult($jobResult);
			$jtbIds[$sex] = $jtbId;
			
			// add lines to event
			foreach($config['lines'] as $line) {
				
				// check if value is empty
				$value = $this->getFieldValue($marks, $line['value']);
				if($value != '') {
					
					// prepare parameter for soap call
					$soapParameter = array(
							$config['soap']['user'],
							$config['soap']['password'],
							$jtbId,
							$this->getFieldValue($marks, $line['name']),
							$value,
						);
					
					// call SOAP function
					$lineNumber = call_user_func_array(array($soap, 'JTBAddEventLine'), $soapParameter);
				}
			}
		}
		
		// assign status
		$htmlMessage->assign('status', 'OK');
		// assign jtbIds
		$htmlMessage->assign('jtbIds', $jtbIds);
		// assign link template from config
		$htmlMessage->assign('jtbLink', $config['ji']['linkTemplate']);
		
		// log job
		$this->logJob('OK', 'Event added');
		
		// return result
		return array(
				'result' => 'OK',
				'data' => array(
						'message' => $htmlMessage->fetch('smarty.webserviceMessage.Judoterminbox.tpl'),
						'title' => 'Webservice Judoderminbox: '._l('action successful'),
				),
			);
	}
	
	
	/**
	 * jtbUpdateEvent() executes the SOAP calls for updating an event
	 * 
	 * @param object the SOAP object to use
	 * @param object $calendar calendar object to get the data from
	 * @return array array containing the output for AJAX call
	 */
	private function jtbUpdateEvent($soap, $calendar) {
		
		// get additional fields
		$additionalFields = $calendar->getAdditionalFields();
		if(array_key_exists('judoterminbox', $additionalFields['webservices'])) {
			
			// get config
			$config = $this->getConfig();
			$jobConfig = $this->getJobConfig();
			
			// get preset
			$preset = new Preset($calendar->get_preset_id(), 'calendar', $calendar->get_id());
			
			// generate marks from calendar and fields
			$marks = $calendar->generateAllMarks($preset);
		
			// prepare return message
			$htmlMessage = new JudoIntranetSmarty();
			$htmlMessage->assign('statusMessage', _l('Successfully updated event in Judoterminbox.'));
			
			// walk through entries
			$jtbIds = array();
			foreach($additionalFields['webservices']['judoterminbox'] as $sexArray) {
				
				// walk through fields and generate values
				$soapParameter = array(
						$config['soap']['user'],
						$config['soap']['password'],
						$sexArray['jtbId'],
				);
				foreach($config['fields'] as $fieldName => $field) {
						
					// check sex
					if($fieldName == 'sex') {
						$soapParameter[] = $sexArray['sex'];
					} elseif($fieldName == 'eventName') {
						$soapParameter[] = $this->getFieldValue($marks, $field). ' '.($sexArray['sex'] == 'm' ? html_entity_decode(_l('male'), ENT_QUOTES, 'UTF-8') : html_entity_decode(_l('female'), ENT_QUOTES, 'UTF-8'));
					} elseif($fieldName == 'weightclass') {
						$soapParameter[] = $this->getFieldValue($marks, $field)[$sexArray['sex']];
					} else {
						$soapParameter[] = $this->getFieldValue($marks, $field);
					}
				}
				
				// call SOAP function
				$jtbIds[$sexArray['sex']] = call_user_func_array(array($soap, 'JTBUpdateEvent'), $soapParameter);
				
				// delete all lines from event
				$soapParameter = array(
						$config['soap']['user'],
						$config['soap']['password'],
						$sexArray['jtbId'],
					);
				$lastDeletedLine = call_user_func_array(array($soap, 'JTBDeleteEventLines'), $soapParameter);

				// readd lines to event
				foreach($config['lines'] as $line) {
				
					// check if value is empty
					$value = $this->getFieldValue($marks, $line['value']);
					if($value != '') {
							
						// prepare parameter for soap call
						$soapParameter = array(
								$config['soap']['user'],
								$config['soap']['password'],
								$sexArray['jtbId'],
								$this->getFieldValue($marks, $line['name']),
								$value,
							);
							
						// call SOAP function
						$lineNumber = call_user_func_array(array($soap, 'JTBAddEventLine'), $soapParameter);
					}
				}
			}
		}
		
		// assign status
		$htmlMessage->assign('status', 'OK');
		// assign jtbIds
		$htmlMessage->assign('jtbIds', $jtbIds);
		// assign link template from config
		$htmlMessage->assign('jtbLink', $config['ji']['linkTemplate']);
		
		// log job
		$this->logJob('OK', 'Event updated');
		
		// return result
		return array(
				'result' => 'OK',
				'data' => array(
						'message' => $htmlMessage->fetch('smarty.webserviceMessage.Judoterminbox.tpl'),
						'title' => 'Webservice Judoderminbox: '._l('action successful'),
				),
			);
	}
	
	
	/**
	 * jtbDeleteEvent() executes the SOAP calls for deleting an event
	 * 
	 * @param object the SOAP object to use
	 * @param object $calendar calendar object to get the data from
	 * @return array array containing the output for AJAX call
	 */
	private function jtbDeleteEvent($soap, $calendar) {

		// get config
		$config = $this->getConfig();
		$jobConfig = $this->getJobConfig();
		
		// get results
		$results = $calendar->getAdditionalFields()['webservices']['judoterminbox'];
		
		// walk through results
		foreach($results as $jobResult) {
			
			// prepare $soapParameter
			$soapParameter = array(
					$config['soap']['user'],
					$config['soap']['password'],
					$jobResult['jtbId'],
				);

			// call SOAP function
			$result = call_user_func_array(array($soap, 'JTBDeleteEvent'), $soapParameter);
		}
		
		// remove results
		$this->deleteJobResult();
		
		// log job
		$this->logJob('OK', 'Event deleted');
		
		// return result
		return array(
				'result' => 'OK',
				'data' => array(
						'message' => _l('Successfully deleted event from Judoterminbox.'),
						'title' => 'Webservice Judoterminbox: '._l('action successful'),
				),
			);
	}
	
	
	/**
	 * getFieldValue($field) checks the callback and generates the according value for $field
	 * 
	 * @param array $marks array containing the marks to get field values from
	 * @param mixed $field the field config
	 * @return string the value string for $field
	 */
	private function getFieldValue($marks, $field) {
		
		// check if absolute value
		if(!is_array($field)) {
			return $field;
		} else {
				
			// check if callback is set
			if($field[1] == '') {
				return (isset($marks[$field[0]]) ? $marks[$field[0]] : '');
			} else {
		
				// check if callback exists
				if(is_callable(array($this, $field[1]))) {
					if($field[0] == '') {
						return call_user_func(array($this, $field[1]), $field[0]);
					} else {
						return (isset($marks[$field[0]]) ? call_user_func(array($this, $field[1]), $marks[$field[0]]) : '');
					}
				} else {
					return '';
				}
			}
		}
	}
}