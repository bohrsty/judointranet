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
 * class Api implements the data handling of the public api
 */
class ApiHandlerCalendar extends ApiHandler {
	
	
	/*
	 * class-variables
	 */
	
	
	/*
	 * getter/setter
	 */
	
	/*
	 * constructor/destructor
	 */
	public function __construct($request) {
		
		// setup parent
		parent::__construct($request);
		
		// set request
		$this->setRequest($request);
	}
	
	/*
	 * methods
	 */
	/**
	 * getResult($request) handles the api requests for calendar
	 * 
	 * @return array array containing the result 
	 */
	public function getResult() {
		
		// check "id" parameter of $request
		if($this->getRequest()['data']['id'] != '') {
			$apiMethod = 'handle'.ucfirst($this->getRequest()['data']['id']);
			if(is_callable(array($this, $apiMethod))) {
				
				// call method and return result
				return call_user_func(array($this, $apiMethod));
			} else {
				return array(
						'result' => 'ERROR',
						'message' => 'API call failed [id not found \''.get_class($this).'::'.$apiMethod.'\']',
					);
			}
		} else {
			return array(
					'result' => 'ERROR',
					'message' => 'API call failed [id not given]',
				);
		}
	}
	
	
	/**
	 * handleDetails() returns the data for the calendar entry "tid" in $request
	 * 
	 * @return array array containing the details of the calendar entry
	 */
	public function handleDetails() {
		
		// get request
		$request = $this->getRequest();
		
		// check tid given
		if(!isset($request['data']['tid']) || !is_numeric($request['data']['tid'])) {
			return array(
					'result' => 'ERROR',
					'message' => 'API call failed [tid not given or not numeric]',
				);
		}
		
		// check existance
		if(Page::exists('calendar', $request['data']['tid']) === false) {
			return array(
					'result' => 'ERROR',
					'message' => 'API call failed [entry does not exists]',
				);
		}
		
		// check permission
		if($this->getUser()->hasPermission('calendar', $request['data']['tid']) === false) {
			return array(
					'result' => 'ERROR',
					'message' => 'API call failed [not permitted to access this entry]',
				);
		}
		
		// get object
		$calendar = new Calendar($request['data']['tid']);
		
		// check valid
		if($calendar->get_valid() == 0) {
			return array(
					'result' => 'ERROR',
					'message' => 'API call failed [entry does not exists]',
			);
		}
		
		// collect data
		// modifiedBy
		$modifiedUser = new User(false);
		$modifiedUser->change_user($calendar->getModifiedBy(), false, 'id');
		// announcement
		$announcement = array();
		$draftValue = Calendar::getDraftValue($calendar->get_preset_id(), $calendar->get_id());
		if(	($calendar->get_preset_id() != 0
				&& Calendar::check_ann_value($calendar->get_id()) == 1)
			&& ($draftValue == 0
					|| ($draftValue == 1 && $this->getUser()->get_loggedin()))) {
			$announcement['details'] = 'announcement.php?id=details&cid='.$calendar->get_id().'&pid='.$calendar->get_preset_id();
			$announcement['pdf'] = 'file.php?id=cached&table=calendar&tid='.$calendar->get_id();
		}
		// files
		$files = array();
		$fileIds = File::attachedTo('calendar', $calendar->get_id());
		if(count($fileIds) > 0) {
			foreach($fileIds as $fileId) {
				$file = new File($fileId);
				$files[] = array('filename' => $file->getFilename(),
							'href' => 'file.php?id=download&fid='.$fileId);
			}
		}
		// result
		if(count(Result::getIdsForCalendar($calendar->get_id())) > 0) {
			$announcement['result'] = 'result.php?id=list&cid='.$calendar->get_id();
		}
		// webservice results
		$webserviceResults = array();
		if(isset($calendar->getAdditionalFields()['webservices'])) {
			foreach($calendar->getAdditionalFields()['webservices'] as $wsName => $wsResult) {
				$class = 'WebserviceJob'.ucfirst(strtolower($wsName));
				$webserviceResults = $class::resultToHtml($wsResult);
			}
		}
		$data = array(
				'id' => $calendar->get_id(),
				'start' => $calendar->get_date('U'),
				'end' => $calendar->getEndDate('U'),
				'name' => $calendar->get_name(),
				'type' => $calendar->get_type(),
				'content' => $calendar->get_content(),
				'city' => $calendar->getCity(),
				'lastModified' => $calendar->getLastModified(),
				'modifiedBy' => $modifiedUser->get_userinfo('name'),
				'files' => $files,
				'announcement' => $announcement,
				'announcementDraft' => $draftValue == 1,
				'locale' => $this->getUser()->get_lang(),
				'color' => $calendar->getColor(),
				'isExternal' => $calendar->getIsExternal(),
				'webserviceResults' => $webserviceResults,
			);
		
		// check html
		if(isset($request['options']['html']) && $request['options']['html'] == 1) {
			
			// prepare template
			$smarty = new JudoIntranetSmarty();
			$smarty->assign('data', $data);
			return array(
					'result' => 'OK',
					'data' => $smarty->fetch('smarty.apiHandler.calendar.details.tpl'),
				);
		} else {
			return array(
					'result' => 'OK',
					'data' => $data,
			);
		}
	}
}

?>
