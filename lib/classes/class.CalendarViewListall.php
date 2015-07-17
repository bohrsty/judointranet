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
	 * show() generates the output of the page
	 * 
	 * @return string output for the page to be added to the template
	 */
	public function show() {
		
		// return		
		return $this->getListallTable();
	}
	
	
	/**
	 * getListallTable() generates the calendar listall table
	 * 
	 * @return string HTML string of the generated listall table
	 */
	private function getListallTable() {
		
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
		$jtable->setActions('calendar.php', 'CalendarListall', false, false, false);
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
		
		// filter
		// prepare template
		$sListall = new JudoIntranetSmarty();
		// assign help button
		$sListall->assign('helpButton', $this->helpButton(HELP_MSG_CALENDARLISTSORTLINKS));
		// assign container id
		$sListall->assign('containerId', $containerId);
		
		// get all filter
		$allFilter = Filter::allExistingFilter();
		// create links
		$groupfilter = array();
		foreach($allFilter as $filter) {
				
			// smarty
			$groupfilter[] = array(
					'id' => $filter->getId(),
					'name' => $filter->getName(),
			);
		}
		usort($groupfilter, array($this, 'callbackCompareFilter'));
		$sListall->assign('groupfilter', $groupfilter);
		
		// prepare dates
		$dates = array(
				'tomorrow' => '+1 day',
				'next week' => '+1 week',
				'next two weeks' => '+2 weeks',
				'next month' => '+1 month',
				'next halfyear' => '+6 months',
				'next year' => '+1 year'
			);
		
		// create links
		$datefilter = array();
		foreach($dates as $name => $date) {
				
			// smarty
			$datefilter[] = array(
					'from' => date('d.m.Y',time()),
					'to' => date('d.m.Y', strtotime($date)),
					'name' => _l($name)
			);
		}
		$sListall->assign('datefilter', $datefilter);
		
		// prepare javascript
		$this->add_jquery('
			var filterIds = {};
			var updateTable = function() {
				$("#'.$containerId.'").jtable("load", {filter: JSON.stringify(filterIds),from: $("#dateFrom").val(), to: $("#dateTo").val()});
			};
			$("#'.$containerId.'").jtable('.$jtableJscript.');
			$("#showFilterButton").click(function(event) {
				if($(event.target).closest(".helpButton").length == 0) {
					$("#filterDialog").slideToggle();
				}
			});
			$(".groupFilterCheckbox").each(function() {
				$(this).change(function() {
					$(this).parent().toggleClass("filterChecked", $(this).prop("checked"));
					filterIds[$(this).attr("id").substring(3)] = $(this).prop("checked");
				});
				$(this).prop("checked", true).change();
			});
			$("#groupAll").click(function() {
				$(".groupFilterCheckbox").prop("checked", true).change();
				updateTable();
			});
			$("#groupNone").click(function() {
				$(".groupFilterCheckbox").prop("checked", false).change();
				updateTable();
			});
			$(".groupFilterText").parent().click(function() {
				var filterButton = $(this);
				var checkbox = filterButton.find(".groupFilterCheckbox");
				checkbox.prop("checked", !checkbox.prop("checked")).change();
				updateTable();
			});
			$("#dateFrom").Zebra_DatePicker({
				format:"d.m.Y",
				show_icon: false,
				pair: $("#dateTo"),
				days: ["'._l('Sunday').'", "'._l('Monday').'", "'._l('Tuesday').'", "'._l('Wednesday').'", "'._l('Thursday').'", "'._l('Friday').'", "'._l('Saturday').'"],
				months: ["'._l('January').'", "'._l('February').'", "'._l('March').'", "'._l('April').'", "'._l('May').'", "'._l('June').'", "'._l('July').'", "'._l('August').'", "'._l('September').'", "'._l('October').'", "'._l('November').'", "'._l('December').'"],
				show_select_today: "'._l('Today').'",
				lang_clear_date: "'._l('Delete').'",
				onSelect: function() {
					$("#dateFrom").change();
					updateTable();
				}
			});
			$("#dateTo").Zebra_DatePicker({
				format:"d.m.Y",
				show_icon: false,
				days: ["'._l('Sunday').'", "'._l('Monday').'", "'._l('Tuesday').'", "'._l('Wednesday').'", "'._l('Thursday').'", "'._l('Friday').'", "'._l('Saturday').'"],
				months: ["'._l('January').'", "'._l('February').'", "'._l('March').'", "'._l('April').'", "'._l('May').'", "'._l('June').'", "'._l('July').'", "'._l('August').'", "'._l('September').'", "'._l('October').'", "'._l('November').'", "'._l('December').'"],
				show_select_today: "'._l('Today').'",
				lang_clear_date: "'._l('Delete').'",
				onSelect: function() {
					$("#dateTo").change();
					updateTable();
				}
			});
			$(".dateFilter").click(function() {
				$("#dateFrom").val($(this).attr("title").substring(0, 10));
				$("#dateTo").val($(this).attr("title").substring(11));
				updateTable();
			});
			$("#resetDate").click(function() {
				$("#dateFrom").val("");
				$("#dateTo").val("");
				updateTable();
			});
			$(".dateInput").change(function() {
				updateTable();
			});
			updateTable();
		');
		
		// enable jtable in template
		$this->getTpl()->assign('jtable', true);
		
		// return
		return $sListall->fetch('smarty.calendar.listall.tpl');
	}
	
	
	/**
	 * getFilterLinks($getid) returns links to list "week" "month" "year" etc
	 * and filter
	 * 
	 * @param string $getid $_GET['get'] to use in links
	 * @return string html-string with the links
	 */
	private function getFilterLinks($getid) {
		
		// prepare output
		$date_links = $group_links = $output = $reset_links = '';
		
		// smarty-template
		$sS = new JudoIntranetSmarty();
		
		// if filter, attach filter
		$filter = '';
		if($this->get('filter') !== false) {
			$filter = '&filter='.$this->get('filter');
		}
		// if from or to add from or to
		$from = $to = '';
		if($this->get('from') !== false) {
			$from = '&from='.$this->get('from');
		}
		if($this->get('to') !== false) {
			$to = '&to='.$this->get('to');
		}
		
		// prepare content
		$dates = array(
					'tomorrow' => '+1 day',
					'next week' => '+1 week',
					'next two weeks' => '+2 weeks',
					'next month' => '+1 month',
					'next halfyear' => '+6 months',
					'next year' => '+1 year'
					);
		
		// create links
		foreach($dates as $name => $date) {
			
			// smarty
			$dl[] = array(
					'href' => 'calendar.php?id='.$getid.'&from='.date('Y-m-d',time()).'&to='.date('Y-m-d',strtotime($date)).$filter,
					'title' => _l($name),
					'content' => _l($name)
				);
		}
		$sS->assign('dl', $dl);
		$sS->assign('dateFilter', _l('date filter'));
		
		// add group-links
		$allFilter = Filter::allExistingFilter();
		
		// create links
		foreach($allFilter as $filter) {
			
			// smarty
			$groupfilter[] = array(
					'id' => $filter->getId(),
					'name' => $filter->getName(),
				);
		}
		usort($groupfilter, array($this, 'callbackCompareFilter'));
		$sS->assign('groupfilter', $groupfilter);
		$sS->assign('helpButton', $this->helpButton(HELP_MSG_CALENDARLISTSORTLINKS));
		
		$this->add_jquery('
				$("#showFilterButton").click(function(event) {
					if($(event.target).closest(".helpButton").length == 0) {
						$("#filterDialog").slideToggle();
					}
				});
				$(".groupFilterCheckbox").each(function() {
					$(this).change(function() {
						$(this).parent().toggleClass("filterChecked", $(this).prop("checked"));
					});
					if($(this).attr("id").substring(3) != 1) {
						$(this).prop("checked", true).change();
					}
				});
				$("#gcb1").parent().click(function() {
					$(".groupFilterCheckbox").each(function() {
						if($(this).attr("id").substring(3) != 1) {
							$(this).prop("checked", true).change();
						}
					});
				});
				$(".groupFilterText").parent().click(function() {
					var filterButton = $(this);
					var checkbox = filterButton.find(".groupFilterCheckbox");
					if(checkbox.attr("id").substring(3) != 1) {
						checkbox.prop("checked", !checkbox.prop("checked")).change();
					}
				});
				$("#historyDate").Zebra_DatePicker({
					format:"d.m.Y",
					days: ["'._l('Sunday').'", "'._l('Monday').'", "'._l('Tuesday').'", "'._l('Wednesday').'", "'._l('Thursday').'", "'._l('Friday').'", "'._l('Saturday').'"],
					months: ["'._l('January').'", "'._l('February').'", "'._l('March').'", "'._l('April').'", "'._l('May').'", "'._l('June').'", "'._l('July').'", "'._l('August').'", "'._l('September').'", "'._l('October').'", "'._l('November').'", "'._l('December').'"],
					show_select_today: "'._l('Today').'",
					lang_clear_date: "'._l('Delete').'"
				});
			');
		
		// return
		return $sS->fetch('smarty.calendar.filterlinks.tpl');
	}
	
	
	/**
	 * callbackCompareFilter compares two arrays of filter entries by string (for usort)
	 * 
	 * @param object $first first filter entry
	 * @param object $second second filter entry
	 * @return int -1 if $first<$second, 0 if equal, 1 if $first>$second
	 */
	private function callbackCompareFilter($first,$second) {
	
		// compare dates
		if($first['name'] < $second['name']) {
			return -1;
		}
		if($first['name'] == $second['name']) {
			return 0;
		}
		if($first['name'] > $second['name']) {
			return 1;
		}
	}
}

?>
