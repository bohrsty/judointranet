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

class NaviTest extends PHPUnit_Framework_TestCase {
	
	// variables
	private $user;
	
	// setup
	public function setUp() {
		
	}
	
	
	public function tearDown() {
		
	}
	
	
	public function testNaviSetterGetter() {
		
		// create navi
		$id = 1;
		$navi = new Navi($id);
		$this->assertEquals($id, $navi->getId());
		$this->assertEquals('class.Navi#item#name#mainPage', $navi->getName());
		$this->assertEquals(0, $navi->getParent());
		$this->assertEquals('index.php|', $navi->getFileParam());
		$this->assertEquals(0, $navi->getPosition());
		$this->assertEquals(1, $navi->getShow());
		$this->assertEquals(1, $navi->getValid());
		$this->assertEquals('r', $navi->getRequiredPermission());
		$subItems = $navi->getSubItems();
		$this->assertInternalType('array', $subItems);
		$this->assertInstanceOf('Navi', $subItems[0]);
		$this->assertInstanceOf('Navi', $subItems[1]);
		
		// id
		$data = 0;
		
		$navi->setId($data);
		$this->assertEquals($data, $navi->getId());
		
		// name
		$data = 'naviName';
		
		$navi->setName($data);
		$this->assertEquals($data, $navi->getName());
		
		// parent
		$data = -1;
		
		$navi->setParent($data);
		$this->assertEquals($data, $navi->getParent());
		
		// file and parameter
		$data = 'file.php|parameter';
		
		$navi->setFileParam($data);
		$this->assertEquals($data, $navi->getFileParam());
		
		// position
		$data = 1;
		
		$navi->setPosition($data);
		$this->assertEquals($data, $navi->getPosition());
		
		// show
		$data = 0;
		
		$navi->setShow($data);
		$this->assertEquals($data, $navi->getShow());
		
		// valid
		$data = 0;
		
		$navi->setValid($data);
		$this->assertEquals($data, $navi->getValid());
		
		// required_permission
		$data = 'w';
		
		$navi->setRequiredPermission($data);
		$this->assertEquals($data, $navi->getRequiredPermission());
		
		// subGroups
		$data = array(1,2,3);
		
		$navi->setSubItems($data);
		$this->assertEquals($data, $navi->getSubItems());
		
		// existance
		$this->assertTrue(Navi::exists(1));
		$this->assertFalse(Navi::exists(-1));
	}
	
	
	public function testNaviOutputToHtml() {
		
		// cli workaround
		$_SERVER['REQUEST_URI'] = 'index.php';
		// get object
		$id = 1;
		$navi = new Navi($id);
		$output = $navi->output('index.php', '');
		// check translation
		$this->assertContains(TestObject::lang($navi->getName()), $output);
		$this->assertNotContains('not translated', $output);
		// check handling of mainpage
		$this->assertNotContains('logout', $output);
		$navi->getUser()->set_loggedin(true);
		$output = $navi->output('index.php', '');
		$this->assertNotContains('login', $output);
	}
	
	
	public function testNaviGetStaticIdForPermissionCheck() {
		
		// check id for given file and param
		$file = 'index.php';
		$param = '';
		$id = Navi::idFromFileParam($file, $param);
		$this->assertEquals(1, $id);
	}
}
?>
