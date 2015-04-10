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
 * class ResultViewDetails implements the control of the id "details" result page
 */
class ResultViewDetails extends ResultView {
	
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
		$this->getTpl()->assign('pagecaption', _l('result details'));
		
		// check if result exists
		if(Page::exists('result', $this->get('rid'))) {
			// get result
			$result = new Result($this->get('rid'));
			
			// check authorisation (against calendar, there is no result w/o calendar entry)
			if($this->getUser()->hasPermission('calendar', $result->getCalendar()->get_id())) {
				// get template
				$filledTemplate = $result->getFilledTemplateAsString(true, true);
				$this->smarty->assign('page', $filledTemplate['html']);
			} else {
				throw new NotAuthorizedException($this);
			}
		} else {
			throw new ResultNotExistsException($this, $this->get('rid'));
		}
				
		// smarty-return		
		return $this->smarty->fetch('smarty.result.details.tpl');
	}
}

?>
