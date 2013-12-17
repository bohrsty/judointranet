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
		$this->tpl->assign('pagename',parent::lang('class.AnnouncementView#page#init#name'));
		
		// init helpmessages
		$this->initHelp();
		
		// switch $_GET['id'] if set
		if($this->get('id') !== false) {
			
			// check permissions
			$naviId = Navi::idFromFileParam(basename($_SERVER['SCRIPT_FILENAME']), $this->get('id'));
			if($this->getUser()->hasPermission('navi', $naviId)) {
				
				switch($this->get('id')) {
					
					case 'new':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.AnnouncementView#init#new#title')));
						$this->tpl->assign('main', $this->newEntry());
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('zebraform', true);
					break;
					
					case 'edit':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.AnnouncementView#init#edit#title')));
						$this->tpl->assign('main', $this->edit());
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('zebraform', true);
					break;
					
					case 'delete':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.AnnouncementView#init#delete#title')));
						$this->tpl->assign('main', $this->delete());
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('zebraform', true);
					break;
					
					case 'details':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.AnnouncementView#init#details#title')));
						$this->tpl->assign('main', $this->details());
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('zebraform', false);
					break;
					
					case 'topdf':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.AnnouncementView#init#topdf#title')));
						$this->tpl->assign('main', $this->topdf());
						$this->tpl->assign('jquery', false);
						$this->tpl->assign('zebraform', false);
					break;
					
					default:
						
						// id set, but no functionality
						$errno = $this->getError()->error_raised('GETUnkownId','entry:'.$this->get('id'),$this->get('id'));
						$this->getError()->handle_error($errno);
						
						// smarty
						$this->tpl->assign('title', '');
						$this->tpl->assign('main', $this->getError()->to_html($errno));
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('zebraform', false);
					break;
				}
			} else {
				
				// error not authorized
				// main content
				$errno = $this->getError()->error_raised('NotAuthorized','entry:'.$this->get('id'),$this->get('id'));
				$this->getError()->handle_error($errno);

				// smarty
				$this->tpl->assign('title', $this->title(parent::lang('class.AnnouncementView#init#Error#NotAuthorized')));
				$this->tpl->assign('main', $this->getError()->to_html($errno));
				$this->tpl->assign('jquery', true);
				$this->tpl->assign('zebraform', false);
			}
		} else {
			
			// id not set
			// smarty-title
			$this->tpl->assign('title', $this->title(parent::lang('class.AnnouncementView#init#default#title')));
			// smarty-pagecaption
			$this->tpl->assign('pagecaption', $this->defaultContent()); 
			// smarty-main
			$this->tpl->assign('main', '');
			// smarty-jquery
			$this->tpl->assign('jquery', true);
			// smarty-hierselect
			$this->tpl->assign('zebraform', false);
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
		
		// check permissions
		if($this->getUser()->hasPermission('calendar', $this->get('cid'))) {
			
			// check cid and pid given
			if ($this->get('cid') !== false && $this->get('pid') !== false) {
			
				// check cid and pid exists
				if(Calendar::check_id($this->get('cid')) && Preset::check_preset($this->get('pid'),'calendar')) {
					
					// prepare return
					$return = '';
					
					// get preset
					$preset = new Preset($this->get('pid'),'calendar',$this->get('cid'), $this);
					
					// get fields
					$fields = $preset->get_fields();
					
					// formular
					$form = new Zebra_Form(
							'newAnnouncement',			// id/name
							'post',				// method
							'announcement.php?id=new&cid='.$this->get('cid').'&pid='.$this->get('pid')	// action
						);
					// set language
					$form->language('deutsch');
					// set docktype xhtml
					$form->doctype('xhtml');
					
					// prepare formIds
					$formIds = array();
					
					// add fields to form
					foreach($fields as $field) {
						
						// set form
						$field->setForm($form);
						
						// generate zebra_form
						$field->addFormElement(array(), true, $formIds);
						
					}
					
					// submit-button
					$form->add(
						'submit',		// type
						'buttonSubmit',	// id/name
						parent::lang('class.AnnouncementView#new_entry#form#submitButton')	// value
					);
					
					// validate
					if($form->validate()) {
						
						// get calendar
						$calendar = new Calendar($this->get('cid'));
						
						// prepare marker-array
						$announcement = array(
								'version' => 0
							);
						
						// get data
						$data = $this->getFormValues($formIds);
						
						// insert values
						foreach($fields as $field) {
							
							// values to db
							$field->value($data[$field->get_table().'-'.$field->get_id()]);
							$field->writeDb('insert');
						}
						
						// add calendar-fields to array
						$calendar->add_marks($announcement);
						
						// add field-names and -values to array
						$preset->add_marks($announcement);
						
						// get field name and value
						$values = array();
						foreach($fields as $field) {
							$values[] = $field->valueToHtml();
						}
						// smarty
						$sAe = new JudoIntranetSmarty();
						$sAe->assign('a', $announcement);
						for($i=0;$i<count($values);$i++) {
							if(preg_match('/\{\$a\..*\}/U', $values[$i]['value'])) {
								$values[$i]['value'] = $sAe->fetch('string:'.$values[$i]['value']);
							}
						}
						$sAe->assign('v',$values);
						return $sAe->fetch('smarty.announcement.edit.tpl');
						
					} else {
						return $form->render('', true);
					}
				} else {
					
					// error
					$errno = $this->getError()->error_raised('WrongParams','entry:cid_or_pid','cid_or_pid');
					$this->getError()->handle_error($errno);
					return $this->getError()->to_html($errno);
				}
			} else {
				
				// error
				$errno = $this->getError()->error_raised('MissingParams','entry:cid_or_pid','cid_or_pid');
				$this->getError()->handle_error($errno);
				return $this->getError()->to_html($errno);
			}
		} else {
			
			// error
			$errno = $this->getError()->error_raised('NotAuthorized','entry:'.$this->get('id'),$this->get('id'));
			$this->getError()->handle_error($errno);
			return $this->getError()->to_html($errno);
		}
	}
	
	
	
	
	
	
	
	/**
	 * edit edits the entry
	 * 
	 * @return string html-string
	 */
	private function edit() {
		
		// smarty-templates
		$sD = new JudoIntranetSmarty();
		
		// check permissions
		if($this->getUser()->hasPermission('calendar', $this->get('cid'))) {
			
			// check cid and pid given
			if ($this->get('cid') !== false && $this->get('pid') !== false) {
			
				// check cid and pid exists
				if(Calendar::check_id($this->get('cid')) && Preset::check_preset($this->get('pid'),'calendar')) {
					
					// pagecaption
					$this->tpl->assign('pagecaption',parent::lang('class.AnnouncementView#page#caption#edit'));
					
					// prepare return
					$return = '';
					
					// get preset
					$preset = new Preset($this->get('pid'),'calendar',$this->get('cid'), $this);
					
					// get fields
					$fields = $preset->get_fields();
					
					// formular
					$form = new Zebra_Form(
							'editAnnouncement',			// id/name
							'post',				// method
							'announcement.php?id=edit&cid='.$this->get('cid').'&pid='.$this->get('pid')	// action
						);
					// set language
					$form->language('deutsch');
					// set docktype xhtml
					$form->doctype('xhtml');
					
					// prepare formIds
					$formIds = array();
					
					// add fields to form
					foreach($fields as $field) {
						
						// set form
						$field->setForm($form);
						
						// add fields to form
						$field->addFormElement(array(), true, $formIds);
					}
					
					// submit-button
					$form->add(
						'submit',		// type
						'buttonSubmit',	// id/name
						parent::lang('class.AnnouncementView#new_entry#form#submitButton')	// value
					);
					
					// validate
					if($form->validate()) {
						
						// get calendar
						$calendar = new Calendar($this->get('cid'));
						
						// prepare marker-array
						$announcement = array(
								'version' => 0
							);
						
						// get data
						$data = $this->getFormValues($formIds);
						
						// insert values
						foreach($fields as $field) {
							
							// values to db
							$field->value($data[$field->get_table().'-'.$field->get_id()]);
							$field->writeDb('update');
						}
						
						// add calendar-fields to array
						$calendar->add_marks($announcement);
						
						// add field-names and -values to array
						$preset->add_marks($announcement);
						
						// get field name and value
						$values = array();
						foreach($fields as $field) {
							$values[] = $field->valueToHtml();
						}
						// smarty
						$sAe = new JudoIntranetSmarty();
						$sAe->assign('a', $announcement);
						for($i=0;$i<count($values);$i++) {
							if(preg_match('/\{\$a\..*\}/U', $values[$i]['value'])) {
								$values[$i]['value'] = $sAe->fetch('string:'.$values[$i]['value']);
							}
						}
						$sAe->assign('v',$values);
						return $sAe->fetch('smarty.announcement.edit.tpl');
						
					} else {
						return $form->render('', true);
					}
				} else {
					
					// error
					$errno = $this->getError()->error_raised('WrongParams','entry:cid_or_pid','cid_or_pid');
					$this->getError()->handle_error($errno);
					return $this->getError()->to_html($errno);
				}
			} else {
				
				// error
				$errno = $this->getError()->error_raised('MissingParams','entry:cid_or_pid','cid_or_pid');
				$this->getError()->handle_error($errno);
				return $this->getError()->to_html($errno);
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
	private function delete() {
	
		// check permissions
		if($this->getUser()->hasPermission('calendar', $this->get('cid'))) {
			
			// check cid and pid given
			if ($this->get('cid') !== false && $this->get('pid') !== false) {
			
				// check cid and pid exists
				if(Calendar::check_id($this->get('cid')) && Preset::check_preset($this->get('pid'),'calendar')) {
					
					// pagecaption
					$this->tpl->assign('pagecaption',parent::lang('class.AnnouncementView#page#caption#delete'));
					
					// prepare return
					$return = '';
					
					// smarty-templates
					$sConfirmation = new JudoIntranetSmarty();
					
					// form
					$form = new Zebra_Form(
						'formConfirm',			// id/name
						'post',				// method
						'announcement.php?id=delete&cid='.$this->get('cid').'&pid='.$this->get('pid')		// action
					);
					// set language
					$form->language('deutsch');
					// set docktype xhtml
					$form->doctype('xhtml');
					
					// add button
					$form->add(
						'submit',		// type
						'buttonSubmit',	// id/name
						parent::lang('class.AnnouncementView#delete#form#yes'),	// value
						array('title' => parent::lang('class.AnnouncementView#delete#title#yes'))
					);
					
					// smarty-link
					$link = array(
									'params' => 'class="submit"',
									'href' => 'calendar.php?id=listall',
									'title' => parent::lang('class.AnnouncementView#delete#title#cancel'),
									'content' => parent::lang('class.AnnouncementView#delete#form#cancel')
								);
					$sConfirmation->assign('link', $link);
					$sConfirmation->assign('spanparams', 'id="cancel"');
					$sConfirmation->assign('message', parent::lang('class.AnnouncementView#delete#message#confirm').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_DELETE));
					$sConfirmation->assign('form', $form->render('', true));
					
					// validate
					if($form->validate()) {
					
						
						// get calendar-object
						$calendar = new Calendar($this->get('cid'));
						
						// get preset
						$preset = new Preset($this->get('pid'),'calendar',$this->get('cid'), $this);
						
						// get fields
						$fields = $preset->get_fields();
						
						// delete values of the fields
						if(Calendar::check_ann_value($calendar->get_id(),$calendar->get_preset_id()) === true) {
							
							foreach($fields as $field) {
								
								// delete value
								$field->deleteValue();
							}
						}
						
						// set preset to 0
						$calendar->update(array('preset_id' => 0));
						
						// smarty
						$sConfirmation->assign('message', parent::lang('class.AnnouncementView#delete#message#done'));
						$sConfirmation->assign('form', '');
						
						// write entry
						try {
							$calendar->write_db('update');
						} catch(Exception $e) {
							$this->getError()->handle_error($e);
							$output = $this->getError()->to_html($e);
						}
					}
					
					// smarty return
					return $sConfirmation->fetch('smarty.confirmation.tpl');
				} else {
					
					// error
					$errno = $this->getError()->error_raised('WrongParams','entry:cid_or_pid','cid_or_pid');
					$this->getError()->handle_error($errno);
					return $this->getError()->to_html($errno);
				}
			} else {
				
				// error
				$errno = $this->getError()->error_raised('MissingParams','entry:cid_or_pid','cid_or_pid');
				$this->getError()->handle_error($errno);
				return $this->getError()->to_html($errno);
			}
		} else {
			
			// error
			$errno = $this->getError()->error_raised('NotAuthorized','entry:'.$this->get('id'),$this->get('id'));
			$this->getError()->handle_error($errno);
			return $this->getError()->to_html($errno);
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
					
					// pagecaption
					$this->tpl->assign('pagecaption',parent::lang('class.AnnouncementView#page#caption#details'));
					
					// prepare return
					$return = '';
					
					// get preset
					$preset = new Preset($this->get('pid'),'calendar',$this->get('cid'), $this);
					
					// smarty
					$sA = new JudoIntranetSmarty();

					// get calendar
					$calendar = new Calendar($this->get('cid'));
					
					// prepare marker-array
					$announcement = array(
							'version' => '01.01.1970 01:00'
						);
					
					// add calendar-fields to array
					$calendar->add_marks($announcement);
					
					// add field-names and -values to array
					$preset->add_marks($announcement);
					
					// smarty
					$sA->assign('a', $announcement);
					// check marks in values
					foreach($announcement as $k => $v) {
						
						if(preg_match('/\{\$a\..*\}/U', $v)) {
							$announcement[$k] = $sA->fetch('string:'.$v);
						}
					}
					
					// smarty
					$sA->assign('a', $announcement);
					$div_out = $sA->fetch($preset->get_path());
					
					// smarty
					$sAd = new JudoIntranetSmarty();
					$sAd->assign('page', $div_out);
					return $sAd->fetch('smarty.announcement.details.tpl');
				} else {
					
					// error
					$errno = $this->getError()->error_raised('AnnNotExists','entry:'.$this->get('cid').'|'.$this->get('pid'),$this->get('cid').'|'.$this->get('pid'));
					$this->getError()->handle_error($errno);
					return $this->getError()->to_html($errno);
				}
			} else {
				
				// error
				$errno = $this->getError()->error_raised('WrongParams','entry:cid_or_pid','cid_or_pid');
				$this->getError()->handle_error($errno);
				return $this->getError()->to_html($errno);
			}
		} else {
			
			// error
			$errno = $this->getError()->error_raised('MissingParams','entry:cid_or_pid','cid_or_pid');
			$this->getError()->handle_error($errno);
			return $this->getError()->to_html($errno);
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
					$preset = new Preset($this->get('pid'),'calendar',$this->get('cid'), $this);
					
					// smarty
					$sA = new JudoIntranetSmarty();
					
					// get calendar
					$calendar = new Calendar($this->get('cid'));
					
					// prepare marker-array
					$announcement = array(
							'version' => '01.01.70 01:00'
						);
					
					// add calendar-fields to array
					$calendar->add_marks($announcement);
					
					// add field-names and -values to array
					$preset->add_marks($announcement);
					
					// smarty
					$sA->assign('a', $announcement);
					// check marks in values
					foreach($announcement as $k => $v) {
						
						if(preg_match('/\{\$a\..*\}/U', $v)) {
							$announcement[$k] = $sA->fetch('string:'.$v);
						}
					}
					
					// smarty
					$sA->assign('a', $announcement);
					$pdf_out = $sA->fetch($preset->get_path());			
					
					// get HTML2PDF-object
					$pdf = new HTML2PDF('P', 'A4', 'de', true, 'UTF-8', array(0, 0, 0, 0));
					
					// convert
					$pdf->writeHTML($pdf_out, false);
					
					// output
					$pdf_filename = $this->replace_umlaute(html_entity_decode($sA->fetch('string:'.$preset->get_filename()),ENT_COMPAT,'ISO-8859-1'));
					$pdf->Output($pdf_filename,'D');
					
					// return
					return $return;
				} else {
					
					// error
					$errno = $this->getError()->error_raised('AnnNotExists','entry:'.$this->get('cid').'|'.$this->get('pid'),$this->get('cid').'|'.$this->get('pid'));
					$this->getError()->handle_error($errno);
					return $this->getError()->to_html($errno);
				}
			} else {
				
				// error
				$errno = $this->getError()->error_raised('WrongParams','entry:cid_or_pid','cid_or_pid');
				$this->getError()->handle_error($errno);
				return $this->getError()->to_html($errno);
			}
		} else {
			
			// error
			$errno = $this->getError()->error_raised('MissingParams','entry:cid_or_pid','cid_or_pid');
			$this->getError()->handle_error($errno);
			return $this->getError()->to_html($errno);
		}
	}
}



?>
