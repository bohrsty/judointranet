<?php


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
							'id' => crc32('CalendarView') // 4126450689
						),
						'secondlevel' => array(
							1 => array(
								'getid' => 'listall', 
								'name' => 'class.CalendarView#connectnavi#secondlevel#listall',
								'id' => crc32('CalendarView|listall') // 316626287
							),
							0 => array(
								'getid' => 'new', 
								'name' => 'class.CalendarView#connectnavi#secondlevel#new',
								'id' => crc32('CalendarView|new') // 1338371484
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
						$this->add_output(array('title' => $this->title(parent::lang('class.CalendarView#init#listall#title'))));
						// navi
						$this->add_output(array('navi' => $this->navi(basename($_SERVER['SCRIPT_FILENAME']))));
						// main-content
						// date-links
						$this->add_output(array('main' => $this->get_date_links($this->get('id'))));
						// p
						$this->add_output(array('main' => $this->p('','')));
						
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
						$this->add_output(array('main' => $this->listall($to,$from)));
					break;
					
					case 'new':
						
						// set contents
						// title
						$this->add_output(array('title' => $this->title(parent::lang('class.CalendarView#init#listall#title'))));
						// navi
						$this->add_output(array('navi' => $this->navi(basename($_SERVER['SCRIPT_FILENAME']))));
						// main-content
						$this->add_output(array('main' => $this->new_entry()));
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
		$output = '';
		$th_out = '';
		$tr_out = '';
		
		// read all entries in future
		$entries = $this->read_all_entries();
		
		// th: get template and set content-array to parse
		try {
			$th = new HtmlTemplate('templates/calendar.listall.th.tpl');
		} catch(Exception $e) {
			$GLOBALS['Error']->handle_error($e);
		}
		
		$contents = array();
		$contents['th.date'] = parent::lang('class.CalendarView#listall#TH#date');
		$contents['th.name'] = parent::lang('class.CalendarView#listall#TH#name');
		
		// parse th
		$th_out .= $th->parse($contents);
		
		// parse list of entries
		try {
			$tr = new HtmlTemplate('templates/calendar.listall.tr.tpl');
		} catch(Exception $e) {
			$GLOBALS['Error']->handle_error($e);
		}
		
		// walk through entries
		$counter = 0;
		foreach($entries as $no => $entry) {
			
			// check timefrom and timeto
			if($entry->return_date('U') > $timefrom && $entry->return_date('U') <= $timeto) {
				
				// set content-array
				$contents = array();
				
				// odd or even
				if($counter%2 == 0) {
					// even
					$contents['class.tr'] = 'calendar.listall.tr even';
				} else {
					// odd
					$contents['class.tr'] = 'calendar.listall.tr odd';
				}
				
				// list-entry
				$contents['tr.date'] = $entry->return_date('d.m.Y');
				$contents['tr.name'] = $entry->return_name();
				
				// parse-template
				$tr_out .= $tr->parse($contents);
				
				// increment counter
				$counter++;
			}
		}
		
		// complete table
		try {
			$table = new HtmlTemplate('templates/table.tpl');
		} catch(Exception $e) {
			$GLOBALS['Error']->handle_error($e);
		}
		$contents = array();
		$contents['table.id'] = 'calendar.listall';
		$contents['th'] = $th_out;
		$contents['tr'] = $tr_out;
		$output = $table->parse($contents);
		
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
				
		// get authorized ids
		$calendar_ids = Rights::get_authorized_entries('calendar');
		
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
	 * get_date_links returns links to list "week" "month" "year" etc
	 * 
	 * @param string $getid $_GET['get'] to use in links
	 * @return string html-string with the links
	 */
	private function get_date_links($getid) {
		
		// prepare output
		$output = '';
		
		// read template
		try {
			$a = new HtmlTemplate('templates/a.tpl');
		} catch(Exception $e) {
			$GLOBALS['Error']->handle_error($e);
		}
		
		// prepare content
		$dates = array(
					'next_day' => '+1 day',
					'next_week' => '+1 week',
					'two_weeks' => '+2 weeks',
					'next_month' => '+1 month',
					'half_year' => '+6 months',
					'next_year' => '+1 year'
					);
		$contents = array();
		$contents['a.class'] = 'a';
		
		// create links
		foreach($dates as $name => $date) {
			
			// href
			$contents['a.href'] = 'calendar.php?id='.$getid.'&from='.date('Y-m-d',time()).'&to='.date('Y-m-d',strtotime($date));
			// alt
			$contents['a.alt'] = parent::lang('class.CalendarView#get_date_links#alt#'.$name);
			// linktext
			$contents['a.name'] = parent::lang('class.CalendarView#get_date_links#dates#'.$name);
			
			// parse template
			$output .= $a->parse($contents);
			$output .= " \n";	
		}
		
		// return
		return $output;
	}
	
	
	
	
	
	
	
	/**
	 * new_entry creates the "new-entry"-form and handle its response
	 * 
	 * @return string html-string with the "new-entry"-form
	 */
	private function new_entry() {
		
		// prepare return
		$return = '';
		
		// formular
		require_once('HTML/QuickForm2.php');
		require_once('HTML/QuickForm2/Renderer.php');
				
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
		$year_min = $now_year;
		$year_max = $now_year + 3;
		$form->addDataSource(new HTML_QuickForm2_DataSource_Array(array('rights' => '0',
																		'dateGroup' => array(
																			'day' => $now_day,
																			'month' => $now_month,
																			'year' => $now_year))));
		
		// renderer
		$renderer = HTML_QuickForm2_Renderer::factory('default');
		$renderer->setOption('required_note',parent::lang('class.CalendarView#new_entry#form#requiredNote'));
		
		// elements
		// date - group
		$date_group = $form->addGroup('dateGroup');
		$date_group->setLabel(parent::lang('class.CalendarView#new_entry#form#date').':');
		// rule
		$date_group->addRule('required',parent::lang('class.CalendarView#new_entry#rule#required.date'));
		$date_group->addRule('callback',parent::lang('class.CalendarView#new_entry#rule#check.date'),array($this,'callback_check_date'));
		
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
			$options[$i] = parent::lang('class.CalendarView#new_entry#date#month.'.$i);
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
		$name->setLabel(parent::lang('class.CalendarView#new_entry#form#name').':');
		$name->addRule('required',parent::lang('class.CalendarView#new_entry#rule#required.name'));
		$name->addRule(
					'regex',
					parent::lang('class.CalendarView#new_entry#rule#regexp.allowedChars').' ['.$_SESSION['GC']->return_config('name.desc').']',
					$_SESSION['GC']->return_config('name.regexp'));
		
		
		// shortname
		$shortname = $form->addElement('text','shortname');
		$shortname->setLabel(parent::lang('class.CalendarView#new_entry#form#shortname').':');
		$shortname->addRule(
						'regex',
						parent::lang('class.CalendarView#new_entry#rule#regexp.allowedChars').' ['.$_SESSION['GC']->return_config('name.desc').']',
						$_SESSION['GC']->return_config('name.regexp'));
	
	
		// type
		$options = array_merge(array(0 => '--'),Calendar::return_types());
		$type = $form->addElement('select','type');
		$type->setLabel(parent::lang('class.CalendarView#new_entry#form#type').':');
		$type->loadOptions($options);
		$type->addRule('required',parent::lang('class.CalendarView#new_entry#rule#required.type'));
		$type->addRule('callback',parent::lang('class.CalendarView#new_entry#rule#check.select'),array($this,'callback_check_select'));
		
		
		// entry_content
		$content = $form->addElement('textarea','entry_content');
		$content->setLabel(parent::lang('class.CalendarView#new_entry#form#entry_content').':');
		$content->addRule(
						'regex',
						parent::lang('class.CalendarView#new_entry#rule#regexp.allowedChars').' ['.$_SESSION['GC']->return_config('textarea.desc').']',
						$_SESSION['GC']->return_config('textarea.regexp'));
		
		
		// select rights
		$options = User::return_all_groups();
		$rights = $form->addElement('select','rights',array('multiple' => 'multiple','size' => 5));
		$rights->setLabel(parent::lang('class.CalendarView#new_entry#form#rights').':');
		$rights->loadOptions($options);
		
		
		// submit-button
		$form->addElement('submit','submit',array('value' => parent::lang('class.CalendarView#new_entry#form#submitButton')));
		
		// validate
		if($form->validate()) {
			
			// create calendar-object
			$data = $form->getValue();
			
			$right_array = array(
								'action' => 'new',
								'new' => $data['rights']);
			
			$calendar = new Calendar(array(
								'date' => $data['dateGroup']['day'].'.'.$data['dateGroup']['month'].'.'.$data['dateGroup']['year'],
								'name' => $data['name'],
								'shortname' => $data['shortname'],
								'type' => $data['type'],
								'content' => $data['entry_content'],
								'rights' => $right_array
								)
				);
				
			// write to db
			$calendar->write_db();
			
			// put entry to output
			// read template
			try {
				$calendar_details = new HtmlTemplate('templates/calendar.details.tpl');
			} catch(Exception $e) {
				$GLOBALS['Error']->handle_error($e);
			}
			// set return
			$return = $calendar->details_to_html($calendar_details);
		} else {
			$return = $form->render($renderer);
		}
		
		// return
		return $return;
	}
	
	
	
	
	
	
	
	/**
	 * callback_check_date checks if a correct date is selected
	 * 
	 * @param array $args arguments to check
	 * @return bool true, if ok, false otherwise
	 */
	public function callback_check_date($args) {
		
		// check values
		if($args['day'] == 0 || $args['month'] == 0 || $args['year'] == 0) {
			return false;
		} else {
			return checkdate($args['month'],$args['day'],$args['year']);
		}
	}
	
	
	
	
	
	
	
	/**
	 * callback_check_select checks if a value other than 0 is selected
	 * 
	 * @param array $args arguments to check
	 * @return bool true, if ok, false otherwise
	 */
	public function callback_check_select($args) {
		
		// check values
		if($args == '0') {
			return false;
		}
		return true;
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
		if($first->return_date() < $second->return_date()) {
			return -1;
		}
		if($first->return_date() == $second->return_date()) {
			return 0;
		}
		if($first->return_date() > $second->return_date()) {
			return 1;
		}
	}
}



?>
