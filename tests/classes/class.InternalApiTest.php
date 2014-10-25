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

class InternalApiTest extends PHPUnit_Framework_TestCase {
	
	private $apiKey;
	private $apiTimeout;
	
	// setup
	public function setUp() {
		
		// get config
		$config = new Config();
		$this->apiKey = $config->get_config('global.apikey');
		$this->apiTimeout = $config->get_config('internalApi.timeout');
	}
	
	
	public function tearDown() {
		
	}
	
	
	public function testConstruction() {
		
		$data = 'InternalApi';
		
		// instance of
		$api = new $data();
		$this->assertEquals($data, get_class($api));
	}
	
	
	public function testApiKey() {
		
		// test apikey
		$this->assertTrue($this->apiKey !== false);
		$this->assertTrue(strlen($this->apiKey) >= 64);
		$this->assertNotEquals('JudoIntranetJudoIntranetJudoIntranetJudoIntranetJudoIntranetJudo', $this->apiKey);
		
	}
	
	
	public function testHandleWrongSignature() {
		
		// set $_GET values for JTable and wrong signature
		$_SESSION['api'] = array('apiClass' => 'JTable', 'apiBase' => 'test.php', 'time' => time());
		$_GET['signedApi'] = base64_encode(json_encode($_SESSION['api']));
		
		// get object
		$api = new InternalApi();
		// test output
		$result = array(
				'Result' => 'ERROR',
				'Message' => _l('API call failed [not signed]'),
			);
		$this->expectOutputString(json_encode($result), $api->handle());
	}
	
	
	public function testHandleTimeout() {
		
		// set $_GET values for JTable and wrong signature
		$_SESSION['api'] = array('apiClass' => 'JTable', 'apiBase' => 'test.php', 'time' => (time() - $this->apiTimeout -10));
		$_GET['signedApi'] = base64_encode(hash_hmac('sha256', json_encode($_SESSION['api']), $this->apiKey));
		
		// get object
		$api = new InternalApi();
		// test output
		$result = array(
				'Result' => 'ERROR',
				'Message' => _l('API call failed [timeout]'),
			);
		$this->expectOutputString(json_encode($result), $api->handle());
	}
	
	
	public function testHandleJtableCorrectSignature() {
		
		// set $_GET values for JTable and correct signature
		$_SESSION['api'] = array('apiClass' => 'JTable', 'apiBase' => 'test.php', 'time' => time());
		$_GET['signedApi'] = base64_encode(hash_hmac('sha256', json_encode($_SESSION['api']), $this->apiKey));
		
		// get object
		$api = new InternalApi();
		// test output
		$result = array(
				'Result' => 'OK',
				'Records' => array(),
			);
		$this->expectOutputString(json_encode($result), $api->handle());
	}
	
	
	public function testHandleJtableActionListNoProvider() {
		
		// set $_GET values for JTable list action
		$_SESSION['api'] = array('apiClass' => 'JTable', 'apiBase' => 'test.php', 'time' => time());
		$_GET['signedApi'] = base64_encode(hash_hmac('sha256', json_encode($_SESSION['api']), $this->apiKey));
		$_GET['action'] = 'list';
		
		// get object
		$api = new InternalApi();
		// test output
		$result = array(
				'Result' => 'ERROR',
				'Message' => _l('API call failed [unknown provider]'),
			);
		$this->expectOutputString(json_encode($result), $api->handle());
	}
	
	
	public function testHandleJtableActionListProviderAccountsettingscost() {
		
		// set $_GET values for JTable list action
		$_SESSION['api'] = array('apiClass' => 'JTable', 'apiBase' => 'test.php', 'time' => time());
		$_GET['signedApi'] = base64_encode(hash_hmac('sha256', json_encode($_SESSION['api']), $this->apiKey));
		$_GET['action'] = 'list';
		$_GET['provider'] = 'AccountingSettingsCosts';
		
		// get object
		$api = new InternalApi();
		// test output
		$this->expectOutputRegex('/\{"Result":"OK","Records":\[.*\],"TotalRecordCount":"\\d+"\}/', $api->handle());
	}
	
}
?>
