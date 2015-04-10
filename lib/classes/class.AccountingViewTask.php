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
 * class AccountingViewTask implements the control of the id "task" accounting page
 */
class AccountingViewTask extends AccountingView {
	
	/*
	 * class-variables
	 */
	private $smarty;
	
	/*
	 * getter/setter
	 */
	
	/*
	 * constructor/destructor
	 */
	public function __construct() {
		
		// setup parent
		parent::__construct();
		
		// create smarty object
		$this->smarty = new JudoIntranetSmarty();
	}
	
	
	/**
	 * show() generates the output of the page
	 * 
	 * @return string output for the page to be added to the template
	 */
	public function show() {
		
		// pagecaption
		$this->getTpl()->assign('pagecaption', _l('dashboard'));
		
		// switch task
		switch($this->get('task')) {
			
			case 'confirmresult':
				$this->confirmresult();
			break;
			
			case 'unconfirmresult':
				$this->unconfirmresult();
			break;
			
			default:
				throw new UnknownTaskException($this);
			break;
		}
	}
	
	
	/**
	 * confirmresult() confirms the result task given in $_GET['rid'] and redirects to dashboard
	 */
	private function confirmresult() {
		
		// get rid
		$rid = $this->get('rid');
		
		// check if result exists
		if(Page::exists('result', $rid)) {
			
			// create task
			$task = new AccountingResultTask();
			// confirm task
			$task->confirm($rid);
		} else {
			throw new ResultIdNotExistsExeption($this);
		}
		
		// redirect to dashboard
		$this->redirectTo('accounting', array('id' => 'dashboard'));
	}
	
	
	/**
	 * unconfirmresult() unconfirms the result task given in $_GET['rid'] and redirects to dashboard
	 */
	private function unconfirmresult() {
		
		// get rid
		$rid = $this->get('rid');
		
		// check if result exists
		if(Page::exists('result', $rid)) {
			
			// create task
			$task = new AccountingResultTask();
			// confirm task
			$task->unconfirm($rid);
		} else {
			throw new ResultIdNotExistsExeption($this);
		}
		
		// redirect to dashboard
		$this->redirectTo('accounting', array('id' => 'dashboard'));
	}
}

?>
