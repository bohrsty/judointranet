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
		$this->getTpl()->assign('pagecaption',parent::lang('result list', true).'&nbsp;'.$this->helpButton(HELP_MSG_RESULTLIST));
		
		// return
		return $this->getResultList();
	}
	
	
	/**
	 * getResultList() generates the table config and returns the HTML element
	 * 
	 * @return string HTML element the list is shown in
	 */
	private function getResultList() {
		
		// define div id for container
		$containerId = 'ResultListTable';
		
		// get Jtable object
		$jtable = new Jtable();
		// set settings
		$jtable->setActions('result.php', 'ResultList', false, false, false, array('cid' => $this->get('cid'),));
		// get JtableFields
		$jtfDesc = new JtableField('desc');
		$jtfDesc->setTitle(_l('result desc'));
		$jtfDesc->setEdit(false);
		$jtfDesc->setSorting(false);
		$jtfDesc->setWidth('1%');
		$jtfName = new JtableField('name');
		$jtfName->setTitle(_l('event name'));
		$jtfName->setEdit(false);
		$jtfDate = new JtableField('date');
		$jtfDate->setTitle(parent::lang('event date'));
		$jtfDate->setEdit(false);
		$jtfDate->setWidth('1%');
		$jtfCity = new JtableField('city');
		$jtfCity->setTitle(parent::lang('event city'));
		$jtfCity->setEdit(false);
		$jtfCity->setWidth('1%');
		$jtfShow = new JtableField('show');
		$jtfShow->setTitle(parent::lang('show'));
		$jtfShow->setEdit(false);
		$jtfShow->setSorting(false);
		$jtfShow->setWidth('1%');
		
		// add fields to $jtable
		$jtable->addField($jtfDesc);
		$jtable->addField($jtfName);
		$jtable->addField($jtfDate);
		$jtable->addField($jtfCity);
		$jtable->addField($jtfShow);
		
		// get java script config
		$jtableJscript = $jtable->asJavaScriptConfig();
		
		// add surrounding javascript
		$jquery = '$("#'.$containerId.'").jtable('.$jtableJscript.');';
		// add to jquery
		$this->add_jquery($jquery);
		$this->add_jquery('$("#'.$containerId.'").jtable("load");');
		
		// enable jtable in template
		$this->getTpl()->assign('jtable', true);
		
		// return
		return '<div id="'.$containerId.'" class="jTable"></div>';
	}
}

?>
