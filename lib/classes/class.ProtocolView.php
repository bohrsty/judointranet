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
 * class ProocolView implements the control of the protocol-page
 */
class ProtocolView extends PageView {
	
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
	
	/**
	 * init chooses the functionality by using $_GET['id']
	 * 
	 * @return void
	 */
	public function init() {
		
		// set pagename
		$this->tpl->assign('pagename',parent::lang('class.ProtocolView#page#init#name'));
		
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
						$this->tpl->assign('title', $this->title(parent::lang('class.ProtocolView#init#title#listall')));
						$this->tpl->assign('main', $this->listall());
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('zebraform', false);
						$this->tpl->assign('tinymce', false);
					break;
					
					case 'new':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.ProtocolView#init#title#new')));
						$this->tpl->assign('main', $this->newEntry());
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('zebraform', true);
						$this->tpl->assign('tinymce', true);
					break;
					
					case 'details':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.ProtocolView#init#title#details')));
						$this->tpl->assign('main', $this->details($this->get('pid')));
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('zebraform', false);
						$this->tpl->assign('tinymce', false);
					break;
					
					case 'edit':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.ProtocolView#init#title#edit')));
						$this->tpl->assign('main', $this->edit($this->get('pid')));
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('zebraform', true);
						$this->tpl->assign('tinymce', true);
					break;
					
					case 'show':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.ProtocolView#init#title#show')));
						$this->tpl->assign('main', $this->show($this->get('pid')));
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('zebraform', false);
						$this->tpl->assign('tinymce', false);
					break;
					
					case 'topdf':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.ProtocolView#init#title#topdf')));
						$this->tpl->assign('main', $this->topdf($this->get('pid')));
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('zebraform', false);
						$this->tpl->assign('tinymce', false);
					break;
					
					case 'delete':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.ProtocolView#init#title#topdf')));
						$this->tpl->assign('main', $this->delete($this->get('pid')));
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('zebraform', true);
						$this->tpl->assign('tinymce', true);
					break;
					
					case 'correct':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.ProtocolView#init#title#correct')));
						$this->tpl->assign('main', $this->correct($this->get('pid')));
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('zebraform', true);
						$this->tpl->assign('tinymce', true);
					break;
					
					case 'showdecisions':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.ProtocolView#init#title#decisions')));
						$this->tpl->assign('main', $this->decisions($this->get('pid')));
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('zebraform', false);
						$this->tpl->assign('tinymce', false);
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
						$this->tpl->assign('tinymce', false);
					break;
				}
			} else {
				
				// error not authorized
				$errno = $this->getError()->error_raised('NotAuthorized','entry:'.$this->get('id'),$this->get('id'));
				$this->getError()->handle_error($errno);
				
				// smarty
				$this->tpl->assign('title', $this->title(parent::lang('class.ProtocolView#init#Error#NotAuthorized')));
				$this->tpl->assign('main', $this->getError()->to_html($errno));
				$this->tpl->assign('jquery', true);
				$this->tpl->assign('zebraform', false);
				$this->tpl->assign('tinymce', false);
			}
		} else {
			
			// id not set
			// smarty-title
			$this->tpl->assign('title', $this->title(parent::lang('class.ProtocolView#init#title#default'))); 
			// smarty-main
			$this->tpl->assign('main', $this->defaultContent());
			// smarty-jquery
			$this->tpl->assign('jquery', true);
			// smarty-hierselect
			$this->tpl->assign('zebraform', false);
			// smarty-tiny_mce
			$this->tpl->assign('tinymce', false);
		}
		
		// global smarty
		$this->showPage('smarty.main.tpl');
	}
	
	
	
	
	
	
	
	/**
	 * default_content returns the html-content to be displayed on page without
	 * parameters or functions
	 * 
	 * @return string html-content as default content
	 */
	protected function defaultContent() {
		
		// smarty-template
		$sD = new JudoIntranetSmarty();
		
		// smarty
		$sD->assign('caption', parent::lang('class.ProtocolView#defaultContent#headline#text'));
		$text[] = array(
				'caption' => '',
				'text' => ''
			);
		$sD->assign('text', $text);
		
		// return
		return $sD->fetch('smarty.default.content.tpl');
	}
	
	
	
	
	
	
	
	/**
	 * listall lists all protocol entries (paged)
	 * shows only entrys for which the user has sufficient rights
	 * 
	 * @return void
	 */
	private function listall() {
		
		// pagecaption
		$this->tpl->assign('pagecaption',parent::lang('class.ProtocolView#page#caption#listall').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_PROTOCOLLISTALL));
		
		// read all entries
		$entries = $this->readAllEntries();
				
		// smarty-templates
		$sListall = new JudoIntranetSmarty();
		
		// smarty
		$sTh = array(
				'date' => parent::lang('class.ProtocolView#listall#TH#date'),
				'type' => parent::lang('class.ProtocolView#listall#TH#type'),
				'location' => parent::lang('class.ProtocolView#listall#TH#location'),
				'show' => parent::lang('class.ProtocolView#listall#TH#show'),
				'admin' => parent::lang('class.ProtocolView#listall#TH#admin')
			);

		$sListall->assign('th', $sTh);
		// loggedin? admin links
		$sListall->assign('loggedin', $this->getUser()->get_loggedin());
		
		// walk through entries
		$counter = 0;
		// smarty
		$sList = array();
		foreach($entries as $no => $entry) {
			
			// check if valid
			if($entry->get_valid() == 1) {
				
				// get status
				$correctable = $entry->get_correctable(false);
				
				// smarty
				$sList[$counter] = array(
						'date' => array(
								'href' => 'protocol.php?id=details&pid='.$entry->get_id(),
								'title' => parent::lang('class.ProtocolView#listall#title#date'),
								'date' => $entry->get_date('d.m.Y')
							),
						'type' => $entry->get_type(),
						'location' => $entry->get_location()
						
					);
				
				// check status
				if($correctable['status'] == 2 || $this->getUser()->get_userinfo('name') == $entry->get_owner()) {
					
					// show
					$sList[$counter]['show'][0] = array(
							'href' => 'protocol.php?id=show&pid='.$entry->get_id(),
							'title' => parent::lang('class.ProtocolView#listall#title#ProtShow'),
							'src' => 'img/prot_details.png',
							'alt' => parent::lang('class.ProtocolView#listall#alt#ProtShow'),
							'show' => true
						);
					$sList[$counter]['show'][1] = array(
							'href' => 'file.php?id=cached&table=protocol&tid='.$entry->get_id(),
							'title' => parent::lang('class.ProtocolView#listall#title#ProtPDF'),
							'src' => 'img/prot_pdf.png',
							'alt' => parent::lang('class.ProtocolView#listall#alt#ProtPDF'),
							'show' => true
						);
				} else {
					
					$sList[$counter]['show'][0] = array(
							'href' => '',
							'title' => '',
							'src' => '',
							'alt' => '',
							'show' => false
						);
					$sList[$counter]['show'][1] = array(
							'href' => '',
							'title' => '',
							'src' => '',
							'alt' => '',
							'show' => false
						);
				}
				
				// add attached file info
				if(count(File::attachedTo('protocoll', $entry->get_id())) > 0) {
					
					$sList[$counter]['show'][2] = array(
							'href' => 'protocol.php?id=details&pid='.$entry->get_id(),
							'title' => parent::lang('class.ProtocolView#listall#title#filesAttached'),
							'src' => 'img/attachment_info.png',
							'alt' => parent::lang('class.ProtocolView#listall#alt#filesAttached'),
							'show' => true
						);
				} else {
					
					$sList[$counter]['show'][2] = array(
							'href' => '',
							'title' => '',
							'src' => '',
							'alt' => '',
							'show' => false
						);
				}
					
				// add admin
				
				// if user is loggedin add admin-links
				if($this->getUser()->get_loggedin() === true) {
					
					// edit and delete only for author
					if($this->getUser()->get_userinfo('name') == $entry->get_owner()
						|| $this->getUser()->get_id() == 1) {
						
						// smarty
						// edit
						$sList[$counter]['admin'][] = array(
								'href' => 'protocol.php?id=edit&pid='.$entry->get_id(),
								'title' => parent::lang('class.ProtocolView#listall#title#edit'),
								'src' => 'img/prot_edit.png',
								'alt' => parent::lang('class.ProtocolView#listall#alt#edit')
							);
						// delete
						$sList[$counter]['admin'][] = array(
								'href' => 'protocol.php?id=delete&pid='.$entry->get_id(),
								'title' => parent::lang('class.ProtocolView#listall#title#delete'),
								'src' => 'img/prot_delete.png',
								'alt' => parent::lang('class.ProtocolView#listall#alt#delete')
							);
						// attachment
						$sList[$counter]['admin'][] = array(
								'href' => 'file.php?id=attach&table=protocol&tid='.$entry->get_id(),
								'title' => parent::lang('class.ProtocolView#listall#title#attach'),
								'src' => 'img/attachment.png',
								'alt' => parent::lang('class.ProtocolView#listall#alt#attach')
							);
					}
					
					// correction
					if($correctable['status'] == 1 && in_array($this->getUser()->get_id(),$correctable['correctors']) && $this->getUser()->get_userinfo('name') != $entry->get_owner()) {
						
						// check if correction is finished
						$correction = new ProtocolCorrection($entry);
						
						if($correction->get_finished() == 1) {
							$sList[$counter]['admin'][] = array(
									'href' => false,
									'title' => parent::lang('class.ProtocolView#listall#title#correctionFinished'),
									'src' => 'img/done.png',
									'alt' => parent::lang('class.ProtocolView#listall#alt#correctionFinished')
								);
						} else {
							$sList[$counter]['admin'][] = array(
									'href' => 'protocol.php?id=correct&pid='.$entry->get_id(),
									'title' => parent::lang('class.ProtocolView#listall#title#correct'),
									'src' => 'img/prot_correct.png',
									'alt' => parent::lang('class.ProtocolView#listall#alt#correct')
								);
						}
					}
					
					// corrected
					if($correctable['status'] == 1 && $this->getUser()->get_userinfo('name') == $entry->get_owner() && $entry->hasCorrections()) {
						
						$sList[$counter]['admin'][] = array(
								'href' => 'protocol.php?id=correct&pid='.$entry->get_id().'&action=diff',
								'title' => parent::lang('class.ProtocolView#listall#title#corrected'),
								'src' => 'img/prot_corrected.png',
								'alt' => parent::lang('class.ProtocolView#listall#alt#corrected')
							);
					}
					
				} else {
					
					// smarty
					$sList[$counter]['admin'][] = array(
							'href' => '',
							'title' => '',
							'src' => '',
							'alt' => ''
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
		// prepare admin help
		$helpListAdmin = $this->getHelp()->getMessage(HELP_MSG_PROTOCOLLISTADMIN);
		if(isset($helpListAdmin)) {
			$sListall->assign('helpListAdmin', $helpListAdmin);
		}
		
		// smarty-return
		return $sListall->fetch('smarty.protocol.listall.tpl');
	}
	
	
	
	
	
	
	
	/**
	 * readAllEntries() get all protocol-entries from db for that the actual
	 * user has sufficient rights. returns an array with protocol-objects
	 * 
	 * @return array all entries as calendar-objects
	 */
	private function readAllEntries() {
		
		// prepare return
		$protocols = array();
		
		// get protocol objects
		$protocol_entries = $this->getUser()->permittedItems('protocol', 'w');
		foreach($protocol_entries as $protocolId) {
			$protocols[] = new Protocol($protocolId);
		}
		
		// sort protocol-entries
		usort($protocols, array($this, 'callbackCompareProtocols'));
		
		// return protocol-objects
		return $protocols;
	}
	
	
	
	
	
	
	
	/**
	 * callbackCompareProtocols($first, $second) compares two protocol-objects by date (for uksort)
	 * 
	 * @param object $first first protocol-objects
	 * @param object $second second protocol-object
	 * @return int -1 if $first<$second, 0 if equal, 1 if $first>$second
	 */
	public function callbackCompareProtocols($first,$second) {
	
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
	 * newEntry() creates the "new-entry"-form and handle its response
	 * 
	 * @return string html-string with the "new-entry"-form
	 */
	private function newEntry() {
		
		// pagecaption
		$this->tpl->assign('pagecaption',parent::lang('class.ProtocolView#page#caption#newEntry').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_PROTOCOLNEW));
		
		// smarty-templates
		$sD = new JudoIntranetSmarty();
		
		// prepare return
		$return = '';
		
		// prepare form
		$form = new Zebra_Form(
				'newProtocol',	// id/name
				'post',					// method
				'protocol.php?id=new'		// action
			);
		// set language
		$form->language('deutsch');
		// set docktype xhtml
		$form->doctype('xhtml');
		
		// elements
		// preset
		$options = Preset::read_all_presets('protocol');
		$paths = Preset::readAllPaths('protocol');
		$formIds['preset'] = array('valueType' => 'int', 'type' => 'select',);
		$form->add(
				'label',		// type
				'labelPreset',	// id/name
				'preset',		// for
				parent::lang('class.ProtocolView#entry#form#preset').':'	// label text
			);
		$preset = $form->add(
				$formIds['preset']['type'],	// type
				'preset',	// id/name
				'',			// default
				array(		// attributes
					)
			);
		$form->add(
				'note',			// type
				'notePreset',	// id/name
				'preset',		// for
				parent::lang('class.ProtocolView#global#info#help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDPRESET)	// note text
			);
		$preset->add_options($options);
		$preset->set_rule(
			array(
					'required' => array(
							'error', parent::lang('class.ProtocolView#entry#rule#required.preset')
						),
				)
		);
		// add jquery for changing css
		$this->tpl->assign('protocolPaths', json_encode($paths));
		$this->tpl->assign('protocolSelectPreset', 'preset');
		
		// date
		$formIds['date'] = array('valueType' => 'string', 'type' => 'date',);
		$form->add(
				'label',		// type
				'labelDate',	// id/name
				'date',			// for
				parent::lang('class.ProtocolView#entry#form#date').':'	// label text
			);
		$date = $form->add(
						$formIds['date']['type'],			// type
						'date',			// id/name
						date('d.m.Y')	// default
			);
		$form->add(
				'note',			// type
				'noteDate',		// id/name
				'date',			// for
				parent::lang('class.ProtocolView#global#info#help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDDATE)	// note text
			);
		// format/position
		$date->format('d.m.Y');
		$date->inside(false);
		// rules
		$date->set_rule(
			array(
				'required' => array(
						'error', parent::lang('class.ProtocolView#entry#rule#required.date'),
					),
				'date' => array(
						'error', parent::lang('class.ProtocolView#entry#rule#check.date')
					),
				)
			);
		
		// type
		$options = Protocol::return_types();
		$formIds['type'] = array('valueType' => 'int', 'type' => 'select',);
		$form->add(
				'label',		// type
				'labelType',	// id/name
				'type',			// for
				parent::lang('class.ProtocolView#entry#form#type').':'	// label text
			);
		$type = $form->add(
				$formIds['type']['type'],	// type
				'type',	// id/name
				'',			// default
				array(		// attributes
					)
			);
		$form->add(
				'note',			// type
				'noteType',	// id/name
				'type',		// for
				parent::lang('class.ProtocolView#global#info#help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDTYPE)	// note text
			);
		$type->add_options($options);
		$type->set_rule(
			array(
					'required' => array(
							'error', parent::lang('class.ProtocolView#entry#rule#required.type')
						),
				)
		);
		
		// location
		$formIds['location'] = array('valueType' => 'string', 'type' => 'text',);
		$form->add(
				'label',		// type
				'labelLocation',	// id/name
				'location',			// for
				parent::lang('class.ProtocolView#entry#form#location'),	// label text
				array('inside' => true,)	// label inside
			);
		$location = $form->add(
						$formIds['location']['type'],		// type
						'location'		// id/name
			);
		$form->add(
				'note',			// type
				'noteLocation',	// id/name
				'location',		// for
				parent::lang('class.ProtocolView#global#info#help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDALLTEXT)	// note text
			);
		
		// add rules
		$location->set_rule(
				array(
						'regexp' => array(
								$this->getGc()->get_config('textarea.regexp.zebra'),
								'error',
								parent::lang('class.ProtocolView#entry#rule#regexp.allowedChars').' ['.$this->getGc()->get_config('textarea.desc').']',
							),
						'required' => array(
								'error',
								parent::lang('class.ProtocolView#entry#rule#required.location'),
							),
					)
			);
		
		// member0
		$formIds['member0'] = array('valueType' => 'string', 'type' => 'text',);
		$form->add(
				'label',		// type
				'labelMember0',	// id/name
				'member0',			// for
				parent::lang('class.ProtocolView#entry#form#member0'),	// label text
				array('inside' => true,)	// label inside
			);
		$member0 = $form->add(
						$formIds['member0']['type'],		// type
						'member0'		// id/name
			);
		
		// add rules
		$member0->set_rule(
				array(
						'regexp' => array(
								$this->getGc()->get_config('textarea.regexp.zebra'),
								'error',
								parent::lang('class.ProtocolView#entry#rule#regexp.allowedChars').' ['.$this->getGc()->get_config('textarea.desc').']',
							),
					)
			);
		$form->add(
				'note',			// type
				'noteMember0',	// id/name
				'member0',		// for
				parent::lang('class.ProtocolView#global#info#help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDALLTEXT)	// note text
			);
		
		// member1
		$formIds['member1'] = array('valueType' => 'string', 'type' => 'text',);
		$form->add(
				'label',		// type
				'labelMember1',	// id/name
				'member1',			// for
				parent::lang('class.ProtocolView#entry#form#member1'),	// label text
				array('inside' => true,)	// label inside
			);
		$member1 = $form->add(
						$formIds['member1']['type'],		// type
						'member1'		// id/name
			);
		$form->add(
				'note',			// type
				'noteMember1',	// id/name
				'member1',		// for
				parent::lang('class.ProtocolView#global#info#help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDALLTEXT)	// note text
			);
		
		// add rules
		$member1->set_rule(
				array(
						'regexp' => array(
								$this->getGc()->get_config('textarea.regexp.zebra'),
								'error',
								parent::lang('class.ProtocolView#entry#rule#regexp.allowedChars').' ['.$this->getGc()->get_config('textarea.desc').']',
							),
					)
			);
		
		// member2
		$formIds['member2'] = array('valueType' => 'string', 'type' => 'text',);
		$form->add(
				'label',		// type
				'labelMember2',	// id/name
				'member2',			// for
				parent::lang('class.ProtocolView#entry#form#member2'),	// label text
				array('inside' => true,)	// label inside
			);
		$member2 = $form->add(
						$formIds['member2']['type'],		// type
						'member2'		// id/name
			);
		$form->add(
				'note',			// type
				'noteMember2',	// id/name
				'member2',		// for
				parent::lang('class.ProtocolView#global#info#help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDALLTEXT)	// note text
			);
		
		// add rules
		$member2->set_rule(
				array(
						'regexp' => array(
								$this->getGc()->get_config('textarea.regexp.zebra'),
								'error',
								parent::lang('class.ProtocolView#entry#rule#regexp.allowedChars').' ['.$this->getGc()->get_config('textarea.desc').']',
							),
					)
			);
		
		// recorder
		$formIds['recorder'] = array('valueType' => 'string', 'type' => 'text',);
		$form->add(
				'label',		// type
				'labelRecorder',	// id/name
				'recorder',			// for
				parent::lang('class.ProtocolView#entry#form#recorder'),	// label text
				array('inside' => true,)	// label inside
			);
		$recorder = $form->add(
						$formIds['recorder']['type'],		// type
						'recorder'		// id/name
			);
		$form->add(
				'note',			// type
				'noteRecorder',	// id/name
				'recorder',		// for
				parent::lang('class.ProtocolView#global#info#help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDALLTEXT)	// note text
			);
		
		// add rules
		$recorder->set_rule(
				array(
						'regexp' => array(
								$this->getGc()->get_config('textarea.regexp.zebra'),
								'error',
								parent::lang('class.ProtocolView#entry#rule#regexp.allowedChars').' ['.$this->getGc()->get_config('textarea.desc').']',
							),
						'required' => array(
								'error',
								parent::lang('class.ProtocolView#entry#rule#required.recorder'),
							),
					)
			);
		
		// protocol text
		$formIds['protocol'] = array('valueType' => 'string', 'type' => 'textarea',);
		$form->add(
				'label',		// type
				'labelProtocol',	// id/name
				'protocol',			// for
				parent::lang('class.ProtocolView#entry#form#protocol').':'	// label text
			);
		$protocolTa = $form->add(
						$formIds['protocol']['type'],		// type
						'protocol'		// id/name
			);
		$form->add(
				'note',			// type
				'noteProtocol',	// id/name
				'protocol',		// for
				parent::lang('class.ProtocolView#global#info#help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDALLTEXT)	// note text
			);
		
		// add rules
		$protocolTa->set_rule(
				array(
						'regexp' => array(
								$this->getGc()->get_config('textarea.regexp.zebra'),
								'error',
								parent::lang('class.ProtocolView#entry#rule#regexp.allowedChars').' ['.$this->getGc()->get_config('textarea.desc').']',
							),
					)
			);
		
		// js tiny_mce
		$tmce = array(
				'element' => 'protocol',
				'css' => 'templates/protocols/tmce_'.$this->getGc()->get_config('tmce.default.css').'.css',
				'transitem' => parent::lang('class.ProtocolView#newEntry#tmce#item'),
				'transdecision' => parent::lang('class.ProtocolView#newEntry#tmce#decision'),
				'action' => 'new',
			);
		// smarty
		$this->tpl->assign('tmce',$tmce);
		
		
		// checkbox public
		$formIds['public'] = array('valueType' => 'int', 'type' => 'checkbox', 'default' => 1);
		$form->add(
				'label',		// type
				'labelPublic',	// id/name
				'public',		// for
				parent::lang('class.ProtocolView#entry#form#public')	// label text
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
				parent::lang('class.ProtocolView#global#info#help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDISPUBLIC)	// note text
			);
		
		// permissions
		$result = $this->zebraAddPermissions($form, 'protocol');
		$form = $result['form'];
		$permissionConfig['ids'] = $result['formIds'];
		$permissionConfig['iconRead'] = $result['iconRead'];
		$permissionConfig['iconEdit'] = $result['iconEdit'];
		
		// submit-button
		$form->add(
				'submit',		// type
				'buttonSubmit',	// id/name
				parent::lang('class.ProtocolView#entry#form#submitButton')	// value
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
			
			$protocol = new Protocol(array(
								'date' => $data['date'],
								'type' => $data['type'],
								'location' => $data['location'],
								'member' => $data['member0'].'|'.$data['member1'].'|'.$data['member2'],
								'protocol' => $data['protocol'],
								'preset' => $data['preset'],
								'owner' => $this->getUser()->get_id(),
								'recorder' => $data['recorder'],
								'valid' => 1,
								'correctable' => "0|"
								)
				);
				
			// write to db
			$protocol->writeDb();
			
			// write permissions
			$protocol->dbDeletePermission();
			$protocol->dbWritePermission($permissions);
						
			// create cached file
			$fid = File::idFromCache('protocol|'.$protocol->get_id());
			$protocol->createCachedFile($fid);
			
			// smarty
			$sCD = new JudoIntranetSmarty();
			$sCD->assign('data', $protocol->details());
			return $sCD->fetch('smarty.protocol.details.tpl');
		} else {
			return $form->render('lib/zebraTemplate.php', true, array($formIds, 'smarty.zebra.permissions.tpl', $permissionConfig,));
		}
	}
	
	
	
	
	
	
	
	/**
	 * details returns the details of a protocol-entry as html-string
	 * 
	 * @param int $pid entry-id for protocol
	 * @return string html-string with the details of the protocol entry
	 */
	private function details($pid) {
	
		// pagecaption
		$this->tpl->assign('pagecaption',parent::lang('class.ProtocolView#page#caption#details'));
		
		// check rights
		if($this->getUser()->hasPermission('protocol', $pid)) {
				
			// get protocol-object
			$protocol = new Protocol($pid);
			$correctable = $protocol->get_correctable(false);
			// check status
			$status = false;
			if($correctable['status'] == 2 || $this->getUser()->get_userinfo('name') == $protocol->get_owner()) {
				$status = true;
			}
			
			// smarty-template
			$sPD = new JudoIntranetSmarty();
			
			// smarty
			$sPD->assign('data', $protocol->details());
			$sPD->assign('status', $status);
			
			// prepare links
			// show
			$links[] = array(
					'href' => 'protocol.php?id=show&pid='.$protocol->get_id(),
					'title' => parent::lang('class.ProtocolView#details#show#title'),
					'name' => parent::lang('class.ProtocolView#details#show#name')
				);
			// decisions
			$links[] = array(
					'href' => 'protocol.php?id=showdecisions&pid='.$protocol->get_id(),
					'title' => parent::lang('class.ProtocolView#details#decisions#title'),
					'name' => parent::lang('class.ProtocolView#details#decisions#name')
				);
			// topdf
			$links[] = array(
					'href' => 'file.php?id=cached&table=protocol&tid='.$protocol->get_id(),
					'title' => parent::lang('class.ProtocolView#details#topdf#title'),
					'name' => parent::lang('class.ProtocolView#details#topdf#name')
				);
			$sPD->assign('links',$links);
			
			// create file objects
			$fileIds = File::attachedTo('protocol', $pid);
			$fileObjects = array();
			foreach($fileIds as $id) {
				$fileObjects[] = new File($id);
			}
			$sPD->assign('files', $fileObjects);
			$sPD->assign('attached', parent::lang('class.ProtocolView#details#text#attached'));
			$sPD->assign('none', parent::lang('class.ProtocolView#details#text#none'));
			$sPD->assign('fileHref', 'file.php?id=download&fid=');
			
			return $sPD->fetch('smarty.protocol.details.tpl');
		} else {
			
			// error
			$errno = $this->getError()->error_raised('NotAuthorized','entry:'.$this->get('id'),$this->get('id'));
			$this->getError()->handle_error($errno);
			return $this->getError()->to_html($errno);
		}
	}
	
	
	
	
	
	
	
	/**
	 * edit returns the protocoltext as html-string for the edit page
	 * 
	 * @param int $pid entry-id for protocol
	 * @return string html-string with the protocoltext
	 */
	private function edit($pid) {
	
		// check rights
		if($this->getUser()->hasPermission('protocol', $pid)) {
			
			// pagecaption
			$this->tpl->assign('pagecaption',parent::lang('class.ProtocolView#page#caption#edit'));
			
			// get protocol-object
			$protocol = new Protocol($pid);
			
			// smarty-templates
			$sD = new JudoIntranetSmarty();
			
			// formular
			$form = new Zebra_Form(
					'editProtocol',	// id/name
					'post',					// method
					'protocol.php?id=edit&pid='.$pid		// action
				);
			// set language
			$form->language('deutsch');
			// set docktype xhtml
			$form->doctype('xhtml');
			
			// get correction status and correctors
			$correctable = $protocol->get_correctable(false);
			
			// elements
			// correction
			// radio
			$formIds['correctable'] = array('valueType' => 'int', 'type' => 'radios', 'default' => 1);
			$form->add(
					'label',		// type
					'labelCorrectable',	// id/name
					'correctable',		// for
					parent::lang('class.ProtocolView#entry#form#correction').':'	// label text
				);
			$form->add(
					$formIds['correctable']['type'],	// type
					'correctable',		// id/name
					array(				// values
							parent::lang('class.ProtocolView#entry#form#correctionInWork'),
							parent::lang('class.ProtocolView#entry#form#correctionCorrect'),
							parent::lang('class.ProtocolView#entry#form#correctionFinished'),
						),
					$correctable['status']	// default
				);
			$form->add(
					'note',			// type
					'noteCorrectable',	// id/name
					'correctable',		// for
					parent::lang('class.ProtocolView#global#info#help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_PROTOCOLCORRECTABLE)	// note text
				);
			
			// select correctors
			// get all users and put id and name to options
			$users = $this->getUser()->return_all_users(array($this->getUser()->get_userinfo('username')));
			$options = array();
			foreach($users as $user) {
				$options[$user->get_userinfo('id')] = $user->get_userinfo('name');
			}
			
			$formIds['correctors'] = array('valueType' => 'array', 'type' => 'select',);
			$form->add(
					'label',		// type
					'labelCorrectors',	// id/name
					'correctors',		// for
					parent::lang('class.ProtocolView#entry#form#correctors').':'	// label text
				);
			$correctors = $form->add(
					$formIds['correctors']['type'],	// type
					'correctors[]',					// id/name
					$correctable['correctors'],					// default
					array(						// attributes
							'multiple' => 'multiple',
							'size' => 5,
						)
				);
			$form->add(
					'note',			// type
					'noteCorrectors',	// id/name
					'correctors',		// for
					parent::lang('class.ProtocolView#global#info#help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_PROTOCOLCORRECTORS)	// note text
				);
			$correctors->add_options($options, true);
			
			// preset
			$presetObject = new Preset($protocol->get_preset()->get_id(), 'protocol', $protocol->get_id());
			$options = Preset::read_all_presets('protocol');
			$formIds['preset'] = array('valueType' => 'int', 'type' => 'select',);
			$form->add(
					'label',		// type
					'labelPreset',	// id/name
					'preset',		// for
					parent::lang('class.ProtocolView#entry#form#preset').':'	// label text
				);
			$preset = $form->add(
					$formIds['preset']['type'],	// type
					'preset',	// id/name
					$presetObject->get_id(),	// default
					array(		// attributes
						)
				);
			$form->add(
					'note',			// type
					'notePreset',	// id/name
					'preset',		// for
					parent::lang('class.ProtocolView#global#info#help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDPRESET)	// note text
				);
			$preset->add_options($options);
			$preset->set_rule(
				array(
						'required' => array(
								'error', parent::lang('class.ProtocolView#entry#rule#required.preset')
							),
					)
			);
			
			// date
			$formIds['date'] = array('valueType' => 'string', 'type' => 'date',);
			$form->add(
					'label',		// type
					'labelDate',	// id/name
					'date',			// for
					parent::lang('class.ProtocolView#entry#form#date')	// label text
				);
			$date = $form->add(
							$formIds['date']['type'],			// type
							'date',			// id/name
							$protocol->get_date('d.m.Y')	// default
				);
			// format/position
			$date->format('d.m.Y');
			$date->inside(false);
			// rules
			$date->set_rule(
				array(
					'required' => array(
							'error', parent::lang('class.ProtocolView#entry#rule#required.date'),
						),
					'date' => array(
							'error', parent::lang('class.ProtocolView#entry#rule#check.date')
						),
					)
				);
			$form->add(
					'note',			// type
					'noteDate',		// id/name
					'date',			// for
					parent::lang('class.ProtocolView#global#info#help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDDATE)	// note text
				);
			
			// type
			$options = Protocol::return_types();
			$formIds['type'] = array('valueType' => 'int', 'type' => 'select',);
			$form->add(
					'label',		// type
					'labelType',	// id/name
					'type',			// for
					parent::lang('class.ProtocolView#entry#form#type').':'	// label text
				);
			$type = $form->add(
					$formIds['type']['type'],	// type
					'type',	// id/name
					$protocol->get_type('i'),	// default
					array(		// attributes
						)
				);
			$form->add(
					'note',			// type
					'noteType',	// id/name
					'type',		// for
					parent::lang('class.ProtocolView#global#info#help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDTYPE)	// note text
				);
			$type->add_options($options);
			$type->set_rule(
				array(
						'required' => array(
								'error', parent::lang('class.ProtocolView#entry#rule#required.type')
							),
					)
			);
			
			// location
			$formIds['location'] = array('valueType' => 'string', 'type' => 'text',);
			$form->add(
					'label',		// type
					'labelLocation',	// id/name
					'location',			// for
					parent::lang('class.ProtocolView#entry#form#location')	// label text
				);
			$location = $form->add(
							$formIds['location']['type'],		// type
							'location',		// id/name
							$protocol->get_location()	// default
				);
			$form->add(
					'note',			// type
					'noteLocation',	// id/name
					'location',		// for
					parent::lang('class.ProtocolView#global#info#help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDALLTEXT)	// note text
				);
			
			// add rules
			$location->set_rule(
					array(
							'regexp' => array(
									$this->getGc()->get_config('textarea.regexp.zebra'),
									'error',
									parent::lang('class.ProtocolView#entry#rule#regexp.allowedChars').' ['.$this->getGc()->get_config('textarea.desc').']',
								),
							'required' => array(
									'error',
									parent::lang('class.ProtocolView#entry#rule#required.location'),
								),
						)
				);
			
			// member0
			$formIds['member0'] = array('valueType' => 'string', 'type' => 'text',);
			$form->add(
					'label',		// type
					'labelMember0',	// id/name
					'member0',			// for
					parent::lang('class.ProtocolView#entry#form#member0')	// label text
				);
			$member0 = $form->add(
							$formIds['member0']['type'],		// type
							'member0',	// id/name
							$protocol->get_member(false,0)	// default
				);
			$form->add(
					'note',			// type
					'noteMember0',	// id/name
					'member0',		// for
					parent::lang('class.ProtocolView#global#info#help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDALLTEXT)	// note text
				);
			
			// add rules
			$member0->set_rule(
					array(
							'regexp' => array(
									$this->getGc()->get_config('textarea.regexp.zebra'),
									'error',
									parent::lang('class.ProtocolView#entry#rule#regexp.allowedChars').' ['.$this->getGc()->get_config('textarea.desc').']',
								),
						)
				);
			
			// member1
			$formIds['member1'] = array('valueType' => 'string', 'type' => 'text',);
			$form->add(
					'label',		// type
					'labelMember1',	// id/name
					'member1',			// for
					parent::lang('class.ProtocolView#entry#form#member1')	// label text
				);
			$member1 = $form->add(
							$formIds['member1']['type'],		// type
							'member1',	// id/name
							$protocol->get_member(false,1)	// default
				);
			$form->add(
					'note',			// type
					'noteMember1',	// id/name
					'member1',		// for
					parent::lang('class.ProtocolView#global#info#help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDALLTEXT)	// note text
				);
			
			// add rules
			$member1->set_rule(
					array(
							'regexp' => array(
									$this->getGc()->get_config('textarea.regexp.zebra'),
									'error',
									parent::lang('class.ProtocolView#entry#rule#regexp.allowedChars').' ['.$this->getGc()->get_config('textarea.desc').']',
								),
						)
				);
			
			// member2
			$formIds['member2'] = array('valueType' => 'string', 'type' => 'text',);
			$form->add(
					'label',		// type
					'labelMember2',	// id/name
					'member2',			// for
					parent::lang('class.ProtocolView#entry#form#member2')	// label text
				);
			$member2 = $form->add(
							$formIds['member2']['type'],		// type
							'member2',	// id/name
							$protocol->get_member(false,2)	// default
				);
			$form->add(
					'note',			// type
					'noteMember2',	// id/name
					'member2',		// for
					parent::lang('class.ProtocolView#global#info#help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDALLTEXT)	// note text
				);
			
			// add rules
			$member2->set_rule(
					array(
							'regexp' => array(
									$this->getGc()->get_config('textarea.regexp.zebra'),
									'error',
									parent::lang('class.ProtocolView#entry#rule#regexp.allowedChars').' ['.$this->getGc()->get_config('textarea.desc').']',
								),
						)
				);
			
			// recorder
			$formIds['recorder'] = array('valueType' => 'string', 'type' => 'text',);
			$form->add(
					'label',		// type
					'labelRecorder',	// id/name
					'recorder',			// for
					parent::lang('class.ProtocolView#entry#form#recorder')	// label text
				);
			$recorder = $form->add(
							$formIds['recorder']['type'],		// type
							'recorder',		// id/name
							$protocol->get_recorder()	// default
				);
			$form->add(
					'note',			// type
					'noteRecorder',	// id/name
					'recorder',		// for
					parent::lang('class.ProtocolView#global#info#help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDALLTEXT)	// note text
				);
			
			// add rules
			$recorder->set_rule(
					array(
							'regexp' => array(
									$this->getGc()->get_config('textarea.regexp.zebra'),
									'error',
									parent::lang('class.ProtocolView#entry#rule#regexp.allowedChars').' ['.$this->getGc()->get_config('textarea.desc').']',
								),
							'required' => array(
									'error',
									parent::lang('class.ProtocolView#entry#rule#required.recorder'),
								),
						)
				);
			
			// protocol text
			$formIds['protocol'] = array('valueType' => 'string', 'type' => 'textarea',);
			$form->add(
					'label',		// type
					'labelProtocol',	// id/name
					'protocol',			// for
					parent::lang('class.ProtocolView#entry#form#protocol').':'	// label text
				);
			$protocolTa = $form->add(
							$formIds['protocol']['type'],		// type
							'protocol',		// id/name
							$protocol->get_protocol()	// default
				);
			$form->add(
					'note',			// type
					'noteProtocol',	// id/name
					'protocol',		// for
					parent::lang('class.ProtocolView#global#info#help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDALLTEXT)	// note text
				);
			
			// add rules
			$protocolTa->set_rule(
					array(
							'regexp' => array(
									$this->getGc()->get_config('textarea.regexp.zebra'),
									'error',
									parent::lang('class.ProtocolView#entry#rule#regexp.allowedChars').' ['.$this->getGc()->get_config('textarea.desc').']',
								),
						)
				);
			
			// js tiny_mce
			$tmce = array(
					'element' => 'protocol',
					'css' => 'templates/protocols/tmce_'.$presetObject->get_path().'.css',
					'transitem' => parent::lang('class.ProtocolView#newEntry#tmce#item'),
					'transdecision' => parent::lang('class.ProtocolView#newEntry#tmce#decision'),
					'action' => 'edit',
				);
			// smarty
			$this->tpl->assign('tmce',$tmce);
			
			
			// checkbox public
			$formIds['public'] = array('valueType' => 'int', 'type' => 'checkbox', 'default' => 1);
			$form->add(
					'label',		// type
					'labelPublic',	// id/name
					'public',		// for
					parent::lang('class.ProtocolView#entry#form#public')	// label text
				);
			$public = $form->add(
					$formIds['public']['type'],		// type
					'public',						// id/name
					'1',							// value
					($protocol->isPermittedFor(0) ? array('checked' => 'checked') : null)							// default
				);
			$form->add(
					'note',			// type
					'notePublic',	// id/name
					'public',		// for
					parent::lang('class.ProtocolView#global#info#help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDISPUBLIC)	// note text
				);
			
			// permissions
			$result = $this->zebraAddPermissions($form, 'protocol');
			$form = $result['form'];
			$permissionConfig['ids'] = $result['formIds'];
			$permissionConfig['iconRead'] = $result['iconRead'];
			$permissionConfig['iconEdit'] = $result['iconEdit'];
			
			// submit-button
			$form->add(
					'submit',		// type
					'buttonSubmit',	// id/name
					parent::lang('class.ProtocolView#entry#form#submitButton')	// value
				);
			
			// validate
			if($form->validate()) {
				
				// get form data
				$data = $this->getFormValues($formIds);
				// get form permissions
				$permissions = $this->getFormPermissions($permissionConfig['ids']);
				
				// set owner
				$data['owner'] = $protocol->get_owner();
				
				// add public access
				if($data['public'] == 1) {
					$permissions[0]['group'] = Group::fakePublic();
					$permissions[0]['value'] = 'r';
				}
				
				// get user and put to update
				$correctionString = $data['correctable'].'|';
				foreach($data['correctors'] as $userid) {
					$correctionString .= $userid.',';
				}
				if(count($data['correctors'])>0) {
					$correctionString = substr($correctionString,0,-1);
				}
				
				$protocolUpdate = array(
									'date' => $data['date'],
									'type' => $data['type'],
									'location' => $data['location'],
									'member' => $data['member0'].'|'.$data['member1'].'|'.$data['member2'],
									'protocol' => $data['protocol'],
									'preset' => new Preset($data['preset'],'protocol',$protocol->get_id(), $this),
									'recorder' => $data['recorder'],
									'correctable' => $correctionString,
									'owner' => $data['owner'],
									'valid' => 1
					);
				
				// update protocol
				$protocol->update($protocolUpdate);
					
				// write to db
				$protocol->writeDb('update');
				
				// write permissions
				$protocol->dbDeletePermission();
				$protocol->dbWritePermission($permissions);
						
				// create cached file
				$fid = File::idFromCache('protocol|'.$protocol->get_id());
				$protocol->createCachedFile($fid);
				
				// create file objects
				$fileIds = File::attachedTo('protocol', $pid);
				$fileObjects = array();
				foreach($fileIds as $id) {
					$fileObjects[] = new File($id);
				}
				
				// smarty
				$sCD = new JudoIntranetSmarty();
				$sCD->assign('data', $protocol->details());
				$sCD->assign('files', $fileObjects);
				$sCD->assign('fileHref', 'file.php?id=download&fid=');
				
				return $sCD->fetch('smarty.protocol.details.tpl');
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
	 * show returns the protocoltext as html-string
	 * 
	 * @param int $pid entry-id for protocol
	 * @return string html-string with the protocoltext
	 */
	private function show($pid) {
	
		// pagecaption
		$this->tpl->assign('pagecaption',parent::lang('class.ProtocolView#page#caption#show'));
		
		// get protocol
		$protocol = new Protocol($pid);
		
		// get status
		$correctable = $protocol->get_correctable(false);
			
		// check rights
		if($this->getUser()->hasPermission('protocol', $pid) && ($correctable['status'] == 2 || $this->getUser()->get_userinfo('name') == $protocol->get_owner())) {
			
			// smarty
			$sP = new JudoIntranetSmarty();
			
			// prepare marker-array
			$infos = array(
					'version' => '01.01.1970 01:00'
				);
			
			// add calendar-fields to array
			$protocol->addMarks($infos,false);
			
			// add tmce-css
			$fh = fopen('templates/protocols/tmce_'.$protocol->get_preset()->get_path().'.css','r');
			$css = fread($fh,filesize('templates/protocols/tmce_'.$protocol->get_preset()->get_path().'.css'));
			fclose($fh);
			$infos['tmceStyles'] = $css;
			
			// smarty
			$sP->assign('p', $infos);
			// check marks in values
			foreach($infos as $k => $v) {
				
				if(preg_match('/\{\$p\..*\}/U', $v)) {
					$infos[$k] = $sA->fetch('string:'.$v);
				}
			}
			
			// decision link
			$decisionLink = array(
									'href' => 'protocol.php?id=showdecisions&pid='.$pid,
									'title' => parent::lang('class.ProtocolView#show#decisionLink#title'),
									'text' => parent::lang('class.ProtocolView#show#decisionLink#text'),
									'number' => $protocol->hasDecisions() 
								);
			
			// smarty
			$sP->assign('p', $infos);
			$div_out = $sP->fetch('templates/protocols/'.$protocol->get_preset()->get_path().'.tpl');
			
			// smarty
			$sPd = new JudoIntranetSmarty();
			$sPd->assign('decisionlink', $decisionLink);
			$sPd->assign('page', $div_out);
			return $sPd->fetch('smarty.protocol.show.tpl');
		} else {
			
			// error
			$errno = $this->getError()->error_raised('NotAuthorized','entry:'.$this->get('id'),$this->get('id'));
			$this->getError()->handle_error($errno);
			return $this->getError()->to_html($errno);
		}
	}
	
	
	
	
	
	
	
	/**
	 * topdf returns the protocol as pdf
	 * 
	 * @param int $pid entry-id for protocol
	 * @return string pdf of the protocol
	 */
	private function topdf($pid) {
	
		// pagecaption
		$this->tpl->assign('pagecaption',parent::lang('class.ProtocolView#page#caption#topdf'));
		
		// prepare return
		$return = '';
		
		// redirect to FileView::download()
		$this->redirectTo('file',
			array(
					'id' => 'cached',
					'table' => 'protocol',
					'tid' => $this->get('pid'),
				)
			);
		
		// return
		return $return;
	}
	
	
	
	
	
	
	
	/**
	 * delete handles the deletion of the protocol
	 * 
	 * @param int $pid entry-id for protocol
	 * @return string html of the deletion page
	 */
	private function delete($pid) {
	
		// pagecaption
		$this->tpl->assign('pagecaption',parent::lang('class.ProtocolView#page#caption#delete'));
		
		// check rights
		if($this->getUser()->hasPermission('protocol', $pid)) {
			
			// smarty-templates
			$sConfirmation = new JudoIntranetSmarty();
			
			// form
			$form = new Zebra_Form(
				'formConfirm',			// id/name
				'post',				// method
				'protocol.php?id=delete&pid='.$pid		// action
			);
			// set language
			$form->language('deutsch');
			// set docktype xhtml
			$form->doctype('xhtml');
			
			// add button
			$form->add(
				'submit',		// type
				'buttonSubmit',	// id/name
				parent::lang('class.ProtocolView#delete#form#yes'),	// value
				array('title' => parent::lang('class.ProtocolView#delete#title#yes'))
			);
			
			// smarty-link
			$link = array(
							'params' => 'class="submit"',
							'href' => 'protocol.php?id=listall',
							'title' => parent::lang('class.ProtocolView#delete#cancel#title'),
							'content' => parent::lang('class.ProtocolView#delete#cancel#form')
						);
			$sConfirmation->assign('link', $link);
			$sConfirmation->assign('spanparams', 'id="cancel"');
			$sConfirmation->assign('message', parent::lang('class.ProtocolView#delete#message#confirm').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_DELETE));
			$sConfirmation->assign('form', $form->render('', true));
			
			// validate
			if($form->validate()) {
			
				// get calendar-object
				$protocol = new Protocol($pid);
				
				// disable entry
				$protocol->update(array('valid' => 0));
				
				// smarty
				$sConfirmation->assign('message', parent::lang('class.ProtocolView#delete#message#done'));
				$sConfirmation->assign('form', '');
				
				// write entry
				try {
					$protocol->writeDb('update');
						
					// delete cached file
					$fid = File::idFromCache('protocol|'.$protocol->get_id());
					File::delete($fid);
					// delete attachements
					File::deleteAttachedFiles('protocol',$protocol->get_id());
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
	 * correct handles the corrections of the protocol
	 * 
	 * @param int $pid entry-id for protocol
	 * @return string html of the correction page
	 */
	private function correct($pid) {
			
		// get protocol object
		$protocol = new Protocol($pid);
		$correctable = $protocol->get_correctable(false);
		
		// js tiny_mce
		$tmce = array(
				'element' => 'protocol',
				'css' => 'templates/protocols/tmce_'.$protocol->get_preset()->get_path().'.css',
				'transitem' => parent::lang('class.ProtocolView#newEntry#tmce#item'),
				'transdecision' => parent::lang('class.ProtocolView#newEntry#tmce#decision'),
				'action' => '',
			);
		// smarty
		$this->tpl->assign('tmce',$tmce);
		
		// check rights
		if($this->getUser()->hasPermission('protocol', $pid) && (in_array($this->getUser()->get_id(),$correctable['correctors']) || $this->getUser()->get_userinfo('name') == $protocol->get_owner())) {
			
			// check owner
			if($this->getUser()->get_userinfo('name') == $protocol->get_owner()) {
				
				// pagecaption
				$this->tpl->assign('pagecaption',parent::lang('class.ProtocolView#page#caption#correct'));
				
				// smarty
				$sPCo = new JudoIntranetSmarty();
				
				// check action
				if($this->get('action') == 'diff' && $this->get('uid') !== false) {
					
					// diff correction of $uid
					// get correction
					$correction = new ProtocolCorrection($protocol,$this->get('uid'));
					
					// clean protocols for diff
					$diffBase = preg_replace_callback('/<p class="(.*)">/', function($match) {return parent::lang('class.ProtocolView#correct#tmceClass#'.$match[1]).': ';}, $protocol->get_protocol());
					$diffBase = html_entity_decode(preg_replace('/<.*>/U', '', $diffBase));
					$diffNew = preg_replace_callback('/<p class="(.*)">/', function($match) {return parent::lang('class.ProtocolView#correct#tmceClass#'.$match[1]).': ';}, $correction->get_protocol());
					$diffNew = html_entity_decode(preg_replace('/<.*>/U', '', $diffNew));
					
					// smarty
					$sJsDL = new JudoIntranetSmarty();
					
					// activate difflib js-files
					$this->tpl->assign('jsdifflib',true);
					// set values for difflib
					$difflib = array(
							'protDiffBase' => 'protDiffBase',
							'protDiffNew' => 'protDiffNew',
							'protDiffOut' => 'diffOut',
							'protDiffBaseCaption' => parent::lang('class.ProtocolView#correct#diff#baseCaption'),
							'protDiffNewCaption' => parent::lang('class.ProtocolView#correct#diff#newCaption')
						);
					
					// add difflib values to js-template
					$sJsDL->assign('dl',$difflib);
					$this->add_jquery($sJsDL->fetch('smarty.js-jsdifflib.tpl'));
					
					// add diffOut to template
					$sPCo->assign('diffOut','diffOut');
					
					// build form
					$form = new Zebra_Form(
						'diffCorrection',	// id/name
						'post',				// method
						'protocol.php?id=correct&pid='.$pid.'&action=diff&uid='.$this->get('uid')		// action
					);
					// set language
					$form->language('deutsch');
					// set docktype xhtml
					$form->doctype('xhtml');
					
					// elements
					// protocol text
					$formIds['protocol'] = array('valueType' => 'string', 'type' => 'textarea',);
					$form->add(
							'label',		// type
							'labelProtocol',	// id/name
							'protocol',			// for
							parent::lang('class.ProtocolView#entry#form#protocol').':'	// label text
						);
					$protocolTa = $form->add(
									$formIds['protocol']['type'],		// type
									'protocol',		// id/name
									$protocol->get_protocol()	// default
						);
					
					// add rules
					$protocolTa->set_rule(
							array(
									'regexp' => array(
											$this->getGc()->get_config('textarea.regexp.zebra'),
											'error',
											parent::lang('class.ProtocolView#entry#rule#regexp.allowedChars').' ['.$this->getGc()->get_config('textarea.desc').']',
										),
								)
						);
					
					// checkbox to mark correction as finished
					$formIds['finished'] = array('valueType' => 'int', 'type' => 'checkbox', 'default' => 0);
					$form->add(
							'label',		// type
							'labelFinished',	// id/name
							'finished',		// for
							parent::lang('class.ProtocolView#entry#form#finished').':'	// label text
						);
					$form->add(
							$formIds['finished']['type'],		// type
							'finished',						// id/name
							'1',							// value
							($correction->get_finished() == '1' ? array('checked' => 'checked') : null)		// default
						);
					
					// hidden textareas for texts to diff
					$form->add(
							'textarea',		// type
							'protDiffBase',	// id/name
							$diffBase		// default
						);
					$form->add(
							'textarea',		// type
							'protDiffNew',	// id/name
							$diffNew		// default
						);
									
					// submit-button
					$form->add(
						'submit',		// type
						'buttonSubmit',	// id/name
						parent::lang('class.ProtocolView#entry#form#submitButton')	// value
					);
					
					// add form to template
					$sPCo->assign('c',true);
					$sPCo->assign('form',$form->render('', true));
					
					// add jquery to style hidden textareas
					$this->add_jquery('
				hideJsdiffTextareas();
						');
					
					// validate
					if($form->validate()) {
						
						// get form data
						$data = $this->getFormValues($formIds);
						
						$correctionUpdate = array(
								'finished' => $data['finished']
							);
						$protocolUpdate = array(
								'protocol' => $data['protocol'],
							);
						
						// update
						$protocol->update($protocolUpdate);
						$correction->update($correctionUpdate);
						
						$protocol->writeDb('update');
						$correction->writeDb('update');
						
						// message
						$message = array(
								'message' => parent::lang('class.ProtocolView#correct#message#corrected'),
								'href' => 'protocol.php?id=correct&pid='.$pid.'&action=diff&uid='.$this->get('uid'),
								'title' => parent::lang('class.ProtocolView#correct#message#back'),
								'text' => parent::lang('class.ProtocolView#correct#message#back')
							);
						
						// assign to template
						$sPCo->assign('c',false);
						$sPCo->assign('message',$message);
					}
					
					// smarty
					$sPCo->assign('caption',parent::lang('class.ProtocolView#correct#diff#pageCaption').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_PROTOCOLDIFF));
					
				} else {
					
					// list all corrections
					// get corrections
					$corrections = ProtocolCorrection::listCorrections($pid);
					// put information together
					$list = array();
					$user = new User(false);
					foreach($corrections as $correction) {
						
						// change user
						$user->change_user($correction['uid'],false,'id');
						// fill list
						$img = false;
						if($correction['finished'] == 1) {
							$img = array(
									'src' => 'img/done.png',
									'alt' => parent::lang('class.ProtocolView#correct#difflist#imgDone'),
									'title' => parent::lang('class.ProtocolView#correct#difflist#imgDone')
								);
						}
						$list[] = array(
								'href' => 'protocol.php?id=correct&pid='.$pid.'&action=diff&uid='.$correction['uid'],
								'title' => parent::lang('class.ProtocolView#correct#difflist#correctedBy').': '.$user->get_userinfo('name'),
								'text' => $user->get_userinfo('name').' ('.date('d.m.Y',strtotime($correction['modified'])).')',
								'img' => $img
							);
					}
					
					// smarty
					$sPCo->assign('caption',parent::lang('class.ProtocolView#correct#difflist#caption').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_PROTOCOLDIFFLIST));
					$sPCo->assign('list', $list);
				}
				
				// return
				return $sPCo->fetch('smarty.protocolcorrection.owner.tpl');
			} else {
				
				// pagecaption
				$this->tpl->assign('pagecaption',parent::lang('class.ProtocolView#page#caption#correct').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_PROTOCOLCORRECT));
				
				// get ProtocolCorretion object
				$correction = new ProtocolCorrection($protocol);
				
				// formular
				$form = new Zebra_Form(
					'correctProtocol',	// id/name
					'post',				// method
					'protocol.php?id=correct&pid='.$pid		// action
				);
				// set language
				$form->language('deutsch');
				// set docktype xhtml
				$form->doctype('xhtml');
				
				// elements
				// protocol text
				$formIds['protocol'] = array('valueType' => 'string', 'type' => 'textarea',);
				$form->add(
						'label',		// type
						'labelProtocol',	// id/name
						'protocol',			// for
						parent::lang('class.ProtocolView#entry#form#protocol').':'	// label text
					);
				$protocolTa = $form->add(
								$formIds['protocol']['type'],		// type
								'protocol',		// id/name
								$correction->get_protocol()	// default
					);
				$form->add(
						'note',			// type
						'noteProtocol',	// id/name
						'protocol',		// for
						parent::lang('class.ProtocolView#global#info#help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDALLTEXT)	// note text
					);
				
				// add rules
				$protocolTa->set_rule(
						array(
								'regexp' => array(
										$this->getGc()->get_config('textarea.regexp.zebra'),
										'error',
										parent::lang('class.ProtocolView#entry#rule#regexp.allowedChars').' ['.$this->getGc()->get_config('textarea.desc').']',
									),
							)
					);
								
				// submit-button
				$form->add(
						'submit',		// type
						'buttonSubmit',	// id/name
						parent::lang('class.ProtocolView#entry#form#submitButton')	// value
					);
				
				// validate
				if($form->validate()) {
					
					// get form data
					$data = $this->getFormValues($formIds);
					
					$correctionUpdate = array(
										'protocol' => $data['protocol'],
										'modified' => date('U'),
										'pid' => $pid
						);
					
					// update protocol
					$correction->update($correctionUpdate);
						
					// write to db
					$action = 'new';
					if(ProtocolCorrection::hasCorrected($pid) === true) {
						$action = 'update';
					}
					$correction->writeDb($action);
					
					return parent::lang('class.ProtocolView#correct#message#done');
				} else {
					return $form->render('', true);
				}
			}
		} else {
			
			// error
			$errno = $this->getError()->error_raised('NotAuthorized','entry:'.$this->get('id'),$this->get('id'));
			$this->getError()->handle_error($errno);
			return $this->getError()->to_html($errno);
		}
	}

	
	
	
	
	
	
	
	/**
	 * decissions shows the decissions of this or all protocols
	 * 
	 * @param int $pid entry-id for protocol
	 * @return string html of the decissions page
	 */
	private function decisions($pid) {
	
		// pagecaption
		$this->tpl->assign('pagecaption',parent::lang('class.ProtocolView#page#caption#decisions').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_PROTOCOLDECISIONS));
		
		// check rights
		if($this->getUser()->hasPermission('protocol', $pid) || $pid == false) {
			
			// prepare template
			$sD = new JudoIntranetSmarty();
			
			// check pid all or single
			if($pid === false) {
				
				// get protocols
				$protocols = array();
				$protocolIds = self::getUser()->permittedItems('protocol', 'w');
				foreach($protocolIds as $protocolId) {
					$protocols[$protocolId] = new Protocol($protocolId);
				}
				
				// sort array by protocols date
				usort($protocols,array($this,'callbackCompareProtocols'));
				
				// walk through ids
				$counter = 0;
				foreach($protocols as $protocol) {
					
					// assign data
					$data[$counter] = array(	'date' => array( 
														'href' => 'protocol.php?id=details&pid='.$protocol->get_id(),
														'title' => parent::lang('class.ProtocolView#decisions#listAllTitle#goTo'),
														'date' => $protocol->get_date('d.m.Y'),
													),
												'type' => $protocol->get_type(),
												'location' => $protocol->get_location(),
												'decisions' => $this->parseHtml($protocol->get_protocol(),'<p class="tmceDecision">|</p>'));
					
					// check if protocol has decisions
					if(count($data[$counter]['decisions']) == 0) {
						unset($data[$counter]);
					}
					$data = array_merge($data);
					
					// add to template
					$sD->assign('data',$data);
					
					// increment counter
					$counter++;
				}
			} else {
				
				// get protocol object
				$protocol = new Protocol($pid);
								
				// assign data
				$data[] = array(	'date' => $protocol->get_date('d.m.Y'),
									'type' => $protocol->get_type(),
									'location' => $protocol->get_location(),
									'decisions' => $this->parseHtml($protocol->get_protocol(),'<p class="tmceDecision">|</p>'));
				
				// add to template
				$sD->assign('data',$data);
			}
			
			// add to template
			$sD->assign('pid', $pid);
			
				// return
				return $sD->fetch('smarty.protocol.showdecisions.tpl');
		} else {
			
			// error
			$errno = $this->getError()->error_raised('NotAuthorized','entry:'.$this->get('id'),$this->get('id'));
			$this->getError()->handle_error($errno);
			return $this->getError()->to_html($errno);
		}
	}

	
	
	
	
	
	
	
	/**
	 * parseHtml parses $text and returns an array containing the text between $tag
	 * 
	 * @param string $text the HTML text to be parsed
	 * @param string $tag the complete HTML tag (open and close, devided by |)
	 * @return array array containing the text between the given HTML tags
	 */
	private function parseHtml($text,$tag) {
	
		// split tag
		list($open,$close) = explode("|",$tag);
		
		// match text
		$matches = array();
		$preg = "|$open(.*)$close|U";
		$result = preg_match_all($preg,$text,$matches);
		
		// return
		return $matches[1];
	}
}



?>
