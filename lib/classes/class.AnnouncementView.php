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
		$this->getTpl()->assign('pagename',_l('announcement'));
		
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
						$this->getTpl()->assign('title', $this->title(_l('announcement: new entry')));
						$this->getTpl()->assign('main', $this->newEntry());
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
					break;
					
					case 'edit':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('announcement: edit')));
						$this->getTpl()->assign('main', $this->edit());
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
					break;
					
					case 'delete':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('announcement: delete')));
						$this->getTpl()->assign('main', $this->delete());
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
					break;
					
					case 'details':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('announcement: details')));
						$this->getTpl()->assign('main', $this->details());
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', false);
					break;
					
					case 'topdf':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('announcement: pdf')));
						$this->getTpl()->assign('main', $this->topdf());
						$this->getTpl()->assign('jquery', false);
						$this->getTpl()->assign('zebraform', false);
					break;
					
					case 'refreshpdf':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('announcement: refreshpdf')));
						$refreshPdf = new AnnouncementViewRefreshpdf();
						$this->getTpl()->assign('main', $refreshPdf->show());
						$this->getTpl()->assign('jquery', false);
						$this->getTpl()->assign('zebraform', false);
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
			$this->getTpl()->assign('title', $this->title(_l('announcement')));
			// smarty-pagecaption
			$this->getTpl()->assign('pagecaption', $this->defaultContent()); 
			// smarty-main
			$this->getTpl()->assign('main', '');
			// smarty-jquery
			$this->getTpl()->assign('jquery', true);
			// smarty-hierselect
			$this->getTpl()->assign('zebraform', false);
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
						_l('save')	// value
					);
					
					// validate
					if($form->validate()) {
						
						// get calendar
						$calendar = new Calendar($this->get('cid'));
						
						// prepare marker-array
						$announcement = array(
								'version' => '01.01.1970 01:00'
							);
						
						// get data
						$data = $this->getFormValues($formIds);
						
						// insert values
						foreach($fields as $field) {
							
							// values to db
							$field->value($data[$field->get_table().'-'.$field->get_id()]);
							$field->writeDb('insert');
						}
						
						// create cached file
						$fid = File::idFromCache('calendar|'.$calendar->get_id());
						$calendar->createCachedFile($fid);
						
						// add calendar-fields to array
						$calendar->add_marks($announcement);
						
						// add field-names and -values to array
						$preset->add_marks($announcement);
						
						// set js redirection
						$this->jsRedirectTimeout('calendar.php?id=listall');
						
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
					throw new WrongParamsException($this, 'cid_or_pid');
				}
			} else {
				throw new MissingParamsException($this, 'cid_or_pid');
			}
		} else {
			throw new NotAuthorizedException($this);
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
		if($this->getUser()->hasPermission('calendar', $this->get('cid'), 'w')) {
			
			// check cid and pid given
			if ($this->get('cid') !== false && $this->get('pid') !== false) {
			
				// check cid and pid exists
				if(Calendar::check_id($this->get('cid')) && Preset::check_preset($this->get('pid'),'calendar')) {
					
					// pagecaption
					$this->getTpl()->assign('pagecaption',_l('edit entry'));
					
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
						_l('save')	// value
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
						
						// create cached file
						$fid = File::idFromCache('calendar|'.$calendar->get_id());
						$calendar->createCachedFile($fid);
						
						// add calendar-fields to array
						$calendar->add_marks($announcement);
						
						// add field-names and -values to array
						$preset->add_marks($announcement);
						
						// set js redirection
						$this->jsRedirectTimeout('calendar.php?id=listall');
						
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
					throw new WrongParamsException($this, 'cid_or_pid');
				}
			} else {
				throw new MissingParamsException($this, 'cid_or_pid');
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
	protected function delete() {
	
		// check permissions
		if($this->getUser()->hasPermission('calendar', $this->get('cid'), 'w')) {
			
			// check cid and pid given
			if ($this->get('cid') !== false && $this->get('pid') !== false) {
			
				// check cid and pid exists
				if(Calendar::check_id($this->get('cid')) && Preset::check_preset($this->get('pid'),'calendar')) {
					
					// pagecaption
					$this->getTpl()->assign('pagecaption',_l('delete entry'));
					
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
						_l('yes'),	// value
						array('title' => _l('yes'))
					);
					
					// smarty-link
					$link = array(
									'params' => 'class="submit"',
									'href' => 'calendar.php?id=listall',
									'title' => _l('cancel'),
									'content' => _l('cancel')
								);
					$sConfirmation->assign('link', $link);
					$sConfirmation->assign('spanparams', 'id="cancel"');
					$sConfirmation->assign('message', _l('you really want to delete').'&nbsp;'.$this->helpButton(HELP_MSG_DELETE));
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
						if(Calendar::check_ann_value($calendar->get_id()) === true) {
							
							foreach($fields as $field) {
								
								// delete value
								$field->deleteValue();
							}
						}
						
						// set preset to 0
						$calendar->update(array('preset_id' => 0));
						
						// delete cached file
						$fid = File::idFromCache('calendar|'.$calendar->get_id());
						if($fid !== false) {
							File::delete($fid);
						}
						// delete attachments
						File::deleteAttachedFiles('calendar',$calendar->get_id());
						
						// smarty
						$sConfirmation->assign('message', _l('entry successful deleted'));
						$sConfirmation->assign('form', '');
						
						// set js redirection
						$this->jsRedirectTimeout('calendar.php?id=listall');
						
						// write entry
						$calendar->write_db('update');
					}
					
					// smarty return
					return $sConfirmation->fetch('smarty.confirmation.tpl');
				} else {
					throw new WrongParamsException($this, 'cid_or_pid');
				}
			} else {
				throw new MissingParamsException($this, 'cid_or_pid');
			}
		} else {
			throw new NotAuthorizedException($this);
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
				$draftValue = Calendar::getDraftValue($this->get('pid'), $this->get('cid'));
				if(	Calendar::check_ann_value($this->get('cid'))
					&& ($draftValue == 0
						|| ($draftValue == 1 && $this->getUser()->get_loggedin()))) {
					
					// pagecaption
					$this->getTpl()->assign('pagecaption',_l('details'));
					
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
					throw new AnnNotExistsException($this, $this->get('cid').'|'.$this->get('pid'));
				}
			} else {
				throw new WrongParamsException($this, 'cid_or_pid');
			}
		} else {
			throw new MissingParamsException($this, 'cid_or_pid');
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
				$draftValue = Calendar::getDraftValue($this->get('pid'), $this->get('cid'));
				if(	Calendar::check_ann_value($this->get('cid'))
					&& ($draftValue == 0
						|| ($draftValue == 1 && $this->getUser()->get_loggedin()))) {
					
					// prepare return
					$return = '';
					
					// redirect to FileView::download()
					$this->redirectTo('file',
						array(
								'id' => 'cached',
								'table' => 'calendar',
								'tid' => $this->get('cid'),
							)
						);
					
					// return
					return $return;
				} else {
					throw new AnnNotExistsException($this, $this->get('cid').'|'.$this->get('pid'));
				}
			} else {
				throw new WrongParamsException($this, 'cid_or_pid');
			}
		} else {
			throw new MissingParamsException($this, 'cid_or_pid');
		}
	}
}



?>
