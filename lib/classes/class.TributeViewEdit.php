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
					$tribute->getPlannedDate()	// default
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
					$tribute->getDate()	// default
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
			
				$("#historySubmit").click(function() {
					$.ajax({
						url: "api/internal.php?id='.$randomId.'&signedApi='.$signedApi.'",
						type: "POST",
						cache: false,
						data: {"tributeId":'.$tid.',"historySubject":$("#historySubject").val(),"historyType":$("#historyType").val(),"historyContent":$("#historyContent").val()},
						beforeSend: function(){
							submit.val("'._l('saving').'...").attr("disabled", "disabled");
						},
						success: function(data){
							var item = $(data).hide().show("slide", 800);
							$("#tributeHistoryEntries").append(item);
							$("#historySubject").val("");
							$("#historyType").val("");
							$("#historyContent").val("");
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
			$this->smarty->assign('postForm', $sPostForm->fetch('smarty.tributeHistory.tpl'));
			
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
								'plannedDate' => ($data['plannedDate'] != '' ? $data['plannedDate'] : null),
								'date' => ($data['date'] != '' ? $data['date'] : null),
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
