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

class AccountingResultTaskTest extends PHPUnit_Framework_TestCase {
	
	private $task;
	
	// setup
	public function setUp() {
		
		$this->task = new AccountingResultTask();
	}
	
	
	public function tearDown() {
		
		$this->task->setState(1, 0);
	}
	
	
	public function testConstruction() {
		
		$data = 'AccountingResultTask';
		
		// instance of
		$task = new $data();
		$this->assertEquals($data, get_class($task));
	}
	
	
	public function testGetterSetter() {
		
		$resultId = 1;
		$task = $this->task;
		
		$state = $task->getState($resultId);
		$this->assertEquals(0, $state);
		
		$state = 1;
		$task->setState($resultId, $state);
		$this->assertEquals($state, $task->getState($resultId));
		
		$state = 0;
		$task->setState($resultId, $state);
		$this->assertEquals(0, $task->getState($resultId));
	}
	
	
	public function testConfirmTask() {
		
		$task = $this->task;
		
		// confirm
		$task->confirm(1);
		$this->assertEquals(1, $task->getState(1));
		
		// unconfirm
		$task->unconfirm(1);
		$this->assertEquals(0, $task->getState(1));
	}
	
}
?>
