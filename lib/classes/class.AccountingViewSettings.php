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
 * class AccountingViewSettings implements the control of the id "settings" accounting page
 */
class AccountingViewSettings extends AccountingView {
	
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
		$this->getTpl()->assign('pagecaption', _l('settings'));
		
		// add dashs
		$dashs[] = $this->getCostsDash();
		$this->smarty->assign('dashs', $dashs);
		
		// smarty-return		
		return $this->smarty->fetch('smarty.accounting.dashboard.tpl');
	}
	
	
	/**
	 * getCostsDash() generates the dash for the costs settings and returns it in an array
	 * 
	 * @return array array containint the costs dash
	 */
	private function getCostsDash() {
		
		// define div id for container
		$containerId = 'costsSettingsTable';
		
		// get Jtable object
		$jtable = new Jtable();
		// set settings
		$jtable->setActions('accounting.php', 'AccountingSettingsCosts', false, true, false);
		// get JtableFields
		$jtfId = new JtableField('id');
		$jtfId->setKey(true);
		$jtfId->setList(false);
		$jtfId->setEdit(false);
		$jtfName = new JtableField('name');
		$jtfName->setTitle(_l('name'));
		$jtfName->setEdit(false);
		$jtfType = new JtableField('type');
		$jtfType->setTitle(_l('type'));
		$jtfType->setEdit(false);
		$jtfType->setType('combobox');
		$jtfType->setOptions(
				array(
						'payback' => _l('payback'),
						'payment' => _l('payment'),
					)
			);
		$jtfValue = new JtableField('value');
		$jtfValue->setTitle(_l('value [EUR]'));
		$jtfValue->validateAgainst('required');
		
		// add fields to $jtable
		$jtable->addField($jtfId);
		$jtable->addField($jtfName);
		$jtable->addField($jtfType);
		$jtable->addField($jtfValue);
		
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
				'head' => _l('Costs').'&nbsp;'.$this->helpButton(HELP_MSG_ACCOUNTINGSETTINGSCOSTS),
				'content' => '<div id="'.$containerId.'" class="jTable"></div>',
			);
	}
}

?>
