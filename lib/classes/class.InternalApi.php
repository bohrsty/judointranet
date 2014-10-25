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
		
		// get $_SESSION data
		$api = (isset($_SESSION['api']) ? $_SESSION['api'] : array('apiClass' => '', 'apiBase' => '', 'time' => 0));
		
		// check signature
		$signedError = false;
		$timeoutError = false;
		if($this->checkApiSignature() === false) {
			$signedError = true;
		} elseif($api['time'] + $this->getGc()->get_config('internalApi.timeout') < time()) {
			$timeoutError = true;
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
					echo json_encode(array(
						'Result' => 'ERROR',
						'Message' => _l('API call failed [timeout]')
					));
				} else {
				// get api object
					$jtable = new InternalApiJtable();
					echo json_encode($jtable->result());
				}
			break;
			
			default:
				echo 'ERROR'._l('API call failed [unknown apiClass]');
			break;
		}
	}
	
	
	/**
	 * checkApiSignature() checks if the submitted data is signed correctly
	 * 
	 * @return bool false if signature is wrong, true if it is correct
	 */
	private final function checkApiSignature() {
		
		// get $_GET data and decode
		$api = (isset($_SESSION['api']) ? $_SESSION['api'] : array('apiClass' => '', 'apiBase' => '', 'time' => 0));
		$signedApi = $this->get('signedApi');
		// get api key
		$apiKey = $this->getGc()->get_config('global.apikey');
	
		// check signature
		return base64_encode(hash_hmac('sha256', json_encode($api), $apiKey)) == $signedApi;
	}
}

?>
