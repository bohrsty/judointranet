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

class ApiHandlerCalendarTest extends PHPUnit_Framework_TestCase {
	
	// setup
	public function setUp() {
		
	}
	
	
	public function tearDown() {
		
	}
	
	
	public function testConstruction() {
		
		// prepare request_uri
		$_SERVER['REQUEST_URI'] = '/api/calendar/';
		$api = new Api();
		
		$data = 'ApiHandlerCalendar';
		
		// instance of
		$apiHandler = new $data($api->getRequest());
		$this->assertEquals($data, get_class($apiHandler));
	}
	
	
	public function testHandleIdNotGiven() {
		
		// prepare request_uri, nothing given
		$_SERVER['REQUEST_URI'] = '/api/calendar/';
		// json result
		$json = json_encode(array(
				'result' => 'ERROR',
				'message' => 'API call failed [id not given]',
			));
		
		// get object
		$api = new Api();
		$this->expectOutputString($json, $api->handle());
	}
	
	
	public function testHandleIdNotExists() {
		
		// prepare request_uri, nothing given
		$apiMethod = 'notexists';
		$_SERVER['REQUEST_URI'] = '/api/calendar/'. $apiMethod;
		// json result
		$json = json_encode(array(
				'result' => 'ERROR',
				'message' => 'API call failed [id not found \'ApiHandlerCalendar::handle'.ucfirst($apiMethod).'\']',
			));
		
		// get object
		$api = new Api();
		$this->expectOutputString($json, $api->handle());
	}
	
	
	public function testHandleDetailsTidNotGiven() {
		
		// prepare request_uri, nothing given
		$_SERVER['REQUEST_URI'] = '/api/calendar/details/';
		// json result
		$json = json_encode(array(
				'result' => 'ERROR',
				'message' => 'API call failed [tid not given or not numeric]',
			));
		
		// get object
		$api = new Api();
		$this->expectOutputString($json, $api->handle());
	}
	
	
	public function testHandleDetailsTidNotNumeric() {
		
		// prepare request_uri, nothing given
		$_SERVER['REQUEST_URI'] = '/api/calendar/details/abc123';
		// json result
		$json = json_encode(array(
				'result' => 'ERROR',
				'message' => 'API call failed [tid not given or not numeric]',
			));
		
		// get object
		$api = new Api();
		$this->expectOutputString($json, $api->handle());
	}
	
	
	public function testHandleDetailsTidNotExists() {
		
		// prepare request_uri, nothing given
		$_SERVER['REQUEST_URI'] = '/api/calendar/details/0';
		// json result
		$json = json_encode(array(
				'result' => 'ERROR',
				'message' => 'API call failed [entry does not exists]',
			));
		
		// get object
		$api = new Api();
		$this->expectOutputString($json, $api->handle());
	}
	
	
	public function testHandleDetailsTidNotValid() {
		
		// prepare request_uri, nothing given
		$_SERVER['REQUEST_URI'] = '/api/calendar/details/1';
		// json result
		$json = json_encode(array(
				'result' => 'ERROR',
				'message' => 'API call failed [entry does not exists]',
			));
		
		// get object
		$api = new Api();
		$this->expectOutputString($json, $api->handle());
	}
	
	
	public function testHandleDetailsTidNotPermitted() {
		
		// get calendar
		$tid = 1;
		$calendar = new Calendar($tid);
		// set valid and remove permissions
		$calendar->update(array('valid' => 1));
		$calendar->write_db();
		$calendar->dbDeletePermission();
		
		// prepare request_uri, nothing given
		$_SERVER['REQUEST_URI'] = '/api/calendar/details/'.$tid;
		// json result
		$json = json_encode(array(
				'result' => 'ERROR',
				'message' => 'API call failed [not permitted to access this entry]',
			));
		
		// get object
		$api = new Api();
		$this->expectOutputString($json, $api->handle());
		
		// reset calendar entry
		$calendar->update(array('valid' => 0));
		$calendar->write_db();
		$permissions[0]['group'] = Group::fakePublic();
		$permissions[0]['value'] = 'r';
		$calendar->dbWritePermission($permissions);
	}
	
	
	public function testHandleDetails() {
		
		// get calendar
		$tid = 1;
		$calendar = new Calendar($tid);
		// set valid and remove permissions
		$calendar->update(array('valid' => 1));
		$calendar->write_db();
		
		// prepare request_uri, nothing given
		$_SERVER['REQUEST_URI'] = '/api/calendar/details/'.$tid;
		// json result
		// get user
		$user = new User(false);
		$user->change_user($calendar->getModifiedBy(), false, 'id');
		// get files
		$files = array();
		$fileIds = File::attachedTo('calendar', $calendar->get_id());
		if(count($fileIds) > 0) {
			foreach($fileIds as $fileId) {
				$file = new File($fileId);
				$files[] = array('filename' => $file->getFilename(),
						'href' => 'file.php?id=download&fid='.$fileId);
			}
		}
		// get result
		if(count(Result::getIdsForCalendar($calendar->get_id())) > 0) {
			$announcement['result'] = 'result.php?id=list&cid='.$calendar->get_id();
		}
		$json = json_encode(array(
				'result' => 'OK',
				'data' => array(
						'id' => (string)$calendar->getId(),
						'start' => $calendar->get_date('U'),
						'end' => $calendar->getEndDate('U'),
						'name' => $calendar->get_name(),
						'type' => $calendar->get_type(),
						'content' => $calendar->get_content(),
						'city' => $calendar->getCity(),
						'lastModified' => $calendar->getLastModified(),
						'modifiedBy' => $user->get_userinfo('name'),
						'files' => $files,
						'announcement' => $announcement,
						'announcementDraft' => Calendar::getDraftValue($calendar->get_preset_id(), $calendar->getId()) == 1,
						'locale' => $calendar->getUser()->get_lang(),
						'color' => $calendar->getColor(),
						'isExternal' => $calendar->getIsExternal(),
					),
			));
		
		// get object
		$api = new Api();
		$this->expectOutputString($json, $api->handle());
		
		// reset calendar entry
		$calendar->update(array('valid' => 0));
		$calendar->write_db();
	}
	
}

?>
