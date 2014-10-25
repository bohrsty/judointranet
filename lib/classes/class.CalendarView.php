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
		try {
			parent::__construct();
		} catch(Exception $e) {
			
			// handle error
			$this->getError()->handle_error($e);
		}
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
		$this->getTpl()->assign('pagename',parent::lang('calendar'));
		
		// init helpmessages
		$this->initHelp();
		
		// switch $_GET['id'] if set
		if($this->get('id') !== false) {
			
			// check permissions
			$naviId = Navi::idFromFileParam(basename($_SERVER['SCRIPT_FILENAME']), $this->get('id'));
			if($this->getUser()->hasPermission('navi', $naviId)) {
				
				switch($this->get('id')) {
					
					case 'listall':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(parent::lang('calendar: listall')));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
						
						// prepare dates
						$from = date('Y-m-d', strtotime('yesterday'));
						$to = '2100-01-01';

						// check $_GET['from'] and $_GET['to']
						if($this->get('from') !== false) {
							$from = $this->get('from');
						}
						if($this->get('to') !== false) {
							$to = $this->get('to');
						}
						$this->getTpl()->assign('main', $this->listall($to,$from));
					break;
					
					case 'new':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(parent::lang('calendar: new')));
						$this->getTpl()->assign('main', $this->newEntry());
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
					break;
					
					case 'details':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(parent::lang('calendar: details')));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', false);
						
						// if cid does not exist, error
						if(Calendar::check_id($this->get('cid'))) {
							// smarty
							$this->getTpl()->assign('main', $this->details($this->get('cid')));
						} else {
							
							// error
							$errno = $this->getError()->error_raised('CidNotExists','details',$this->get('cid'));
							$this->getError()->handle_error($errno);$this->add_output(array('main' => $this->getError()->to_html($errno)),true);
							// smarty
							$this->getTpl()->assign('main', $this->getError()->to_html($errno));
						}
					break;
					
					case 'edit':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(parent::lang('calendar: edit')));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
						
						// if cid does not exist, error
						if(Calendar::check_id($this->get('cid'))) {
							// smarty
							$this->getTpl()->assign('main', $this->edit($this->get('cid')));
						} else {
							
							// error
							$errno = $this->getError()->error_raised('CidNotExists','edit',$this->get('cid'));
							$this->getError()->handle_error($errno);
							// smarty
							$this->getTpl()->assign('main', $this->getError()->to_html($errno));
						}
					break;
					
					case 'delete':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(parent::lang('calendar: delete')));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
						
						// if cid does not exist, error
						if(Calendar::check_id($this->get('cid'))) {
							// smarty
							$this->getTpl()->assign('main', $this->delete($this->get('cid')));
						} else {
							
							// error
							$errno = $this->getError()->error_raised('CidNotExists','delete',$this->get('cid'));
							$this->getError()->handle_error($errno);
							// smarty
							$this->getTpl()->assign('main', $this->getError()->to_html($errno));
						}
					break;
					
					default:
						
						// id set, but no functionality
						$errno = $this->getError()->error_raised('GETUnkownId','entry:'.$this->get('id'),$this->get('id'));
						$this->getError()->handle_error($errno);
						
						// smarty
						$this->getTpl()->assign('title', '');
						$this->getTpl()->assign('main', $this->getError()->to_html($errno));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', false);
					break;
				}
			} else {
				
				// error not authorized
				throw new NotAuthorizedException($this);
			}
		} else {
			
			// id not set
			// smarty-title
			$this->getTpl()->assign('title', $this->title(parent::lang('calendar')));
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
	 * listall lists all calendarentries less/equal than $time in table (paged)
	 * shows only entrys for which the user has sufficient permissions
	 * 
	 * @param int $timeto unix-timestamp from that the entrys are shown
	 * @param int $timefrom unix-timestamp from that the entrys are shown
	 * @return void
	 */
	private function listall($timeto,$timefrom) {
		
		// pagecaption
		$this->getTpl()->assign('pagecaption',parent::lang('listall').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_CALENDARLISTALL));
			
		// prepare return
		$output = $tr_out = $th_out = '';
		
		// read all entries
		$entries = Filter::filterItems($this->get('filter'), 'calendar', $timefrom, $timeto);
		
		// sort calendarentries
		usort($entries,array($this,'callbackCompareCalendars'));
		
		// smarty-templates
		$sListall = new JudoIntranetSmarty();
		// sortlinks
		$sListall->assign('filterlinks', $this->getFilterLinks($this->get('id')));
		
		// smarty
		$sTh = array(
				'date' => _l('date'),
				'name' => _l('event'),
				'city' => _l('city'),
				'show' => _l('show'),
				'admin' => _l('tasks')
			);

		$sListall->assign('th', $sTh);
		// loggedin? admin links
		$sListall->assign('loggedin', $this->getUser()->get_loggedin());
		
		// get public
		$publicUser = new User(false);
		// walk through entries
		$counter = 0;
		// smarty
		$sList = array();
		foreach($entries as $no => $entry) {
			
			// get draft value
			$draftValue = 1;
			if($entry->get_preset_id() != 0) {
				$draftValue = Calendar::getDraftValue($entry->get_preset_id(), $entry->get_id());
			}
			
			// check if valid
			if($entry->get_valid() == 1) {
				
				// smarty
				$sList[$counter] = array(
						'name' => array(
								'href' => 'calendar.php?id=details&cid='.$entry->get_id(),
								'title' => $entry->get_name(),
								'name' => $entry->get_name(),
								'public' => $publicUser->hasPermission('calendar', $entry->get_id(), 'r') && $this->getUser()->get_loggedin(),
							),
						'date' => $entry->get_date('d.m.Y'),
						'city' => $entry->getCity(),
						
					);
				
				// details and pdf if announcement
				$sList[$counter]['show'][0] = array(
						'href' => '',
						'title' => '',
						'src' => '',
						'alt' => '',
					);
				$sList[$counter]['show'][1] = array(
						'href' => '',
						'title' => '',
						'src' => '',
						'alt' => '',
					);
				if(	($entry->get_preset_id() != 0
						&& Calendar::check_ann_value($entry->get_id(),$entry->get_preset_id()) === true)
					&& ($draftValue == 0
						|| ($draftValue == 1 && $this->getUser()->get_loggedin()))) {
					
					// set draft for filenames and translation
					$draftFilename = '';
					$draftTranslate = '';
					if($draftValue == 1) {
						$draftFilename = '_draft_'.$this->getUser()->get_lang();
						$draftTranslate = ' draft';
					}
					
					$sList[$counter]['show'][0] = array(
							'href' => 'announcement.php?id=details&cid='.$entry->get_id().'&pid='.$entry->get_preset_id(),
							'title' => parent::lang('show announcement'.$draftTranslate),
							'src' => 'img/ann_details'.$draftFilename.'.png',
							'alt' => parent::lang('show announcement'.$draftTranslate),
						);
					$sList[$counter]['show'][1] = array(
							'href' => 'file.php?id=cached&table=calendar&tid='.$entry->get_id(),
							'title' => parent::lang('show announcement pdf'.$draftTranslate),
							'src' => 'img/ann_pdf'.$draftFilename.'.png',
							'alt' => parent::lang('show announcement pdf'.$draftTranslate),
						);
				}
				
				// add attached file info
				if($entry->getAdditionalFields()['files'] > 0) {
					
					$sList[$counter]['show'][2] = array(
							'href' => 'calendar.php?id=details&cid='.$entry->get_id(),
							'title' => parent::lang('existing attachments'),
							'src' => 'img/attachment_info.png',
							'alt' => parent::lang('existing attachments'),
						);
				} else {
					
					$sList[$counter]['show'][2] = array(
							'href' => '',
							'title' => '',
							'src' => '',
							'alt' => '',
						);
				}
				
				// add attached result info
				if($entry->getAdditionalFields()['results'] > 0) {
					
					$sList[$counter]['show'][3] = array(
							'href' => 'result.php?id=list&cid='.$entry->get_id(),
							'title' => parent::lang('result attached', true),
							'src' => 'img/result_info.png',
							'alt' => parent::lang('result attached', true),
						);
				} else {
					
					$sList[$counter]['show'][3] = array(
							'href' => '',
							'title' => '',
							'src' => '',
							'alt' => '',
						);
				}
					
				// add admin
				// logged in and write permissions;
				$admin = $this->getUser()->get_loggedin() && $this->getUser()->hasPermission('calendar', $entry->get_id(), 'w'); 
				if($admin === true) {
					
					// prepare admin help
					$helpListAdmin = $this->getHelp()->getMessage(HELP_MSG_CALENDARLISTADMIN);
					// smarty
					// edit
					$sList[$counter]['admin'][] = array(
							'href' => 'calendar.php?id=edit&cid='.$entry->get_id(),
							'title' => parent::lang('edits entry'),
							'src' => 'img/edit.png',
							'alt' => parent::lang('edit'),
							'admin' => $admin
						);
					// delete
					$sList[$counter]['admin'][] = array(
							'href' => 'calendar.php?id=delete&cid='.$entry->get_id(),
							'title' => parent::lang('deletes entry'),
							'src' => 'img/delete.png',
							'alt' => parent::lang('delete'),
							'admin' => $admin
						);
					// attachment
					$sList[$counter]['admin'][] = array(
							'href' => 'file.php?id=attach&table=calendar&tid='.$entry->get_id(),
							'title' => parent::lang('attach file(s)'),
							'src' => 'img/attachment.png',
							'alt' => parent::lang('attach file(s)'),
							'admin' => $admin
						);
					
					if($entry->get_preset_id() == 0) {
						
						// smarty
						$sList[$counter]['annadmin'][] = array(
								'href' => '',
								'title' => '',
								'src' => '',
								'alt' => '',
								'preset' => $entry->get_preset_id(),
								'form' => $this->readPresetForm($entry),
							);
					} else {
						
						// get new or edit
						$action = '';
						if(Calendar::check_ann_value($entry->get_id(),$entry->get_preset_id()) === true) {
							$action = 'edit';
						} else {
							$action = 'new';
						}
						
						// smarty
						// edit/new
						$sList[$counter]['annadmin'][] = array(
								'href' => 'announcement.php?id='.$action.'&cid='.$entry->get_id().'&pid='.$entry->get_preset_id(),
								'title' => parent::lang('edits announcement'),
								'src' => 'img/ann_edit.png',
								'alt' => parent::lang('edit announcement'),
								'preset' => $entry->get_preset_id(),
								'form' => ''
							);
						// delete
						$sList[$counter]['annadmin'][] = array(
								'href' => 'announcement.php?id=delete&cid='.$entry->get_id().'&pid='.$entry->get_preset_id(),
								'title' => parent::lang('delete announcement'),
								'src' => 'img/ann_delete.png',
								'alt' => parent::lang('deletes announcement'),
								'preset' => $entry->get_preset_id(),
								'form' => ''
							);
						// add result
						$sList[$counter]['annadmin'][] = array(
								'href' => 'result.php?id=new&cid='.$entry->get_id(),
								'title' => parent::lang('result new', true),
								'src' => 'img/res_new.png',
								'alt' => parent::lang('result new', true),
								'preset' => $entry->get_preset_id(),
								'form' => ''
							);
					}
				} else {
					
					// smarty
					$sList[$counter]['admin'][] = array(
							'href' => '',
							'title' => '',
							'src' => '',
							'alt' => '',
							'admin' => $admin
						);
				}
				
				// increment counter
				$counter++;
			} else {
				
				// deleted items
			}
		}
		
		// smarty
		$sListall->assign('list', $sList);
		if(isset($helpListAdmin)) {
			$sListall->assign('helpListAdmin', $helpListAdmin);
		}
		
		// smarty-return
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
		
		// prepare resetlinks
		$r = array(
				array( // all
						'href' => 'calendar.php?id='.$getid,
						'title' => parent::lang('reset all filter'),
						'content' => parent::lang('reset all filter')
					),
				array( // dates
						'href' => 'calendar.php?id='.$getid.$filter,
						'title' => parent::lang('reset date filter'),
						'content' => parent::lang('reset date filter')
					),
				array( // groups
						'href' => 'calendar.php?id='.$getid.$from.$to,
						'title' => parent::lang('reset group filter'),
						'content' => parent::lang('reset group filter')
					)
			);
		$sS->assign('r', $r);
		$sS->assign('resetFilter', parent::lang('reset filter'));
		
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
					'title' => parent::lang($name),
					'content' => parent::lang($name)
				);
		}
		$sS->assign('dl', $dl);
		$sS->assign('dateFilter', parent::lang('date filter'));
		
		// add group-links
		$allFilter = Filter::allExistingFilter();
		
		// create links
		$gl = array();
		foreach($allFilter as $filter) {
			
			// smarty
			$gl[] = array(
					'href' => 'calendar.php?id='.$getid.'&filter='.$filter->getId().$from.$to,
					'title' => $filter->getName(),
					'content' => $filter->getName(),
				);
		}
		usort($gl, array($this, 'callbackCompareFilter'));
		$sS->assign('gl', $gl);
		$sS->assign('groupFilter', parent::lang('group filter'));
		$sS->assign('chooseDate', parent::lang('select date'));
		$sS->assign('chooseGroup', parent::lang('select group'));
		
		// add slider-link
		$link = array(
				'params' => 'id="toggleFilter" class="spanLink"',
				'title' => parent::lang('show filter'),
				'content' => parent::lang('show filter'),
				'help' => $this->getHelp()->getMessage(HELP_MSG_CALENDARLISTSORTLINKS),
			);
		$sS->assign('link', $link);
		
		// assign dialog title
		$sS->assign('dialogTitle', parent::lang('select filter'));
		
		// add jquery-ui dialog
		$dialog = array(
			'dialogClass' => 'filterDialog',
			'openerClass' => 'toggleFilter',
			'autoOpen' => 'false',
			'effect' => 'fade',
			'duration' => 300,
			'modal' => 'true',
			'closeText' => parent::lang('close filter'),
			'height' => 500,
			'maxHeight' => 500,
			'width' => 500,
		);
		// smarty jquery
		$sJsToggleSlide = new JudoIntranetSmarty();
		$sJsToggleSlide->assign('dialog', $dialog);
		$this->add_jquery($sJsToggleSlide->fetch('smarty.js-dialog.tpl'));
		$this->add_jquery('$( "#filterTabs" ).tabs();');
		
		// return
		return $sS->fetch('smarty.calendar.filterlinks.tpl');
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
		// date
		$formIds['date'] = array('valueType' => 'string', 'type' => 'date',);
		$form->add(
				'label',		// type
				'labelDate',	// id/name
				'date',			// for
				parent::lang('date')	// label text
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
						'error', parent::lang('required date'),
					),
				'date' => array(
						'error', parent::lang('check date')
					),
				)
			);
		$form->add(
				'note',			// type
				'noteDate',		// id/name
				'date',			// for
				parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDDATE)	// note text
			);
		
		
		// name
		$formIds['name'] = array('valueType' => 'string', 'type' => 'text',);
		$form->add(
				'label',		// type
				'labelName',	// id/name
				'name',			// for
				parent::lang('name'),	// label text
				array('inside' => true,)	// label inside
			);
		$name = $form->add(
						$formIds['name']['type'],		// type
						'name'		// id/name
			);
		$name->set_rule(
				array(
						'required' => array(
								'error', parent::lang('required name'),
							),
						'regexp' => array(
								$this->getGc()->get_config('name.regexp.zebra'),	// regexp
								'error',	// error variable
								parent::lang('allowed chars').' ['.$this->getGc()->get_config('name.desc').']',	// message
							),
					)
			);
		$form->add(
				'note',			// type
				'noteName',		// id/name
				'name',			// for
				parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDNAME)	// note text
			);
		
		
		// shortname
		$formIds['shortname'] = array('valueType' => 'string', 'type' => 'text',);
		$form->add(
				'label',		// type
				'labelShortname',	// id/name
				'shortname',			// for
				parent::lang('shortname'),	// label text
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
								parent::lang('allowed chars').' ['.$this->getGc()->get_config('name.desc').']',	// message
							),
					)
			);
		$form->add(
				'note',				// type
				'noteShortname',	// id/name
				'shortname',		// for
				parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDSHORTNAME)	// note text
			);
		
		
		// type
		$options = Calendar::return_types();
		$formIds['type'] = array('valueType' => 'int', 'type' => 'select',);
		$form->add(
				'label',		// type
				'labelType',	// id/name
				'type',			// for
				parent::lang('type')	// label text
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
								'error', parent::lang('required type')
							),
					)
			);
		$form->add(
				'note',		// type
				'noteType',	// id/name
				'type',		// for
				parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDTYPE)	// note text
			);
		
		
		// entry_content
		$formIds['entryContent'] = array('valueType' => 'string', 'type' => 'textarea',);
		$form->add(
				'label',		// type
				'labelContent',	// id/name
				'entryContent',	// for
				parent::lang('content/description'),	// label text
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
								parent::lang('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
							),
					)
			);
		$form->add(
				'note',			// type
				'noteContent',	// id/name
				'entryContent',		// for
				parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDCONTENT)	// note text
			);
		
		// city
		$formIds['city'] = array('valueType' => 'string', 'type' => 'text',);
		$form->add(
				'label',		// type
				'labelCity',	// id/name
				'city',			// for
				parent::lang('city'),	// label text
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
								parent::lang('allowed chars').' ['.$this->getGc()->get_config('name.desc').']',	// message
							),
					)
			);
		$form->add(
				'note',			// type
				'noteCity',		// id/name
				'city',			// for
				parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDCITY)	// note text
			);
		
		
		// filter
		$options = Filter::allExistingFilter('name');
		$formIds['filter'] = array('valueType' => 'array', 'type' => 'select',);
		$form->add(
				'label',		// type
				'labelFilter',	// id/name
				'filter',		// for
				parent::lang('filtering groups (multi select)')	// label text
			);
		$filter = $form->add(
				$formIds['filter']['type'],	// type
				'filter[]',					// id/name
				'',							// default
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
				parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDSORT)	// note text
			);
		
		
		// checkbox public
		$formIds['public'] = array('valueType' => 'int', 'type' => 'checkbox', 'default' => 1);
		$form->add(
				'label',		// type
				'labelPublic',	// id/name
				'public',		// for
				parent::lang('public access')	// label text
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
				parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDISPUBLIC)	// note text
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
				parent::lang('save')	// value
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
			
			// create calendar
			$calendar = new Calendar(array(
								'date' => $data['date'],
								'name' => $data['name'],
								'shortname' => $data['shortname'],
								'type' => $data['type'],
								'content' => $data['entryContent'],
								'city' => $data['city'],
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
			$sCD->assign('attached', parent::lang('attached files'));
			$sCD->assign('none', parent::lang('- none -'));
			
			// pagecaption
			$this->getTpl()->assign('pagecaption',parent::lang('create new entry'));
			
			return $sCD->fetch('smarty.calendar.details.tpl');
		} else {
			// pagecaption
			$this->getTpl()->assign('pagecaption',parent::lang('create new entry').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_CALENDARNEW));
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
	 * details returns the details of a calendar-entry as html-string
	 * 
	 * @param int $cid entry-id for calendar
	 * @return string html-string with the details of the calendar entry
	 */
	private function details($cid) {
	
		// pagecaption
		$this->getTpl()->assign('pagecaption', parent::lang('details'));
		
		// check permissions
		if($this->getUser()->hasPermission('calendar', $cid)) {
				
			// get calendar-object
			$calendar = new Calendar($cid);
			
			// smarty-template
			$sCD = new JudoIntranetSmarty();
			
			// create file objects
			$fileIds = File::attachedTo('calendar', $cid);
			$fileObjects = array();
			foreach($fileIds as $id) {
				$fileObjects[] = new File($id);
			}
			
			// smarty
			$sCD->assign('data', $calendar->detailsToHtml());
			$sCD->assign('files', $fileObjects);
			$sCD->assign('attached', parent::lang('files attached<br />'));
			$sCD->assign('none', parent::lang('- none -'));
			$sCD->assign('fileHref', 'file.php?id=download&fid=');
			return $sCD->fetch('smarty.calendar.details.tpl');
		} else {
			
			// error
			$errno = $this->getError()->error_raised('NotAuthorized','entry:'.$this->get('id'),$this->get('id'));
			$this->getError()->handle_error($errno);
			return $this->getError()->to_html($errno);
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
			$this->getTpl()->assign('pagecaption',parent::lang('edit entry').": \"$cid\" (".$calendar->get_name().")");
			
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
			// date
			$formIds['date'] = array('valueType' => 'string', 'type' => 'date',);
			$form->add(
					'label',		// type
					'labelDate',	// id/name
					'date',			// for
					parent::lang('date')	// label text
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
							'error', parent::lang('required date'),
						),
					'date' => array(
							'error', parent::lang('check date')
						),
					)
				);
			$form->add(
					'note',			// type
					'noteDate',		// id/name
					'date',			// for
					parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDDATE)	// note text
				);
			
			
			// name
			$formIds['name'] = array('valueType' => 'string', 'type' => 'text',);
			$form->add(
					'label',		// type
					'labelName',	// id/name
					'name',			// for
					parent::lang('name')	// label text
				);
			$name = $form->add(
							$formIds['name']['type'],		// type
							'name',		// id/name
							$calendar->get_name()	// default
				);
			$name->set_rule(
					array(
							'required' => array(
									'error', parent::lang('required name'),
								),
							'regexp' => array(
									$this->getGc()->get_config('name.regexp.zebra'),	// regexp
									'error',	// error variable
									parent::lang('allowed chars').' ['.$this->getGc()->get_config('name.desc').']',	// message
								),
						)
				);
			$form->add(
					'note',			// type
					'noteName',		// id/name
					'name',			// for
					parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDNAME)	// note text
				);
			
			
			// shortname
			$formIds['shortname'] = array('valueType' => 'string', 'type' => 'text',);
			$form->add(
					'label',		// type
					'labelShortname',	// id/name
					'shortname',			// for
					parent::lang('shortname')	// label text
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
									parent::lang('allowed chars').' ['.$this->getGc()->get_config('name.desc').']',	// message
								),
						)
				);
			$form->add(
					'note',				// type
					'noteShortname',	// id/name
					'shortname',		// for
					parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDSHORTNAME)	// note text
				);
			
			
			// type
			$options = Calendar::return_types();
			$formIds['type'] = array('valueType' => 'int', 'type' => 'select',);
			$form->add(
					'label',		// type
					'labelType',	// id/name
					'type',			// for
					parent::lang('event type')	// label text
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
									'error', parent::lang('required type')
								),
						)
				);
			$form->add(
					'note',		// type
					'noteType',	// id/name
					'type',		// for
					parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDTYPE)	// note text
				);
			
			
			// entry_content
			$formIds['entryContent'] = array('valueType' => 'string', 'type' => 'textarea',);
			$form->add(
					'label',		// type
					'labelContent',	// id/name
					'entryContent',	// for
					parent::lang('content/description')	// label text
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
									parent::lang('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
								),
						)
				);
			$form->add(
					'note',			// type
					'noteContent',	// id/name
					'content',		// for
					parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDCONTENT)	// note text
				);
			
			// check if announcement exists, display field only if not
			if($calendar->get_preset_id() == 0) {
				// city
				$formIds['city'] = array('valueType' => 'string', 'type' => 'text',);
				$form->add(
						'label',		// type
						'labelCity',	// id/name
						'city',			// for
						parent::lang('city')	// label text
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
										parent::lang('allowed chars').' ['.$this->getGc()->get_config('name.desc').']',	// message
									),
							)
					);
				$form->add(
						'note',			// type
						'noteCity',		// id/name
						'city',			// for
						parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDCITY)	// note text
					);
			}
			
			
			// filter
			$options = Filter::allExistingFilter('name');
			$formIds['filter'] = array('valueType' => 'array', 'type' => 'select',);
			$form->add(
					'label',		// type
					'labelFilter',	// id/name
					'filter',		// for
					parent::lang('filtering groups (multi select)')	// label text
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
					parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDSORT)	// note text
				);
			
			
			// checkbox public
			$formIds['public'] = array('valueType' => 'int', 'type' => 'checkbox', 'default' => 1);
			$form->add(
					'label',		// type
					'labelPublic',	// id/name
					'public',		// for
					parent::lang('public access')	// label text
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
					parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDISPUBLIC)	// note text
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
					parent::lang('save')	// value
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
				
				$calendar_new = array(
						'date' => $data['date'],
						'name' => $data['name'],
						'shortname' => $data['shortname'],
						'type' => $data['type'],
						'content' => $data['entryContent'],
						'city' => $data['city'],
						'filter' => $data['filter'],
						'valid' => 1
					);
					
				// update calendar
				$calendar->update($calendar_new);
				
				// put entry to output
				// smarty-template
				$sCD = new JudoIntranetSmarty();
				
				// write entry
				try {
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
					
					// smarty
					$sCD->assign('data', $calendar->detailsToHtml());
					$sCD->assign('files', $fileObjects);
					$sCD->assign('attached', parent::lang('files attached'));
					$sCD->assign('none', parent::lang('- none -'));
					$sCD->assign('fileHref', 'file.php?id=download&fid=');
					return $sCD->fetch('smarty.calendar.details.tpl');
				} catch(Exception $e) {
					$this->getError()->handle_error($e);
					return $this->getError()->to_html($e);
				}
			} else {
				return $form->render('lib/zebraTemplate.php', true, array($formIds, 'smarty.zebra.permissions.tpl', $permissionConfig,));
			}
		} else {
			
			// error
			$errno = $this->getError()->error_raised('NotAuthorized','entry:'.$this->get('id'),$this->get('id'));
			$this->getError()->handle_error($errno);
			return $this->getError()->to_html($errno);
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
		$this->getTpl()->assign('pagecaption',parent::lang('delete entry').": $cid");
		
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
				parent::lang('yes'),	// value
				array('title' => parent::lang('deletes entry'))
			);
			
			// smarty-link
			$link = array(
							'params' => 'class="submit"',
							'href' => 'calendar.php?id=listall',
							'title' => parent::lang('cancels deletion'),
							'content' => parent::lang('cancel')
						);
			$sConfirmation->assign('link', $link);
			$sConfirmation->assign('spanparams', 'id="cancel"');
			$sConfirmation->assign('message', parent::lang('you really want to delete').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_DELETE));
			$sConfirmation->assign('form', $form->render('', true));
			
			// validate
			if($form->validate()) {
			
				// get calendar-object
				$calendar = new Calendar($cid);
				
				// disable entry
				$calendar->update(array('valid' => 0));
				
				// smarty
				$sConfirmation->assign('message', parent::lang('entry successful deleted'));
				$sConfirmation->assign('form', '');
				
				// write entry
				try {
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
				} catch(Exception $e) {
					$this->getError()->handle_error($e);
					return $this->getError()->to_html($e);
				}
			}
			
			// smarty return
			return $sConfirmation->fetch('smarty.confirmation.tpl');
		} else {
			
			// error
			$errno = $this->getError()->error_raised('NotAuthorized','entry:'.$this->get('id'),$this->get('id'));
			$this->getError()->handle_error($errno);
			return $this->getError()->to_html($errno);
		}
	}
	
	
	
	
	
	
	
	/**
	 * readPresetForm($calendar) generates a zebra_form to choose the announcement-preset,
	 * if validated redirect to announcement.php?id=new&cid=$id
	 * 
	 * @param object $calendar the actual calendarentry
	 * @return string returns the generated form or redirects to listall if validated
	 */
	private function readPresetForm(&$calendar) {
		
		// check sort or from/to
		$sort = ($this->get('filter') !== false ? "&amp;filter=".$this->get('filter') : '');
		$from = ($this->get('from') !== false ? "&amp;from=".$this->get('from') : '');
		$to = ($this->get('to') !== false ? "&amp;to=".$this->get('to') : '');
		
		// form
		$form = new Zebra_Form(
				'choose_preset_'.$calendar->get_id(),	 	// id/name
				'post',						// method
				'calendar.php?id=listall'.$sort.$from.$to	// action
			);
		// set language
		$form->language('deutsch');
		// set docktype xhtml
		$form->doctype('xhtml');
		
		// add selectfield
		$formIds['preset'] = array('valueType' => 'int', 'type' => 'select',);
		$options = Preset::read_all_presets('calendar');
		$preset = $form->add(
				$formIds['preset']['type'],	// type
				'preset',		// id/name
				'',			// default
				array(		// attributes
					)
			);
		$preset->add_options($options);
		$preset->set_rule(
				array(
					'custom' => array(
						array(
							array($this, 'callbackCheckSelect'),	// callback
							null,		// optional argument
							'error',	// error variable
							parent::lang('select template'),	// error message
						)
					)
				)
			);
		
		// add submit
		$form->add(
				'submit',		// type
				'submitPreset',	// id/name
				parent::lang('+')	// value
			);
		
		// validate
		if($form->validate()) {
			
			// get data
			$data = $this->getFormValues($formIds);
			
			// insert preset_id in calendar-entry
			$update = array('preset_id' => $data['preset']);
			$calendar->update($update);
			$calendar->write_db('update');
			
			// redirect to listall
			header('Location: calendar.php?id=listall'.$sort.$from.$to);
			exit;
		} else {
			return $form->render('', true);
		}
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
		if($first['content'] < $second['content']) {
			return -1;
		}
		if($first['content'] == $second['content']) {
			return 0;
		}
		if($first['content'] > $second['content']) {
			return 1;
		}
	}
}



?>
