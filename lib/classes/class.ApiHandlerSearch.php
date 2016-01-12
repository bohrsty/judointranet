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
 * class ApiHandlerSearch implements the data handling of full text search with the public api
 */
class ApiHandlerSearch extends ApiHandler {
	
	
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
	 * getResult() handles the api requests for filesystem
	 * 
	 * @return array array containing the result 
	 */
	/**
	 * getResult($request) handles the api requests
	 * 
	 * @return array array containing the result 
	 */
	public function getResult() {

		// check "id" parameter of $request
		if($this->getRequest()['data']['id'] != '') {
			$apiMethod = 'handle'.ucfirst($this->getRequest()['data']['id']);
			if(is_callable(array($this, $apiMethod))) {
				
				// get search term
				$query = $this->get('term');
				
				// call method and return result
				return call_user_func(array($this, $apiMethod), $query);
			} else {
				return array(
						'label' => _l('ERROR').': API call failed [id not found \''.get_class($this).'::'.$apiMethod.'\']',
						'value' => 'file.php?id=listall',
					);
			}
		} else {
			return array(
					'label' => _l('ERROR').': API call failed [id not given]',
					'value' => 'file.php?id=listall',
				);
		}
	}
	
	
	/**
	 * handleFile($query) returns the prepared and highlighted search result as array 
	 * 
	 * @param string $query the search string
	 * @return array array containing the result data
	 */
	public function handleFile($query) {
		
		// search and return
		return FileListallListing::apiSearch($query);
	}
}

?>
