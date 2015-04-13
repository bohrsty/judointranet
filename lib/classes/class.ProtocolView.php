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
		parent::__construct();
	}
	
	/**
	 * init chooses the functionality by using $_GET['id']
	 * 
	 * @return void
	 */
	public function init() {
		
		// set pagename
		$this->getTpl()->assign('pagename',_l('protocols'));
		
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
						$this->getTpl()->assign('title', $this->title(_l('protocols: listall')));
						$protocolViewListall = new ProtocolViewListall();
						$this->getTpl()->assign('main', $protocolViewListall->show());
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', false);
						$this->getTpl()->assign('tinymce', false);
					break;
					
					case 'new':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('protocols: new protocol')));
						$this->getTpl()->assign('main', $this->newEntry());
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
						$this->getTpl()->assign('tinymce', true);
					break;
					
					case 'details':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('protocols: details')));
						$this->getTpl()->assign('main', $this->details($this->get('pid')));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', false);
						$this->getTpl()->assign('tinymce', false);
					break;
					
					case 'edit':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('protocols: edit protocol')));
						$this->getTpl()->assign('main', $this->edit($this->get('pid')));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
						$this->getTpl()->assign('tinymce', true);
					break;
					
					case 'show':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('protocols: show protocol')));
						$this->getTpl()->assign('main', $this->showProtocol($this->get('pid')));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', false);
						$this->getTpl()->assign('tinymce', false);
					break;
					
					case 'topdf':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('protocols: protocol as PDF')));
						$this->getTpl()->assign('main', $this->topdf($this->get('pid')));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', false);
						$this->getTpl()->assign('tinymce', false);
					break;
					
					case 'delete':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('protocols: delete protocol')));
						$this->getTpl()->assign('main', $this->delete($this->get('pid')));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
						$this->getTpl()->assign('tinymce', true);
					break;
					
					case 'correct':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('protocols: correct protocol')));
						$this->getTpl()->assign('main', $this->correct($this->get('pid')));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
						$this->getTpl()->assign('tinymce', true);
					break;
					
					case 'showdecisions':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('protocols: show decisions')));
						$protocolViewDecisions = new ProtocolViewDecisions();
						$this->getTpl()->assign('main', $protocolViewDecisions->show());
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', false);
						$this->getTpl()->assign('tinymce', false);
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
			$this->getTpl()->assign('title', $this->title(_l('protocols'))); 
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
		$sD->assign('caption', _l('protocols'));
		$text[] = array(
				'caption' => '',
				'text' => ''
			);
		$sD->assign('text', $text);
		
		// return
		return $sD->fetch('smarty.default.content.tpl');
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
		$this->getTpl()->assign('pagecaption',_l('new protocol').'&nbsp;'.$this->helpButton(HELP_MSG_PROTOCOLNEW));
		
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
				_l('preset').':'	// label text
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
				_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDPRESET)	// note text
			);
		$preset->add_options($options);
		$preset->set_rule(
			array(
					'required' => array(
							'error', _l('required to select preset')
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
				_l('date').':'	// label text
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
				_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDDATE)	// note text
			);
		// format/position
		$date->format('d.m.Y');
		$date->inside(false);
		// rules
		$date->set_rule(
			array(
				'required' => array(
						'error', _l('required date'),
					),
				'date' => array(
						'error', _l('error date check')
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
				_l('kind of meeting').':'	// label text
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
				_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDTYPE)	// note text
			);
		$type->add_options($options);
		$type->set_rule(
			array(
					'required' => array(
							'error', _l('required kind of meeting')
						),
				)
		);
		
		// location
		$formIds['location'] = array('valueType' => 'string', 'type' => 'text',);
		$form->add(
				'label',		// type
				'labelLocation',	// id/name
				'location',			// for
				_l('city'),	// label text
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
				_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDALLTEXT)	// note text
			);
		
		// add rules
		$location->set_rule(
				array(
						'regexp' => array(
								$this->getGc()->get_config('textarea.regexp.zebra'),
								'error',
								_l('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
							),
						'required' => array(
								'error',
								_l('required location'),
							),
					)
			);
		
		// member0
		$formIds['member0'] = array('valueType' => 'string', 'type' => 'text',);
		$form->add(
				'label',		// type
				'labelMember0',	// id/name
				'member0',			// for
				_l('participants (attendant)'),	// label text
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
								_l('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
							),
					)
			);
		$form->add(
				'note',			// type
				'noteMember0',	// id/name
				'member0',		// for
				_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDALLTEXT)	// note text
			);
		
		// member1
		$formIds['member1'] = array('valueType' => 'string', 'type' => 'text',);
		$form->add(
				'label',		// type
				'labelMember1',	// id/name
				'member1',			// for
				_l('participants (excused)'),	// label text
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
				_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDALLTEXT)	// note text
			);
		
		// add rules
		$member1->set_rule(
				array(
						'regexp' => array(
								$this->getGc()->get_config('textarea.regexp.zebra'),
								'error',
								_l('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
							),
					)
			);
		
		// member2
		$formIds['member2'] = array('valueType' => 'string', 'type' => 'text',);
		$form->add(
				'label',		// type
				'labelMember2',	// id/name
				'member2',			// for
				_l('participants (without excuse)'),	// label text
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
				_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDALLTEXT)	// note text
			);
		
		// add rules
		$member2->set_rule(
				array(
						'regexp' => array(
								$this->getGc()->get_config('textarea.regexp.zebra'),
								'error',
								_l('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
							),
					)
			);
		
		// recorder
		$formIds['recorder'] = array('valueType' => 'string', 'type' => 'text',);
		$form->add(
				'label',		// type
				'labelRecorder',	// id/name
				'recorder',			// for
				_l('recorder'),	// label text
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
				_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDALLTEXT)	// note text
			);
		
		// add rules
		$recorder->set_rule(
				array(
						'regexp' => array(
								$this->getGc()->get_config('textarea.regexp.zebra'),
								'error',
								_l('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
							),
						'required' => array(
								'error',
								_l('required recorder'),
							),
					)
			);
		
		// protocol text
		$formIds['protocol'] = array('valueType' => 'string', 'type' => 'textarea',);
		$form->add(
				'label',		// type
				'labelProtocol',	// id/name
				'protocol',			// for
				_l('content/protocol text').':'	// label text
			);
		$protocolTa = $form->add(
						$formIds['protocol']['type'],		// type
						'protocol'		// id/name
			);
		$form->add(
				'note',			// type
				'noteProtocol',	// id/name
				'protocol',		// for
				_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDALLTEXT)	// note text
			);
		
		// add rules
		$protocolTa->set_rule(
				array(
						'regexp' => array(
								$this->getGc()->get_config('textarea.regexp.zebra'),
								'error',
								_l('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
							),
					)
			);
		
		// js tiny_mce
		$tmce = array(
				'element' => 'protocol',
				'css' => 'templates/protocols/tmce_'.$this->getGc()->get_config('tmce.default.css').'.css',
				'transitem' => _l('item'),
				'transdecision' => _l('decision'),
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
				_l('public access')	// label text
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
				_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDISPUBLIC)	// note text
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
				_l('save')	// value
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
			
			// set js redirection
			$this->jsRedirectTimeout('protocol.php?id=listall');
			
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
		$this->getTpl()->assign('pagecaption',_l('details'));
		
		// check rights
		if($this->getUser()->hasPermission('protocol', $pid)) {
				
			// get protocol-object
			$protocol = new Protocol($pid);
			$correctable = $protocol->get_correctable(false);
			// check status
			$status = false;
			if($correctable['status'] == 2 || $this->getUser()->get_id() == $protocol->get_owner()) {
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
					'title' => _l('show protocol'),
					'name' => _l('protocol')
				);
			// decisions
			$links[] = array(
					'href' => 'protocol.php?id=showdecisions&pid='.$protocol->get_id(),
					'title' => _l('show all decisions of this protocol'),
					'name' => _l('decisions')
				);
			// topdf
			$links[] = array(
					'href' => 'file.php?id=cached&table=protocol&tid='.$protocol->get_id(),
					'title' => _l('show protocol as PDF'),
					'name' => _l('PDF')
				);
			$sPD->assign('links',$links);
			
			// create file objects
			$fileIds = File::attachedTo('protocol', $pid);
			$fileObjects = array();
			foreach($fileIds as $id) {
				$fileObjects[] = new File($id);
			}
			$sPD->assign('files', $fileObjects);
			$sPD->assign('attached', _l('attached files'));
			$sPD->assign('none', _l('- none -'));
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
			$this->getTpl()->assign('pagecaption',_l('edit protocol'));
			
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
			
			// get all users
			$users = $this->getUser()->return_all_users();
			
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
					_l('state').':'	// label text
				);
			$form->add(
					$formIds['correctable']['type'],	// type
					'correctable',		// id/name
					array(				// values
							_l('in progress'),
							_l('correction enabled'),
							_l('published'),
						),
					$correctable['status']	// default
				);
			$form->add(
					'note',			// type
					'noteCorrectable',	// id/name
					'correctable',		// for
					_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_PROTOCOLCORRECTABLE)	// note text
				);
			
			
			// select change owner if admin
			if($this->getUser()->isAdmin()) {
				
				$options = array();
				foreach($users as $user) {
					$options[$user->get_userinfo('id')] = $user->get_userinfo('name');
				}
				
				$formIds['owner'] = array('valueType' => 'array', 'type' => 'select',);
				$form->add(
						'label',		// type
						'labelOwner',	// id/name
						'owner',		// for
						_l('owner').':'	// label text
					);
				$owner = $form->add(
						$formIds['owner']['type'],	// type
						'owner',					// id/name
						$protocol->get_owner()		// default
					);
				$owner->add_options($options);
			}
			
			// select correctors
			// get all users and put id and name to options
			$options = array();
			foreach($users as $user) {
				// exclude own user
				if($user->get_id() != $this->getUser()->get_id()) {
					$options[$user->get_userinfo('id')] = $user->get_userinfo('name');
				}
			}
			
			$formIds['correctors'] = array('valueType' => 'array', 'type' => 'select',);
			$form->add(
					'label',		// type
					'labelCorrectors',	// id/name
					'correctors',		// for
					_l('correctors').':'	// label text
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
					_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_PROTOCOLCORRECTORS)	// note text
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
					_l('preset').':'	// label text
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
					_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDPRESET)	// note text
				);
			$preset->add_options($options);
			$preset->set_rule(
				array(
						'required' => array(
								'error', _l('required preset')
							),
					)
			);
			
			// date
			$formIds['date'] = array('valueType' => 'string', 'type' => 'date',);
			$form->add(
					'label',		// type
					'labelDate',	// id/name
					'date',			// for
					_l('date')	// label text
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
							'error', _l('required date'),
						),
					'date' => array(
							'error', _l('error date check')
						),
					)
				);
			$form->add(
					'note',			// type
					'noteDate',		// id/name
					'date',			// for
					_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDDATE)	// note text
				);
			
			// type
			$options = Protocol::return_types();
			$formIds['type'] = array('valueType' => 'int', 'type' => 'select',);
			$form->add(
					'label',		// type
					'labelType',	// id/name
					'type',			// for
					_l('kind of meeting').':'	// label text
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
					_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDTYPE)	// note text
				);
			$type->add_options($options);
			$type->set_rule(
				array(
						'required' => array(
								'error', _l('required kind of meeting')
							),
					)
			);
			
			// location
			$formIds['location'] = array('valueType' => 'string', 'type' => 'text',);
			$form->add(
					'label',		// type
					'labelLocation',	// id/name
					'location',			// for
					_l('city')	// label text
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
					_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDALLTEXT)	// note text
				);
			
			// add rules
			$location->set_rule(
					array(
							'regexp' => array(
									$this->getGc()->get_config('textarea.regexp.zebra'),
									'error',
									_l('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
								),
							'required' => array(
									'error',
									_l('required location'),
								),
						)
				);
			
			// member0
			$formIds['member0'] = array('valueType' => 'string', 'type' => 'text',);
			$form->add(
					'label',		// type
					'labelMember0',	// id/name
					'member0',			// for
					_l('participants (attendant)')	// label text
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
					_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDALLTEXT)	// note text
				);
			
			// add rules
			$member0->set_rule(
					array(
							'regexp' => array(
									$this->getGc()->get_config('textarea.regexp.zebra'),
									'error',
									_l('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
								),
						)
				);
			
			// member1
			$formIds['member1'] = array('valueType' => 'string', 'type' => 'text',);
			$form->add(
					'label',		// type
					'labelMember1',	// id/name
					'member1',			// for
					_l('participants (excused)')	// label text
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
					_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDALLTEXT)	// note text
				);
			
			// add rules
			$member1->set_rule(
					array(
							'regexp' => array(
									$this->getGc()->get_config('textarea.regexp.zebra'),
									'error',
									_l('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
								),
						)
				);
			
			// member2
			$formIds['member2'] = array('valueType' => 'string', 'type' => 'text',);
			$form->add(
					'label',		// type
					'labelMember2',	// id/name
					'member2',			// for
					_l('participants (without excuse)')	// label text
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
					_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDALLTEXT)	// note text
				);
			
			// add rules
			$member2->set_rule(
					array(
							'regexp' => array(
									$this->getGc()->get_config('textarea.regexp.zebra'),
									'error',
									_l('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
								),
						)
				);
			
			// recorder
			$formIds['recorder'] = array('valueType' => 'string', 'type' => 'text',);
			$form->add(
					'label',		// type
					'labelRecorder',	// id/name
					'recorder',			// for
					_l('recorder')	// label text
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
					_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDALLTEXT)	// note text
				);
			
			// add rules
			$recorder->set_rule(
					array(
							'regexp' => array(
									$this->getGc()->get_config('textarea.regexp.zebra'),
									'error',
									_l('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
								),
							'required' => array(
									'error',
									_l('required recorder'),
								),
						)
				);
			
			// protocol text
			$formIds['protocol'] = array('valueType' => 'string', 'type' => 'textarea',);
			$form->add(
					'label',		// type
					'labelProtocol',	// id/name
					'protocol',			// for
					_l('protocol').':'	// label text
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
					_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDALLTEXT)	// note text
				);
			
			// add rules
			$protocolTa->set_rule(
					array(
							'regexp' => array(
									$this->getGc()->get_config('textarea.regexp.zebra'),
									'error',
									_l('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
								),
						)
				);
			
			// js tiny_mce
			$tmce = array(
					'element' => 'protocol',
					'css' => 'templates/protocols/tmce_'.$presetObject->get_path().'.css',
					'transitem' => _l('item'),
					'transdecision' => _l('decision'),
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
					_l('public access')	// label text
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
					_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDISPUBLIC)	// note text
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
					_l('save')	// value
				);
			
			// validate
			if($form->validate()) {
				
				// get form data
				$data = $this->getFormValues($formIds);
				// get form permissions
				$permissions = $this->getFormPermissions($permissionConfig['ids']);
				
				// set owner
				$data['owner'] = (isset($data['owner']) ? $data['owner'] : $protocol->get_owner());
				
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
				
				// set js redirection
				$this->jsRedirectTimeout('protocol.php?id=listall');
				
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
			throw new NotAuthorizedException($this);
		}
	}
	
	
	
	
	
	
	
	/**
	 * showProtocol returns the protocoltext as html-string
	 * 
	 * @param int $pid entry-id for protocol
	 * @return string html-string with the protocoltext
	 */
	private function showProtocol($pid) {
	
		// pagecaption
		$this->getTpl()->assign('pagecaption',_l('show protocol'));
		
		// get protocol
		$protocol = new Protocol($pid);
		
		// get status
		$correctable = $protocol->get_correctable(false);
			
		// check rights
		if($this->getUser()->hasPermission('protocol', $pid) && ($correctable['status'] == 2 || $this->getUser()->get_id() == $protocol->get_owner())) {
			
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
									'title' => _l('show conclusion of decisions'),
									'text' => _l('show decisions of this protocol'),
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
			throw new NotAuthorizedException($this);
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
		$this->getTpl()->assign('pagecaption',_l('show PDF'));
		
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
		$this->getTpl()->assign('pagecaption',_l('delete protocol'));
		
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
				_l('yes'),	// value
				array('title' => _l('delete protocol'))
			);
			
			// smarty-link
			$link = array(
							'params' => 'class="submit"',
							'href' => 'protocol.php?id=listall',
							'title' => _l('cancels deletion'),
							'content' => _l('cancel')
						);
			$sConfirmation->assign('link', $link);
			$sConfirmation->assign('spanparams', 'id="cancel"');
			$sConfirmation->assign('message', _l('delete confirm').'&nbsp;'.$this->helpButton(HELP_MSG_DELETE));
			$sConfirmation->assign('form', $form->render('', true));
			
			// validate
			if($form->validate()) {
			
				// get calendar-object
				$protocol = new Protocol($pid);
				
				// disable entry
				$protocol->update(array('valid' => 0));
				
				// smarty
				$sConfirmation->assign('message', _l('delete done'));
				$sConfirmation->assign('form', '');
				
				// write entry
				$protocol->writeDb('update');
					
				// delete cached file
				$fid = File::idFromCache('protocol|'.$protocol->get_id());
				File::delete($fid);
				// delete attachements
				File::deleteAttachedFiles('protocol',$protocol->get_id());
				
				// set js redirection
				$this->jsRedirectTimeout('protocol.php?id=listall');
			}
			
			// smarty return
			return $sConfirmation->fetch('smarty.confirmation.tpl');
		} else {
			throw new NotAuthorizedException($this);
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
				'transitem' => _l('item'),
				'transdecision' => _l('decision'),
				'action' => '',
			);
		// smarty
		$this->getTpl()->assign('tmce',$tmce);
		
		// check rights
		if($this->getUser()->hasPermission('protocol', $pid) && (in_array($this->getUser()->get_id(),$correctable['correctors']) || $this->getUser()->get_id() == $protocol->get_owner())) {
			
			// check owner
			if($this->getUser()->get_id() == $protocol->get_owner()) {
				
				// pagecaption
				$this->getTpl()->assign('pagecaption',_l('correct protocol'));
				
				// smarty
				$sPCo = new JudoIntranetSmarty();
				
				// check action
				if($this->get('action') == 'diff' && $this->get('uid') !== false) {
					
					// diff correction of $uid
					// get correction
					$correction = new ProtocolCorrection($protocol,$this->get('uid'));
					
					// clean protocols for diff
					$diffBase = preg_replace_callback('/<p class="(.*)">/', function($match) {return _l('diff_'.$match[1]).': ';}, $protocol->get_protocol());
					$diffBase = html_entity_decode(preg_replace('/<.*>/U', '', $diffBase));
					$diffNew = preg_replace_callback('/<p class="(.*)">/', function($match) {return _l('diff_'.$match[1]).': ';}, $correction->get_protocol());
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
							'protDiffBaseCaption' => _l('original text'),
							'protDiffNewCaption' => _l('correction')
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
							_l('protocol').':'	// label text
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
											_l('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
										),
								)
						);
					
					// checkbox to mark correction as finished
					$formIds['finished'] = array('valueType' => 'int', 'type' => 'checkbox', 'default' => 0);
					$form->add(
							'label',		// type
							'labelFinished',	// id/name
							'finished',		// for
							_l('finished correction').':'	// label text
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
						_l('save')	// value
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
								'message' => _l('successful updated protocol'),
								'href' => 'protocol.php?id=correct&pid='.$pid.'&action=diff&uid='.$this->get('uid'),
								'title' => _l('back to correction'),
								'text' => _l('back to correction')
							);
						
						// assign to template
						$sPCo->assign('c',false);
						$sPCo->assign('message',$message);
					}
					
					// smarty
					$sPCo->assign('caption',_l('compare correction').'&nbsp;'.$this->helpButton(HELP_MSG_PROTOCOLDIFF));
					
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
									'alt' => _l('reviewed correction'),
									'title' => _l('reviewed correction')
								);
						}
						$list[] = array(
								'href' => 'protocol.php?id=correct&pid='.$pid.'&action=diff&uid='.$correction['uid'],
								'title' => _l('correction of').': '.$user->get_userinfo('name'),
								'text' => $user->get_userinfo('name').' ('.date('d.m.Y',strtotime($correction['modified'])).')',
								'img' => $img
							);
					}
					
					// smarty
					$sPCo->assign('caption',_l('list of existing corrections').'&nbsp;'.$this->helpButton(HELP_MSG_PROTOCOLDIFFLIST));
					$sPCo->assign('list', $list);
				}
				
				// return
				return $sPCo->fetch('smarty.protocolcorrection.owner.tpl');
			} else {
				
				// pagecaption
				$this->getTpl()->assign('pagecaption',_l('correct protocol').'&nbsp;'.$this->helpButton(HELP_MSG_PROTOCOLCORRECT));
				
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
						_l('protocol').':'	// label text
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
						_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDALLTEXT)	// note text
					);
				
				// add rules
				$protocolTa->set_rule(
						array(
								'regexp' => array(
										$this->getGc()->get_config('textarea.regexp.zebra'),
										'error',
										_l('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
									),
							)
					);
								
				// submit-button
				$form->add(
						'submit',		// type
						'buttonSubmit',	// id/name
						_l('save')	// value
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
					
					return _l('<b>successful saved correction</b>');
				} else {
					return $form->render('', true);
				}
			}
		} else {
			throw new NotAuthorizedException($this);
		}
	}
}



?>
