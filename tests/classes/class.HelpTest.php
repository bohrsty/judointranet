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


class TestObject extends Object {
	
	function __construct() {
		parent::__construct();
	}
	
	public static function lang($string) {
		return parent::lang($string);
	}
}

class HelpTest extends PHPUnit_Framework_TestCase {
	
	// variables
	private $help;
	
	// setup
	public function setUp() {
		
		$page = new MainView();
		
		$this->help = $GLOBALS['help'];
	}
	
	
	public function tearDown() {
		
	}
	
	
	public function testHelpMessageGenerationExistingId() {
		
		// register message 1
		$message = $this->help->getMessage(1);
		// check output
		$this->assertContains(TestObject::lang('class.Help#global#title#title-1'), $message);
		$this->assertContains(TestObject::lang('class.Help#global#message#message-1'), $message);
		$this->assertContains('class="'.$_SESSION['GC']->get_config('help.buttonClass').'"', $message);
		$this->assertContains('class="'.$_SESSION['GC']->get_config('help.dialogClass').'"', $message);
		
		// check nonexistent id
		$message2 = $this->help->getMessage(-1);
		$this->assertContains(TestObject::lang('class.Help#global#title#errorIdNotExists'), $message2);
		$this->assertContains(TestObject::lang('class.Help#global#message#errorIdNotExists'), $message2);
		
		// check replacement
		$message3 = $this->help->getMessage(1,array('version' => $_SESSION['GC']->get_config('global.version')));
		$this->assertContains($_SESSION['GC']->get_config('global.version'), $message3);
		
	}
}


?>
