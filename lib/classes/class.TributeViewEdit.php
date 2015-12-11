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
 * class TributeViewNew implements the control of the id "edit" tribute page
 */
class TributeViewEdit extends TributeView {
	
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

		// get tid
		$tid = $this->get('tid');
		
		// check permissions
		if($this->getUser()->hasPermission('tribute', $tid, 'w')) {
			
			// pagecaption
			$this->getTpl()->assign('pagecaption', _l('edit tribute').'&nbsp;'.$this->helpButton(HELP_MSG_TRIBUTEEDIT));
			
			// get object
			$tribute = new Tribute($tid);
			
			// prepare form
			$form = new JudoIntranet_Zebra_Form(
					'tribute',			// id/name
					'post',				// method
					'tribute.php?id=edit&tid='.$tid	// action
			);
			
			// name
			$formIds['name'] = array('valueType' => 'string', 'type' => 'text',);
			$form->add(
					'label',		// type
					'labelName',	// id/name
					'name',			// for
					_l('name')	// label text
			);
			$name = $form->add(
					$formIds['name']['type'],		// type
					'name',		// id/name
					$tribute->getName()		// default
			);
			$name->set_rule(
					array(
							'required' => array(
									'error', _l('required name'),
							),
							'regexp' => array(
									$this->getGc()->get_config('name.regexp.zebra'),	// regexp
									'error',	// error variable
									_l('allowed chars').' ['.$this->getGc()->get_config('name.desc').']',	// message
							),
					)
			);
			$form->add(
					'note',			// type
					'noteName',		// id/name
					'name',			// for
					_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDNAME)	// note text
			);
		
		
		// club
		$clubs = Page::readClubs(true);
		$options = array();
		foreach($clubs as $no => $temp) {
			$options[$no] = $clubs[$no]['name'];
		}
		$formIds['club'] = array('valueType' => 'int', 'type' => 'select',);
		$form->add(
				'label',		// type
				'labelClub',	// id/name
				'club',			// for
				_l('club')	// label text
		);
		$state = $form->add(
				$formIds['club']['type'],	// type
				'club',		// id/name
				$tribute->getClub(),			// default
				array(		// attributes
				)
		);
		$state->add_options($options);
		$form->add(
				'note',		// type
				'noteClub',	// id/name
				'club',		// for
				_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDTYPE)	// note text
		);
			
			
			// plannedDate
			$formIds['plannedDate'] = array('valueType' => 'string', 'type' => 'date',);
			$form->add(
					'label',		// type
					'labelPlannedDate',	// id/name
					'plannedDate',			// for
					_l('planned tribute on')	// label text
			);
			$plannedDate = $form->add(
					$formIds['plannedDate']['type'],			// type
					'plannedDate',			// id/name
					$tribute->getPlannedDate('d.m.Y')	// default
			);
			// format/position
			$plannedDate->format('d.m.Y');
			$plannedDate->inside(false);
			// rules
			$plannedDate->set_rule(
					array(
							'date' => array(
									'error', _l('check date')
							),
					)
			);
			$form->add(
					'note',				// type
					'notePlannedDate',	// id/name
					'plannedDate',		// for
					_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDDATE)	// note text
			);
			
			
			// date
			$formIds['date'] = array('valueType' => 'string', 'type' => 'date',);
			$form->add(
					'label',		// type
					'labelDate',	// id/name
					'date',			// for
					_l('tribute given on')	// label text
			);
			$date = $form->add(
					$formIds['date']['type'],			// type
					'date',			// id/name
					$tribute->getDate('d.m.Y')	// default
			);
			// format/position
			$date->format('d.m.Y');
			$date->inside(false);
			// rules
			$date->set_rule(
					array(
							'date' => array(
									'error', _l('check date')
							),
					)
			);
			$form->add(
					'note',			// type
					'noteDate',		// id/name
					'date',			// for
					_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDDATE)	// note text
			);
		
		
			// state
			$states = Tribute::getAllStates(); 
			$options = array();
			foreach($states as $entry) {
				$options[$entry['id']] = $entry['name'];
			}
			$formIds['state'] = array('valueType' => 'int', 'type' => 'select',);
			$form->add(
					'label',		// type
					'labelState',	// id/name
					'state',			// for
					_l('state')	// label text
			);
			$state = $form->add(
					$formIds['state']['type'],	// type
					'state',		// id/name
					$tribute->getState(),			// default
					array(		// attributes
					)
			);
			$state->add_options($options);
			$state->set_rule(
					array(
							'required' => array(
									'error', _l('required state')
							),
					)
			);
			$form->add(
					'note',		// type
					'noteState',	// id/name
					'state',		// for
					_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDTYPE)	// note text
			);
			
			
			// testimonial
			$testimonials = Tribute::getAllTestimonials(true); 
			$options = array();
			foreach($testimonials as $entry) {
				$options[$entry['id']] = $entry['name'];
			}
			$formIds['testimonial'] = array('valueType' => 'int', 'type' => 'select',);
			$form->add(
					'label',		// type
					'labelTestimonial',	// id/name
					'testimonial',			// for
					_l('testimonial')	// label text
			);
			$testimonial = $form->add(
					$formIds['testimonial']['type'],	// type
					'testimonial',		// id/name
					$tribute->getTestimonialId(),			// default
					array(		// attributes
					)
			);
			$testimonial->add_options($options);
			$testimonial->set_rule(
					array(
							'required' => array(
									'error', _l('required testimonial')
							),
					)
			);
			$form->add(
					'note',		// type
					'noteTestimonial',	// id/name
					'testimonial',		// for
					_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDTYPE)	// note text
			);
			
			// description
			$formIds['description'] = array('valueType' => 'string', 'type' => 'textarea',);
			$form->add(
					'label',		// type
					'labelDescription',	// id/name
					'description',	// for
					_l('description')	// label text
			);
			$description = $form->add(
					$formIds['description']['type'],		// type
					'description',	// id/name
					$tribute->getDescription()	// default
			);
			$description->set_rule(
					array(
							'regexp' => array(
									$this->getGc()->get_config('textarea.regexp.zebra'),
									'error',
									_l('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
							),
					)
			);
			$form->add(
					'note',			// type
					'noteDescription',	// id/name
					'description',		// for
					_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDCONTENT)	// note text
			);
			
			
			// permissions
			$result = $this->zebraAddPermissions($form, 'tribute');
			$form = $result['form'];
			$permissionConfig['ids'] = $result['formIds'];
			$permissionConfig['iconRead'] = $result['iconRead'];
			$permissionConfig['iconEdit'] = $result['iconEdit'];
			
			
			// submit-button
			$form->add(
					'submit',		// type
					'buttonSubmit',	// id/name
					_l('save')	// value
			);
			
			// prepare tribute file upload
			$sTributeFile = new JudoIntranetSmarty();
			// get files
			$sTributeFile->assign('tributeFiles', Tribute::getAllFiles($tid, true));
			
			// get types for form
			$fileTypes = TributeFile::getAllFileTypes();
			$sTributeFile->assign('fileTypes', $fileTypes);
			
			// activate validationEnginge
			$this->getTpl()->assign('validationEngine', true);
			
			// prepare api signature
			// get random id
			$randomId = Object::getRandomId();
				
			// collect data for signature
			$data = array(
					'apiClass' => 'TributeFileupload',
					'apiBase' => 'tribute.php',
					'randomId' => $randomId,
			);
			$_SESSION['api'][$randomId] = $data;
			$_SESSION['api'][$randomId]['time'] = time();
			$signedApi = base64_encode(hash_hmac('sha256', json_encode($data), $this->getGc()->get_config('global.apikey')));
				
			// activate jquery upload file
			$this->getTpl()->assign('jqueryUploadFile', true);
			// add java script
			$this->add_jquery('
				$("#showUpload").click(function() {
					$("#uploadForm").slideToggle();
				});
					$("#showFiles").click(function() {
					$("#tributeFiles").slideToggle();
				});
				var uploadMessage = $(\'<div id="uploadMessage"></div>\');
				var uploadObject = $("#uploadFile").uploadFile({
					url: "api/internal.php?id='.$randomId.'&signedApi='.$signedApi.'",
					returnType: "json",
					dynamicFormData: function() {
						return {"tid": '.$tid.', "fileType": $("#fileType").val()};
					},
					allowedTypes: "pdf",
					acceptFiles: "application/pdf",
					uploadButtonClass: "button",
					dragDrop: false,
					autoSubmit: false,
					multiple: false,
					maxFileCount: 1,
					onSuccess: function(files, response, xhr, pd) {
						$("body").append(uploadMessage);
						var windowWidth = $(window).width();
						uploadMessage.css({
								"background-color": (response.result == "ERROR" ? "#ff915f" : "#91ff5f"),
								"border-color": (response.result == "ERROR" ? "red" : "green"),
								"margin-left": windowWidth * 0.1 / 2,
								"top": $(document).scrollTop() + 20
							})
							.text(response.message)
							.fadeIn();
						setTimeout(function() {
							uploadMessage.fadeOut(3000);
						}, 5000);
						$("#fileType").val("");
						if(response.result == "OK") {
							var countFiles = + $(\'#countFiles\').text();
							$(\'#countFiles\').text(countFiles + 1);
							$(\'#noFiles\').remove();
							var deleteFile = $(\'<img src="img/common_delete.png" id="deleteFile\'+response.data.id+\'" class="deleteFile" alt="'._l('delete').'" title="'._l('delete file').'" />\');
							var newFile = $(\'<div id="fileEntry\'+response.data.id+\'" class="fileEntry"></div>\');
							var newImg = $(\'<img src="api/filesystem/tribute_file/\'+response.data.id+\'?thumb=1" alt="\'+response.data.name+\'" title="\'+response.data.type+\'" />\');
							var br = $(\'<br />\');
							var newLink = $(\'<a href="api/filesystem/tribute_file/\'+response.data.id+\'" title="\'+response.data.fullname+\'">\'+response.data.name+\'</a>\');
							var script = $(\'<script>\').attr("type", "text/javascript").text(\'$("#deleteFile\'+response.data.id+\'").click(function() {$("#confirmDelete").dialog("option", "buttons",[{"text": "'.html_entity_decode(_l('Delete'), ENT_XHTML).'", "click": function() {$.ajax({method: "POST",url: "api/filesystem/tribute_file/\'+response.data.id+\'/delete",data: {"confirmed":true},dataType: "json",success: function(data) {if(data.result == "OK") {$("#fileEntry\'+response.data.id+\'").fadeOut(1000, function(){$(this).remove()});var countFiles = + $("#countFiles").text();$("#countFiles").text(countFiles - 1);} else {var deleteMessage = $("<div>").attr("id", "deleteMessage");$("body").append(deleteMessage);var windowWidth = $(window).width();deleteMessage.css({"margin-left": windowWidth * 0.1 / 2,"top": $(document).scrollTop() + 20}).text(data.message).fadeIn();setTimeout(function() {deleteMessage.fadeOut(3000);}, 5000);}}});$(this).dialog("close");}},{"text": "'.html_entity_decode(_l('Cancel'), ENT_XHTML).'", "click": function() {$(this).dialog("close");}}]);$("#confirmDelete").dialog("open");});\');
							newFile.append(deleteFile).append(newImg).append(br).append(newLink).append(script);
							$(\'#tributeFiles\').append(newFile);
						}
					},
					onError: function(files, status, message, pd) {
						$("#fileType").val("");
						$("body").append(uploadMessage);
						var windowWidth = $(window).width();
						uploadMessage.css({
								"background-color":"#ff915f",
								"border-color": "red",
								"margin-left": windowWidth * 0.1 / 2,
								"top": $(document).scrollTop() + 20
							})
							.text(message)
							.fadeIn();
						setTimeout(function() {
							uploadMessage.fadeOut(3000);
						}, 5000);
					},
					dragDropStr: "<span><b>Dateien hier hineinziehen (Drag &amp; Drop)</b></span>",
					abortStr: "Abbrechen",
					cancelStr: "Entfernen",
					deletelStr: "L&ouml;schen",
					doneStr: "Fertig",
					multiDragErrorStr: "Mehrere Dateien per Drag &amp; Drop ist nicht erlaubt.",
					extErrorStr: "nicht erlaubt. Erlaubte Dateitypen: ",
					duplicateErrorStr: "nicht erlaubt. Datei existiert bereits.",
					sizeErrorStr: "nicht erlaubt. Maximale Dateigr&ouml;&szlig;e: ",
					uploadErrorStr: "Hochladen ist nicht erlaubt.",
					maxFileCountErrorStr: " nicht erlaubt. Maximale Anzahl Dateien: ",
					downloadStr: "Herunterladen",
					uploadFolder: "tmp"
				});
				$("#upload").click(function(e) {
					e.preventDefault();
					var value = $("#fileType").val();
					if(value == "") {
						$("#fileType").validationEngine("showPrompt", "'._l('* Please select an option').'", "error", "topLeft", true);
					} else {
						uploadObject.startUpload();
					}
				});
			');
			
			// prepare tribute history for "postForm"
			$sPostForm = new JudoIntranetSmarty();
			// get all history entries
			$tributeHistory = Tribute::getAllHistory($tribute->getId(), true);
			// assign to template
			$sPostForm->assign('tributeHistory', $tributeHistory);
			
			// get types for form
			$typeOptions = TributeHistory::getAllHistoryTypes();
			$sPostForm->assign('typeOptions', $typeOptions);
			
			// prepare api signature
			// get random id
			$randomId = Object::getRandomId();
			
			// collect data for signature
			$data = array(
					'apiClass' => 'TributeHistoryEntry',
					'apiBase' => 'tribute.php',
					'randomId' => $randomId,
				);
			$_SESSION['api'][$randomId] = $data;
			$_SESSION['api'][$randomId]['time'] = time();
			$signedApi = base64_encode(hash_hmac('sha256', json_encode($data), $this->getGc()->get_config('global.apikey')));
			
			
			// add javascript for new entries
			$this->add_jquery('
				$("#newHistoryEntryButton").click(function() {
					$("#newHistoryEntryForm").slideToggle();
				});
				var submit = $("#historySubmit");
				
				$("#historyDate").Zebra_DatePicker({
					show_icon:false,
					format:"d.m.Y",
					days: ["'._l('Sunday').'", "'._l('Monday').'", "'._l('Tuesday').'", "'._l('Wednesday').'", "'._l('Thursday').'", "'._l('Friday').'", "'._l('Saturday').'"],
					months: ["'._l('January').'", "'._l('February').'", "'._l('March').'", "'._l('April').'", "'._l('May').'", "'._l('June').'", "'._l('July').'", "'._l('August').'", "'._l('September').'", "'._l('October').'", "'._l('November').'", "'._l('December').'"],
					show_select_today: "'._l('Today').'",
					lang_clear_date: "'._l('Delete').'"
				});
				$("#historyDate").hide();
				$("#changeDate").click(function() {
					$("#historyDate").toggle();
				});
				$("#historySubmit").click(function() {
					$.ajax({
						url: "api/internal.php?id='.$randomId.'&signedApi='.$signedApi.'",
						type: "POST",
						cache: false,
						data: {"tributeId":'.$tid.',"historySubject":$("#historySubject").val(),"historyType":$("#historyType").val(),"historyContent":$("#historyContent").val(),"historyDate":$("#historyDate").val()},
						beforeSend: function(){
							submit.val("'._l('saving').'...").attr("disabled", "disabled");
						},
						success: function(data){
							var item = $(data).hide().show("fade", 800);
							$("#tributeHistoryEntries").append(item);
							$("#historySubject").val("");
							$("#historyType").val("");
							$("#historyContent").val("");
							$("#historyDate").val("");
							submit.val("'._l('save').'").removeAttr("disabled");
						},
						error: function(e){
							alert(e);
						}
					});
				});
			');
			
			// add javascript for history entries
			$this->add_jquery('
				$(".divHistorySubject").click(function() {
					$(this).parent().find(".divHistoryContent").slideToggle();
				});
			');
			
			// assign pre and post form HTML
			$this->smarty->assign('preForm', '');
			$this->smarty->assign('postForm',
				$sTributeFile->fetch('smarty.tribute.fileUpload.tpl'). 
				$sPostForm->fetch('smarty.tributeHistory.tpl')
			);
			
			// validate form
			if($form->validate()) {
				
				// get form data
				$data = $this->getFormValues($formIds);
				// get form permissions
				$permissions = $this->getFormPermissions($permissionConfig['ids']);
				
				// update tribute
				$tribute->update(
						array(
								'name' => $data['name'],
								'club' => $data['club'],
								'plannedDate' => ($data['plannedDate'] != '' ? $data['plannedDate'] : null),
								'date' => ($data['date'] != '' ? $data['date'] : null),
								'state' => $data['state'],
								'testimonialId' => $data['testimonial'],
								'description' => $data['description'],
							)
					);
				
				// write tribute
				$tribute->writeDb();
				
				// write permissions
				$tribute->dbDeletePermission();
				$tribute->dbWritePermission($permissions);
				
				// set js redirection
				$this->jsRedirectTimeout('tribute.php?id=edit&tid='.$tid);
				
				// return message
				return _l('Saved successfully.');
			} else {
				return $form->render('lib/zebraTemplate.php', true, array($formIds, 'smarty.zebra.permissions.tpl', $permissionConfig,$this->smarty,));
			}
		} else {
			throw new NotAuthorizedException($this);
		}
	}
}

?>
