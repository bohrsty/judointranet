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
 * class CalendarViewSchedule implements the control of the id "schedule" calendar page
 */
class CalendarViewSchedule extends CalendarView {
	
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
		
		// get preset
		$preset = new Preset($this->getGc()->get_config('schedule.presetId'), 'schedule');
		
		// assign data
		$scheduleListing = new CalendarScheduleListing();
		$this->smarty->assign('s', $scheduleListing->listingAsArray());
		
		// fetch pdf
		$pdfOut = $this->smarty->fetch('templates/schedules/'.$preset->get_path().'.tpl');
		
		// get HTML2PDF-object
		$pdf = new HTML2PDF('P', 'A4', 'de', true, 'UTF-8', array(0, 0, 0, 0));
		$pdf->writeHTML($pdfOut, false);
		
		// output (D=download; F=save on filesystem; S=string)
		// get filename
		$pdfFilename = $this->replace_umlaute(html_entity_decode($this->smarty->fetch('string:'.utf8_encode($preset->get_filename())), ENT_XHTML, 'UTF-8'));
		
		// prepare file
		$pdf->Output($pdfFilename, 'D');
		
		// exit after download
		exit;
	}
}

?>
