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
 * class AccountingViewDashboard implements the control of the id "dashboard" accounting page
 */
class AccountingViewDashboard extends AccountingView {
	
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
		
		// add dashs
		$dashs[] = $this->getResultDash();
		$this->smarty->assign('dashs', $dashs);
		
		// smarty-return		
		return $this->smarty->fetch('smarty.accounting.dashboard.tpl');
	}
	
	
	/**
	 * getResultDash() generates the dash for the results and returns it in an array
	 * 
	 * @return array array containint the result dash
	 */
	private function getResultDash() {
		
		// define div id for container
		$containerId = 'resultTaskTable';
		
		// get Jtable object
		$jtable = new Jtable();
		// set settings
		$jtable->setActions('accounting.php', 'AccountingResultTask', false, false, false);
		
		// prepare status image
		$sStatusImg = new JudoIntranetSmarty();
		$imgArray = array(
				'params' => '',
				'src' => 'img/tasks_confirmed.png',
				'alt' => _l('state'),
			);
		$sStatusImg->assign('img', $imgArray);
		$statusImg = $sStatusImg->fetch('smarty.img.tpl');
		
		// get JtableFields
		$jtfState = new JtableField('state');
		$jtfState->setTitle($statusImg);
		$jtfState->setEdit(false);
		$jtfState->setSorting(false);
		$jtfState->setWidth('0.1%');
		$jtfName = new JtableField('name');
		$jtfName->setTitle(_l('name'));
		$jtfName->setEdit(false);
		$jtfDate = new JtableField('date');
		$jtfDate->setTitle(_l('date'));
		$jtfDate->setEdit(false);
		$jtfDate->setWidth('1%');
		$jtfDesc = new JtableField('desc');
		$jtfDesc->setTitle(_l('desc'));
		$jtfDesc->setEdit(false);
		$jtfDesc->setSorting(false);
		$jtfDesc->setWidth('1%');
		$jtfLastModified = new JtableField('last_modified');
		$jtfLastModified->setTitle(_l('last modified'));
		$jtfLastModified->setEdit(false);
		$jtfLastModified->setSorting(false);
		$jtfLastModified->setWidth('1%');
		$jtfActions = new JtableField('actions');
		$jtfActions->setTitle(_l('actions'));
		$jtfActions->setEdit(false);
		$jtfActions->setSorting(false);
		$jtfActions->setWidth('1%');
		
		// add fields to $jtable
		$jtable->addField($jtfState);
		$jtable->addField($jtfName);
		$jtable->addField($jtfDate);
		$jtable->addField($jtfDesc);
		$jtable->addField($jtfLastModified);
		$jtable->addField($jtfActions);
		
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
		return array(
				'head' => _l('Results').'&nbsp;'.$this->helpButton(HELP_MSG_ACCOUNTINGRESULTS),
				'content' => '<div id="'.$containerId.'" class="jTable"></div>',
			);
	}
}

?>
