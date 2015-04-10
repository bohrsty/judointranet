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
 * class ProtocolViewDecisions implements the control of the id "decisions" protocol page
 */
class ProtocolViewDecisions extends ProtocolView {
	
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
		$this->getTpl()->assign('pagecaption', _l('show decisions').'&nbsp;'.$this->helpButton(HELP_MSG_PROTOCOLDECISIONS));
		
		// return		
		return $this->getDecisionsTable();
	}
	
	
	/**
	 * getDecisionsTable() generates the protocol decisions table
	 * 
	 * @return string HTML string of the generated decisions table
	 */
	private function getDecisionsTable() {
		
		// define div id for container
		$containerId = 'protocolDecisionsTable';
		
		// check if pid is given
		if($this->get('pid') !== false) {
			
			// check if pid exists
			if(Page::exists('protocol', $this->get('pid'))) {
				
				// get object
				$protocol = new Protocol($this->get('pid'));
				
				// get Jtable object
				$jtable = new Jtable();
				$jtable->setSetting('title', $protocol->get_date('d.m.Y').' - '.$protocol->get_type().' - '.$protocol->get_location());
				// set settings
				$jtable->setActions('protocol.php', 'ProtocolDecisions', false, false, false, array('pid' => $protocol->getId()));
				// get JtableFields
				$jtfDecision = new JtableField('decision');
				$jtfDecision->setTitle(_l('decisions'));
				$jtfDecision->setEdit(false);
				$jtfDecision->setWidth('100%');
				$jtfDecision->setSorting(false);
				// add field
				$jtable->addField($jtfDecision);
			} else {
				throw new ProtocolIdNotExistsExeption($this);
			}
		} else {
			
			// define parent data field
			$parentData = 'pid';
			// prepare image
			$imgArray = array(
					'params' => 'class="clickable" title="'._l('open decisions').'"',
					'src' => 'img/plus.png',
					'alt' => _l('open decisions'),
				);
			$this->smarty->assign('img', $imgArray);
			$img = $this->smarty->fetch('smarty.img.tpl');
			
			// define child table
			// get Jtable object
			$childJtable = new Jtable();
			// set settings
			$childJtable->setActions('protocol.php', 'ProtocolDecisions', false, false, false, array(), array('pid' => 'parentData.record.'.$parentData));
			// get JtableFields
			$cjtfDecision = new JtableField('decision');
			$cjtfDecision->setTitle(_l('decisions'));
			$cjtfDecision->setEdit(false);
			$cjtfDecision->setWidth('100%');
			$cjtfDecision->setSorting(false);
			// add field
			$childJtable->addField($cjtfDecision);
			
			// get Jtable object
			$jtable = new Jtable();
			// set settings
			$jtable->setActions('protocol.php', 'ProtocolDecisions', false, false, false);
			$jtable->setSetting('openChildAsAccordion', true);
			// get JtableFields
			$jtfPid = new JtableField('pid');
			$jtfPid->setKey(true);
			$jtfPid->setEdit(false);
			$jtfPid->setWidth('1%');
			$jtfPid->setSorting(false);
			$jtfPid->addChildTable($childJtable, $img, $containerId);
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
			
			// add fields to $jtable
			$jtable->addField($jtfPid);
			$jtable->addField($jtfDate);
			$jtable->addField($jtfType);
			$jtable->addField($jtfLocation);
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
