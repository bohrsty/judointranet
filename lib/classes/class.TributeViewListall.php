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
 * class TributeViewListall implements the control of the id "listall" tribute page
 */
class TributeViewListall extends TributeView {
	
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
		$this->getTpl()->assign('pagecaption', _l('list tributes').'&nbsp;'.$this->helpButton(HELP_MSG_TRIBUTELISTALL));
		
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
		$containerId = 'TributeListTable';
		
		// get Jtable object
		$jtable = new Jtable();
		// set settings
		$jtable->setActions('tribute.php', 'TributeListall', false, false, false);
		$jtable->setSetting('multisort', true);
		$jtable->setSetting('title', _l('Tributes'));
		$jtable->setSetting('toolbar', '{items: [{icon: \'img/jtable_pdf.png\', text: \''._l('Export as PDF').'\', click: function() {$.ajax({url:\'api/export/tribute/0/timestamp\',dataType: \'json\',cache: false}).done(function(response) {if(response.result ==\'OK\') { window.location.href = \'api/export/tribute/0\'; } else {var div = $(\'<div>\').appendTo($(\'body\')).text(response.message).dialog({autoOpen: true, modal: true, position: {my: \'center\',at: \'center\',of: window}, closeText: \''._l('close').'\', close: function() {div.remove();}, buttons: [{text:\'OK\', click: function() {div.dialog(\'close\');}}]});}});}},{icon: \'img/jtable_refresh.png\', text: \''._l('Refresh this table').'\', click: function() {$(\'#'.$containerId.'\').jtable(\'reload\')}}]}', false);
		// get JtableFields
		$jtfName = new JtableField('name');
		$jtfName->setTitle(_l('name'));
		$jtfName->setEdit(false);
		$jtfClub = new JtableField('club');
		$jtfClub->setTitle(_l('club'));
		$jtfClub->setEdit(false);
		$jtfClub->setWidth('1%');
		$jtfYear = new JtableField('year');
		$jtfYear->setTitle(_l('year'));
		$jtfYear->setEdit(false);
		$jtfYear->setWidth('1%');
		$jtfTestimonial = new JtableField('testimonial');
		$jtfTestimonial->setTitle(_l('testimonial'));
		$jtfTestimonial->setEdit(false);
		$jtfTestimonial->setWidth('5%');
		$jtfStartDate = new JtableField('start_date');
		$jtfStartDate->setTitle(_l('tribute start date'));
		$jtfStartDate->setEdit(false);
		$jtfStartDate->setWidth('1%');
		$jtfState = new JtableField('state');
		$jtfState->setTitle(_l('state'));
		$jtfState->setEdit(false);
		$jtfState->setWidth('1%');
		$jtfPlannedDate = new JtableField('planned_date');
		$jtfPlannedDate->setTitle(_l('planned date'));
		$jtfPlannedDate->setEdit(false);
		$jtfPlannedDate->setWidth('1%');
		$jtfDate = new JtableField('date');
		$jtfDate->setTitle(_l('tribute date'));
		$jtfDate->setEdit(false);
		$jtfDate->setWidth('1%');
		$jtfAdmin = new JtableField('admin');
		$jtfAdmin->setTitle(_l('admin'));
		$jtfAdmin->setEdit(false);
		$jtfAdmin->setSorting(false);
		$jtfAdmin->setWidth('1%');
		
		// add fields to $jtable
		$jtable->addField($jtfName);
		$jtable->addField($jtfClub);
		$jtable->addField($jtfYear);
		$jtable->addField($jtfTestimonial);
		$jtable->addField($jtfStartDate);
		$jtable->addField($jtfPlannedDate);
		$jtable->addField($jtfDate);
		$jtable->addField($jtfState);
		// add admin colum if logged in
		if($this->getUser()->get_loggedin() === true) {
			$jtable->addField($jtfAdmin);
		}
		
		// enable jtable in template
		$this->getTpl()->assign('jtable', true);
		
		// get java script config
		$jtableJscript = $jtable->asJavaScriptConfig();
		
		// prepare api calls
		// get random id
		$randomId = Object::getRandomId();
		
		// collect data for signature
		$data = array(
				'apiClass' => 'TributeSearch',
				'apiBase' => 'tribute.php',
				'randomId' => $randomId,
		);
		$_SESSION['api'][$randomId] = $data;
		$_SESSION['api'][$randomId]['time'] = time();
		$signedApi = base64_encode(hash_hmac('sha256', json_encode($data), $this->getGc()->get_config('global.apikey')));
		
		// add surrounding javascript
		$jquery = '$("#'.$containerId.'").jtable('.$jtableJscript.');';
		// add to jquery
		$this->add_jquery($jquery);
		$this->add_jquery('$("#'.$containerId.'").jtable("load");');
		$this->add_jquery('
			$("#year").change(function() {
				var val = $("#year").val();
				if(val != "") {
					$("#testimonial").val("");
					$("#state").val("");
					$("#'.$containerId.'").jtable("load", {select:"year", value:val});
				}
			});
			$("#testimonial").change(function() {
				var val = $("#testimonial").val();
				if(val != "") {
					$("#year").val("");
					$("#state").val("");
					$("#club").val("");
					$("#'.$containerId.'").jtable("load", {select:"testimonial", value:val});
				}
			});
			$("#state").change(function() {
				var val = $("#state").val();
				if(val != "") {
					$("#testimonial").val("");
					$("#year").val("");
					$("#club").val("");
					$("#'.$containerId.'").jtable("load", {select:"state", value:val});
				}
			});
			$("#club").change(function() {
				var val = $("#club").val();
				if(val != "") {
					$("#testimonial").val("");
					$("#year").val("");
					$("#state").val("");
					$("#'.$containerId.'").jtable("load", {select:"club", value:val});
				}
			});
			$("#reset").click(function() {
				$("#year").val("");
				$("#testimonial").val("");
				$("#search").val("");
				$("#club").val("");
				$("#'.$containerId.'").jtable("load");
			});
			$("#search").autocomplete({
				delay: 100,
				minLength: 3,
				html: true,
				source: "api/internal.php?id='.$randomId.'&signedApi='.$signedApi.'&action=list&provider=TributeSearch",
				select: function(event, ui) {
					window.location.href = ui.item.value;
				}
			});
			$("#search").focus();
		');
		
		// add template
		$this->smarty->assign('containerId', $containerId);
		$this->smarty->assign('testimonialId', 'testimonial');
		$this->smarty->assign('stateId', 'state');
		$this->smarty->assign('yearId', 'year');
		$this->smarty->assign('searchId', 'search');
		$this->smarty->assign('clubId', 'club');
		
		// return
		return $this->smarty->fetch('smarty.tribute.listall.tpl');
	}
}

?>
