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
							'id' => crc32('AnnouncementView'), // 3960320393
							'show' => false
						),
						'secondlevel' => array(
							0 => array(
								'getid' => 'new', 
								'name' => 'class.AnnouncementView#connectnavi#secondlevel#new',
								'id' => crc32('AnnouncementView|new'), // 3704676583
								'show' => false
							),
							1 => array(
								'getid' => 'edit', 
								'name' => 'class.AnnouncementView#connectnavi#secondlevel#edit',
								'id' => crc32('AnnouncementView|edit'), // 3109695354
								'show' => false
							),
							2 => array(
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
				if(isset($navi['secondlevel'][$i]['getid']) && $navi['secondlevel'][$i]['getid'] == $this->get('id')) {
					
					// store id and  break
					$naviid = $navi['secondlevel'][$i]['id'];
					break;
				}
			}
			
			// check if naviid is member of authorized entries
			if(in_array($naviid,$rights)) {
				
				switch($this->get('id')) {
					
					case 'new':
						
						// set contents
						// title
						$this->add_output(array('title' => $this->title(parent::lang('class.AnnouncementView#init#new#title'))));
						// navi
						$this->add_output(array('navi' => $this->navi(basename($_SERVER['SCRIPT_FILENAME']))));
						// main-content
						$this->add_output(array('main' => $this->new_entry()));
					break;
					
					case 'edit':
						
						// set contents
						// title
						$this->add_output(array('title' => $this->title(parent::lang('class.CalendarView#init#edit#title'))));
						// navi
						$this->add_output(array('navi' => $this->navi(basename($_SERVER['SCRIPT_FILENAME']))));
						// main-content
						$this->add_output(array('main' => $this->edit()));
					break;
					
					case 'delete':
						
						// set contents
						// title
						$this->add_output(array('title' => $this->title(parent::lang('class.CalendarView#init#delete#title'))));
						// navi
						$this->add_output(array('navi' => $this->navi(basename($_SERVER['SCRIPT_FILENAME']))));
						// main-content
						$this->add_output(array('main' => $this->delete()));
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
		
		// get templates
		// p
		try {
			$p = new HtmlTemplate('templates/p.tpl');
		} catch(Exception $e) {
			$GLOBALS['Error']->handle_error($e);
		}
		
		// check rights
		if(Rights::check_rights($this->get('cid'),'calendar')) {
			
			// check cid and pid given
			if ($this->get('cid') !== false && $this->get('pid') !== false) {
			
				// check cid and pid exists
				if(Calendar::check_id($this->get('cid')) && Preset::check_preset($this->get('pid'),'calendar')) {
					
					// prepare return
					$return = '';
					
					// get preset
					$preset = new Preset($this->get('pid'),'calendar',$this->get('cid'));
					
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
							$datevalues['calendar-'.$field->return_id()]['day'] = $now_day;
							$datevalues['calendar-'.$field->return_id()]['month'] = $now_month;
							$datevalues['calendar-'.$field->return_id()]['year'] = $now_year; 
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
						
						// insert values
						foreach($fields as $field) {
							
							// values to db
							$field->value_to_db($this->get('cid'),$data[$field->return_table().'-'.$field->return_id()]);
							
							// return field and value as HTML
							$return .= $field->value_to_html($p,$data[$field->return_table().'-'.$field->return_id()]);
						}
						
					} else {
						$return = $form->render($renderer);
					}
					
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
	 * edit edits the entry
	 * 
	 * @return string html-string
	 */
	private function edit() {
		
		// get templates
		// p
		try {
			$p = new HtmlTemplate('templates/p.tpl');
		} catch(Exception $e) {
			$GLOBALS['Error']->handle_error($e);
		}
		
		// check rights
		if(Rights::check_rights($this->get('cid'),'calendar')) {
			
			// check cid and pid given
			if ($this->get('cid') !== false && $this->get('pid') !== false) {
			
				// check cid and pid exists
				if(Calendar::check_id($this->get('cid')) && Preset::check_preset($this->get('pid'),'calendar')) {
					
					// prepare return
					$return = '';
					
					// get preset
					$preset = new Preset($this->get('pid'),'calendar',$this->get('cid'));
					
					// get fields
					$fields = $preset->return_fields();
					
					// formular
					$form = new HTML_QuickForm2(
											'edit_announcement',
											'post',
											array(
												'name' => 'edit_announcement',
												'action' => 'announcement.php?id=edit&cid='.$this->get('cid').'&pid='.$this->get('pid')
											)
										);
					
					// values
					$datasource = array();
					foreach($fields as $field) {
						
						// read values
						$field->read_value();
						
						// check type
						if($field->return_type() == 'date') {
							$datasource['calendar-'.$field->return_id()]['day'] = (int) date('d',strtotime($field->return_value()));
							$datasource['calendar-'.$field->return_id()]['month'] = (int) date('m',strtotime($field->return_value()));
							$datasource['calendar-'.$field->return_id()]['year'] = (int) date('Y',strtotime($field->return_value()));
						} else {
							$datasource['calendar-'.$field->return_id()] = $field->return_value();
						}
					}
					
					$form->addDataSource(new HTML_QuickForm2_DataSource_Array($datasource));
					
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
					$form->addSubmit('submit',array('value' => parent::lang('class.AnnouncementView#edit#form#submitButton')));
					
					// validate
					if($form->validate()) {
						
						// get data
						$data = $form->getValue();
						
						// insert values
						foreach($fields as $field) {
							
							// values to db
							$field->value_update_db($this->get('cid'),$data[$field->return_table().'-'.$field->return_id()]);
							
							// return field and value as HTML
							$return .= $field->value_to_html($p,$data[$field->return_table().'-'.$field->return_id()]);
						}
						
					} else {
						$return = $form->render($renderer);
					}
					
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
	 * delete deletes the given entry
	 * 
	 * @param int $cid entry-id for calendar
	 * @return string html-string
	 */
	private function delete() {
	
		// check rights
		if(Rights::check_rights($this->get('cid'),'calendar')) {
			
			// check cid and pid given
			if ($this->get('cid') !== false && $this->get('pid') !== false) {
			
				// check cid and pid exists
				if(Calendar::check_id($this->get('cid')) && Preset::check_preset($this->get('pid'),'calendar')) {
					
					// prepare return
					$return = '';
					
					// get templates
					try {
						$confirmation = new HtmlTemplate('templates/div.confirmation.tpl');
						$a = new HtmlTemplate('templates/a.tpl');
						$div = new HtmlTemplate('templates/div.tpl');
					} catch(Exception $e) {
						$GLOBALS['Error']->handle_error($e);
					}
					
					$form = new HTML_QuickForm2(
											'confirm',
											'post',
											array(
												'name' => 'confirm',
												'action' => 'announcement.php?id=delete&cid='.$this->get('cid').'&pid='.$this->get('pid')
											)
										);
					
					// add button
					$form->addElement('submit','yes',array('value' => parent::lang('class.AnnouncementView#delete#form#yes')));
					
					// prepare cancel
					$cancel_a = $a->parse(array(
							'a.params' => '',
							'a.href' => 'calendar.php?id=listall',
							'a.title' => parent::lang('class.AnnouncementView#delete#title#cancel'),
							'a.content' => parent::lang('class.AnnouncementView#delete#form#cancel')
						));
					$cancel = $div->parse(array(
							'div.params' => ' id="cancel"',
							'div.content' => $cancel_a
					));
					
					// set output
					$return = $confirmation->parse(array(
							'p.message' => parent::lang('class.AnnouncementView#delete#message#confirm'),
							'p.form' => $form."\n".$cancel
						));
					
					// validate
					if($form->validate()) {
					
						
						// get calendar-object
						$calendar = new Calendar($this->get('cid'));
						
						// get preset
						$preset = new Preset($this->get('pid'),'calendar',$this->get('cid'));
						
						// get fields
						$fields = $preset->return_fields();
						
						// delete values of the fields
						if(Calendar::check_ann_value($calendar->return_id(),$calendar->return_preset_id()) === true) {
							
							foreach($fields as $field) {
								
								// delete value
								$field->delete_value($this->get('cid'));
							}
						}
						
						// set preset to 0
						$calendar->update(array('preset_id' => 0));
						
						// set output
						$return = $confirmation->parse(array(
								'p.message' => parent::lang('class.AnnouncementView#delete#message#done'),
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
}



?>
