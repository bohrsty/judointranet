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
							0 => array(
								'getid' => 'listall', 
								'name' => 'class.CalendarView#connectnavi#secondlevel#listall',
								'id' => crc32('CalendarView|listall') // 316626287
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
						$this->add_output(array('title' => $this->title($this->lang('class.CalendarView#init#listall#title'))));
						// navi
						$this->add_output(array('navi' => $this->navi(basename($_SERVER['SCRIPT_FILENAME']))));
						// main-content
						// date-links
						$this->add_output(array('main' => $this->get_date_links($this->get('id'))));
						// p
						$this->add_output(array('main' => $this->p('','')));
						
						// prepare dates
						$from = time();
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
				$this->add_output(array('title' => $this->title($this->lang('class.CalendarView#init#Error#NotAuthorized'))));
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
			$this->add_output(array('title' => $this->title($this->lang('class.CalendarView#init#default#title')))); 
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
		$contents['th.date'] = $this->lang('class.CalendarView#listall#TH#date');
		$contents['th.name'] = $this->lang('class.CalendarView#listall#TH#name');
		
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
			$contents['a.alt'] = $this->lang('class.CalendarView#get_date_links#alt#'.$name);
			// linktext
			$contents['a.name'] = $this->lang('class.CalendarView#get_date_links#dates#'.$name);
			
			// parse template
			$output .= $a->parse($contents);
			$output .= " \n";	
		}
		
		// return
		return $output;
	}
}



?>
