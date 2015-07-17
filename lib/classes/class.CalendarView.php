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
 * class CalendarView implements the control of the calendar-page
 */
class CalendarView extends PageView {
	
	/*
	 * class-variables
	 */
	
	/*
	 * getter/setter
	 */
	
	/*
	 * constructor/destructor
	 */
	public function __construct() {
		
		// setup parent
		parent::__construct();
	}
	
	/*
	 * methods
	 */
	/**
	 * init chooses the functionality by using $_GET['id']
	 * 
	 * @return void
	 */
	public function init() {
		
		// set pagename
		$this->getTpl()->assign('pagename',_l('calendar'));
		
		// init helpmessages
		$this->initHelp();
		
		// switch $_GET['id'] if set
		if($this->get('id') !== false) {
			
			// check permissions
			$naviId = Navi::idFromFileParam(basename($_SERVER['SCRIPT_FILENAME']), $this->get('id'));
			if($this->getUser()->hasPermission('navi', $naviId)) {
				
				switch($this->get('id')) {
					
					case 'listall':
						
						// pagecaption
						$this->getTpl()->assign('pagecaption',_l('listall').'&nbsp;'.$this->helpButton(HELP_MSG_CALENDARLISTALL));
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('calendar: listall')));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
						
						$calendarViewListall = new CalendarViewListall();
						$this->getTpl()->assign('main', $calendarViewListall->show());
					break;
					
					case 'new':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('calendar: new')));
						$this->getTpl()->assign('main', $this->newEntry());
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
					break;
					
					case 'edit':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('calendar: edit')));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
						
						// if cid does not exist, error
						if(Calendar::check_id($this->get('cid'))) {
							// smarty
							$this->getTpl()->assign('main', $this->edit($this->get('cid')));
						} else {
							throw new CidNotExistsException($this, $this->get('cid'));
						}
					break;
					
					case 'delete':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('calendar: delete')));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
						
						// if cid does not exist, error
						if(Calendar::check_id($this->get('cid'))) {
							// smarty
							$this->getTpl()->assign('main', $this->delete($this->get('cid')));
						} else {
							throw new CidNotExistsException($this, $this->get('cid'));
						}
					break;
					
					case 'calendar':
						
						// pagecaption
						$this->getTpl()->assign('pagecaption', _l('calendarview').'&nbsp;'.$this->helpButton(HELP_MSG_CALENDARCALENDAR));
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('calendar: calendar')));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', false);
						
						$calendarViewCalendar = new CalendarViewCalendar();
						$this->getTpl()->assign('main', $calendarViewCalendar->show());
					break;
					
					case 'schedule':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('calendar: schedule')));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', false);
						
						$calendarViewSchedule = new CalendarViewSchedule();
						$this->getTpl()->assign('main', $calendarViewSchedule->show());
					break;
					
					default:
						
						// id set, but no functionality
						throw new GetUnknownIdException($this, $this->get('id'));
					break;
				}
			} else {
				
				// error not authorized
				throw new NotAuthorizedException($this);
			}
		} else {
			
			// id not set
			// smarty-title
			$this->getTpl()->assign('title', $this->title(_l('calendar')));
			// smarty-pagecaption
			$this->getTpl()->assign('pagecaption', $this->defaultContent()); 
			// smarty-main
			$this->getTpl()->assign('main', '');
			// smarty-jquery
			$this->getTpl()->assign('jquery', true);
			// smarty-hierselect
			$this->getTpl()->assign('hierselect', false);
		}
		
		// global smarty
		$this->showPage('smarty.main.tpl');
	}
	
	
	
	
	
	
	
	/**
	 * newEntry() creates the "new-entry"-form and handle its response
	 * 
	 * @return string html-string with the "new-entry"-form
	 */
	private function newEntry() {
		
		// smarty-templates
		$sD = new JudoIntranetSmarty();
		
		// prepare return
		$return = '';
		
		// form
		$form = new Zebra_Form(
				'newCalendarEntry',			// id/name
				'post',						// method
				'calendar.php?id=new'		// action
			);
		// set language
		$form->language('deutsch');
		// set docktype xhtml
		$form->doctype('xhtml');
		
		// elements
		// checkbox isExternal
		$formIds['isExternal'] = array('valueType' => 'int', 'type' => 'checkbox', 'default' => 1);
		$form->add(
				'label',		// type
				'labelIsExternal',	// id/name
				'isExternal',		// for
				_l('is external appointment')	// label text
			);
		$public = $form->add(
				$formIds['isExternal']['type'],		// type
				'isExternal',						// id/name
				'1',							// value
				null							// default
			);
		$form->add(
				'note',			// type
				'noteIsExternal',	// id/name
				'public',		// for
				_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDISEXTERNAL)	// note text
			);
		// hide not used fields
		$this->add_jquery('
			function slideExternal() {
					var shortname = $(\'#shortname\').parent();
					if(shortname.prop(\'tagName\') == \'SPAN\') {
						shortname.parent().slideToggle();
					} else {
						shortname.slideToggle();
					}
					$(\'#filter\').parent().slideToggle();
					$(\'#type\').parent().toggleClass(\'even\');
					var content = $(\'#entryContent\').parent();
					if(content.prop(\'tagName\') == \'SPAN\') {
						content.parent().toggleClass(\'even\');
					} else {
						content.toggleClass(\'even\');
					}
					var city = $(\'#city\').parent();
					if(city.prop(\'tagName\') == \'SPAN\') {
						city.parent().toggleClass(\'even\');
					} else {
						city.toggleClass(\'even\');
					}
				}
				$(\'#isExternal_1\').on(\'change\', function() {
					slideExternal();
					if($(\'#isExternal_1\').prop(\'checked\') == true) {
						$(\'#type\').val(\'external\');
						$(\'#color\').setColor(\''.$this->getGc()->get_config('calendar.defaultExternalColor').'\');
						$(\'#color\').val(\''.$this->getGc()->get_config('calendar.defaultExternalColor').'\');
					} else {
						$(\'#type\').val(\'\');
						$(\'#color\').setColor(\''.$this->getGc()->get_config('calendar.defaultColor').'\');
						$(\'#color\').val(\''.$this->getGc()->get_config('calendar.defaultColor').'\');
					}
				});
		');
		
		
		// date
		$formIds['date'] = array('valueType' => 'string', 'type' => 'date',);
		$form->add(
				'label',		// type
				'labelDate',	// id/name
				'date',			// for
				_l('start date')	// label text
			);
		$date = $form->add(
						$formIds['date']['type'],			// type
						'date',			// id/name
						date('d.m.Y')	// default
			);
		// format/position
		$date->format('d.m.Y');
		$date->inside(false);
		// rules
		$date->set_rule(
			array(
				'required' => array(
						'error', _l('required date'),
					),
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

		// enddate
		$formIds['endDate'] = array('valueType' => 'string', 'type' => 'date',);
		$form->add(
				'label',		// type
				'labelEndDate',	// id/name
				'endDate',		// for
				_l('end date')	// label text
		);
		$endDate = $form->add(
				$formIds['endDate']['type'],	// type
				'endDate'			// id/name
		);
		// format/position
		$endDate->format('d.m.Y');
		$endDate->inside(false);
		// rules
		$endDate->set_rule(
				array(
						'date' => array(
								'error', _l('check date')
						),
				)
		);
		$form->add(
				'note',			// type
				'noteEndDate',		// id/name
				'endDate',			// for
				_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDDATE)	// note text
		);
		
		
		// color
		$formIds['color'] = array('valueType' => 'string', 'type' => 'text',);
		$form->add(
				'label',		// type
				'labelColor',	// id/name
				'color',			// for
				_l('color')	// label text
		);
		$color = $form->add(
				$formIds['color']['type'],		// type
				'color',				// id/name
				$this->getGc()->get_config('calendar.defaultColor')	// default
		);
		$form->add(
				'note',			// type
				'noteColor',	// id/name
				'color',			// for
				_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDCOLOR)	// note text
		);
		$this->getTpl()->assign('simpleColor', true);
		$this->add_jquery('
			$(\'#color\').simpleColor({
				columns: 3,
				cellWidth: 20,
				cellHeight: 20,
				colors:
					'.$this->getGc()->get_config('calendar.colors').',
				livePreview: true,	
			});
		');
		
		
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
		
		
		// shortname
		$formIds['shortname'] = array('valueType' => 'string', 'type' => 'text',);
		$form->add(
				'label',		// type
				'labelShortname',	// id/name
				'shortname',			// for
				_l('shortname'),	// label text
				array('inside' => true,)	// label inside
			);
		$shortname = $form->add(
						$formIds['shortname']['type'],		// type
						'shortname'		// id/name
			);
		$shortname->set_rule(
				array(
						'regexp' => array(
								$this->getGc()->get_config('name.regexp.zebra'),	// regexp
								'error',	// error variable
								_l('allowed chars').' ['.$this->getGc()->get_config('name.desc').']',	// message
							),
					)
			);
		$form->add(
				'note',				// type
				'noteShortname',	// id/name
				'shortname',		// for
				_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDSHORTNAME)	// note text
			);
		
		
		// type
		$options = Calendar::return_types();
		$formIds['type'] = array('valueType' => 'int', 'type' => 'select',);
		$form->add(
				'label',		// type
				'labelType',	// id/name
				'type',			// for
				_l('type')	// label text
			);
		$type = $form->add(
				$formIds['type']['type'],	// type
				'type',		// id/name
				'',			// default
				array(		// attributes
					)
			);
		$type->add_options($options);
		$type->set_rule(
				array(
						'required' => array(
								'error', _l('required type')
							),
					)
			);
		$form->add(
				'note',		// type
				'noteType',	// id/name
				'type',		// for
				_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDTYPE)	// note text
			);
		
		
		// entry_content
		$formIds['entryContent'] = array('valueType' => 'string', 'type' => 'textarea',);
		$form->add(
				'label',		// type
				'labelContent',	// id/name
				'entryContent',	// for
				_l('content/description'),	// label text
				array('inside' => true)
			);
		$content = $form->add(
				$formIds['entryContent']['type'],		// type
				'entryContent'	// id/name
			);
		$content->set_rule(
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
				'noteContent',	// id/name
				'entryContent',		// for
				_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDCONTENT)	// note text
			);
		
		// city
		$formIds['city'] = array('valueType' => 'string', 'type' => 'text',);
		$form->add(
				'label',		// type
				'labelCity',	// id/name
				'city',			// for
				_l('city'),	// label text
				array('inside' => true,)	// label inside
			);
		$name = $form->add(
						$formIds['city']['type'],		// type
						'city'		// id/name
			);
		$name->set_rule(
				array(
						'regexp' => array(
								$this->getGc()->get_config('name.regexp.zebra'),	// regexp
								'error',	// error variable
								_l('allowed chars').' ['.$this->getGc()->get_config('name.desc').']',	// message
							),
					)
			);
		$form->add(
				'note',			// type
				'noteCity',		// id/name
				'city',			// for
				_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDCITY)	// note text
			);
		
		
		// filter
		$options = Filter::allExistingFilter('name');
		$formIds['filter'] = array('valueType' => 'array', 'type' => 'select',);
		$form->add(
				'label',		// type
				'labelFilter',	// id/name
				'filter',		// for
				_l('filtering groups (multi select)')	// label text
			);
		$filter = $form->add(
				$formIds['filter']['type'],	// type
				'filter[]',					// id/name
				1,							// default
				array(						// attributes
						'multiple' => 'multiple',
						'size' => 5,
					)
			);
		$filter->add_options($options, true);
		$form->add(
				'note',			// type
				'noteFilter',	// id/name
				'filter',		// for
				_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDSORT)	// note text
			);
		
		
		// checkbox public
		$formIds['public'] = array('valueType' => 'int', 'type' => 'checkbox', 'default' => 1);
		$form->add(
				'label',		// type
				'labelPublic',	// id/name
				'public',		// for
				_l('public access')	// label text
			);
		$public = $form->add(
				$formIds['public']['type'],		// type
				'public',						// id/name
				'1',							// value
				null							// default
			);
		$form->add(
				'note',			// type
				'notePublic',	// id/name
				'public',		// for
				_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDISPUBLIC)	// note text
			);
		
		// permissions
		$result = $this->zebraAddPermissions($form, 'calendar');
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
		
		// validate
		if($form->validate()) {
			
			// get form data
			$data = $this->getFormValues($formIds);
			// get form permissions
			$permissions = $this->getFormPermissions($permissionConfig['ids']);
			
			// add public access
			if($data['public'] == 1) {
				$permissions[0]['group'] = Group::fakePublic();
				$permissions[0]['value'] = 'r';
			}
			
			// check end date
			if(strtotime($data['endDate']) <= strtotime($data['date'])) {
				$data['endDate'] = '';
			}
			
			// create calendar
			$calendar = new Calendar(array(
								'date' => $data['date'],
								'endDate' => ($data['endDate'] != '' ? $data['endDate'] : null),
								'name' => $data['name'],
								'shortname' => $data['shortname'],
								'type' => $data['type'],
								'content' => $data['entryContent'],
								'city' => $data['city'],
								'color' => ($data['color'] != '' ? $data['color'] : null),
								'isExternal' => $data['isExternal'] == 1,
								'filter' => $data['filter'],
								'valid' => 1,
								)
				);
							
			// write to db
			$calendar->write_db('new');
						
			// create cached file
			$fid = File::idFromCache('calendar|'.$calendar->get_id());
			if($fid !== false) {
				$calendar->createCachedFile($fid);
			}
			
			// write permissions
			$calendar->dbDeletePermission();
			$calendar->dbWritePermission($permissions);
			
			// smarty
			$sCD = new JudoIntranetSmarty();
			$sCD->assign('data', $calendar->detailsToHtml());
			$sCD->assign('files', array());
			$sCD->assign('attached', _l('<b>attached files</b>'));
			$sCD->assign('none', _l('- none -'));
			
			// pagecaption
			$this->getTpl()->assign('pagecaption',_l('create new entry'));
			
			// set js redirection
			$this->jsRedirectTimeout('calendar.php?id=listall');
			
			return $sCD->fetch('smarty.calendar.details.tpl');
		} else {
			// pagecaption
			$this->getTpl()->assign('pagecaption',_l('create new entry').'&nbsp;'.$this->helpButton(HELP_MSG_CALENDARNEW));
			return $form->render('lib/zebraTemplate.php', true, array($formIds, 'smarty.zebra.permissions.tpl', $permissionConfig,));
		}
	}
	
	
	
	
	
	
	
	/**
	 * callbackCompareCalendars($first, $second) compares two calendar-objects by date (for uksort)
	 * 
	 * @param object $first first calendar-objects
	 * @param object $second second calendar-object
	 * @return int -1 if $first<$second, 0 if equal, 1 if $first>$second
	 */
	public function callbackCompareCalendars($first,$second) {
	
		// compare dates
		if($first->get_date() < $second->get_date()) {
			return -1;
		}
		if($first->get_date() == $second->get_date()) {
			return 0;
		}
		if($first->get_date() > $second->get_date()) {
			return 1;
		}
	}
	
	
	
	
	
	
	
	/**
	 * edit edits the given entry
	 * 
	 * @param int $cid entry-id for calendar
	 * @return string html-string
	 */
	private function edit($cid) {
		
		// check permissions
		if($this->getUser()->hasPermission('calendar', $cid, 'w')) {
			
			// smarty-templates
			$sD = new JudoIntranetSmarty();
							
			// get calendar-object
			$calendar = new Calendar($cid);
			
			// pagecaption
			$this->getTpl()->assign('pagecaption',_l('edit entry').": \"$cid\" (".$calendar->get_name().")");
			
			// prepare return
			$return = '';
			
			// form
			$form = new Zebra_Form(
				'editCalendarEntry',			// id/name
				'post',				// method
				'calendar.php?id=edit&cid='.$cid		// action
			);
			// set language
			$form->language('deutsch');
			// set docktype xhtml
			$form->doctype('xhtml');
			
			$now_year = (int) date('Y');
			$year_min = $now_year;
			$year_max = $now_year + 3;
			
			// get filter
			$allFilter = Filter::allFilterOf('calendar', $calendar->get_id());
			$filterIds = array();
			foreach($allFilter as $filter) {
				$filterIds[] = $filter->getId();
			}
			
			// elements
			// checkbox isExternal
			$formIds['isExternal'] = array('valueType' => 'int', 'type' => 'checkbox', 'default' => 1);
			$form->add(
					'label',		// type
					'labelIsExternal',	// id/name
					'isExternal',		// for
					_l('is external appointment')	// label text
				);
			$public = $form->add(
					$formIds['isExternal']['type'],		// type
					'isExternal',						// id/name
					'1',							// value
					($calendar->getIsExternal() === true ? array('checked' => true) : null)		// default
				);
			$form->add(
					'note',			// type
					'noteIsExternal',	// id/name
					'public',		// for
					_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDISEXTERNAL)	// note text
				);
			// hide not used fields
			$this->add_jquery('
				function slideExternal() {
					var shortname = $(\'#shortname\').parent();
					if(shortname.prop(\'tagName\') == \'SPAN\') {
						shortname.parent().slideToggle();
					} else {
						shortname.slideToggle();
					}
					$(\'#filter\').parent().slideToggle();
					$(\'#type\').parent().toggleClass(\'even\');
					var content = $(\'#entryContent\').parent();
					if(content.prop(\'tagName\') == \'SPAN\') {
						content.parent().toggleClass(\'even\');
					} else {
						content.toggleClass(\'even\');
					}
					var city = $(\'#city\').parent();
					if(city.prop(\'tagName\') == \'SPAN\') {
						city.parent().toggleClass(\'even\');
					} else {
						city.toggleClass(\'even\');
					}
				}
				if($(\'#isExternal_1\').prop(\'checked\') == true) {
					slideExternal();
				}
				$(\'#isExternal_1\').on(\'change\', function() {
					if($(\'#isExternal_1\').prop(\'checked\') == true) {
						if(confirm(\''._l('Saving this as "external" appointment will delete any existing announcement! Continue?').'\')) {
							slideExternal();
							$(\'#type\').val(\'external\');
							$(\'#color\').setColor(\''.$this->getGc()->get_config('calendar.defaultExternalColor').'\');
						} else {
							$(\'#isExternal_1\').prop(\'checked\', false);
						}
					} else {
						slideExternal();
						$(\'#type\').val(\'\');
						$(\'#color\').setColor(\''.$this->getGc()->get_config('calendar.defaultColor').'\');
					}
				});
			');
			
			
			
			// date
			$formIds['date'] = array('valueType' => 'string', 'type' => 'date',);
			$form->add(
					'label',		// type
					'labelDate',	// id/name
					'date',			// for
					_l('start date')	// label text
				);
			$date = $form->add(
							$formIds['date']['type'],			// type
							'date',			// id/name
							$calendar->get_date('d.m.Y')	// default
				);
			// format/position
			$date->format('d.m.Y');
			$date->inside(false);
			// rules
			$date->set_rule(
				array(
					'required' => array(
							'error', _l('required date'),
						),
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
			

			// enddate
			$formIds['endDate'] = array('valueType' => 'string', 'type' => 'date',);
			$form->add(
					'label',		// type
					'labelEndDate',	// id/name
					'endDate',			// for
					_l('end date')	// label text
			);
			$endDate = $form->add(
					$formIds['endDate']['type'],			// type
					'endDate',			// id/name
					$calendar->getEndDate('d.m.Y')	// default
			);
			// format/position
			$endDate->format('d.m.Y');
			$endDate->inside(false);
			// rules
			$endDate->set_rule(
					array(
							'date' => array(
									'error', _l('check date')
							),
					)
			);
			$form->add(
					'note',			// type
					'noteEndDate',		// id/name
					'endDate',			// for
					_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDDATE)	// note text
			);
			
			
			// color
			$formIds['color'] = array('valueType' => 'string', 'type' => 'text',);
			$form->add(
					'label',		// type
					'labelColor',	// id/name
					'color',			// for
					_l('color')	// label text
			);
			$color = $form->add(
					$formIds['color']['type'],		// type
					'color',				// id/name
					$calendar->getColor()	// default
			);
			$form->add(
					'note',			// type
					'noteColor',	// id/name
					'color',			// for
					_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDCOLOR)	// note text
			);
			$this->getTpl()->assign('simpleColor', true);
			$this->add_jquery('
				$(\'#color\').simpleColor({
					columns: 3,
					cellWidth: 20,
					cellHeight: 20,
					colors:
						'.$this->getGc()->get_config('calendar.colors').',
					livePreview: true,	
				});
			');
			
			
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
							$calendar->get_name()	// default
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
			
			
			// shortname
			$formIds['shortname'] = array('valueType' => 'string', 'type' => 'text',);
			$form->add(
					'label',		// type
					'labelShortname',	// id/name
					'shortname',			// for
					_l('shortname')	// label text
				);
			$shortname = $form->add(
							$formIds['shortname']['type'],		// type
							'shortname',		// id/name
							$calendar->get_shortname()	// default
				);
			$shortname->set_rule(
					array(
							'regexp' => array(
									$this->getGc()->get_config('name.regexp.zebra'),	// regexp
									'error',	// error variable
									_l('allowed chars').' ['.$this->getGc()->get_config('name.desc').']',	// message
								),
						)
				);
			$form->add(
					'note',				// type
					'noteShortname',	// id/name
					'shortname',		// for
					_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDSHORTNAME)	// note text
				);
			
			
			// type
			$options = Calendar::return_types();
			$formIds['type'] = array('valueType' => 'int', 'type' => 'select',);
			$form->add(
					'label',		// type
					'labelType',	// id/name
					'type',			// for
					_l('event type')	// label text
				);
			$type = $form->add(
					$formIds['type']['type'],	// type
					'type',		// id/name
					$calendar->return_type(),	// default
					array(		// attributes
						)
				);
			$type->add_options($options);
			$type->set_rule(
					array(
							'required' => array(
									'error', _l('required type')
								),
						)
				);
			$form->add(
					'note',		// type
					'noteType',	// id/name
					'type',		// for
					_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDTYPE)	// note text
				);
			
			
			// entry_content
			$formIds['entryContent'] = array('valueType' => 'string', 'type' => 'textarea',);
			$form->add(
					'label',		// type
					'labelContent',	// id/name
					'entryContent',	// for
					_l('content/description')	// label text
				);
			$content = $form->add(
					$formIds['entryContent']['type'],		// type
					'entryContent',	// id/name
					$calendar->get_content()	// default
				);
			$content->set_rule(
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
					'noteContent',	// id/name
					'content',		// for
					_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDCONTENT)	// note text
				);
			
			// check if announcement exists, display field only if not
			if($calendar->get_preset_id() == 0) {
				// city
				$formIds['city'] = array('valueType' => 'string', 'type' => 'text',);
				$form->add(
						'label',		// type
						'labelCity',	// id/name
						'city',			// for
						_l('city')	// label text
					);
				$name = $form->add(
								$formIds['city']['type'],		// type
								'city',		// id/name
								$calendar->getCity()		// default
					);
				$name->set_rule(
						array(
								'regexp' => array(
										$this->getGc()->get_config('name.regexp.zebra'),	// regexp
										'error',	// error variable
										_l('allowed chars').' ['.$this->getGc()->get_config('name.desc').']',	// message
									),
							)
					);
				$form->add(
						'note',			// type
						'noteCity',		// id/name
						'city',			// for
						_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDCITY)	// note text
					);
			}
			
			
			// filter
			$options = Filter::allExistingFilter('name');
			$formIds['filter'] = array('valueType' => 'array', 'type' => 'select',);
			$form->add(
					'label',		// type
					'labelFilter',	// id/name
					'filter',		// for
					_l('filtering groups (multi select)')	// label text
				);
			$filter = $form->add(
					$formIds['filter']['type'],	// type
					'filter[]',					// id/name
					$filterIds,					// default
					array(						// attributes
							'multiple' => 'multiple',
							'size' => 5,
						)
				);
			$filter->add_options($options, true);
			$form->add(
					'note',			// type
					'noteFilter',	// id/name
					'filter',		// for
					_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDSORT)	// note text
				);
			
			
			// checkbox public
			$formIds['public'] = array('valueType' => 'int', 'type' => 'checkbox', 'default' => 1);
			$form->add(
					'label',		// type
					'labelPublic',	// id/name
					'public',		// for
					_l('public access')	// label text
				);
			$public = $form->add(
					$formIds['public']['type'],		// type
					'public',						// id/name
					'1',							// value
					($calendar->isPermittedFor(0) ? array('checked' => 'checked') : null)
				);
			$form->add(
					'note',			// type
					'notePublic',	// id/name
					'public',		// for
					_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDISPUBLIC)	// note text
				);
			
			// permissions
			$result = $this->zebraAddPermissions($form, 'calendar', $calendar->get_id());
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
			
			// validate
			if($form->validate()) {
				
				// get form data
				$data = $this->getFormValues($formIds);
				// get form permissions
				$permissions = $this->getFormPermissions($permissionConfig['ids']);
								
				// check filter
				if(!isset($data['filter'])) {
					$data['filter'] = array();
				}
				
				// add public access
				if($data['public'] == '1') {
					$permissions[0]['group'] = Group::fakePublic();
					$permissions[0]['value'] = 'r';
				}
				
				// check end date
				if(strtotime($data['endDate']) <= strtotime($data['date'])) {
					$data['endDate'] = '';
				}
				
				// set values
				$calendar_new = array(
						'date' => $data['date'],
						'endDate' => ($data['endDate'] != '' ? $data['endDate'] : null),
						'name' => $data['name'],
						'shortname' => $data['shortname'],
						'type' => $data['type'],
						'content' => $data['entryContent'],
						'color' => ($data['color'] != '' ? $data['color'] : null),
						'isExternal' => $data['isExternal'] == 1,
						'filter' => $data['filter'],
						'valid' => 1,
				);
				
				// delete announcement and files if external
				if($data['isExternal'] == 1) {
					
					// get preset
					$preset = new Preset($calendar->get_preset_id(),'calendar',$calendar->getId());
					
					// get fields
					$fields = $preset->get_fields();
					
					// delete values of the fields
					if(Calendar::check_ann_value($calendar->get_id()) === true) {
							
						foreach($fields as $field) {
					
							// delete value
							$field->deleteValue();
						}
					}
					
					// set preset 0
					$calendar_new['preset_id'] = 0;
					
					// delete cached file
					$fid = File::idFromCache('calendar|'.$calendar->get_id());
					if($fid !== false) {
						File::delete($fid);
					}
				}
				
				// check if city was set
				if(isset($data['city'])) {
					$calendar_new['city'] = $data['city'];
				}
					
				// update calendar
				$calendar->update($calendar_new);
				
				// put entry to output
				// smarty-template
				$sCD = new JudoIntranetSmarty();
				
				// write entry
				$calendar->write_db('update');
					
				// create cached file
				$fid = File::idFromCache('calendar|'.$calendar->get_id());
				if($fid !== false) {
					$calendar->createCachedFile($fid);
				}
			
				// write permissions
				$calendar->dbDeletePermission();
				$calendar->dbWritePermission($permissions);
				
				// create file objects
				$fileIds = File::attachedTo('calendar', $cid);
				$fileObjects = array();
				foreach($fileIds as $id) {
					$fileObjects[] = new File($id);
				}
				
				// set js redirection
				$this->jsRedirectTimeout('calendar.php?id=listall');
				
				// smarty
				$sCD->assign('data', $calendar->detailsToHtml());
				$sCD->assign('files', $fileObjects);
				$sCD->assign('attached', _l('<b>files attached</b>'));
				$sCD->assign('none', _l('- none -'));
				$sCD->assign('fileHref', 'file.php?id=download&fid=');
				return $sCD->fetch('smarty.calendar.details.tpl');
			} else {
				return $form->render('lib/zebraTemplate.php', true, array($formIds, 'smarty.zebra.permissions.tpl', $permissionConfig,));
			}
		} else {
			throw new NotAuthorizedException($this);
		}
	}
	
	
	
	
	
	
	
	/**
	 * delete deletes the given entry
	 * 
	 * @param int $cid entry-id for calendar
	 * @return string html-string
	 */
	protected function delete($cid) {
	
		// pagecaption
		$this->getTpl()->assign('pagecaption',_l('delete entry').": $cid");
		
		// check permissions
		if($this->getUser()->hasPermission('calendar', $cid, 'w')) {
				
			// prepare return
			$output = '';
			
			// smarty-templates
			$sConfirmation = new JudoIntranetSmarty();
			
			// form
			$form = new Zebra_Form(
				'formConfirm',			// id/name
				'post',				// method
				'calendar.php?id=delete&cid='.$this->get('cid')		// action
			);
			// set language
			$form->language('deutsch');
			// set docktype xhtml
			$form->doctype('xhtml');
			
			// add button
			$form->add(
				'submit',		// type
				'buttonSubmit',	// id/name
				_l('yes'),	// value
				array('title' => _l('deletes entry'))
			);
			
			// smarty-link
			$link = array(
							'params' => 'class="submit"',
							'href' => 'calendar.php?id=listall',
							'title' => _l('cancels deletion'),
							'content' => _l('cancel')
						);
			$sConfirmation->assign('link', $link);
			$sConfirmation->assign('spanparams', 'id="cancel"');
			$sConfirmation->assign('message', _l('you really want to delete').'&nbsp;'.$this->helpButton(HELP_MSG_DELETE));
			$sConfirmation->assign('form', $form->render('', true));
			
			// validate
			if($form->validate()) {
			
				// get calendar-object
				$calendar = new Calendar($cid);
				
				// disable entry
				$calendar->update(array('valid' => 0));
				
				// smarty
				$sConfirmation->assign('message', _l('entry successful deleted'));
				$sConfirmation->assign('form', '');
				
				// write entry
				$calendar->write_db('update');
					
				// delete cached file
				$fid = File::idFromCache('calendar|'.$calendar->get_id());
				if($fid !== false) {
					File::delete($fid);
				}
				// delete attachments
				File::deleteAttachedFiles('calendar',$calendar->get_id());
				// delete results
				foreach(Result::getIdsForCalendar($cid) as $resultId) {
					Result::delete($resultId);
				}
				
				// set js redirection
				$this->jsRedirectTimeout('calendar.php?id=listall');
			}
			
			// smarty return
			return $sConfirmation->fetch('smarty.confirmation.tpl');
		} else {
			throw new NotAuthorizedException($this);
		}
	}
}



?>
