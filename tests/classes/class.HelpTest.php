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
	
	// setup
	public function setUp() {
		
	}
	
	
	public function tearDown() {
		
	}
	
	
	public function testHelpMessageGenerationExistingId() {
		
		// test message 1
		$mid = 1;
		$_GET['hid'] = $mid;
		
		// get objects
		$page = new PageView();
		$help = new Help();
		
		// get template
		$replacements['version'] = $page->getGc()->get_config('global.version');
		$smarty = new JudoIntranetSmarty();
		$smarty->assign('object', $this);
		$smarty->assign('replace', $replacements);
		
		// test output
		$testOutput = json_encode(
			array(
					'result' => 'OK',
					'title' => $smarty->fetch('string:'._l('HELP_TITLE_'.$mid)),
					'content' => $smarty->fetch('string:'._l('HELP_MESSAGE_'.$mid)),
				)
		);
		$this->expectOutputString($testOutput);
		$help->handle();
		
		// test button 1
		$button = $page->helpButton($mid);
		$this->assertContains('id="'.$mid.'"', $button);
		$this->assertContains('class="'.$page->getGc()->get_config('help.buttonClass').'"', $button);
		$this->assertContains('title="'._l('help').'"', $button);
	}
	
	
	public function testHelpMessageGenerationNonExistingId() {
		
		// check nonexistent id
		$mid = -1;
		$_GET['hid'] = $mid;
		
		// get objects
		$page = new PageView();
		$help = new Help();
		
		// get template
		$replacements['version'] = $page->getGc()->get_config('global.version');
		$smarty = new JudoIntranetSmarty();
		$smarty->assign('object', $this);
		$smarty->assign('replace', $replacements);
		
		// test output
		$testOutput = json_encode(
			array(
					'result' => 'OK',
					'title' => $smarty->fetch('string:'._l('HELP_TITLE_error')),
					'content' => $smarty->fetch('string:'._l('HELP_MESSAGE_error')),
				)
		);
		$this->expectOutputString($testOutput);
		$help->handle();
	}
}


?>
