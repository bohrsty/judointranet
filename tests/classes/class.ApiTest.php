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

class ApiTest extends PHPUnit_Framework_TestCase {
	
	// setup
	public function setUp() {
		
	}
	
	
	public function tearDown() {
		
	}
	
	
	public function testConstruction() {
		
		// prepare request_uri
		$_SERVER['REQUEST_URI'] = '/api/';
		
		$data = 'Api';
		
		// instance of
		$api = new $data();
		$this->assertEquals($data, get_class($api));
	}
	
	
	public function testHandleNothingGiven() {
		
		// prepare request_uri, nothing given
		$_SERVER['REQUEST_URI'] = '/api/';
		
		// get object
		$api = new Api();
		$this->expectOutputString('[]', $api->handle());
	}
	
	
	public function testHandleNotExistingHandler() {
		
		// prepare request_uri
		$apiName = 'notexists';
		$_SERVER['REQUEST_URI'] = '/api/'.$apiName;
		// json result
		$json = json_encode(array(
				'result' => 'ERROR',
				'message' => 'API call failed [handler not found \'ApiHandler'.ucfirst($apiName).'\']',
			));
		
		// get object
		$api = new Api();
		$this->expectOutputString($json, $api->handle());
	}
	
	
	public function testHandleInvalidChars() {
		
		// prepare request_uri
		$apiName = 'invalid\'chars\'';
		$_SERVER['REQUEST_URI'] = '/api/'.$apiName;
		// json result
		$json = json_encode(array(
				'result' => 'ERROR',
				'message' => 'API call failed [invalid characters in request string]',
			));
		
		// get object
		$api = new Api();
		$this->expectOutputString($json, $api->handle());
	}
	
}
?>
