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
			$GLOBALS['Error']->handle_error($e);
		}
	}
	
	/*
	 * methods
	 */
	/**
	 * navi knows about the functionalities used in navigation returns an array
	 * containing first- and second-level-navientries
	 * 
	 * @return array contains first- and second-level-navientries
	 */
	public static function connectnavi() {
		
		// set first- and secondlevel names and set secondlevel $_GET['id']-values
		static $navi = array();
		
		$navi = array(
						'firstlevel' => array(
							'name' => 'class.CalendarView#connectnavi#firstlevel#name',
							'file' => 'calendar.php',
							'position' => 1,
							'class' => get_class(),
							'id' => md5('CalendarView'), // 9163fef556569ffd1f18f8e3e2a9404d
							'show' => true
						),
						'secondlevel' => array(
							1 => array(
								'getid' => 'listall', 
								'name' => 'class.CalendarView#connectnavi#secondlevel#listall',
								'id' => md5('CalendarView|listall'), // 49769d7048c1520f081c9de448f35de6
								'show' => true
							),
							0 => array(
								'getid' => 'new', 
								'name' => 'class.CalendarView#connectnavi#secondlevel#new',
								'id' => md5('CalendarView|new'), // 00638c249fae83ef1a03e92d291cf1a3
								'show' => true
							),
							2 => array(
								'getid' => 'details', 
								'name' => 'class.CalendarView#connectnavi#secondlevel#details',
								'id' => md5('CalendarView|details'), // e7d37edd6f90df7a8b47098b6c57ebf3 
								'show' => false
							),
							3 => array(
								'getid' => 'edit', 
								'name' => 'class.CalendarView#connectnavi#secondlevel#edit',
								'id' => md5('CalendarView|edit'), // fc3947ed132c20dd2e1681ce4cd12fe6
								'show' => false
							),
							4 => array(
								'getid' => 'delete', 
								'name' => 'class.CalendarView#connectnavi#secondlevel#delete',
								'id' => md5('CalendarView|delete'), //  1b35786f36c1601d20c9e86155363c7f
								'show' => false
							)
						)
					);
		
		// return array
		return $navi;
	}
	
	
	
	
	
	
	
	/**
	 * init chooses the functionality by using $_GET['id']
	 * 
	 * @return void
	 */
	public function init() {
		
		// set pagename
		$this->tpl->assign('pagename',parent::lang('class.CalendarView#page#init#name'));
		
		// switch $_GET['id'] if set
		if($this->get('id') !== false) {
			
			// check rights
			// get class
			$class = get_class();
			// get naviitems
			$navi = $class::connectnavi();
			// get rights from db
			$rights = Rights::get_authorized_entries('navi');
			$naviid = 0;
			// walk through secondlevel-entries to find actual entry
			for($i=0;$i<count($navi['secondlevel']);$i++) {
				if($navi['secondlevel'][$i]['getid'] == $this->get('id')) {
					
					// store id and  break
					$naviid = $navi['secondlevel'][$i]['id'];
					break;
				}
			}
			
			// check if naviid is member of authorized entries
			if(in_array($naviid,$rights)) {
				
				switch($this->get('id')) {
					
					case 'listall':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.CalendarView#init#listall#title')));
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('hierselect', false);
						
						// prepare dates
						$from = strtotime('yesterday');
						$to = strtotime('next year');

						// check $_GET['from'] and $_GET['to']
						if($this->get('from') !== false) {
							$from = strtotime($this->get('from'));
						}
						if($this->get('to') !== false) {
							$to = strtotime($this->get('to'));
						}
						$this->tpl->assign('main', $this->listall($to,$from));
					break;
					
					case 'new':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.CalendarView#init#new#title')));
						$this->tpl->assign('main', $this->new_entry());
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('hierselect', true);
					break;
					
					case 'details':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.CalendarView#init#details#title')));
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('hierselect', true);
						
						// if cid does not exist, error
						if(Calendar::check_id($this->get('cid'))) {
							// smarty
							$this->tpl->assign('main', $this->details($this->get('cid')));
						} else {
							
							// error
							$errno = $GLOBALS['Error']->error_raised('CidNotExists','details',$this->get('cid'));
							$GLOBALS['Error']->handle_error($errno);$this->add_output(array('main' => $GLOBALS['Error']->to_html($errno)),true);
							// smarty
							$this->tpl->assign('main', $GLOBALS['Error']->to_html($errno));
						}
					break;
					
					case 'edit':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.CalendarView#init#edit#title')));
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('hierselect', true);
						
						// if cid does not exist, error
						if(Calendar::check_id($this->get('cid'))) {
							// smarty
							$this->tpl->assign('main', $this->edit($this->get('cid')));$this->add_output(array('main' => $this->edit($this->get('cid'))));
						} else {
							
							// error
							$errno = $GLOBALS['Error']->error_raised('CidNotExists','edit',$this->get('cid'));
							$GLOBALS['Error']->handle_error($errno);
							// smarty
							$this->tpl->assign('main', $GLOBALS['Error']->to_html($errno));
						}
					break;
					
					case 'delete':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.CalendarView#init#delete#title')));
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('hierselect', true);
						
						// if cid does not exist, error
						if(Calendar::check_id($this->get('cid'))) {
							// smarty
							$this->tpl->assign('main', $this->delete($this->get('cid')));
						} else {
							
							// error
							$errno = $GLOBALS['Error']->error_raised('CidNotExists','delete',$this->get('cid'));
							$GLOBALS['Error']->handle_error($errno);
							// smarty
							$this->tpl->assign('main', $GLOBALS['Error']->to_html($errno));
						}
					break;
					
					default:
						
						// id set, but no functionality
						$errno = $GLOBALS['Error']->error_raised('GETUnkownId','entry:'.$this->get('id'),$this->get('id'));
						$GLOBALS['Error']->handle_error($errno);
						
						// smarty
						$this->tpl->assign('title', '');
						$this->tpl->assign('main', $GLOBALS['Error']->to_html($errno));
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('hierselect', false);
					break;
				}
			} else {
				
				// error not authorized
				$errno = $GLOBALS['Error']->error_raised('NotAuthorized','entry:'.$this->get('id'),$this->get('id'));
				$GLOBALS['Error']->handle_error($errno);
				
				// smarty
				$this->tpl->assign('title', $this->title(parent::lang('class.CalendarView#init#Error#NotAuthorized')));
				$this->tpl->assign('main', $GLOBALS['Error']->to_html($errno));
				$this->tpl->assign('jquery', true);
				$this->tpl->assign('hierselect', false);
			}
		} else {
			
			// id not set
			// smarty-title
			$this->tpl->assign('title', $this->title(parent::lang('class.CalendarView#init#default#title')));
			// smarty-pagecaption
			$this->tpl->assign('pagecaption', $this->defaultContent()); 
			// smarty-main
			$this->tpl->assign('main', '');
			// smarty-jquery
			$this->tpl->assign('jquery', true);
			// smarty-hierselect
			$this->tpl->assign('hierselect', false);
		}
		
		// global smarty
		$this->showPage();
	}
	
	
	
	
	
	
	
	/**
	 * listall lists all calendarentries less/equal than $time in table (paged)
	 * shows only entrys for which the user has sufficient rights
	 * 
	 * @param int $timeto unix-timestamp from that the entrys are shown
	 * @param int $timefrom unix-timestamp from that the entrys are shown
	 * @return void
	 */
	private function listall($timeto,$timefrom) {
		
		// pagecaption
		$this->tpl->assign('pagecaption',parent::lang('class.CalendarView#page#caption#listall'));
			
		// prepare return
		$output = $tr_out = $th_out = '';
		
		// read all entries
		$calendars = $this->read_all_entries();
		// check sort
		$entries = array();
		if($this->get('sort') !== false) {
			
			// check if entry is in sort
			foreach($calendars as $id => $entry) {
				
				if(in_array($this->get('sort'),$entry->get_rights()->get_rights())) {
					$entries[$id] = $entry;
				}
			}
		} else {
			$entries = $calendars;
		}
		
		// smarty-templates
		$sListall = new JudoIntranetSmarty();
		// sortlinks
		$sListall->assign('sortlinks', $this->get_sort_links($this->get('id')));
		
		// smarty
		$sTh = array(
				'date' => parent::lang('class.CalendarView#listall#TH#date'),
				'name' => parent::lang('class.CalendarView#listall#TH#name'),
				'show' => parent::lang('class.CalendarView#listall#TH#show'),
				'admin' => parent::lang('class.CalendarView#listall#TH#admin')
			);

		$sListall->assign('th', $sTh);
		// loggedin? admin links
		$sListall->assign('loggedin', $_SESSION['user']->get_loggedin());
		
		// walk through entries
		$counter = 0;
		// smarty
		$sList = array();
		foreach($entries as $no => $entry) {
			
			// check if valid
			if($entry->get_valid() == 1) {
					
				// check timefrom and timeto
				if($entry->get_date('U') > $timefrom && $entry->get_date('U') <= $timeto) {
					
					// smarty
					$sList[$counter] = array(
							'name' => array(
									'href' => 'calendar.php?id=details&cid='.$entry->get_id(),
									'title' => $entry->get_name(),
									'name' => $entry->get_name()
								),
							'date' => $entry->get_date('d.m.Y'),
							
						);
					
					// details and pdf if announcement
					if($entry->get_preset_id() != 0 && Calendar::check_ann_value($entry->get_id(),$entry->get_preset_id()) === true) {
						
						$sList[$counter]['show'][] = array(
								'href' => 'announcement.php?id=details&cid='.$entry->get_id().'&pid='.$entry->get_preset_id(),
								'title' => parent::lang('class.CalendarView#listall#title#AnnDetails'),
								'src' => 'img/ann_details.png',
								'alt' => parent::lang('class.CalendarView#listall#alt#AnnDetails'),
								'show' => true
							);
						$sList[$counter]['show'][] = array(
								'href' => 'announcement.php?id=topdf&cid='.$entry->get_id().'&pid='.$entry->get_preset_id(),
								'title' => parent::lang('class.CalendarView#listall#title#AnnPDF'),
								'src' => 'img/ann_pdf.png',
								'alt' => parent::lang('class.CalendarView#listall#alt#AnnPDF'),
								'show' => true
							);
					} else {
						
						// smarty show
						$sList[$counter]['show'][] = array(
								'href' => '',
								'title' => '',
								'src' => '',
								'alt' => '',
								'show' => false
							);
						$sList[$counter]['show'][] = array(
								'href' => '',
								'title' => '',
								'src' => '',
								'alt' => '',
								'show' => false
							);
					}
						
					// add admin
					// get intersection of user-groups and rights
					$intersect = array_intersect(array_keys($_SESSION['user']->return_all_groups()),$entry->get_rights()->get_rights());
					$admin = false;
					// check if $intersect has values other than 0
					foreach($intersect as $num => $igroup) {
						if($igroup != 0) {
							$admin = true;
							break;
						}
					}
					
					// if $admin is true add admin-links
					if($admin === true) {
						
						// smarty
						// edit
						$sList[$counter]['admin'][] = array(
								'href' => 'calendar.php?id=edit&cid='.$entry->get_id(),
								'title' => parent::lang('class.CalendarView#listall#title#edit'),
								'src' => 'img/edit.png',
								'alt' => parent::lang('class.CalendarView#listall#alt#edit'),
								'admin' => $admin
							);
						// delete
						$sList[$counter]['admin'][] = array(
								'href' => 'calendar.php?id=delete&cid='.$entry->get_id(),
								'title' => parent::lang('class.CalendarView#listall#title#delete'),
								'src' => 'img/delete.png',
								'alt' => parent::lang('class.CalendarView#listall#alt#delete'),
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
									'form' => $this->read_preset_form($entry)
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
									'title' => parent::lang('class.CalendarView#listall#title#AnnEdit'),
									'src' => 'img/ann_edit.png',
									'alt' => parent::lang('class.CalendarView#listall#alt#AnnEdit'),
									'preset' => $entry->get_preset_id(),
									'form' => ''
								);
							// delete
							$sList[$counter]['annadmin'][] = array(
									'href' => 'announcement.php?id=delete&cid='.$entry->get_id().'&pid='.$entry->get_preset_id(),
									'title' => parent::lang('class.CalendarView#listall#title#AnnDelete'),
									'src' => 'img/ann_delete.png',
									'alt' => parent::lang('class.CalendarView#listall#alt#AnnDelete'),
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
				}
			} else {
				
				// deleted items
			}
		}
		
		// smarty
		$sListall->assign('list', $sList);
		
		// smarty-return
		return $sListall->fetch('smarty.calendar.listall.tpl');
	}
	
	
	
	
	
	
	
	/**
	 * read_all_entries get all calendar-entries from db for that the actual
	 * user has sufficient rights. returns an array with calendar-objects
	 * 
	 * @return array all entries as calendar-objects
	 */
	private function read_all_entries() {
		
		// prepare return
		$calendar_entries = array();
				
		// get ids
		$calendar_ids = Calendar::return_calendars();
		
		// create calendar-objects
		foreach($calendar_ids as $index => $id) {
			$calendar_entries[] = new Calendar($id);
		}
		
		// sort calendar-entries
		usort($calendar_entries,array($this,'callback_compare_calendars'));
		
		// return calendar-objects
		return $calendar_entries;
	}
	
	
	
	
	
	
	
	/**
	 * get_sort_links returns links to list "week" "month" "year" etc
	 * and sortable groups
	 * 
	 * @param string $getid $_GET['get'] to use in links
	 * @return string html-string with the links
	 */
	private function get_sort_links($getid) {
		
		// prepare output
		$date_links = $group_links = $output = $reset_links = '';
		
		// smarty-template
		$sS = new JudoIntranetSmarty();
		
		// if sort, attach sort
		$sort = '';
		if($this->get('sort') !== false) {
			$sort = '&sort='.$this->get('sort');
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
						'title' => parent::lang('class.CalendarView#get_sort_links#title#resetAll'),
						'content' => parent::lang('class.CalendarView#get_sort_links#reset#all')
					),
				array( // dates
						'href' => 'calendar.php?id='.$getid.$sort,
						'title' => parent::lang('class.CalendarView#get_sort_links#title#resetDate'),
						'content' => parent::lang('class.CalendarView#get_sort_links#reset#date')
					),
				array( // groups
						'href' => 'calendar.php?id='.$getid.$from.$to,
						'title' => parent::lang('class.CalendarView#get_sort_links#title#resetGroups'),
						'content' => parent::lang('class.CalendarView#get_sort_links#reset#groups')
					)
			);
		$sS->assign('r', $r);
		
		// prepare content
		$dates = array(
					'next_day' => '+1 day',
					'next_week' => '+1 week',
					'two_weeks' => '+2 weeks',
					'next_month' => '+1 month',
					'half_year' => '+6 months',
					'next_year' => '+1 year'
					);
		
		// create links
		$dl = array();
		foreach($dates as $name => $date) {
			
			// smarty
			$dl[] = array(
					'href' => 'calendar.php?id='.$getid.'&from='.date('Y-m-d',time()).'&to='.date('Y-m-d',strtotime($date)).$sort,
					'title' => parent::lang('class.CalendarView#get_sort_links#title#'.$name),
					'content' => parent::lang('class.CalendarView#get_sort_links#dates#'.$name)
				);
		}
		$sS->assign('dl', $dl);
		
		// add group-links
		$groups = $_SESSION['user']->return_all_groups('sort');
		
		// create links
		$gl = array();
		foreach($groups as $g_id => $name) {
			
			// smarty
			$gl[] = array(
					'href' => 'calendar.php?id='.$getid.'&sort='.$g_id.$from.$to,
					'title' => $name,
					'content' => $name
				);
		}
		$sS->assign('gl', $gl);
		
		// add slider-link
		$link = array(
				'params' => 'id="toggleFilter"',
				'title' => parent::lang('class.CalendarView#get_sort_links#toggleFilter#title'),
				'content' => parent::lang('class.CalendarView#get_sort_links#toggleFilter#name')
			);
		$sS->assign('link', $link);
		
		// add jquery
		// smarty jquery
		$sJsToggleSlide = new JudoIntranetSmarty();
		$sJsToggleSlide->assign('id', '#toggleFilter');
		$sJsToggleSlide->assign('toToggle', '#sortlinks');
		$sJsToggleSlide->assign('time', '');
		$this->add_jquery($sJsToggleSlide->fetch('smarty.js-toggleSlide.tpl'));
		$sS->assign('divparams', 'id="sortlinks"');
		
		// return
		return $sS->fetch('smarty.calendar.sortlinks.tpl');
	}
	
	
	
	
	
	
	
	/**
	 * new_entry creates the "new-entry"-form and handle its response
	 * 
	 * @return string html-string with the "new-entry"-form
	 */
	private function new_entry() {
		
		// pagecaption
		$this->tpl->assign('pagecaption',parent::lang('class.CalendarView#page#caption#new_entry'));
		
		// smarty-templates
		$sD = new JudoIntranetSmarty();
		
		// prepare return
		$return = '';
		
		// formular
		$form = new HTML_QuickForm2(
								'new_calendar_entry',
								'post',
								array(
									'name' => 'new_calendar_entry',
									'action' => 'calendar.php?id=new'
								)
							);
		
		$now_year = (int) date('Y');
		$now_month = (int) date('m');
		$now_day = (int) date('d');
		$now = date('Y-m-d');
		$year_min = $now_year;
		$year_max = $now_year + 3;
		$form->addDataSource(new HTML_QuickForm2_DataSource_Array(array('rights' => '0',
																		'date' => $now)));
		
		// renderer
		$renderer = HTML_QuickForm2_Renderer::factory('default');
		$renderer->setOption('required_note',parent::lang('class.CalendarView#entry#form#requiredNote'));
		
		// elements
		// date
		$date = $form->addElement('text','date',array());
		$date->setLabel(parent::lang('class.CalendarView#entry#form#date').':');
		// rule
		$date->addRule('required',parent::lang('class.CalendarView#entry#rule#required.date'));
		$date->addRule('callback',parent::lang('class.CalendarView#entry#rule#check.date'),array($this,'callback_check_date'));
		// add jquery-datepicker
		// smarty
		$sD->assign('elementid', 'date-0');
		$sD->assign('dateFormat', 'yy-mm-dd');
		$sD->assign('dateValue', $now);
		$this->add_jquery($sD->fetch('smarty.js-datepicker.tpl'));
		
		// name
		$name = $form->addElement('text','name');
		$name->setLabel(parent::lang('class.CalendarView#entry#form#name').':');
		$name->addRule('required',parent::lang('class.CalendarView#entry#rule#required.name'));
		$name->addRule(
					'regex',
					parent::lang('class.CalendarView#entry#rule#regexp.allowedChars').' ['.$_SESSION['GC']->get_config('name.desc').']',
					$_SESSION['GC']->get_config('name.regexp'));
		
		
		// shortname
		$shortname = $form->addElement('text','shortname');
		$shortname->setLabel(parent::lang('class.CalendarView#entry#form#shortname').':');
		$shortname->addRule(
						'regex',
						parent::lang('class.CalendarView#entry#rule#regexp.allowedChars').' ['.$_SESSION['GC']->get_config('name.desc').']',
						$_SESSION['GC']->get_config('name.regexp'));
	
	
		// type
		$options = array_merge(array(0 => '--'),Calendar::return_types());
		$type = $form->addElement('select','type');
		$type->setLabel(parent::lang('class.CalendarView#entry#form#type').':');
		$type->loadOptions($options);
		$type->addRule('required',parent::lang('class.CalendarView#entry#rule#required.type'));
		$type->addRule('callback',parent::lang('class.CalendarView#entry#rule#check.select'),array($this,'callback_check_select'));
		
		
		// entry_content
		$content = $form->addElement('textarea','entry_content');
		$content->setLabel(parent::lang('class.CalendarView#entry#form#entry_content').':');
		$content->addRule(
						'regex',
						parent::lang('class.CalendarView#entry#rule#regexp.allowedChars').' ['.$_SESSION['GC']->get_config('textarea.desc').']',
						$_SESSION['GC']->get_config('textarea.regexp'));
		
		
		// select rights
		$options = $_SESSION['user']->return_all_groups('sort');
		$rights = $form->addElement('select','rights',array('multiple' => 'multiple','size' => 5));
		$rights->setLabel(parent::lang('class.CalendarView#entry#form#rights').':');
		$rights->loadOptions($options);
		
		
		// checkbox public
		$rights = $form->addElement('checkbox','public');
		$rights->setLabel(parent::lang('class.CalendarView#entry#form#public').':');
		
		
		// submit-button
		$form->addElement('submit','submit',array('value' => parent::lang('class.CalendarView#entry#form#submitButton')));
		
		// validate
		if($form->validate()) {
			
			// create calendar-object
			$data = $form->getValue();
				
			// check $data['rights']
			if(!isset($data['rights']))
			{
				$data['rights'] = array();
			}
			
			// merge with own groups, add admin
			$data['rights'] = array_merge($data['rights'],$_SESSION['user']->get_groups(),array(1));
			
			// add public access
			$kPublicAccess = array_search(0,$data['rights']);
			if($kPublicAccess === false && isset($data['public']) && $data['public'] == 1) {
				$data['rights'][] = 0;
			} elseif($kPublicAccess !== false && !isset($data['public'])) {
				unset($data['rights'][$kPublicAccess]);
			}
			
			$right_array = array(
								'action' => 'new',
								'new' => $data['rights']);
			
			$calendar = new Calendar(array(
								'date' => $data['date'],
								'name' => $data['name'],
								'shortname' => $data['shortname'],
								'type' => $data['type'],
								'content' => $data['entry_content'],
								'rights' => $right_array,
								'valid' => 1
								)
				);
				
			// write to db
			$calendar->write_db();
			
			// smarty
			$sCD = new JudoIntranetSmarty();
			$sCD->assign('data', $calendar->details_to_html());
			return $sCD->fetch('smarty.calendar.details.tpl');
		} else {
			return $form->render($renderer);
		}
	}
	
	
	
	
	
	
	
	/**
	 * callback_compare_calendars compares two calendar-objects by date (for uksort)
	 * 
	 * @param object $first first calendar-objects
	 * @param object $second second calendar-object
	 * @return int -1 if $first<$second, 0 if equal, 1 if $first>$second
	 */
	public function callback_compare_calendars($first,$second) {
	
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
		$this->tpl->assign('pagecaption',parent::lang('class.CalendarView#page#caption#details'));
		
		// check rights
		if(Rights::check_rights($cid,'calendar',true)) {
				
			// get calendar-object
			$calendar = new Calendar($cid);
			
			// smarty-template
			$sCD = new JudoIntranetSmarty();
			
			// smarty
			$sCD->assign('data', $calendar->details_to_html());
			return $sCD->fetch('smarty.calendar.details.tpl');
		} else {
			
			// error
			$errno = $GLOBALS['Error']->error_raised('NotAuthorized','entry:'.$this->get('id'),$this->get('id'));
			$GLOBALS['Error']->handle_error($errno);
			return $GLOBALS['Error']->to_html($errno);
		}
	}
	
	
	
	
	
	
	
	/**
	 * edit edits the given entry
	 * 
	 * @param int $cid entry-id for calendar
	 * @return string html-string
	 */
	private function edit($cid) {
		
		// check rights
		if(Rights::check_rights($cid,'calendar')) {
			
			// smarty-templates
			$sD = new JudoIntranetSmarty();
							
			// get calendar-object
			$calendar = new Calendar($cid);
			
			// pagecaption
			$this->tpl->assign('pagecaption',parent::lang('class.CalendarView#page#caption#edit').": \"$cid\" (".$calendar->get_name().")");
			
			// get rights
			$cRights = $calendar->get_rights()->get_rights();
			// check public access
			$kPublicAccess = array_search(0,$cRights);
			$publicAccess = false;
			if($kPublicAccess !== false) {
				$publicAccess = true;
				unset($cRights[$kPublicAccess]);
			}
					
			// prepare return
			$return = '';
					
			$form = new HTML_QuickForm2(
									'edit_calendar_entry',
									'post',
									array(
										'name' => 'edit_calendar_entry',
										'action' => 'calendar.php?id=edit&cid='.$cid
									)
								);
			
			$now_year = (int) date('Y');
			$year_min = $now_year;
			$year_max = $now_year + 3;
			
			// get datasource
			$datasource = array(
					'date' => $calendar->get_date(),
					'name' => $calendar->get_name(),
					'shortname' => $calendar->get_shortname(),
					'type' => $calendar->return_type(),
					'entry_content' => $calendar->get_content(),
					'rights' => $cRights
				);
			// add public access
			if($publicAccess) {
				$datasource['public'] = 1;
			}
			
			$form->addDataSource(new HTML_QuickForm2_DataSource_Array($datasource));
			
			// renderer
			$renderer = HTML_QuickForm2_Renderer::factory('default');
			$renderer->setOption('required_note',parent::lang('class.CalendarView#entry#form#requiredNote'));
			
			// elements
			// date
			$date = $form->addElement('text','date',array());
			$date->setLabel(parent::lang('class.CalendarView#entry#form#date').':');
			// rule
			$date->addRule('required',parent::lang('class.CalendarView#entry#rule#required.date'));
			$date->addRule('callback',parent::lang('class.CalendarView#entry#rule#check.date'),array($this,'callback_check_date'));
			// add jquery-datepicker
			// smarty
			$sD->assign('elementid', 'date-0');
			$sD->assign('dateFormat', 'yy-mm-dd');
			$sD->assign('dateValue', $calendar->get_date());
			$this->add_jquery($sD->fetch('smarty.js-datepicker.tpl'));
			
			// name
			$name = $form->addElement('text','name');
			$name->setLabel(parent::lang('class.CalendarView#entry#form#name').':');
			$name->addRule('required',parent::lang('class.CalendarView#entry#rule#required.name'));
			$name->addRule(
						'regex',
						parent::lang('class.CalendarView#entry#rule#regexp.allowedChars').' ['.$_SESSION['GC']->get_config('name.desc').']',
						$_SESSION['GC']->get_config('name.regexp'));
			
			
			// shortname
			$shortname = $form->addElement('text','shortname');
			$shortname->setLabel(parent::lang('class.CalendarView#entry#form#shortname').':');
			$shortname->addRule(
							'regex',
							parent::lang('class.CalendarView#entry#rule#regexp.allowedChars').' ['.$_SESSION['GC']->get_config('name.desc').']',
							$_SESSION['GC']->get_config('name.regexp'));
		
		
			// type
			$options = array_merge(array(0 => '--'),Calendar::return_types());
			$type = $form->addElement('select','type');
			$type->setLabel(parent::lang('class.CalendarView#entry#form#type').':');
			$type->loadOptions($options);
			$type->addRule('required',parent::lang('class.CalendarView#entry#rule#required.type'));
			$type->addRule('callback',parent::lang('class.CalendarView#entry#rule#check.select'),array($this,'callback_check_select'));
			
			
			// entry_content
			$content = $form->addElement('textarea','entry_content');
			$content->setLabel(parent::lang('class.CalendarView#entry#form#entry_content').':');
			$content->addRule(
							'regex',
							parent::lang('class.CalendarView#entry#rule#regexp.allowedChars').' ['.$_SESSION['GC']->get_config('textarea.desc').']',
							$_SESSION['GC']->get_config('textarea.regexp'));
			
			
			// select rights
			$options = $_SESSION['user']->return_all_groups('sort');
			$rights = $form->addElement('select','rights',array('multiple' => 'multiple','size' => 5));
			$rights->setLabel(parent::lang('class.CalendarView#entry#form#rights').':');
			$rights->loadOptions($options);
			
		
			// checkbox public
			$rights = $form->addElement('checkbox','public');
			$rights->setLabel(parent::lang('class.CalendarView#entry#form#public').':');
			
			
			// submit-button
			$form->addElement('submit','submit',array('value' => parent::lang('class.CalendarView#entry#form#submitButton')));
			
			// validate
			if($form->validate()) {
				
				// create calendar-object
				$data = $form->getValue();
								
				// check $data['rights']
				if(!isset($data['rights']))
				{
					$data['rights'] = array();
				}
				
				// merge with own groups, add admin
				$data['rights'] = array_merge($data['rights'],$_SESSION['user']->get_groups(),array(1));
				
				// add public access
				$kPublicAccess = array_search(0,$data['rights']);
				if($kPublicAccess === false && isset($data['public']) && $data['public'] == 1) {
					$data['rights'][] = 0;
				} elseif($kPublicAccess !== false && !isset($data['public'])) {
					unset($data['rights'][$kPublicAccess]);
				}
				
				$calendar_new = array(
						'date' => $data['date'],
						'name' => $data['name'],
						'shortname' => $data['shortname'],
						'type' => $data['type'],
						'content' => $data['entry_content'],
						'rights' => $data['rights'],
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
					// smarty
					$sCD->assign('data', $calendar->details_to_html());
					return $sCD->fetch('smarty.calendar.details.tpl');
				} catch(Exception $e) {
					$GLOBALS['Error']->handle_error($e);
					return $GLOBALS['Error']->to_html($e);
				}
			} else {
				return $form->render($renderer);
			}
		} else {
			
			// error
			$errno = $GLOBALS['Error']->error_raised('NotAuthorized','entry:'.$this->get('id'),$this->get('id'));
			$GLOBALS['Error']->handle_error($errno);
			return $GLOBALS['Error']->to_html($errno);
		}
	}
	
	
	
	
	
	
	
	/**
	 * delete deletes the given entry
	 * 
	 * @param int $cid entry-id for calendar
	 * @return string html-string
	 */
	private function delete($cid) {
	
		// pagecaption
		$this->tpl->assign('pagecaption',parent::lang('class.CalendarView#page#caption#delete').": $cid");
		
		// check rights
		if(Rights::check_rights($cid,'calendar')) {
				
			// prepare return
			$output = '';
			
			// smarty-templates
			$sConfirmation = new JudoIntranetSmarty();
			
			$form = new HTML_QuickForm2(
									'confirm',
									'post',
									array(
										'name' => 'confirm',
										'action' => 'calendar.php?id=delete&cid='.$this->get('cid')
									)
								);
			
			// add button
			$form->addElement('submit','yes',array('value' => parent::lang('class.CalendarView#delete#form#yes')));
			
			// smarty-link
			$link = array(
							'params' => '',
							'href' => 'calendar.php?id=listall',
							'title' => parent::lang('class.CalendarView#delete#title#cancel'),
							'content' => parent::lang('class.CalendarView#delete#form#cancel')
						);
			$sConfirmation->assign('link', $link);
			$sConfirmation->assign('spanparams', 'id="cancel"');
			$sConfirmation->assign('message', parent::lang('class.CalendarView#delete#message#confirm'));
			$sConfirmation->assign('form', $form);
			
			// validate
			if($form->validate()) {
			
				// get calendar-object
				$calendar = new Calendar($cid);
				
				// disable entry
				$calendar->update(array('valid' => 0));
				
				// smarty
				$sConfirmation->assign('message', parent::lang('class.CalendarView#delete#message#done'));
				$sConfirmation->assign('form', '');
				
				// write entry
				try {
					$calendar->write_db('update');
				} catch(Exception $e) {
					$GLOBALS['Error']->handle_error($e);
					return $GLOBALS['Error']->to_html($e);
				}
			}
			
			// smarty return
			return $sConfirmation->fetch('smarty.confirmation.tpl');
		} else {
			
			// error
			$errno = $GLOBALS['Error']->error_raised('NotAuthorized','entry:'.$this->get('id'),$this->get('id'));
			$GLOBALS['Error']->handle_error($errno);
			return $GLOBALS['Error']->to_html($errno);
		}
	}
	
	
	
	
	
	
	
	/**
	 * read_preset_form generates a quickform-object to choose the announcement-preset,
	 * if validated redirect to announcement.php?id=new&cid=$id
	 * 
	 * @param object $calendar the actual calendarentry
	 * @return object quickform-object to choose the preset, if validated redirect to new announcement
	 */
	private function read_preset_form(&$calendar) {
		
		// check sort or from/to
		$sort = $from = $to = '';
		if($this->get('sort') !== false) {$sort = "&sort=".$this->get('sort');}
		if($this->get('from') !== false) {$from = "&from=".$this->get('from');}
		if($this->get('to') !== false) {$to = "&to=".$this->get('to');}
		// form-object
		$form = new HTML_QuickForm2(
									'choose_preset_'.$calendar->get_id(),
									'post',
									array(
										'name' => 'choose_preset_'.$calendar->get_id(),
										'action' => 'calendar.php?id=listall'.$sort.$from.$to
									)
								);
		
		// add selectfield
		$select = $form->addSelect('preset',array());
		$options = array(0 => parent::lang('class.CalendarView#read_preset_form#select#choosePreset'));
		$options = $options + Preset::read_all_presets('calendar');
		$select->loadOptions($options);
		$select->addRule('callback',parent::lang('class.CalendarView#read_preset_form#rule#select'),array($this,'callback_check_select'));
		
		// add submit
		$submit = $form->addSubmit('submit',array('value' => parent::lang('class.CalendarView#read_preset_form#select#submit')));
		
		// validate
		if($form->validate()) {
			
			// get data
			$data = $form->getValue();
			
			// insert preset_id in calendar-entry
			$update = array('preset_id' => $data['preset']);
			$calendar->update($update);
			$calendar->write_db('update');
			
			// redirect to listall
			header('Location: calendar.php?id=listall'.$sort.$from.$to);
			exit;
		} else {
			return $form;
		}
	}
}



?>
