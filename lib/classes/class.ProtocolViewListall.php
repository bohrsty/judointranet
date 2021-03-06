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
 * class ProtocolViewListall implements the control of the id "listall" protocol page
 */
class ProtocolViewListall extends ProtocolView {
	
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
		$this->getTpl()->assign('pagecaption', _l('listall').'&nbsp;'.$this->helpButton(HELP_MSG_PROTOCOLLISTALL));
		
		// return		
		return $this->getListallTable();
	}
	
	
	/**
	 * getListallTable() generates the protocol listall table
	 * 
	 * @return string HTML string of the generated listall table
	 */
	private function getListallTable() {
		
		// define div id for container
		$containerId = 'protocolListallTable';
		
		// get Jtable object
		$jtable = new Jtable();
		// set settings
		$jtable->setActions('protocol.php', 'ProtocolListall', false, false, false);
		// get JtableFields
		$jtfDate = new JtableField('date');
		$jtfDate->setTitle(_l('date'));
		$jtfDate->setEdit(false);
		$jtfDate->setWidth('1%');
		$jtfType = new JtableField('type');
		$jtfType->setTitle(_l('kind'));
		$jtfType->setEdit(false);
		$jtfLocation = new JtableField('location');
		$jtfLocation->setTitle(_l('city'));
		$jtfLocation->setEdit(false);
		$jtfShow = new JtableField('show');
		$jtfShow->setTitle(_l('show'));
		$jtfShow->setWidth('1%');
		$jtfShow->setEdit(false);
		$jtfShow->setSorting(false);
		$jtfTasks = new JtableField('admin');
		$jtfTasks->setTitle(_l('tasks').$this->helpButton(HELP_MSG_PROTOCOLLISTADMIN));
		$jtfTasks->setEdit(false);
		$jtfTasks->setWidth('5%');
		$jtfTasks->setSorting(false);
		
		// add fields to $jtable
		$jtable->addField($jtfDate);
		$jtable->addField($jtfType);
		$jtable->addField($jtfLocation);
		$jtable->addField($jtfShow);
		// add admin colum if logged in
		if($this->getUser()->get_loggedin() === true) {
			$jtable->addField($jtfTasks);
		}
		
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
