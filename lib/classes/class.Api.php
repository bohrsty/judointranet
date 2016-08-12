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
class Api extends Object {
	
	
	/*
	 * class-variables
	 */
	private $request;
	
	
	/*
	 * getter/setter
	 */
	public function getRequest() {
		return $this->request;
	}
	public function setRequest($request) {
		$this->request = $request;
	}
	
	/*
	 * constructor/destructor
	 */
	public function __construct() {
		
		// setup parent
		parent::__construct();
		
		// set request
		$this->getRequestData();
	}
	
	/*
	 * methods
	 */
	/**
	 * handle() handles the api requests
	 * 
	 * JSON object contains two field:
	 * "result" = ERROR or OK
	 * if result is ERROR the second has to be "message" that explains the error
	 * if result is OK the second has to be "data" an array containig the result of the request
	 * 
	 *  @param bool $show echoes directly if true, returns data if false
	 */
	public final function handle($show) {
		
		// check request
		$request = $this->getRequest();
		if($request !== false) {
			
			// prepare result
			$result = array();
			
			// check if class exists
			$apiName = 'ApiHandler'.ucfirst($request['data']['table']);
			if(@class_exists($apiName, true)) {
				
				// get object
				$api = new $apiName($request);
				// get result
				$result = $api->getResult();
			} else {
				
				// prepare error
				$result = array(
					'result' => 'ERROR',
					'message' => 'API call failed [handler not found \''.$apiName.'\']',
				);
			}
			
			// check html param and output
			$return = array(
					'html' => isset($request['options']['html']) && $request['options']['html'] == 1,
				);
			if($return['html'] === true) {
				$return['data'] = (isset($result['data']) ? $result['data'] : $result['message']);
			} else {
				$return['data'] = $result;
			}
		} else {
			
			// prepare and output error
			$return['html'] = false;
			$return['data'] = array(
					'result' => 'ERROR',
					'message' => 'API call failed [invalid characters in request string]',
				);
		}
		
		// return or echo result
		if($show === true) {
			echo json_encode($return['data']);
		} else {
			return $return;
		}
	}
	
	
	/**
	 * getRequestData() reads the path and the options from the request string and sets it as array
	 */
	private function getRequestData() {
		
		// prepare path names
		$apiNames = array(
				'table',
				'id',
				'tid',
				'action',
		);
		
		// prepare value array
		$api = array();
		
		// check request string
		$apiRequest = $this->check_valid_chars('apirequest', $_SERVER['REQUEST_URI']);
		if($apiRequest !== false) {
			
			// get relevant part of the request string
			$apiUri = explode('/api/', $apiRequest)[1];
			// separate options
			$apiArray = explode('?', $apiUri);
			// get path values and name them
			$apiData = explode('/', $apiArray[0]);
			for($i=0; $i<count($apiData); $i++) {
				$api['data'][(isset($apiNames[$i]) ? $apiNames[$i] : $i)] = $apiData[$i];
			}
			// check action
			if(!isset($api['data']['action'])) {
				$api['data']['action'] = null;
			}
			// check if options are set
			if(isset($apiArray[1])) {
				
				// extract options and name them
				foreach(explode('&', $apiArray[1]) as $option) {
					
					$temp = explode('=', $option);
					$api['options'][$temp[0]] = $temp[1];
				}
			}
			
			// set class variable
			$this->setRequest($api);
		} else {
			
			// set class variable in case of error
			$this->setRequest(false);
		}
	}
}

?>
