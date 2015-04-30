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
 * class CalendarViewListall implements the control of the id "listall" calendar page
 */
class CalendarViewListall extends CalendarView {
	
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
		
		// activate validationEngine
		$this->getTpl()->assign('validationEnging', true);
	}
	
	
	/**
	 * show($from, $to) generates the output of the page
	 * 
	 * @param string $from date from which the listing starts
	 * @param string $to date where the listing ends
	 * @return string output for the page to be added to the template
	 */
	public function show($from, $to = '2100-01-01') {
		
		// return		
		return $this->getListallTable($from, $to);
	}
	
	
	/**
	 * getListallTable($from, $to) generates the calendar listall table
	 * 
	 * @param string $from date from which the listing starts
	 * @param string $to date where the listing ends
	 * @return string HTML string of the generated listall table
	 */
	private function getListallTable($from, $to) {
		
		// add jquery for detail "popup"
		$recordsLoaded = 'function(event, data) { $(\'.calendarDetails\').click(function() {var id = this.id.substr(1); var dialogDiv = $(\'<div id="dialog_\'+this.id+\'" title="'._l('appointment').'" style="display: none"></div>\'); dialogDiv.load(\'api/calendar/details/\'+id+\'?html=1\'); $(\'body\').append(dialogDiv); dialogDiv.dialog({ autoOpen: true, modal: true, position: { my: \'center\', at: \'center\', of: window }, closeText: \''._l('close').'\', minWidth: 600, minHeight: 250, maxHeight: 600, close: function(event, ui) {dialogDiv.remove();} }); }) }';
		
		// define div id for container
		$containerId = 'calendarListallTable';
		
		// prepare public image
		$sImg = new JudoIntranetSmarty();
		$imgArray = array(
				'params' => 'class="icon" title="'._l('public').'"',
				'src' => 'img/public.png',
				'alt' => _l('public'),
			);
		$sImg->assign('img', $imgArray);
		$publicImg = $sImg->fetch('smarty.img.tpl');
		$imgArray = array(
				'params' => 'class="icon" title="'._l('is external').'"',
				'src' => 'img/external.png',
				'alt' => _l('is external'),
		);
		$sImg->assign('img', $imgArray);
		$externalImg = $sImg->fetch('smarty.img.tpl');
		
		// get Jtable object
		$jtable = new Jtable();
		// set settings
		$jtable->setActions('calendar.php', 'CalendarListall', false, false, false, array('from' => $from, 'to' => $to, 'filter' => $this->get('filter')));
		// get JtableFields
		$jtfDate = new JtableField('date');
		$jtfDate->setTitle(_l('start date'));
		$jtfDate->setEdit(false);
		$jtfDate->setWidth('1%');
		$jtfEndDate = new JtableField('endDate');
		$jtfEndDate->setTitle(_l('end date'));
		$jtfEndDate->setEdit(false);
		$jtfEndDate->setSorting(false);
		$jtfEndDate->setWidth('1%');
		$jtfEvent = new JtableField('event');
		$jtfEvent->setTitle(_l('event'));
		$jtfEvent->setEdit(false);
		$jtfCity = new JtableField('city');
		$jtfCity->setTitle(_l('city'));
		$jtfCity->setEdit(false);
		$jtfCity->setWidth('1%');
		$jtfShow = new JtableField('show');
		$jtfShow->setTitle(_l('show'));
		$jtfShow->setEdit(false);
		$jtfShow->setWidth('1%');
		$jtfShow->addListClass('nowrap');
		$jtfShow->setSorting(false);
		$jtfTasks = new JtableField('admin');
		$jtfTasks->setTitle(_l('tasks').$this->helpButton(HELP_MSG_CALENDARLISTADMIN));
		$jtfTasks->setEdit(false);
		$jtfTasks->setWidth('5%');
		$jtfTasks->setSorting(false);
		$jtfPublic = new JtableField('public');
		$jtfPublic->setTitle($publicImg);
		$jtfPublic->setEdit(false);
		$jtfPublic->setWidth('0.1%');
		$jtfPublic->setSorting(false);
		$jtfExternal = new JtableField('isExternal');
		$jtfExternal->setTitle($externalImg);
		$jtfExternal->setEdit(false);
		$jtfExternal->setWidth('0.1%');
		$jtfExternal->setSorting(false);
		
		// add fields to $jtable
		$jtable->addField($jtfDate);
		$jtable->addField($jtfEndDate);
		$jtable->addField($jtfEvent);
		$jtable->addField($jtfCity);
		$jtable->addField($jtfExternal);
		// add public colum if logged in
		if($this->getUser()->get_loggedin() === true) {
			$jtable->addField($jtfPublic);
		}
		$jtable->addField($jtfShow);
		// add admin colum if logged in
		if($this->getUser()->get_loggedin() === true) {
			$jtable->addField($jtfTasks);
		}
		
		// set recordsLoaded
		$jtable->setSetting('recordsLoaded', $recordsLoaded, false);
		
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
