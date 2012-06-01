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
							),
							3 => array(
								'getid' => 'details', 
								'name' => 'class.AnnouncementView#connectnavi#secondlevel#details',
								'id' => crc32('AnnouncementView|details'), // 3931860135 
								'show' => false
							),
							4 => array(
								'getid' => 'topdf', 
								'name' => 'class.AnnouncementView#connectnavi#secondlevel#topdf',
								'id' => crc32('AnnouncementView|topdf'), // 353262192
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
						// jquery
						$this->add_output(array('jquery' => $this->get_jquery()));
					break;
					
					case 'edit':
						
						// set contents
						// title
						$this->add_output(array('title' => $this->title(parent::lang('class.CalendarView#init#edit#title'))));
						// navi
						$this->add_output(array('navi' => $this->navi(basename($_SERVER['SCRIPT_FILENAME']))));
						// main-content
						$this->add_output(array('main' => $this->edit()));
						// jquery
						$this->add_output(array('jquery' => $this->get_jquery()));
					break;
					
					case 'delete':
						
						// set contents
						// title
						$this->add_output(array('title' => $this->title(parent::lang('class.CalendarView#init#delete#title'))));
						// navi
						$this->add_output(array('navi' => $this->navi(basename($_SERVER['SCRIPT_FILENAME']))));
						// main-content
						$this->add_output(array('main' => $this->delete()));
						// jquery
						$this->add_output(array('jquery' => $this->get_jquery()));
					break;
					
					case 'details':
						
						// set contents
						// title
						$this->add_output(array('title' => $this->title(parent::lang('class.CalendarView#init#details#title'))));
						// navi
						$this->add_output(array('navi' => $this->navi(basename($_SERVER['SCRIPT_FILENAME']))));
						// main-content
						$this->add_output(array('main' => $this->details()));
						// jquery
						$this->add_output(array('jquery' => $this->get_jquery()));
					break;
					
					case 'topdf':
						
						// set contents
						// title
						$this->add_output(array('title' => $this->title(parent::lang('class.CalendarView#init#topdf#title'))));
						// navi
						$this->add_output(array('navi' => $this->navi(basename($_SERVER['SCRIPT_FILENAME']))));
						// main-content
						$this->add_output(array('main' => $this->topdf()));
						// jquery
						$this->add_output(array('jquery' => $this->get_jquery()));
					break;
					
					default:
						
						// id set, but no functionality
						$errno = $GLOBALS['Error']->error_raised('GETUnkownId','entry:'.$this->get('id'),$this->get('id'));
						$GLOBALS['Error']->handle_error($errno);
						$this->add_output(array('main' => $GLOBALS['Error']->to_html($errno)),true);
						// jquery
						$this->add_output(array('jquery' => $this->get_jquery()));
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
				// jquery
				$this->add_output(array('jquery' => $this->get_jquery()));
			}
		} else {
			
			// id not set
			// title
			$this->add_output(array('title' => $this->title(parent::lang('class.CalendarView#init#default#title')))); 
			// default-content
			$this->add_output(array('main' => '<h2>default content</h2>'));
			// navi
			$this->add_output(array('navi' => $this->navi(basename($_SERVER['SCRIPT_FILENAME']))));
			// jquery
			$this->add_output(array('jquery' => $this->get_jquery()));
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
			$js_datepicker = new HtmlTemplate('templates/js-datepicker.tpl');
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
					$fields = $preset->get_fields();
					
					// formular
					$form = new HTML_QuickForm2(
											'new_announcement',
											'post',
											array(
												'name' => 'new_announcement',
												'action' => 'announcement.php?id=new&cid='.$this->get('cid').'&pid='.$this->get('pid')
											)
										);
					
					// get fieldtype "date" for values
					$datevalue = array();
					foreach($fields as $field) {
						
						// check type
						if($field->get_type() == 'date') {
							$datevalue['calendar-'.$field->get_id()] = date('Y-m-d'); 
						}
					}
					
					$form->addDataSource(new HTML_QuickForm2_DataSource_Array($datevalue));
					
					// renderer
					$renderer = HTML_QuickForm2_Renderer::factory('default');
					$renderer->setOption('required_note',parent::lang('class.AnnouncementView#entry#form#requiredNote'));
					
					// generate field-quickform and add to form
					foreach($fields as $field) {
						
						// generate quickform
						$field_id = $field->read_quickform(array(),true);
						
						// check $field_id
						if($field_id != '' && $field->get_type() == 'date') {
							$this->add_jquery($js_datepicker->parse(array(
										'elementid' => $field_id.'-0',
										'addFunctions' => ''
									)));
						}
						
						// add to form
						$form->appendChild($field->get_quickform());
					}
					
					// submit-button
					$form->addSubmit('submit',array('value' => parent::lang('class.AnnouncementView#new_entry#form#submitButton')));
					
					// validate
					if($form->validate()) {
						
						// get calendar
						$calendar = new Calendar($this->get('cid'));
						
						// prepare marker-array
						$announcement = array(
								'version' => date('dmy')
							);
						
						// get data
						$data = $form->getValue();
						
						// insert values
						foreach($fields as $field) {
							
							// values to db
							$field->value($data[$field->get_table().'-'.$field->get_id()]);
							$field->write_db('insert');
						}
						
						// add calendar-fields to array
						$calendar->add_marks($announcement);
						
						// add field-names and -values to array
						$preset->add_marks($announcement);
						
						// return field and value as HTML
						foreach($fields as $field) {
							$return .= $field->value_to_html($p,$announcement);
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
		try {
			$p = new HtmlTemplate('templates/p.tpl');
			$js_datepicker = new HtmlTemplate('templates/js-datepicker.tpl');
			$js_datepicker_parse = new HtmlTemplate('templates/js-datepicker-parse.tpl');
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
					$fields = $preset->get_fields();
					
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
						if($field->get_type() == 'text') {
							
							// check defaults
							$datasource['calendar-'.$field->get_id()]['manual'] = '';
							$datasource['calendar-'.$field->get_id()]['defaults'] = 0;
							if($field->get_value() == '') {
								$datasource['calendar-'.$field->get_id()]['defaults'] = 'd'.$field->get_defaults();
							} else {
								$datasource['calendar-'.$field->get_id()]['manual'] = $field->get_value();
							}
						} elseif($field->get_type() == 'dbhierselect') {
							
							// explode value
							list($v_first,$v_second) = explode('|',$field->get_value(),2);
							
							// set values
							$datasource['calendar-'.$field->get_id()][0] = $v_first;
							$datasource['calendar-'.$field->get_id()][1] = $v_second;
						} elseif($field->get_type() == 'dbselect') {
							
							// check multiple
							if(strpos($field->get_value(),'|') !== false) {
								// separate value
								$values = explode('|',$field->get_value());
								foreach($values as $i => $value) {
									$datasource['calendar-'.$field->get_id()][$i] = $value;
								}
							} else {
								$datasource['calendar-'.$field->get_id()] = $field->get_value();
							}
						} else {
							$datasource['calendar-'.$field->get_id()] = $field->get_value();
						}
					}
					
					$form->addDataSource(new HTML_QuickForm2_DataSource_Array($datasource));
					
					// renderer
					$renderer = HTML_QuickForm2_Renderer::factory('default');
					$renderer->setOption('required_note',parent::lang('class.AnnouncementView#entry#form#requiredNote'));
					
					// generate field-quickform and add to form
					foreach($fields as $field) {
						
						// generate quickform
						$field_id = $field->read_quickform(array(),true);
						
						// check $field_id
						if($field_id != '' && $field->get_type() == 'date') {
							$js_datepicker_parse_out = $js_datepicker_parse->parse(array(
										'elementid' => $field_id.'-0',
										'format' => 'yy-mm-dd',
										'value' => $field->get_value()
									));
							$this->add_jquery($js_datepicker->parse(array(
										'elementid' => $field_id.'-0',
										'addFunctions' => $js_datepicker_parse_out
									)));
						}
						
						// add to form
						$form->appendChild($field->get_quickform());
					}
					
					// submit-button
					$form->addSubmit('submit',array('value' => parent::lang('class.AnnouncementView#edit#form#submitButton')));
					
					// validate
					if($form->validate()) {
						
						// get calendar
						$calendar = new Calendar($this->get('cid'));
						
						// prepare marker-array
						$announcement = array(
								'version' => date('dmy')
							);
						
						// get data
						$data = $form->getValue();
						
						// insert values
						foreach($fields as $field) {
							
							// values to db
							$field->value($data[$field->get_table().'-'.$field->get_id()]);
							$field->write_db('update');
						}
						
						// add calendar-fields to array
						$calendar->add_marks($announcement);
						
						// add field-names and -values to array
						$preset->add_marks($announcement);
						
						// return field and value as HTML
						foreach($fields as $field) {
							$return .= $field->value_to_html($p,$announcement);
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
						$fields = $preset->get_fields();
						
						// delete values of the fields
						if(Calendar::check_ann_value($calendar->get_id(),$calendar->get_preset_id()) === true) {
							
							foreach($fields as $field) {
								
								// delete value
								$field->delete_value();
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
	
	
	
	
	
	
	
	/**
	 * shows the details of the entry
	 * 
	 * @return string html-string
	 */
	private function details() {
		
		// check cid and pid given
		if ($this->get('cid') !== false && $this->get('pid') !== false) {
		
			// check cid and pid exists
			if(Calendar::check_id($this->get('cid')) && Preset::check_preset($this->get('pid'),'calendar')) {
				
				// check if announcement has values
				if(Calendar::check_ann_value($this->get('cid'))) {
					
					// prepare return
					$return = '';
					
					// get preset
					$preset = new Preset($this->get('pid'),'calendar',$this->get('cid'));
					
					// get templates
					try {
						$p = new HtmlTemplate('templates/p.tpl');
						$template = new HtmlTemplate($preset->get_path());
						$div = new HtmlTemplate('templates/div.tpl');
					} catch(Exception $e) {
						$GLOBALS['Error']->handle_error($e);
					}
					// get calendar
					$calendar = new Calendar($this->get('cid'));
					
					// prepare marker-array
					$announcement = array(
							'version' => date('dmy')
						);
					
					// add calendar-fields to array
					$calendar->add_marks($announcement);
					
					// add field-names and -values to array
					$preset->add_marks($announcement);
					
					// template
					$div_out = $template->parse($announcement);
					
					// surrounding divs
					$page = $div->parse(array(
								'div.params' => ' class="announcement-page"',
								'div.content' => $div_out
							));
					$return = $div->parse(array(
								'div.params' => ' class="announcement-page-line"',
								'div.content' => $page
							));				
					
					// return
					return $return;
				} else {
					
					// error
					$errno = $GLOBALS['Error']->error_raised('AnnNotExists','entry:'.$this->get('cid').'|'.$this->get('pid'),$this->get('cid').'|'.$this->get('pid'));
					$GLOBALS['Error']->handle_error($errno);
					return $GLOBALS['Error']->to_html($errno);
				}
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
	}
	
	
	
	
	
	
	
	/**
	 * shows the details of the entry as pdf
	 * 
	 * @return string pdf-string
	 */
	private function topdf() {
		
		// check cid and pid given
		if ($this->get('cid') !== false && $this->get('pid') !== false) {
		
			// check cid and pid exists
			if(Calendar::check_id($this->get('cid')) && Preset::check_preset($this->get('pid'),'calendar')) {
				
				// check if announcement has values
				if(Calendar::check_ann_value($this->get('cid'))) {
					
					// prepare return
					$return = '';
					
					// get preset
					$preset = new Preset($this->get('pid'),'calendar',$this->get('cid'));
					
					// get templates
					try {
						$p = new HtmlTemplate('templates/p.tpl');
						$template = new HtmlTemplate($preset->get_path());
						$dummy = new HtmlTemplate('templates/dummy.tpl');
					} catch(Exception $e) {
						$GLOBALS['Error']->handle_error($e);
					}
					
					// get calendar
					$calendar = new Calendar($this->get('cid'));
					
					// prepare marker-array
					$announcement = array(
							'version' => date('dmy')
						);
					
					// add calendar-fields to array
					$calendar->add_marks($announcement);
					
					// add field-names and -values to array
					$preset->add_marks($announcement);
					
					// template
					$pdf_out = $template->parse($announcement);				
					
					// get HTML2PDF-object
					$pdf = new HTML2PDF('P', 'A4', 'de', true, 'UTF-8', array(0, 0, 0, 0));
					
					// convert
					$pdf->writeHTML($pdf_out, false);
					
					// output
					$dummy->dummy_string($preset->get_filename());
					$pdf_filename = strtolower($this->replace_umlaute(html_entity_decode($dummy->parse($announcement))));
					$pdf->Output($pdf_filename,'D');
					
					// return
					return $return;
				} else {
					
					// error
					$errno = $GLOBALS['Error']->error_raised('AnnNotExists','entry:'.$this->get('cid').'|'.$this->get('pid'),$this->get('cid').'|'.$this->get('pid'));
					$GLOBALS['Error']->handle_error($errno);
					return $GLOBALS['Error']->to_html($errno);
				}
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
	}
}



?>
