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
 * class ResultViewList implements the control of the id "list" result page
 */
class ResultViewList extends ResultView {
	
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
		$this->getTpl()->assign('pagecaption',parent::lang('result list', true));//.'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FILELISTALL));
		
		// smarty
		$sTh = array(
				'desc' => parent::lang('result desc', true),
				'name' => parent::lang('event name', true),
				'date' => parent::lang('event date', true),
				'city' => parent::lang('event city', true),
				'show' => parent::lang('show', true),
				'admin' => parent::lang('admin', true),
			);
		// assign table header
		$this->smarty->assign('th', $sTh);
		// assign loggedin? admin links
		$this->smarty->assign('loggedin', $this->getUser()->get_loggedin());
		
		// get array of result objects (sorted by date)
		$results = $this->readAllEntries();
		
		// get array for smarty
		$resultList = $this->prepareResults($results, $this->get('cid'));		
		
		// assign data
		$this->smarty->assign('resultList', $resultList);
		
		// smarty-return		
		return $this->smarty->fetch('smarty.result.listall.tpl');
	}
}

?>
