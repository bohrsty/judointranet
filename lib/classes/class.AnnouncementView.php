<?php


/**
 * class AnnouncementView implements the control of the announcement-page
 */
class AnnouncementView extends PageView {
	
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
							'name' => 'class.AnnouncementView#connectnavi#firstlevel#name',
							'file' => 'announcement.php',
							'position' => 2,
							'class' => get_class(),
							'id' => crc32('AnnouncementView') // 3960320393
						),
						'secondlevel' => array(
							1 => array(
								'getid' => 'listall', 
								'name' => 'class.AnnouncementView#connectnavi#secondlevel#listall',
								'id' => crc32('AnnouncementView|listall'), // 4169844043
								'show' => true
							),
							0 => array(
								'getid' => 'new', 
								'name' => 'class.AnnouncementView#connectnavi#secondlevel#new',
								'id' => crc32('AnnouncementView|new'), // 3704676583
								'show' => false
							),
							2 => array(
								'getid' => 'details', 
								'name' => 'class.AnnouncementView#connectnavi#secondlevel#details',
								'id' => crc32('AnnouncementView|details'), // 3931860135
								'show' => false
							),
							3 => array(
								'getid' => 'edit', 
								'name' => 'class.AnnouncementView#connectnavi#secondlevel#edit',
								'id' => crc32('AnnouncementView|edit'), // 3109695354
								'show' => false
							),
							4 => array(
								'getid' => 'delete', 
								'name' => 'class.AnnouncementView#connectnavi#secondlevel#delete',
								'id' => crc32('AnnouncementView|delete'), // 2505436613 
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
						
						// set contents
						// title
						$this->add_output(array('title' => $this->title(parent::lang('class.AnouncementView#init#listall#title'))));
						// navi
						$this->add_output(array('navi' => $this->navi(basename($_SERVER['SCRIPT_FILENAME']))));
						// main-content
						$this->add_output(array('main' => $this->listall()));
					break;
					
					case 'new':
						
						// set contents
						// title
						$this->add_output(array('title' => $this->title(parent::lang('class.AnnouncementView#init#new#title'))));
						// navi
						$this->add_output(array('navi' => $this->navi(basename($_SERVER['SCRIPT_FILENAME']))));
						// main-content
						$this->add_output(array('main' => $this->new_entry()));
					break;
					
					case 'details':
						
						// set contents
						// title
						$this->add_output(array('title' => $this->title(parent::lang('class.CalendarView#init#details#title'))));
						// navi
						$this->add_output(array('navi' => $this->navi(basename($_SERVER['SCRIPT_FILENAME']))));
						// main-content
						// if cid does not exist, error
						if(Calendar::check_id($this->get('cid'))) {
							$this->add_output(array('main' => $this->details($this->get('cid'))));
						} else {
							
							// error
							$errno = $GLOBALS['Error']->error_raised('CidNotExists','details',$this->get('cid'));
							$GLOBALS['Error']->handle_error($errno);
							$this->add_output(array('main' => $GLOBALS['Error']->to_html($errno)),true);
						}
					break;
					
					case 'edit':
						
						// set contents
						// title
						$this->add_output(array('title' => $this->title(parent::lang('class.CalendarView#init#edit#title'))));
						// navi
						$this->add_output(array('navi' => $this->navi(basename($_SERVER['SCRIPT_FILENAME']))));
						// main-content
						// if cid does not exist, error
						if(Calendar::check_id($this->get('cid'))) {
							$this->add_output(array('main' => $this->edit($this->get('cid'))));
						} else {
							
							// error
							$errno = $GLOBALS['Error']->error_raised('CidNotExists','edit',$this->get('cid'));
							$GLOBALS['Error']->handle_error($errno);
							$this->add_output(array('main' => $GLOBALS['Error']->to_html($errno)),true);
						}
					break;
					
					case 'delete':
						
						// set contents
						// title
						$this->add_output(array('title' => $this->title(parent::lang('class.CalendarView#init#delete#title'))));
						// navi
						$this->add_output(array('navi' => $this->navi(basename($_SERVER['SCRIPT_FILENAME']))));
						// main-content
						// if cid does not exist, error
						if(Calendar::check_id($this->get('cid'))) {
							$this->add_output(array('main' => $this->delete($this->get('cid'))));
						} else {
							
							// error
							$errno = $GLOBALS['Error']->error_raised('CidNotExists','delete',$this->get('cid'));
							$GLOBALS['Error']->handle_error($errno);
							$this->add_output(array('main' => $GLOBALS['Error']->to_html($errno)),true);
						}
					break;
					
					default:
						
						// id set, but no functionality
						$errno = $GLOBALS['Error']->error_raised('GETUnkownId','entry:'.$this->get('id'),$this->get('id'));
						$GLOBALS['Error']->handle_error($errno);
						$this->add_output(array('main' => $GLOBALS['Error']->to_html($errno)),true);
					break;
				}
			} else {
				
				// error not authorized
				// set contents
				// title
				$this->add_output(array('title' => $this->title(parent::lang('class.CalendarView#init#Error#NotAuthorized'))));
				// navi
				$this->add_output(array('navi' => $this->navi(basename($_SERVER['SCRIPT_FILENAME']))));
				// main content
				$errno = $GLOBALS['Error']->error_raised('NotAuthorized','entry:'.$this->get('id'),$this->get('id'));
				$GLOBALS['Error']->handle_error($errno);
				$this->add_output(array('main' => $GLOBALS['Error']->to_html($errno)),true);
			}
		} else {
			
			// id not set
			// title
			$this->add_output(array('title' => $this->title(parent::lang('class.CalendarView#init#default#title')))); 
			// default-content
			$this->add_output(array('main' => '<h2>default content</h2>'));
			// navi
			$this->add_output(array('navi' => $this->navi(basename($_SERVER['SCRIPT_FILENAME']))));
		}
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
		
		// prepare return
		$output = $tr_out = $th_out = '';
		
		// read all entries
		$calendars = $this->read_all_entries();
		// check sort
		$entries = array();
		if($this->get('sort') !== false) {
			
			// check if entry is in sort
			foreach($calendars as $id => $entry) {
				
				if(in_array($this->get('sort'),$entry->return_rights()->return_rights())) {
					$entries[$id] = $entry;
				}
			}
		} else {
			$entries = $calendars;
		}
		
		// get templates
		// a
		try {
			$a = new HtmlTemplate('templates/a.tpl');
		} catch(Exception $e) {
			$GLOBALS['Error']->handle_error($e);
		}
		// table
		try {
			$table = new HtmlTemplate('templates/table.tpl');
		} catch(Exception $e) {
			$GLOBALS['Error']->handle_error($e);
		}
		// tr
		try {
			$tr = new HtmlTemplate('templates/tr.tpl');
		} catch(Exception $e) {
			$GLOBALS['Error']->handle_error($e);
		}
		// th
		try {
			$th = new HtmlTemplate('templates/th.tpl');
		} catch(Exception $e) {
			$GLOBALS['Error']->handle_error($e);
		}
		// td
		try {
			$td = new HtmlTemplate('templates/td.tpl');
		} catch(Exception $e) {
			$GLOBALS['Error']->handle_error($e);
		}
		// img
		try {
			$img = new HtmlTemplate('templates/img.tpl');
		} catch(Exception $e) {
			$GLOBALS['Error']->handle_error($e);
		}
		
		// prepare th
		$th_out .= $th->parse(array( // date
				'th.params' => ' class="date"',
				'th.content' => parent::lang('class.CalendarView#listall#TH#date')
			));
		$th_out .= $th->parse(array( // name
				'th.params' => ' class="name"',
				'th.content' => parent::lang('class.CalendarView#listall#TH#name')
			));
		// if loggedin show admin links
		if($_SESSION['user']->loggedin() === true) {
			$th_out .= $th->parse(array( // admin
					'th.params' => ' class="admin"',
					'th.content' => parent::lang('class.CalendarView#listall#TH#admin')
				));
		}
		
		// parse tr for th
		$tr_out .= $tr->parse(array(
				'tr.params' => '',
				'tr.content' => $th_out)
			);
		
		// walk through entries
		$counter = 0;
		foreach($entries as $no => $entry) {
			
			// check if valid
			if($entry->return_valid() == 1) {
					
				// check timefrom and timeto
				if($entry->return_date('U') > $timefrom && $entry->return_date('U') <= $timeto) {
					
					// odd or even
					if($counter%2 == 0) {
						// even
						$tr_params = ' class="calendar.listall.tr even"';
					} else {
						// odd
						$tr_params = ' class="calendar.listall.tr odd"';
					}
					
					// prepare name-link
					$a_out = $a->parse(array(
							'a.params' => '',
							'a.href' => 'calendar.php?id=details&cid='.$entry->return_id(),
							'a.title' => $entry->return_name(),
							'a.content' => $entry->return_name()
						));
					
					// prepare td
					$td_out = $td->parse(array( // date
							'td.params' => ' class="date"',
							'td.content' => $entry->return_date('d.m.Y')
						));
					$td_out .= $td->parse(array( // name
							'td.params' => '',
							'td.content' => $a_out
						));
						
					// add admin
					// get intersection of user-groups and rights
					$intersect = array_intersect(array_keys($_SESSION['user']->return_all_groups()),$entry->return_rights()->return_rights());
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
						
						// prepare edit
						// prepare img
						$img_out = $img->parse(array(
								'img.src' => 'img/edit.png',
								'img.alt' => parent::lang('class.CalendarView#listall#alt#edit'),
								'img.params' => 'title="'.parent::lang('class.CalendarView#listall#title#edit').'"'
							));
						
						// prepare edit-link
						$a_out = $a->parse(array(
								'a.params' => '',
								'a.href' => 'calendar.php?id=edit&cid='.$entry->return_id(),
								'a.title' => parent::lang('class.CalendarView#listall#title#edit'),
								'a.content' => $img_out
							));
							
						// prepare delete
						// prepare img
						$img_out = $img->parse(array(
								'img.src' => 'img/delete.png',
								'img.alt' => parent::lang('class.CalendarView#listall#alt#delete'),
								'img.params' => 'title="'.parent::lang('class.CalendarView#listall#title#delete').'"'
							));
						
						// prepare delete-link
						$a_out .= $a->parse(array(
								'a.params' => '',
								'a.href' => 'calendar.php?id=delete&cid='.$entry->return_id(),
								'a.title' => parent::lang('class.CalendarView#listall#title#delete'),
								'a.content' => $img_out
							));
						// prepare td
						$td_out .= $td->parse(array( // admin
								'td.params' => ' class="admin"',
								'td.content' => $a_out
							));
						// if no announcement (preset_id==0), choose preset
						if($entry->return_preset_id() == 0) {
							
							// create form
							$td_out .= $this->read_preset_form($entry->return_id());
						}
					}
					
					// prepare tr
					$tr_out .= $tr->parse(array(
							'tr.params' => $tr_params,
							'tr.content' => $td_out
						));
					
					// increment counter
					$counter++;
				}
			} else {
				
				// deleted items
			}
		}
		
		// complete table
		$output = $table->parse(array(	'table.params' => ' id="calendar.listall"',
										'table.content' => $tr_out));
		
		// return
		return $output;
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
	 * new_entry creates the "new-entry"-form and handle its response
	 * 
	 * @return string html-string with the "new-entry"-form
	 */
	private function new_entry() {
		
		// check rights
		if(Rights::check_rights($this->get('cid'),'calendar')) {
			
			// check cid and pid given
			if ($this->get('cid') !== false && $this->get('pid') !== false) {
			
				// check cid and pid exists
				if(Calendar::check_id($this->get('cid')) && Preset::check_preset($this->get('pid'),'announcement')) {
					
					// prepare return
					$return = '';
					
					// get preset
					$preset = new Preset($this->get('pid'),'announcement',$this->get('cid'));
					
					// get fields
					$fields = $preset->return_fields();
					
					// formular
					$form = new HTML_QuickForm2(
											'new_announcement',
											'post',
											array(
												'name' => 'new_announcement',
												'action' => 'announcement.php?id=new&cid='.$this->get('cid').'&pid='.$this->get('pid')
											)
										);
					
					// get dates (now)
					$now_year = (int) date('Y');
					$now_month = (int) date('m');
					$now_day = (int) date('d');
					
					// get fieldtype "date" for values
					$datevalues = array();
					foreach($fields as $field) {
						
						// check type
						if($field->return_type() == 'date') {
							$datevalues['announcement-'.$field->return_id()]['day'] = $now_day;
							$datevalues['announcement-'.$field->return_id()]['month'] = $now_month;
							$datevalues['announcement-'.$field->return_id()]['year'] = $now_year; 
						}
					}
					
					$form->addDataSource(new HTML_QuickForm2_DataSource_Array($datevalues));
					
					// renderer
					$renderer = HTML_QuickForm2_Renderer::factory('default');
					$renderer->setOption('required_note',parent::lang('class.AnnouncementView#entry#form#requiredNote'));
					
					// generate field-quickform and add to form
					foreach($fields as $field) {
						
						// generate quickform
						$field->read_quickform();
						
						// add to form
						$form->appendChild($field->return_quickform());
					}
					
					// submit-button
					$form->addSubmit('submit',array('value' => parent::lang('class.AnnouncementView#new_entry#form#submitButton')));
					
					// validate
					if($form->validate()) {
						
						// get data
						$data = $form->getValue();
						
						// insert announcement
						
						// insert values
						foreach($fields as $field) {
							$field->values_to_db($insert_id,$data[$field->return_table().'-'.$field->return_id()]);
						}
						
						// update calendar-entry
					} else {
						$return = $form->render($renderer);
					}
						
			//		// validate
			//		if($form->validate()) {
			//			
			//			// create calendar-object
			//			$data = $form->getValue();
			//			
			//			$right_array = array(
			//								'action' => 'new',
			//								'new' => $data['rights']);
			//			
			//			$calendar = new Calendar(array(
			//								'date' => $data['dateGroup']['day'].'.'.$data['dateGroup']['month'].'.'.$data['dateGroup']['year'],
			//								'name' => $data['name'],
			//								'shortname' => $data['shortname'],
			//								'type' => $data['type'],
			//								'content' => $data['entry_content'],
			//								'rights' => $right_array,
			//								'valid' => 1
			//								)
			//				);
			//				
			//			// write to db
			//			$calendar->write_db();
			//			
			//			// put entry to output
			//			// read template
			//			try {
			//				$calendar_details = new HtmlTemplate('templates/calendar.details.tpl');
			//			} catch(Exception $e) {
			//				$GLOBALS['Error']->handle_error($e);
			//			}
			//			// set return
			//			$return = $calendar->details_to_html($calendar_details);
			//		} else {
			//			$return = $form->render($renderer);
			//		}
			
					// return
					return $return;
				} else {
					
				// error
				$errno = $GLOBALS['Error']->error_raised('WrongParams','entry:cid_or_pid','cid_or_pid');
				$GLOBALS['Error']->handle_error($errno);
				return $GLOBALS['Error']->to_html($errno);
				}
			} else {
				
				// error
				$errno = $GLOBALS['Error']->error_raised('MissingParams','entry:cid_or_pid','cid_or_pid');
				$GLOBALS['Error']->handle_error($errno);
				return $GLOBALS['Error']->to_html($errno);
			}
		} else {
			
			// error
			$errno = $GLOBALS['Error']->error_raised('NotAuthorized','entry:'.$this->get('id'),$this->get('id'));
			$GLOBALS['Error']->handle_error($errno);
			return $GLOBALS['Error']->to_html($errno);
		}
	}
	
	
	
	
	
	
	
	/**
	 * details returns the details of a calendar-entry as html-string
	 * 
	 * @param int $cid entry-id for calendar
	 * @return string html-string with the details of the calendar entry
	 */
	private function details($cid) {
	
		// check rights
		if(Rights::check_rights($cid,'calendar',true)) {
				
			// get calendar-object
			$calendar = new Calendar($cid);
			
			// read template
			try {
				$calendar_details = new HtmlTemplate('templates/calendar.details.tpl');
			} catch(Exception $e) {
				$GLOBALS['Error']->handle_error($e);
			}
			
			// return html-string
			return $calendar->details_to_html($calendar_details);
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
				
			// get calendar-object
			$calendar = new Calendar($cid);
					
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
			$form->addDataSource(new HTML_QuickForm2_DataSource_Array(array(
					'dateGroup' => array(
						'day' => (int) $calendar->return_date('d'),
						'month' => (int) $calendar->return_date('m'),
						'year' => (int) $calendar->return_date('Y')
					),
					'name' => $calendar->return_name(),
					'shortname' => $calendar->return_shortname(),
					'type' => $calendar->return_type(),
					'entry_content' => $calendar->return_content(),
					'announcement' => $calendar->return_preset_id(),
					'rights' => $calendar->return_rights()->return_rights()
				)));
			
			// renderer
			$renderer = HTML_QuickForm2_Renderer::factory('default');
			$renderer->setOption('required_note',parent::lang('class.CalendarView#entry#form#requiredNote'));
			
			// elements
			// date - group
			$date_group = $form->addGroup('dateGroup');
			$date_group->setLabel(parent::lang('class.CalendarView#entry#form#date').':');
			// rule
			$date_group->addRule('required',parent::lang('class.CalendarView#entry#rule#required.date'));
			$date_group->addRule('callback',parent::lang('class.CalendarView#entry#rule#check.date'),array($this,'callback_check_date'));
			
			// select day
			$options = array('--');
			for($i=1;$i<=31;$i++) {
				$options[$i] = $i;
			}
			$select_day = $date_group->addElement('select','day',array());
			$select_day->loadOptions($options);
			
			// select month
			$options = array('--');
			for($i=1;$i<=12;$i++) {
				$options[$i] = parent::lang('class.CalendarView#entry#date#month.'.$i);
			}
			$select_month = $date_group->addElement('select','month',array());
			$select_month->loadOptions($options);
			
			// select year
			$options = array('--');
			for($i=$year_min;$i<=$year_max;$i++) {
				$options[$i] = $i;
			}
			$select_year = $date_group->addElement('select','year',array());
			$select_year->loadOptions($options);
			
			
			// name
			$name = $form->addElement('text','name');
			$name->setLabel(parent::lang('class.CalendarView#entry#form#name').':');
			$name->addRule('required',parent::lang('class.CalendarView#entry#rule#required.name'));
			$name->addRule(
						'regex',
						parent::lang('class.CalendarView#entry#rule#regexp.allowedChars').' ['.$_SESSION['GC']->return_config('name.desc').']',
						$_SESSION['GC']->return_config('name.regexp'));
			
			
			// shortname
			$shortname = $form->addElement('text','shortname');
			$shortname->setLabel(parent::lang('class.CalendarView#entry#form#shortname').':');
			$shortname->addRule(
							'regex',
							parent::lang('class.CalendarView#entry#rule#regexp.allowedChars').' ['.$_SESSION['GC']->return_config('name.desc').']',
							$_SESSION['GC']->return_config('name.regexp'));
		
		
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
							parent::lang('class.CalendarView#entry#rule#regexp.allowedChars').' ['.$_SESSION['GC']->return_config('textarea.desc').']',
							$_SESSION['GC']->return_config('textarea.regexp'));
			
			
			// select rights
			$options = $_SESSION['user']->return_all_groups();
			$rights = $form->addElement('select','rights',array('multiple' => 'multiple','size' => 5));
			$rights->setLabel(parent::lang('class.CalendarView#entry#form#rights').':');
			$rights->loadOptions($options);
			
			
			// submit-button
			$form->addElement('submit','submit',array('value' => parent::lang('class.CalendarView#entry#form#submitButton')));
			
			// validate
			if($form->validate()) {
				
				// create calendar-object
				$data = $form->getValue();
				
				$calendar_new = array(
						'date' => $data['dateGroup']['day'].'.'.$data['dateGroup']['month'].'.'.$data['dateGroup']['year'],
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
				// read template
				try {
					$calendar_details = new HtmlTemplate('templates/calendar.details.tpl');
				} catch(Exception $e) {
					$GLOBALS['Error']->handle_error($e);
				}
				
				// write entry
				try {
					$calendar->write_db('update');
					// set return
					$return = $calendar->details_to_html($calendar_details);
				} catch(Exception $e) {
					$GLOBALS['Error']->handle_error($e);
					$return = $GLOBALS['Error']->to_html($e);
				}
			} else {
				$return = $form->render($renderer);
			}
			
			// return
			return $return;
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
	
		// check rights
		if(Rights::check_rights($cid,'calendar')) {
				
			// prepare return
			$output = '';
			
			// get templates
			try {
				$confirmation = new HtmlTemplate('templates/div.confirmation.tpl');
			} catch(Exception $e) {
				$GLOBALS['Error']->handle_error($e);
			}
			try {
				$a = new HtmlTemplate('templates/a.tpl');
			} catch(Exception $e) {
				$GLOBALS['Error']->handle_error($e);
			}
			try {
				$div = new HtmlTemplate('templates/div.tpl');
			} catch(Exception $e) {
				$GLOBALS['Error']->handle_error($e);
			}
			
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
			
			// prepare cancel
			$cancel_a = $a->parse(array(
					'a.params' => '',
					'a.href' => 'calendar.php?id=listall',
					'a.title' => parent::lang('class.CalendarView#delete#title#cancel'),
					'a.content' => parent::lang('class.CalendarView#delete#form#cancel')
				));
			$cancel = $div->parse(array(
					'div.params' => ' id="cancel"',
					'div.content' => $cancel_a
			));
			
			// set output
			$output = $confirmation->parse(array(
					'p.message' => parent::lang('class.CalendarView#delete#message#confirm'),
					'p.form' => $form."\n".$cancel
				));
			
			// validate
			if($form->validate()) {
			
				// get calendar-object
				$calendar = new Calendar($cid);
				
				// disable entry
				$calendar->update(array('valid' => 0));
				
				// set output
				$output = $confirmation->parse(array(
						'p.message' => parent::lang('class.CalendarView#delete#message#done'),
						'p.form' => ''
					));
				
				// write entry
				try {
					$calendar->write_db('update');
				} catch(Exception $e) {
					$GLOBALS['Error']->handle_error($e);
					$output = $GLOBALS['Error']->to_html($e);
				}
			}
			
			// return
			return $output;
		} else {
			
			// error
			$errno = $GLOBALS['Error']->error_raised('NotAuthorized','entry:'.$this->get('id'),$this->get('id'));
			$GLOBALS['Error']->handle_error($errno);
			return $GLOBALS['Error']->to_html($errno);
		}
	}
}



?>
