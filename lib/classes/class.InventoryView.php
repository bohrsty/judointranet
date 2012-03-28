<?php


/**
 * class InventoryView implements the control of the inventory-page
 */
class InventoryView extends PageView {
	
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
							'name' => 'class.InventoryView#connectnavi#firstlevel#name',
							'file' => 'inventory.php',
							'position' => 3,
							'class' => get_class(),
							'id' => crc32('InventoryView') // 3652205019
						),
						'secondlevel' => array(
							1 => array(
								'getid' => 'listall', 
								'name' => 'class.InventoryView#connectnavi#secondlevel#listall',
								'id' => crc32('InventoryView|listall'), // 2615517752
								'show' => true
							),
							0 => array(
								'getid' => 'my', 
								'name' => 'class.InventoryView#connectnavi#secondlevel#my',
								'id' => crc32('InventoryView|my'), // 521760874
								'show' => true
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
						$this->add_output(array('main' => $this->get_sort_links($this->get('id'))));
						
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
					
					case 'my':
						
						// set contents
						// title
						$this->add_output(array('title' => $this->title(parent::lang('class.AnnouncementView#init#new#title'))));
						// navi
						$this->add_output(array('navi' => $this->navi(basename($_SERVER['SCRIPT_FILENAME']))));
						// main-content
						$this->add_output(array('main' => $this->my()));
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
	 * listall lists all inventoryentries, shows only entrys for which
	 * the user has sufficient rights
	 * 
	 * @return string html-string with the output
	 */
	private function listall() {
		
//		// prepare return
//		$output = $tr_out = $th_out = '';
//		
//		// read all entries
//		$calendars = $this->read_all_entries();
//		// check sort
//		$entries = array();
//		if($this->get('sort') !== false) {
//			
//			// check if entry is in sort
//			foreach($calendars as $id => $entry) {
//				
//				if(in_array($this->get('sort'),$entry->return_rights()->return_rights())) {
//					$entries[$id] = $entry;
//				}
//			}
//		} else {
//			$entries = $calendars;
//		}
//		
//		// get templates
//		// a
//		try {
//			$a = new HtmlTemplate('templates/a.tpl');
//		} catch(Exception $e) {
//			$GLOBALS['Error']->handle_error($e);
//		}
//		// table
//		try {
//			$table = new HtmlTemplate('templates/table.tpl');
//		} catch(Exception $e) {
//			$GLOBALS['Error']->handle_error($e);
//		}
//		// tr
//		try {
//			$tr = new HtmlTemplate('templates/tr.tpl');
//		} catch(Exception $e) {
//			$GLOBALS['Error']->handle_error($e);
//		}
//		// th
//		try {
//			$th = new HtmlTemplate('templates/th.tpl');
//		} catch(Exception $e) {
//			$GLOBALS['Error']->handle_error($e);
//		}
//		// td
//		try {
//			$td = new HtmlTemplate('templates/td.tpl');
//		} catch(Exception $e) {
//			$GLOBALS['Error']->handle_error($e);
//		}
//		// img
//		try {
//			$img = new HtmlTemplate('templates/img.tpl');
//		} catch(Exception $e) {
//			$GLOBALS['Error']->handle_error($e);
//		}
//		
//		// prepare th
//		$th_out .= $th->parse(array( // date
//				'th.params' => ' class="date"',
//				'th.content' => parent::lang('class.CalendarView#listall#TH#date')
//			));
//		$th_out .= $th->parse(array( // name
//				'th.params' => ' class="name"',
//				'th.content' => parent::lang('class.CalendarView#listall#TH#name')
//			));
//		// if loggedin show admin links
//		if($_SESSION['user']->loggedin() === true) {
//			$th_out .= $th->parse(array( // admin
//					'th.params' => ' class="admin"',
//					'th.content' => parent::lang('class.CalendarView#listall#TH#admin')
//				));
//		}
//		
//		// parse tr for th
//		$tr_out .= $tr->parse(array(
//				'tr.params' => '',
//				'tr.content' => $th_out)
//			);
//		
//		// walk through entries
//		$counter = 0;
//		foreach($entries as $no => $entry) {
//			
//			// check if valid
//			if($entry->return_valid() == 1) {
//					
//				// check timefrom and timeto
//				if($entry->return_date('U') > $timefrom && $entry->return_date('U') <= $timeto) {
//					
//					// odd or even
//					if($counter%2 == 0) {
//						// even
//						$tr_params = ' class="calendar.listall.tr even"';
//					} else {
//						// odd
//						$tr_params = ' class="calendar.listall.tr odd"';
//					}
//					
//					// prepare name-link
//					$a_out = $a->parse(array(
//							'a.params' => '',
//							'a.href' => 'calendar.php?id=details&cid='.$entry->return_id(),
//							'a.title' => $entry->return_name(),
//							'a.content' => $entry->return_name()
//						));
//					
//					// prepare td
//					$td_out = $td->parse(array( // date
//							'td.params' => ' class="date"',
//							'td.content' => $entry->return_date('d.m.Y')
//						));
//					$td_out .= $td->parse(array( // name
//							'td.params' => '',
//							'td.content' => $a_out
//						));
//						
//					// add admin
//					// get intersection of user-groups and rights
//					$intersect = array_intersect(array_keys($_SESSION['user']->return_all_groups()),$entry->return_rights()->return_rights());
//					$admin = false;
//					// check if $intersect has values other than 0
//					foreach($intersect as $num => $igroup) {
//						if($igroup != 0) {
//							$admin = true;
//							break;
//						}
//					}
//					
//					// if $admin is true add admin-links
//					if($admin === true) {
//						
//						// prepare edit
//						// prepare img
//						$img_out = $img->parse(array(
//								'img.src' => 'img/edit.png',
//								'img.alt' => parent::lang('class.CalendarView#listall#alt#edit'),
//								'img.params' => 'title="'.parent::lang('class.CalendarView#listall#title#edit').'"'
//							));
//						
//						// prepare edit-link
//						$a_out = $a->parse(array(
//								'a.params' => '',
//								'a.href' => 'calendar.php?id=edit&cid='.$entry->return_id(),
//								'a.title' => parent::lang('class.CalendarView#listall#title#edit'),
//								'a.content' => $img_out
//							));
//							
//						// prepare delete
//						// prepare img
//						$img_out = $img->parse(array(
//								'img.src' => 'img/delete.png',
//								'img.alt' => parent::lang('class.CalendarView#listall#alt#delete'),
//								'img.params' => 'title="'.parent::lang('class.CalendarView#listall#title#delete').'"'
//							));
//						
//						// prepare delete-link
//						$a_out .= $a->parse(array(
//								'a.params' => '',
//								'a.href' => 'calendar.php?id=delete&cid='.$entry->return_id(),
//								'a.title' => parent::lang('class.CalendarView#listall#title#delete'),
//								'a.content' => $img_out
//							));
//						// prepare td
//						$td_out .= $td->parse(array( // admin
//								'td.params' => ' class="admin"',
//								'td.content' => $a_out
//							));
//						// if no announcement (ann_id==0), choose preset
//						if($entry->return_ann_id() == 0) {
//							
//							// create form
//							$td_out .= $this->read_preset_form($entry->return_id());
//						}
//					}
//					
//					// prepare tr
//					$tr_out .= $tr->parse(array(
//							'tr.params' => $tr_params,
//							'tr.content' => $td_out
//						));
//					
//					// increment counter
//					$counter++;
//				}
//			} else {
//				
//				// deleted items
//			}
//		}
//		
//		// complete table
//		$output = $table->parse(array(	'table.params' => ' id="calendar.listall"',
//										'table.content' => $tr_out));
$output = 'listall';		
		// return
		return $output;
	}
	
	
	
	
	
	
	
	/**
	 * read_all_entries get all inventory-entries from db for that the actual
	 * user has sufficient rights. returns an array with inventory-objects
	 * 
	 * @return array all entries as inventory-objects
	 */
	private function read_all_entries() {
		
		// prepare return
		$inventory_entries = array();
				
		// get ids
		$inventory_ids = Inventory::return_my_inventories();
		
		// create inventory-objects
		foreach($inventory_ids as $index => $id) {
			$inventory_entries[] = new Inventory($id);
		}
		
		// return calendar-objects
		return $inventory_entries;
	}
	
	
	
	
	
	
	
	/**
	 * my creates the "my"-form and handle its response
	 * 
	 * @return string html-string with the "my"-form
	 */
	private function my() {
		
		// prepare return
		$output = $tr_out = $th_out = '';
		
		// read all entries
		$entries = $this->read_all_entries();
				
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
		$th_out .= $th->parse(array( // name
				'th.params' => ' class="name"',
				'th.content' => parent::lang('class.InventoryView#my#TH#name')
			));
		$th_out .= $th->parse(array( // number
				'th.params' => ' class="number"',
				'th.content' => parent::lang('class.InventoryView#my#TH#number')
			));
		// if loggedin show admin links
		if($_SESSION['user']->loggedin() === true) {
			$th_out .= $th->parse(array( // admin
					'th.params' => ' class="admin"',
					'th.content' => parent::lang('class.InventoryView#my#TH#admin')
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
			
				// odd or even
				if($counter%2 == 0) {
					// even
					$tr_params = ' class="inventory.my.tr even"';
				} else {
					// odd
					$tr_params = ' class="inventory.my.tr odd"';
				}
				
				// prepare td
				$td_out = $td->parse(array( // name
						'td.params' => ' class="name"',
						'td.content' => $entry->return_name()
					));
				$td_out .= $td->parse(array( // number
						'td.params' => '',
						'td.content' => $entry->return_inventory_no()
					));
					
				// add admin
				// prepare exchange-link
				if($entry->return_owned() == 'taken') {
					
					$a_out = $a->parse(array(
						'a.params' => '',
						'a.href' => 'inventory.php?id=my&action=give&did='.$entry->return_id(),
						'a.title' => parent::lang('class.InventoryView#my#title#give'),
						'a.content' => parent::lang('class.InventoryView#my#content#give')
					));
				} else {
					
					$a_out = $a->parse(array(
						'a.params' => '',
						'a.href' => 'inventory.php?id=my&action=take&did='.$entry->return_id(),
						'a.title' => parent::lang('class.InventoryView#my#title#take'),
						'a.content' => parent::lang('class.InventoryView#my#content#take')
					));
				}
					
				// prepare td
				$td_out .= $td->parse(array( // admin
						'td.params' => ' class="admin"',
						'td.content' => $a_out
					));
				
				// prepare tr
				$tr_out .= $tr->parse(array(
						'tr.params' => $tr_params,
						'tr.content' => $td_out
					));
				
				// increment counter
				$counter++;
			} else {
				
				// deleted items
			}
		}
		
		// complete table
		$output = $table->parse(array(	'table.params' => ' id="inventory.my"',
										'table.content' => $tr_out));
		
		// return
		return $output;
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
}



?>
