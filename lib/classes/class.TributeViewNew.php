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
 * class TributeViewNew implements the control of the id "new" tribute page
 */
class TributeViewNew extends TributeView {
	
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
		$this->getTpl()->assign('pagecaption', _l('plan new tribute').'&nbsp;'.$this->helpButton(HELP_MSG_TRIBUTENEW));
		
		// prepare form
		$form = new JudoIntranet_Zebra_Form(
				'tribute',			// id/name
				'post',				// method
				'tribute.php?id=new'	// action
		);
		
		// name
		$formIds['name'] = array('valueType' => 'string', 'type' => 'text',);
		$form->add(
				'label',		// type
				'labelName',	// id/name
				'name',			// for
				_l('name'),	// label text
				array('inside' => true,)	// label inside
		);
		$name = $form->add(
				$formIds['name']['type'],		// type
				'name'		// id/name
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
				'',			// default
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
		
		
		// startDate
		$formIds['startDate'] = array('valueType' => 'string', 'type' => 'date',);
		$form->add(
				'label',		// type
				'labelStartDate',	// id/name
				'startDate',			// for
				_l('Started planning tribute on')	// label text
		);
		$startDate = $form->add(
				$formIds['startDate']['type'],			// type
				'startDate',			// id/name
				null	// default
		);
		// format/position
		$startDate->format('d.m.Y');
		$startDate->inside(false);
		// rules
		$startDate->set_rule(
				array(
						'date' => array(
								'error', _l('check date')
						),
				)
		);
		$form->add(
				'note',				// type
				'noteStartDate',	// id/name
				'startDate',		// for
				_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDDATE)	// note text
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
				null	// default
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
				null	// default
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
				'',			// default
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
				'',			// default
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
				_l('description'),	// label text
				array('inside' => true)
		);
		$description = $form->add(
				$formIds['description']['type'],		// type
				'description'	// id/name
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
		
		// assign pre and post form HTML
		$this->smarty->assign('preForm', '');
		$this->smarty->assign('postForm', '');
		
		// validate form
		if($form->validate()) {
			
			// get form data
			$data = $this->getFormValues($formIds);
			// get form permissions
			$permissions = $this->getFormPermissions($permissionConfig['ids']);
			
			// check startDate not to be in future
			if(date('U', strtotime($data['startDate'])) > strtotime('today 00:00')) {
				$data['startDate'] = date('Y-m-d');
			}
			
			// new tribute
			$tribute = new Tribute(
					array(
							'name' => $data['name'],
							'club' => $data['club'],
							'startDate' => ($data['startDate'] != '' ? date('Y-m-d', strtotime($data['startDate'])) : null),
							'plannedDate' => ($data['plannedDate'] != '' ? date('Y-m-d', strtotime($data['plannedDate'])) : null),
							'date' => ($data['date'] != '' ? date('Y-m-d', strtotime($data['date'])) : null),
							'state' => $data['state'],
							'testimonialId' => $data['testimonial'],
							'description' => $data['description'],
							'valid' => '1',
						)
				);
			
			// write tribute
			$newId = $tribute->writeDb();
			
			// add initial history entry
			$tributeHistoryArray = array(
					'tributeId' => $newId,
					'type' => -1,
					'subject' => _l('Started planning tribute'),
					'content' => _l('Started planning tribute'),
					'valid' => '1',
				);
			// check startdate for history entry (and only in past)
			if(date('U', strtotime($data['startDate'])) < strtotime('today 00:00')) {
				$tributeHistoryArray['lastModified'] = ($data['startDate'] != '' ? date('Y-m-d', strtotime($data['startDate'])) : date('Y-m-d')); 
			}
			$tributeHistory = new TributeHistory($tributeHistoryArray);
			$tributeHistory->writeDb();
			
			// write permissions
			$tribute->dbDeletePermission();
			$tribute->dbWritePermission($permissions);
			
			// set js redirection
			$this->jsRedirectTimeout('tribute.php?id=edit&tid='.$newId);
			
			// return message
			return _l('Saved successfully.');
		} else {
			return $form->render(__DIR__.'/../zebraTemplate.php', true, array($formIds, 'smarty.zebra.permissions.tpl', $permissionConfig,$this->smarty,));
		}
	}
}

?>
