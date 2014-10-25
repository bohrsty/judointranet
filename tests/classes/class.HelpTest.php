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

class HelpTest extends PHPUnit_Framework_TestCase {
	
	// variables
	private $page;
	private $help;
	
	// setup
	public function setUp() {
		
		$this->page = new TestView();
		
		$this->help = $this->page->getHelp();
	}
	
	
	public function tearDown() {
		
	}
	
	
	public function testHelpMessageGenerationExistingId() {
		
		// register message 1
		$mid = 1;
		$message = $this->help->getMessage($mid);
		// check output
		$this->assertContains(TestObject::lang('help'), $message);
		$this->assertContains('id="'.$this->page->getGc()->get_config('help.buttonClass'), $message);
		
		// test output
		$smarty = new JudoIntranetSmarty();
		$smarty->assign('replace', array('version' => $this->page->getGc()->get_config('global.version')));
		$this->assertContains($smarty->fetch('string:'.TestObject::lang('HELP_MESSAGE_1')), $this->page->getHelpmessages());
		$this->assertContains('id="'.$this->page->getGc()->get_config('help.dialogClass'), $this->page->getHelpmessages());
		
	}
	
	
	public function testHelpMessageGenerationNonExistingId() {
		
		// check nonexistent id
		$message = $this->help->getMessage(-1);
		$this->assertContains(TestObject::lang('HELP_MESSAGE_error'), $this->page->getHelpmessages());
	}
	
	
	public function testHelpMessageGenerationReplacement() {
		
		// check replacement
		$message = $this->help->getMessage(1,array('version' => $this->page->getGc()->get_config('global.version')));
		$this->assertContains($this->page->getGc()->get_config('global.version'), $this->page->getHelpmessages());
	}
}


?>
