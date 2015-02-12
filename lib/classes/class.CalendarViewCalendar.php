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
 * class CalendarViewCalendar implements the control of the id "calendar" calendar page
 */
class CalendarViewCalendar extends CalendarView {
	
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
		
		// define div id for container
		$containerId = 'calendarFullcalendar';
		
		// get random id
		$randomId = Object::getRandomId();
		
		// collect data for signature
		$data = array(
				'apiClass' => 'Fullcalendar',
				'apiBase' => 'calendar.php',
				'randomId' => $randomId,
		);
		$_SESSION['api'][$randomId] = $data;
		$_SESSION['api'][$randomId]['time'] = time();
		$signedApi = base64_encode(hash_hmac('sha256', json_encode($data), $this->getGc()->get_config('global.apikey')));
		
		// get java script config
		$jquery ='
				$(\'#'.$containerId.'\').fullCalendar({
					header: {
						left: \'prev,next today\',
						center: \'title\',
						right: \'month,basicWeek\'
					},
					defaultDate: \''.date('Y-m-d').'\',
					editable: false,
					eventLimit: true,
					weekNumbers: true,
					eventColor: \''.$this->getGc()->get_config('calendar.defaultColor').'\',
					eventTextColor: \'black\',
					events: {
						url: \'api/internal.php?id='.$randomId.'&signedApi='.$signedApi.'\',
						error: function() {
							$("#apiError").fadeIn();
						}
					},
					eventRender: function(event, element) {
						element.attr(\'title\', event.title);
						element.tooltip();
					},
					eventClick: function(event, jsEvent, view) {
						var dialogDiv = $(\'<div id="eventDialog" title="'._l('appointment').'" style="display: none"></div>\');
						dialogDiv.load(\'api/calendar/details/\'+event.id+\'?html=1\');
						$(\'body\').append(dialogDiv);
						dialogDiv.dialog({
							autoOpen: true,
							modal: true,
							position: { 
								my: \'center\', 
								at: \'center\', 
								of: window
							},
							closeText: \''._l('close').'\',
							minWidth: 600,
							minHeight: 250,
							maxHeight: 600,
							close: function(event, ui) {dialogDiv.remove();}
						});
					},
					loading: function(bool) {
						$("#loading").toggle(bool);
					}
				});
			';
		
		// add to jquery
		$this->add_jquery($jquery);
		
		// enable fullcalendar in template
		$this->getTpl()->assign('fullcalendar', true);
		
		// return
		return '<p id="apiError">'._l('error loading entries').'</p>'.PHP_EOL.'<p id="loading"><span>'._l('loading...').'</span></p>'.PHP_EOL.'<div id="'.$containerId.'"></div>';
	}
}

?>
