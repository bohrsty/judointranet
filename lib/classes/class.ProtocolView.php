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
		$this->getTpl()->assign('pagename',parent::lang('protocols'));
		
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
						$this->getTpl()->assign('title', $this->title(parent::lang('protocols: listall')));
						$this->getTpl()->assign('main', $this->listall());
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', false);
						$this->getTpl()->assign('tinymce', false);
					break;
					
					case 'new':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(parent::lang('protocols: new protocol')));
						$this->getTpl()->assign('main', $this->newEntry());
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
						$this->getTpl()->assign('tinymce', true);
					break;
					
					case 'details':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(parent::lang('protocols: details')));
						$this->getTpl()->assign('main', $this->details($this->get('pid')));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', false);
						$this->getTpl()->assign('tinymce', false);
					break;
					
					case 'edit':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(parent::lang('protocols: edit protocol')));
						$this->getTpl()->assign('main', $this->edit($this->get('pid')));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
						$this->getTpl()->assign('tinymce', true);
					break;
					
					case 'show':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(parent::lang('protocols: show protocol')));
						$this->getTpl()->assign('main', $this->show($this->get('pid')));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', false);
						$this->getTpl()->assign('tinymce', false);
					break;
					
					case 'topdf':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(parent::lang('protocols: protocol as PDF')));
						$this->getTpl()->assign('main', $this->topdf($this->get('pid')));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', false);
						$this->getTpl()->assign('tinymce', false);
					break;
					
					case 'delete':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(parent::lang('protocols: delete protocol')));
						$this->getTpl()->assign('main', $this->delete($this->get('pid')));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
						$this->getTpl()->assign('tinymce', true);
					break;
					
					case 'correct':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(parent::lang('protocols: correct protocol')));
						$this->getTpl()->assign('main', $this->correct($this->get('pid')));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
						$this->getTpl()->assign('tinymce', true);
					break;
					
					case 'showdecisions':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(parent::lang('protocols: show decisions')));
						$this->getTpl()->assign('main', $this->decisions($this->get('pid')));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', false);
						$this->getTpl()->assign('tinymce', false);
					break;
					
					default:
						
						// id set, but no functionality
						$errno = $this->getError()->error_raised('GETUnkownId','entry:'.$this->get('id'),$this->get('id'));
						$this->getError()->handle_error($errno);
						
						// smarty
						$this->getTpl()->assign('title', '');
						$this->getTpl()->assign('main', $this->getError()->to_html($errno));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', false);
						$this->getTpl()->assign('tinymce', false);
					break;
				}
			} else {
				
				// error not authorized
				throw new NotAuthorizedException($this);
			}
		} else {
			
			// id not set
			// smarty-title
			$this->getTpl()->assign('title', $this->title(parent::lang('protocols'))); 
			// smarty-main
			$this->getTpl()->assign('main', $this->defaultContent());
			// smarty-jquery
			$this->getTpl()->assign('jquery', true);
			// smarty-hierselect
			$this->getTpl()->assign('zebraform', false);
			// smarty-tiny_mce
			$this->getTpl()->assign('tinymce', false);
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
		$sD->assign('caption', parent::lang('protocols'));
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
		$this->getTpl()->assign('pagecaption',parent::lang('listall').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_PROTOCOLLISTALL));
		
		// read all entries
		$entries = $this->readAllEntries();
				
		// smarty-templates
		$sListall = new JudoIntranetSmarty();
		
		// smarty
		$sTh = array(
				'date' => parent::lang('date'),
				'type' => parent::lang('kind'),
				'location' => parent::lang('location'),
				'show' => parent::lang('show'),
				'admin' => parent::lang('tasks')
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
								'title' => parent::lang('date'),
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
							'title' => parent::lang('show protocol'),
							'src' => 'img/prot_details.png',
							'alt' => parent::lang('show protocol'),
							'show' => true
						);
					$sList[$counter]['show'][1] = array(
							'href' => 'file.php?id=cached&table=protocol&tid='.$entry->get_id(),
							'title' => parent::lang('protocol as PDF'),
							'src' => 'img/prot_pdf.png',
							'alt' => parent::lang('protocol as PDF'),
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
							'title' => parent::lang('existing attachments'),
							'src' => 'img/attachment_info.png',
							'alt' => parent::lang('existing attachments'),
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
								'title' => parent::lang('edit protocol'),
								'src' => 'img/prot_edit.png',
								'alt' => parent::lang('edit protocol')
							);
						// delete
						$sList[$counter]['admin'][] = array(
								'href' => 'protocol.php?id=delete&pid='.$entry->get_id(),
								'title' => parent::lang('delete protocol'),
								'src' => 'img/prot_delete.png',
								'alt' => parent::lang('delete protocol')
							);
						// attachment
						$sList[$counter]['admin'][] = array(
								'href' => 'file.php?id=attach&table=protocol&tid='.$entry->get_id(),
								'title' => parent::lang('attach file(s)'),
								'src' => 'img/attachment.png',
								'alt' => parent::lang('attach file(s)')
							);
					}
					
					// correction
					if($correctable['status'] == 1 && in_array($this->getUser()->get_id(),$correctable['correctors']) && $this->getUser()->get_userinfo('name') != $entry->get_owner()) {
						
						// check if correction is finished
						$correction = new ProtocolCorrection($entry);
						
						if($correction->get_finished() == 1) {
							$sList[$counter]['admin'][] = array(
									'href' => false,
									'title' => parent::lang('finished correction'),
									'src' => 'img/done.png',
									'alt' => parent::lang('finished correction')
								);
						} else {
							$sList[$counter]['admin'][] = array(
									'href' => 'protocol.php?id=correct&pid='.$entry->get_id(),
									'title' => parent::lang('correct protocol'),
									'src' => 'img/prot_correct.png',
									'alt' => parent::lang('correct protocol')
								);
						}
					}
					
					// corrected
					if($correctable['status'] == 1 && $this->getUser()->get_userinfo('name') == $entry->get_owner() && $entry->hasCorrections()) {
						
						$sList[$counter]['admin'][] = array(
								'href' => 'protocol.php?id=correct&pid='.$entry->get_id().'&action=diff',
								'title' => parent::lang('existing corrections, please check'),
								'src' => 'img/prot_corrected.png',
								'alt' => parent::lang('existing corrections, please check')
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
		$this->getTpl()->assign('pagecaption',parent::lang('new protocol').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_PROTOCOLNEW));
		
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
				parent::lang('preset').':'	// label text
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
				parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDPRESET)	// note text
			);
		$preset->add_options($options);
		$preset->set_rule(
			array(
					'required' => array(
							'error', parent::lang('required to select preset')
						),
				)
		);
		// add jquery for changing css
		$this->getTpl()->assign('protocolPaths', json_encode($paths));
		$this->getTpl()->assign('protocolSelectPreset', 'preset');
		
		// date
		$formIds['date'] = array('valueType' => 'string', 'type' => 'date',);
		$form->add(
				'label',		// type
				'labelDate',	// id/name
				'date',			// for
				parent::lang('date').':'	// label text
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
				parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDDATE)	// note text
			);
		// format/position
		$date->format('d.m.Y');
		$date->inside(false);
		// rules
		$date->set_rule(
			array(
				'required' => array(
						'error', parent::lang('required date'),
					),
				'date' => array(
						'error', parent::lang('error date check')
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
				parent::lang('kind of meeting').':'	// label text
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
				parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDTYPE)	// note text
			);
		$type->add_options($options);
		$type->set_rule(
			array(
					'required' => array(
							'error', parent::lang('required kind of meeting')
						),
				)
		);
		
		// location
		$formIds['location'] = array('valueType' => 'string', 'type' => 'text',);
		$form->add(
				'label',		// type
				'labelLocation',	// id/name
				'location',			// for
				parent::lang('location'),	// label text
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
				parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDALLTEXT)	// note text
			);
		
		// add rules
		$location->set_rule(
				array(
						'regexp' => array(
								$this->getGc()->get_config('textarea.regexp.zebra'),
								'error',
								parent::lang('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
							),
						'required' => array(
								'error',
								parent::lang('required location'),
							),
					)
			);
		
		// member0
		$formIds['member0'] = array('valueType' => 'string', 'type' => 'text',);
		$form->add(
				'label',		// type
				'labelMember0',	// id/name
				'member0',			// for
				parent::lang('participants (attendant)'),	// label text
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
								parent::lang('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
							),
					)
			);
		$form->add(
				'note',			// type
				'noteMember0',	// id/name
				'member0',		// for
				parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDALLTEXT)	// note text
			);
		
		// member1
		$formIds['member1'] = array('valueType' => 'string', 'type' => 'text',);
		$form->add(
				'label',		// type
				'labelMember1',	// id/name
				'member1',			// for
				parent::lang('participants (excused)'),	// label text
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
				parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDALLTEXT)	// note text
			);
		
		// add rules
		$member1->set_rule(
				array(
						'regexp' => array(
								$this->getGc()->get_config('textarea.regexp.zebra'),
								'error',
								parent::lang('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
							),
					)
			);
		
		// member2
		$formIds['member2'] = array('valueType' => 'string', 'type' => 'text',);
		$form->add(
				'label',		// type
				'labelMember2',	// id/name
				'member2',			// for
				parent::lang('participants (without excuse)'),	// label text
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
				parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDALLTEXT)	// note text
			);
		
		// add rules
		$member2->set_rule(
				array(
						'regexp' => array(
								$this->getGc()->get_config('textarea.regexp.zebra'),
								'error',
								parent::lang('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
							),
					)
			);
		
		// recorder
		$formIds['recorder'] = array('valueType' => 'string', 'type' => 'text',);
		$form->add(
				'label',		// type
				'labelRecorder',	// id/name
				'recorder',			// for
				parent::lang('recorder'),	// label text
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
				parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDALLTEXT)	// note text
			);
		
		// add rules
		$recorder->set_rule(
				array(
						'regexp' => array(
								$this->getGc()->get_config('textarea.regexp.zebra'),
								'error',
								parent::lang('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
							),
						'required' => array(
								'error',
								parent::lang('required recorder'),
							),
					)
			);
		
		// protocol text
		$formIds['protocol'] = array('valueType' => 'string', 'type' => 'textarea',);
		$form->add(
				'label',		// type
				'labelProtocol',	// id/name
				'protocol',			// for
				parent::lang('content/protocol text').':'	// label text
			);
		$protocolTa = $form->add(
						$formIds['protocol']['type'],		// type
						'protocol'		// id/name
			);
		$form->add(
				'note',			// type
				'noteProtocol',	// id/name
				'protocol',		// for
				parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDALLTEXT)	// note text
			);
		
		// add rules
		$protocolTa->set_rule(
				array(
						'regexp' => array(
								$this->getGc()->get_config('textarea.regexp.zebra'),
								'error',
								parent::lang('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
							),
					)
			);
		
		// js tiny_mce
		$tmce = array(
				'element' => 'protocol',
				'css' => 'templates/protocols/tmce_'.$this->getGc()->get_config('tmce.default.css').'.css',
				'transitem' => parent::lang('item'),
				'transdecision' => parent::lang('decision'),
				'action' => 'new',
			);
		// smarty
		$this->getTpl()->assign('tmce',$tmce);
		
		
		// checkbox public
		$formIds['public'] = array('valueType' => 'int', 'type' => 'checkbox', 'default' => 1);
		$form->add(
				'label',		// type
				'labelPublic',	// id/name
				'public',		// for
				parent::lang('public access')	// label text
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
				parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDISPUBLIC)	// note text
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
				parent::lang('save')	// value
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
		$this->getTpl()->assign('pagecaption',parent::lang('details'));
		
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
					'title' => parent::lang('show protocol'),
					'name' => parent::lang('protocol')
				);
			// decisions
			$links[] = array(
					'href' => 'protocol.php?id=showdecisions&pid='.$protocol->get_id(),
					'title' => parent::lang('show all decisions of this protocol'),
					'name' => parent::lang('decisions')
				);
			// topdf
			$links[] = array(
					'href' => 'file.php?id=cached&table=protocol&tid='.$protocol->get_id(),
					'title' => parent::lang('show protocol as PDF'),
					'name' => parent::lang('PDF')
				);
			$sPD->assign('links',$links);
			
			// create file objects
			$fileIds = File::attachedTo('protocol', $pid);
			$fileObjects = array();
			foreach($fileIds as $id) {
				$fileObjects[] = new File($id);
			}
			$sPD->assign('files', $fileObjects);
			$sPD->assign('attached', parent::lang('attached files'));
			$sPD->assign('none', parent::lang('- none -'));
			$sPD->assign('fileHref', 'file.php?id=download&fid=');
			
			return $sPD->fetch('smarty.protocol.details.tpl');
		} else {
			
			// error
			throw new NotAuthorizedException($this);
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
			$this->getTpl()->assign('pagecaption',parent::lang('edit protocol'));
			
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
					parent::lang('state').':'	// label text
				);
			$form->add(
					$formIds['correctable']['type'],	// type
					'correctable',		// id/name
					array(				// values
							parent::lang('in progress'),
							parent::lang('correction enabled'),
							parent::lang('published'),
						),
					$correctable['status']	// default
				);
			$form->add(
					'note',			// type
					'noteCorrectable',	// id/name
					'correctable',		// for
					parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_PROTOCOLCORRECTABLE)	// note text
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
					parent::lang('correctors').':'	// label text
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
					parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_PROTOCOLCORRECTORS)	// note text
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
					parent::lang('preset').':'	// label text
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
					parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDPRESET)	// note text
				);
			$preset->add_options($options);
			$preset->set_rule(
				array(
						'required' => array(
								'error', parent::lang('required preset')
							),
					)
			);
			
			// date
			$formIds['date'] = array('valueType' => 'string', 'type' => 'date',);
			$form->add(
					'label',		// type
					'labelDate',	// id/name
					'date',			// for
					parent::lang('date')	// label text
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
							'error', parent::lang('required date'),
						),
					'date' => array(
							'error', parent::lang('error date check')
						),
					)
				);
			$form->add(
					'note',			// type
					'noteDate',		// id/name
					'date',			// for
					parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDDATE)	// note text
				);
			
			// type
			$options = Protocol::return_types();
			$formIds['type'] = array('valueType' => 'int', 'type' => 'select',);
			$form->add(
					'label',		// type
					'labelType',	// id/name
					'type',			// for
					parent::lang('kind of meeting').':'	// label text
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
					parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDTYPE)	// note text
				);
			$type->add_options($options);
			$type->set_rule(
				array(
						'required' => array(
								'error', parent::lang('required kind of meeting')
							),
					)
			);
			
			// location
			$formIds['location'] = array('valueType' => 'string', 'type' => 'text',);
			$form->add(
					'label',		// type
					'labelLocation',	// id/name
					'location',			// for
					parent::lang('location')	// label text
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
					parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDALLTEXT)	// note text
				);
			
			// add rules
			$location->set_rule(
					array(
							'regexp' => array(
									$this->getGc()->get_config('textarea.regexp.zebra'),
									'error',
									parent::lang('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
								),
							'required' => array(
									'error',
									parent::lang('required location'),
								),
						)
				);
			
			// member0
			$formIds['member0'] = array('valueType' => 'string', 'type' => 'text',);
			$form->add(
					'label',		// type
					'labelMember0',	// id/name
					'member0',			// for
					parent::lang('participants (attendant)')	// label text
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
					parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDALLTEXT)	// note text
				);
			
			// add rules
			$member0->set_rule(
					array(
							'regexp' => array(
									$this->getGc()->get_config('textarea.regexp.zebra'),
									'error',
									parent::lang('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
								),
						)
				);
			
			// member1
			$formIds['member1'] = array('valueType' => 'string', 'type' => 'text',);
			$form->add(
					'label',		// type
					'labelMember1',	// id/name
					'member1',			// for
					parent::lang('participants (excused)')	// label text
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
					parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDALLTEXT)	// note text
				);
			
			// add rules
			$member1->set_rule(
					array(
							'regexp' => array(
									$this->getGc()->get_config('textarea.regexp.zebra'),
									'error',
									parent::lang('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
								),
						)
				);
			
			// member2
			$formIds['member2'] = array('valueType' => 'string', 'type' => 'text',);
			$form->add(
					'label',		// type
					'labelMember2',	// id/name
					'member2',			// for
					parent::lang('participants (without excuse)')	// label text
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
					parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDALLTEXT)	// note text
				);
			
			// add rules
			$member2->set_rule(
					array(
							'regexp' => array(
									$this->getGc()->get_config('textarea.regexp.zebra'),
									'error',
									parent::lang('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
								),
						)
				);
			
			// recorder
			$formIds['recorder'] = array('valueType' => 'string', 'type' => 'text',);
			$form->add(
					'label',		// type
					'labelRecorder',	// id/name
					'recorder',			// for
					parent::lang('recorder')	// label text
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
					parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDALLTEXT)	// note text
				);
			
			// add rules
			$recorder->set_rule(
					array(
							'regexp' => array(
									$this->getGc()->get_config('textarea.regexp.zebra'),
									'error',
									parent::lang('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
								),
							'required' => array(
									'error',
									parent::lang('required recorder'),
								),
						)
				);
			
			// protocol text
			$formIds['protocol'] = array('valueType' => 'string', 'type' => 'textarea',);
			$form->add(
					'label',		// type
					'labelProtocol',	// id/name
					'protocol',			// for
					parent::lang('protocol').':'	// label text
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
					parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDALLTEXT)	// note text
				);
			
			// add rules
			$protocolTa->set_rule(
					array(
							'regexp' => array(
									$this->getGc()->get_config('textarea.regexp.zebra'),
									'error',
									parent::lang('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
								),
						)
				);
			
			// js tiny_mce
			$tmce = array(
					'element' => 'protocol',
					'css' => 'templates/protocols/tmce_'.$presetObject->get_path().'.css',
					'transitem' => parent::lang('item'),
					'transdecision' => parent::lang('decision'),
					'action' => 'edit',
				);
			// smarty
			$this->getTpl()->assign('tmce',$tmce);
			
			
			// checkbox public
			$formIds['public'] = array('valueType' => 'int', 'type' => 'checkbox', 'default' => 1);
			$form->add(
					'label',		// type
					'labelPublic',	// id/name
					'public',		// for
					parent::lang('public access')	// label text
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
					parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDISPUBLIC)	// note text
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
					parent::lang('save')	// value
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
		$this->getTpl()->assign('pagecaption',parent::lang('show protocol'));
		
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
					$infos[$k] = $sP->fetch('string:'.$v);
				}
			}
			
			// decision link
			$decisionLink = array(
									'href' => 'protocol.php?id=showdecisions&pid='.$pid,
									'title' => parent::lang('show conclusion of decisions'),
									'text' => parent::lang('show decisions of this protocol'),
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
		$this->getTpl()->assign('pagecaption',parent::lang('show PDF'));
		
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
	protected function delete($pid) {
	
		// pagecaption
		$this->getTpl()->assign('pagecaption',parent::lang('delete protocol'));
		
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
				parent::lang('yes'),	// value
				array('title' => parent::lang('delete protocol'))
			);
			
			// smarty-link
			$link = array(
							'params' => 'class="submit"',
							'href' => 'protocol.php?id=listall',
							'title' => parent::lang('cancels deletion'),
							'content' => parent::lang('cancel')
						);
			$sConfirmation->assign('link', $link);
			$sConfirmation->assign('spanparams', 'id="cancel"');
			$sConfirmation->assign('message', parent::lang('delete confirm').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_DELETE));
			$sConfirmation->assign('form', $form->render('', true));
			
			// validate
			if($form->validate()) {
			
				// get calendar-object
				$protocol = new Protocol($pid);
				
				// disable entry
				$protocol->update(array('valid' => 0));
				
				// smarty
				$sConfirmation->assign('message', parent::lang('delete done'));
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
				'transitem' => parent::lang('item'),
				'transdecision' => parent::lang('decision'),
				'action' => '',
			);
		// smarty
		$this->getTpl()->assign('tmce',$tmce);
		
		// check rights
		if($this->getUser()->hasPermission('protocol', $pid) && (in_array($this->getUser()->get_id(),$correctable['correctors']) || $this->getUser()->get_userinfo('name') == $protocol->get_owner())) {
			
			// check owner
			if($this->getUser()->get_userinfo('name') == $protocol->get_owner()) {
				
				// pagecaption
				$this->getTpl()->assign('pagecaption',parent::lang('correct protocol'));
				
				// smarty
				$sPCo = new JudoIntranetSmarty();
				
				// check action
				if($this->get('action') == 'diff' && $this->get('uid') !== false) {
					
					// diff correction of $uid
					// get correction
					$correction = new ProtocolCorrection($protocol,$this->get('uid'));
					
					// clean protocols for diff
					$diffBase = preg_replace_callback('/<p class="(.*)">/', function($match) {return parent::lang('diff_'.$match[1]).': ';}, $protocol->get_protocol());
					$diffBase = html_entity_decode(preg_replace('/<.*>/U', '', $diffBase));
					$diffNew = preg_replace_callback('/<p class="(.*)">/', function($match) {return parent::lang('diff_'.$match[1]).': ';}, $correction->get_protocol());
					$diffNew = html_entity_decode(preg_replace('/<.*>/U', '', $diffNew));
					
					// smarty
					$sJsDL = new JudoIntranetSmarty();
					
					// activate difflib js-files
					$this->getTpl()->assign('jsdifflib',true);
					// set values for difflib
					$difflib = array(
							'protDiffBase' => 'protDiffBase',
							'protDiffNew' => 'protDiffNew',
							'protDiffOut' => 'diffOut',
							'protDiffBaseCaption' => parent::lang('original text'),
							'protDiffNewCaption' => parent::lang('correction')
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
							parent::lang('protocol').':'	// label text
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
											parent::lang('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
										),
								)
						);
					
					// checkbox to mark correction as finished
					$formIds['finished'] = array('valueType' => 'int', 'type' => 'checkbox', 'default' => 0);
					$form->add(
							'label',		// type
							'labelFinished',	// id/name
							'finished',		// for
							parent::lang('finished correction').':'	// label text
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
						parent::lang('save')	// value
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
								'message' => parent::lang('successful updated protocol'),
								'href' => 'protocol.php?id=correct&pid='.$pid.'&action=diff&uid='.$this->get('uid'),
								'title' => parent::lang('back to correction'),
								'text' => parent::lang('back to correction')
							);
						
						// assign to template
						$sPCo->assign('c',false);
						$sPCo->assign('message',$message);
					}
					
					// smarty
					$sPCo->assign('caption',parent::lang('compare correction').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_PROTOCOLDIFF));
					
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
									'alt' => parent::lang('reviewed correction'),
									'title' => parent::lang('reviewed correction')
								);
						}
						$list[] = array(
								'href' => 'protocol.php?id=correct&pid='.$pid.'&action=diff&uid='.$correction['uid'],
								'title' => parent::lang('correction of').': '.$user->get_userinfo('name'),
								'text' => $user->get_userinfo('name').' ('.date('d.m.Y',strtotime($correction['modified'])).')',
								'img' => $img
							);
					}
					
					// smarty
					$sPCo->assign('caption',parent::lang('list of existing corrections').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_PROTOCOLDIFFLIST));
					$sPCo->assign('list', $list);
				}
				
				// return
				return $sPCo->fetch('smarty.protocolcorrection.owner.tpl');
			} else {
				
				// pagecaption
				$this->getTpl()->assign('pagecaption',parent::lang('correct protocol').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_PROTOCOLCORRECT));
				
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
						parent::lang('protocol').':'	// label text
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
						parent::lang('help').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_FIELDALLTEXT)	// note text
					);
				
				// add rules
				$protocolTa->set_rule(
						array(
								'regexp' => array(
										$this->getGc()->get_config('textarea.regexp.zebra'),
										'error',
										parent::lang('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
									),
							)
					);
								
				// submit-button
				$form->add(
						'submit',		// type
						'buttonSubmit',	// id/name
						parent::lang('save')	// value
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
					
					return parent::lang('<b>successful saved correction</b>');
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
		$this->getTpl()->assign('pagecaption',parent::lang('show decisions').'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_PROTOCOLDECISIONS));
		
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
														'title' => parent::lang('go to protocol'),
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
