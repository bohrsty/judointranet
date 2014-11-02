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

class JtableTest extends PHPUnit_Framework_TestCase {
	
	// setup
	public function setUp() {
		
	}
	
	
	public function tearDown() {
		
	}
	
	
	public function testConstruction() {
		
		$data = 'Jtable';
		
		// instance of
		$api = new $data();
		$this->assertEquals($data, get_class($api));
		
		// cleanup
		unset($_SESSION['api']);
	}
	
	public function testGetAsJavaScriptConfig() {
		
		// get object
		$jtable = new Jtable();
		
		$this->assertStringStartsWith('{', $jtable->asJavaScriptConfig());
		$this->assertStringEndsWith('}', $jtable->asJavaScriptConfig());
		
		// cleanup
		unset($_SESSION['api']);
	}
	
	public function testSetter() {
		
		// get object
		$jtable = new Jtable();
		
		// set string
		$jtable->setSetting('title', 'a title');
		$this->assertContains('"title":"a title"', $jtable->asJavaScriptConfig());
		
		// set bool
		$jtable->setSetting('columnResizable', true);
		$this->assertContains('"columnResizable":true', $jtable->asJavaScriptConfig());
		
		// cleanup
		unset($_SESSION['api']);
	}
	
	public function testSetActions() {
		
		// get object
		$jtable = new Jtable();
		$provider = 'Provider';
		
		// list only
		$jtable->setActions('test.php', $provider, false, false, false);
		// get random id
		$randomId = '';
		foreach($_SESSION['api'] as $key => $value) {
			$randomId = $key;
		}
		$this->assertContains('"listAction":"api\/internal.php?id='.$randomId.'&signedApi=', $jtable->asJavaScriptConfig());
		$this->assertContains('&action=list&provider='.$provider.'"', $jtable->asJavaScriptConfig());
		
		// cleanup
		unset($_SESSION['api']);
		
		// list, create
		$jtable->setActions('test.php', $provider, true, false, false);
		// get random id
		$randomId = '';
		foreach($_SESSION['api'] as $key => $value) {
			$randomId = $key;
		}
		$this->assertContains('"createAction":"api\/internal.php?id='.$randomId.'&signedApi=', $jtable->asJavaScriptConfig());
		$this->assertContains('&action=create&provider='.$provider.'"', $jtable->asJavaScriptConfig());
		
		// cleanup
		unset($_SESSION['api']);
		
		// list, create, update
		$jtable->setActions('test.php', $provider, true, true, false);
		// get random id
		$randomId = '';
		foreach($_SESSION['api'] as $key => $value) {
			$randomId = $key;
		}
		$this->assertContains('"updateAction":"api\/internal.php?id='.$randomId.'&signedApi=', $jtable->asJavaScriptConfig());
		$this->assertContains('&action=update&provider='.$provider.'"', $jtable->asJavaScriptConfig());
		
		// cleanup
		unset($_SESSION['api']);
		
		// list, create, update, delete
		$jtable->setActions('test.php', $provider, true, true, true);
		// get random id
		$randomId = '';
		foreach($_SESSION['api'] as $key => $value) {
			$randomId = $key;
		}
		$this->assertContains('"deleteAction":"api\/internal.php?id='.$randomId.'&signedApi=', $jtable->asJavaScriptConfig());
		$this->assertContains('&action=delete&provider='.$provider.'"', $jtable->asJavaScriptConfig());
		
		// cleanup
		unset($_SESSION['api']);
		
		// list, create, update, delete
		$jtable->setActions('test.php', $provider);
		// get random id
		$randomId = '';
		foreach($_SESSION['api'] as $key => $value) {
			$randomId = $key;
		}
		$this->assertContains('"listAction":"api\/internal.php?id='.$randomId.'&signedApi=', $jtable->asJavaScriptConfig());
		$this->assertContains('&action=list&provider='.$provider.'"', $jtable->asJavaScriptConfig());
		$this->assertContains('"createAction":"api\/internal.php?id='.$randomId.'&signedApi=', $jtable->asJavaScriptConfig());
		$this->assertContains('&action=create&provider='.$provider.'"', $jtable->asJavaScriptConfig());
		$this->assertContains('"updateAction":"api\/internal.php?id='.$randomId.'&signedApi=', $jtable->asJavaScriptConfig());
		$this->assertContains('&action=update&provider='.$provider.'"', $jtable->asJavaScriptConfig());
		$this->assertContains('"deleteAction":"api\/internal.php?id='.$randomId.'&signedApi=', $jtable->asJavaScriptConfig());
		$this->assertContains('&action=delete&provider='.$provider.'"', $jtable->asJavaScriptConfig());
		
		// cleanup
		unset($_SESSION['api']);
	}
	
	public function testAddField() {
		
		// get object
		$jtable = new Jtable();
		
		// get fields
		$field1 = new JtableField('field1');
		$field2 = new JtableField('field2');
		// get json
		$field1Json = json_encode($field1->asArray());
		$field2Json = json_encode($field2->asArray());
		
		// add one field
		$jtable->addField($field1);
		$this->assertContains('"fields":{"'.$field1->getName().'":'.$field1Json.'}', $jtable->asJavaScriptConfig());
		
		// add two fields
		$jtable->addField($field1);
		$jtable->addField($field2);
		$this->assertContains('"fields":{"'.$field1->getName().'":'.$field1Json, $jtable->asJavaScriptConfig());
		$this->assertContains('"'.$field2->getName().'":'.$field2Json.'}', $jtable->asJavaScriptConfig());
		
		// cleanup
		unset($_SESSION['api']);
	}
	
}
?>
