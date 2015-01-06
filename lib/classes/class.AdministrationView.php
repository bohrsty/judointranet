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
 * class AdministrationView implements the control of the administration-pages
 */
class AdministrationView extends PageView {
	
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
		$this->getTpl()->assign('pagename', _l('administration'));
		
		// init helpmessages
		$this->initHelp();
		
		// switch $_GET['id'] if set
		if($this->get('id') !== false) {
			
			// check permissions
			$naviId = Navi::idFromFileParam(basename($_SERVER['SCRIPT_FILENAME']), $this->get('id'));
			if($this->getUser()->hasPermission('navi', $naviId)) {
				
				switch($this->get('id')) {
					
					case 'field':
						
						// set caption
						$this->getTpl()->assign('caption', _l('manage user tables'));
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('administration: manage user tables')));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', false);
						$fieldAdmin = new AdministrationViewField();
						$this->getTpl()->assign('main', $fieldAdmin->show());
					break;
					
					case 'user':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('administration: manage users and permissions')));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
						$this->getTpl()->assign('main', $this->useradmin());
					break;
					
					case 'newyear':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('administration: create new year')));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', false);
						$this->getTpl()->assign('main', $this->createNewYear());
					break;
					
					default:
						
						// id set, but no functionality
						$this->getTpl()->assign('title', '');
						$this->getTpl()->assign('main', '');
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', false);
						$this->getTpl()->assign('tinymce', false);
						
						// throw exception
						throw new GetUnknownIdException($this);
					break;
				}
			} else {
				
				// error not authorized
				throw new NotAuthorizedException($this);
			}
		} else {
			
			// id not set
			// smarty-title
			$this->getTpl()->assign('title', $this->title(_l('administration'))); 
			// smarty-main
			$this->getTpl()->assign('main', $this->defaultContent());
			// smarty-jquery
			$this->getTpl()->assign('jquery', true);
			// smarty-hierselect
			$this->getTpl()->assign('zebraform', false);
		}
		
		// global smarty
		$this->showPage('smarty.admin.tpl');
	}
	
	
	
	
	
	
	
	/**
	 * getUsertables() returns an array containing all usertables
	 * 
	 * @return array array containing all user-editable tables
	 */
	protected function getUsertables() {
		
		// get all fields to administer
		// get systemtables
		$systemtables = explode(',',$this->getGc()->get_config('systemtables'));
		
		// get user tables
		$usertables = array();
		
		// get tables from database
		$result = Db::ArrayValue('
			SHOW TABLES
		',
		MYSQL_NUM);
		if($result === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// get tables that are not system tables
		foreach($result as $table) {
			if(!in_array($table[0],$systemtables)) {
				$usertables[] = $table[0];
			}
		}
		
		// return
		return $usertables;
	}
	
	
	/**
	 * useradmin() handles the administration of users, groups and permissions
	 * 
	 * @return string html-string with the user administration page
	 */
	private function useradmin() {
		
		// smarty caption
		$this->getTpl()->assign('caption', parent::lang('manage user, groups and permissions'));
		
		// prepare variables
		$content = '';
		$active = 0;
		
		// check action
		if($this->get('action') !== false) {
			
			// switch $_GET['action']
			switch($this->get('action')) {
				
				case 'user':
					$active = 0;
					break;
				
				case 'group':
					$active = 1;
					break;
				
				case 'permission':
					$active = 2;
					break;
				
				default:
					$active = 0;
					break;
			}
		
		} else {
			$active = 0;
		}
		
		// template
		$sUserAdmin = new JudoIntranetSmarty();
		// assign variables
		$tabsJsOptions = '{ active: '.$active.' }';
		$this->getTpl()->assign('tabsJs', true);
		$this->getTpl()->assign('userAdminJs', true);
		$this->getTpl()->assign('tabsJsOptions', $tabsJsOptions);
		// data array
		$data = array(
				0 => array(
						'tab' => parent::lang('user management'),
						'caption' => parent::lang('user management'),
						'content' => $this->userContent($sUserAdmin),
						'action' => 'user',
					),
				1 => array(
						'tab' => parent::lang('group management'),
						'caption' => parent::lang('group management'),
						'content' => $this->groupContent($sUserAdmin),
						'action' => 'group',
					),
				2 => array(
						'tab' => parent::lang('permission management'),
						'caption' => parent::lang('permission management'),
						'content' => $this->permissionContent($sUserAdmin),
						'action' => 'permission',
					),
			);
		
		$sUserAdmin->assign('data', $data);
		$sUserAdmin->assign('backName', parent::lang('&lArr; back'));
		
		// fetch content
		return $sUserAdmin->fetch('smarty.admin.useradmin.tpl');
		
	}
	
	
	/**
	 * userContent() handles the administration of users
	 * 
	 * @return string html-string with the user administration page
	 */
	private function userContent() {
		
		// prepare variables
		$uid = $this->get('uid');
		$subaction = $this->get('subaction');
		$content = '';
		
		// prepare user list
		$sUserList = new JudoIntranetSmarty();
		$sUserList->assign('users', $this->getUser()->return_all_users());
		$sUserList->assign('action', 'user');
		
		// check for user list or edit page
		if($subaction == 'useredit') {
			
			// provide edit form
			// check uid
			if(User::exists($uid) && $uid!=1) {
				
				// output template
				$sOutput = new JudoIntranetSmarty();
				
				// get user
				$user = new User(false);
				$user->change_user($uid, false, 'id');
				
				$ps[] = array(
						'params' => 'class="bold"',
						'content' => parent::lang('edit user:').' '.$user->get_userinfo('name'),
					);
				$sOutput->assign('ps', $ps);
				
				// edit form
				// form
				$form = new Zebra_Form(
						'userEdit',			// id/name
						'post',						// method
						'administration.php?id=user&action=user&subaction=useredit&uid='.$uid		// action
					);
				// set language
				$form->language('deutsch');
				// set docktype xhtml
				$form->doctype('xhtml');
				
				// username
				$formIds['username'] = array('valueType' => 'string', 'type' => 'text',);
				$form->add(
						'label',		// type
						'labelUsername',	// id/name
						'username',			// for
						parent::lang('username')	// label text
					);
				$name = $form->add(
								$formIds['username']['type'],		// type
								'username',		// id/name
								$user->get_userinfo('username')	// default
					);
				$name->set_rule(
						array(
								'required' => array(
										'error', parent::lang('required username!'),
									),
								'regexp' => array(
										$this->getGc()->get_config('name.regexp.zebra'),	// regexp
										'error',	// error variable
										parent::lang('allowed chars').' ['.$this->getGc()->get_config('name.desc').']',	// message
									),
							)
					);
				$form->add(
						'note',			// type
						'noteUsername',		// id/name
						'username',			// for
						parent::lang('help').'&nbsp;'//.$this->helpButton(HELP_MSG_GROUPNAME)	// note text
					);
				
				// password
				$formIds['password'] = array('valueType' => 'string', 'type' => 'password',);
				$form->add(
						'label',			// type
						'labelPassword',	// id/name
						'password',			// for
						parent::lang('password')	// label text
					);
				$password = $form->add(
						$formIds['password']['type'],		// type
						'password',		// id/name
						'',				// value
						array('data-prefix' => 'img:img/iconTextboxPassword.png')
					);
				// passwordConfirm
				$formIds['passwordConfirm'] = array('valueType' => 'string', 'type' => 'password',);
				$form->add(
						'label',				// type
						'labelPasswordConfirm',	// id/name
						'passwordConfirm',				// for
						parent::lang('repeat password')	// label text
					);
				$passwordConfirm = $form->add(
						$formIds['passwordConfirm']['type'],			// type
						'passwordConfirm',	// id/name
						'',					// value
						array('data-prefix' => 'img:img/iconTextboxPassword.png')
					);
				$passwordConfirm->set_rule(
						array(
							'compare' => array(
								'password', 'error', parent::lang('has to be the same'),
								
							),
						)
					);
				
				// name
				$formIds['name'] = array('valueType' => 'string', 'type' => 'text',);
				$form->add(
						'label',		// type
						'labelName',	// id/name
						'name',			// for
						parent::lang('username')	// label text
					);
				$name = $form->add(
								$formIds['name']['type'],		// type
								'name',		// id/name
								$user->get_userinfo('name')	// default
					);
				$name->set_rule(
						array(
								'required' => array(
										'error', parent::lang('required name!'),
									),
								'regexp' => array(
										$this->getGc()->get_config('name.regexp.zebra'),	// regexp
										'error',	// error variable
										parent::lang('allowed chars').' ['.$this->getGc()->get_config('name.desc').']',	// message
									),
							)
					);
				$form->add(
						'note',			// type
						'noteName',		// id/name
						'name',			// for
						parent::lang('help').'&nbsp;'//.$this->helpButton(HELP_MSG_GROUPNAME)	// note text
					);
				
				// email
				$formIds['email'] = array('valueType' => 'string', 'type' => 'text',);
				$form->add(
						'label',		// type
						'labelEmail',	// id/name
						'email',			// for
						parent::lang('emailaddress')	// label text
					);
				$name = $form->add(
								$formIds['email']['type'],		// type
								'email',		// id/name
								$user->get_userinfo('email')	// default
					);
				$name->set_rule(
						array(
								'required' => array(
										'error', parent::lang('required email address!'),
									),
								'email' => array(
										'error',	// error variable
										parent::lang('valid email'),	// message
									),
							)
					);
				$form->add(
						'note',			// type
						'noteEmail',		// id/name
						'email',			// for
						parent::lang('help').'&nbsp;'//.$this->helpButton(HELP_MSG_GROUPNAME)	// note text
					);
				
				// groups
				$options = array();
				$config = array(
						0 => '',
						1 => '|--',
						'1+' => '|&nbsp;&nbsp;&nbsp;',
					);
				$allGroups = Group::allExistingGroups();
				usort($allGroups, array($this, 'callbackSortGroupsAlpha'));
				foreach($allGroups as $optionGroup) {
					$options[$optionGroup->getId()] = $optionGroup->nameToTextIntended($config);
				}
				$ownGroups = array();
				$ownGroupObjects = $user->get_groups();
				foreach($ownGroupObjects as $groupObject) {
					$ownGroups[] = $groupObject->getId();
				}
				$formIds['groups'] = array('valueType' => 'array', 'type' => 'select',);
				$form->add(
						'label',		// type
						'labelGroups',	// id/name
						'groups',			// for
						parent::lang('groups')	// label text
					);
				$groups = $form->add(
						$formIds['groups']['type'],	// type
						'groups[]',		// id/name
						$ownGroups,	// default
						array(		// attributes
								'size' => 10,
								'multiple' => 'multiple',
							)
					);
				$groups->add_options($options);
				$form->add(
						'note',		// type
						'noteGroups',	// id/name
						'groups',		// for
						parent::lang('help').'&nbsp;'//.$this->helpButton(HELP_MSG_FIELDTYPE)	// note text
					);
				
				// submit button
				$form->add(
						'submit',		// type
						'buttonSubmitUp',	// id/name
						parent::lang('save user')	// value
					);
				
				// validate
				if($form->validate()) {
					
					// get form data
					$data = $this->getFormValues($formIds);
					
					// update user and write to db
					$user->set_userinfo(
							array(
									'username' => $data['username'],
									'password' => ($data['password'] == '' ? $user->get_userinfo('password') : md5($data['password'])),
									'name' => $data['name'],
									'email' => $data['email'],
									'active' => 1,
								)
						);
					// groups
					$newGroups = array();
					foreach($data['groups'] as $groupId) {
						$newGroups[] = new Group($groupId);
					}
					$user->set_groups($newGroups);
					$user->writeDb();
					
					// output success message
					$ps[] = array(
							'params' => '',
							'content' => parent::lang('successful saved user:').' '.$data['name'].' ('.$user->get_id().')',
						);
					$sOutput->assign('ps', $ps);
					$content .= $sOutput->fetch('smarty.p.tpl');
				} else {
					$content .= $sOutput->fetch('smarty.p.tpl').$form->render('', true);
				}
			} else {
				
				// error
				$errno = $this->getError()->error_raised('UidNotExists','userContent',$uid);
				$this->getError()->handle_error($errno);
				return $this->getError()->to_html($errno);
			}
		} elseif($subaction == 'userdelete') {
			
			// provide delete form
			// check uid
			if(User::exists($uid) && $uid!=1) {
				
				// check usage
				if(!User::isUsed($uid)) {
					
					// delete form
					// smarty-templates
					$sConfirmation = new JudoIntranetSmarty();
					
					// form
					$form = new Zebra_Form(
						'formConfirm',			// id/name
						'post',				// method
						'administration.php?id=user&action=user&subaction=userdelete&uid='.$uid		// action
					);
					// set language
					$form->language('deutsch');
					// set docktype xhtml
					$form->doctype('xhtml');
					
					// add button
					$form->add(
						'submit',		// type
						'buttonSubmit',	// id/name
						parent::lang('delete'),	// value
						array('title' => parent::lang('delete'))
					);
					
					// smarty-link
					$link = array(
									'params' => 'class="submit"',
									'href' => 'administration.php?id=user&action=user',
									'title' => parent::lang('cancel'),
									'content' => parent::lang('cancel')
								);
					$sConfirmation->assign('link', $link);
					$sConfirmation->assign('spanparams', 'id="cancel"');
					$sConfirmation->assign('message', parent::lang('do you want to completely remove this user?').'&nbsp;');//.$this->helpButton(HELP_MSG_DELETE));
					$sConfirmation->assign('form', $form->render('', true));
					
					// validate
					if($form->validate()) {
						
						$sConfirmation->assign('message', parent::lang('successful removed user!'));
						$sConfirmation->assign('form', '');
						
						$user = new User(false);
						$user->change_user($uid, false, 'id');
						$user->delete();
					}
					
					// smarty return
					$content = $sConfirmation->fetch('smarty.confirmation.tpl');
				} else {
					
					// error
					$errno = $this->getError()->error_raised('ObjectInUse', 'userContent', $uid);
					$this->getError()->handle_error($errno);
					return $this->getError()->to_html($errno);
				}
			} else {
				
				// error
				$errno = $this->getError()->error_raised('UidNotExists','groupContent',$uid);
				$this->getError()->handle_error($errno);
				return $this->getError()->to_html($errno);
			}
		} else {
			
			// provide form and list users
			// form
			$form = new Zebra_Form(
					'userNew',			// id/name
					'post',						// method
					'administration.php?id=user&action=user'		// action
				);
			// set language
			$form->language('deutsch');
			// set docktype xhtml
			$form->doctype('xhtml');
			
			// username
			$formIds['username'] = array('valueType' => 'string', 'type' => 'text',);
			$form->add(
					'label',		// type
					'labelUsername',	// id/name
					'username',			// for
					parent::lang('username')	// label text
				);
			$name = $form->add(
							$formIds['username']['type'],		// type
							'username',		// id/name
							''	// default
				);
			$name->set_rule(
					array(
							'required' => array(
									'error', parent::lang('required username!'),
								),
							'regexp' => array(
									$this->getGc()->get_config('name.regexp.zebra'),	// regexp
									'error',	// error variable
									parent::lang('allowed chars').' ['.$this->getGc()->get_config('name.desc').']',	// message
								),
						)
				);
			$form->add(
					'note',			// type
					'noteUsername',		// id/name
					'username',			// for
					parent::lang('help').'&nbsp;'//.$this->helpButton(HELP_MSG_GROUPNAME)	// note text
				);
			
			// password
			$formIds['password'] = array('valueType' => 'string', 'type' => 'password',);
			$form->add(
					'label',			// type
					'labelPassword',	// id/name
					'password',			// for
					parent::lang('password')	// label text
				);
			$password = $form->add(
					$formIds['password']['type'],		// type
					'password',		// id/name
					'',				// value
					array('data-prefix' => 'img:img/iconTextboxPassword.png')
				);
			$password->set_rule(
					array(
							'required' => array(
									'error', parent::lang('required password!'),
								),
						)
				);
			// passwordConfirm
			$formIds['passwordConfirm'] = array('valueType' => 'string', 'type' => 'password',);
			$form->add(
					'label',				// type
					'labelPasswordConfirm',	// id/name
					'passwordConfirm',				// for
					parent::lang('repeat password')	// label text
				);
			$passwordConfirm = $form->add(
					$formIds['passwordConfirm']['type'],			// type
					'passwordConfirm',	// id/name
					'',					// value
					array('data-prefix' => 'img:img/iconTextboxPassword.png')
				);
			$passwordConfirm->set_rule(
					array(
						'compare' => array(
							'password', 'error', parent::lang('has to be the same'),
							
						),
					)
				);
			
			// name
			$formIds['name'] = array('valueType' => 'string', 'type' => 'text',);
			$form->add(
					'label',		// type
					'labelName',	// id/name
					'name',			// for
					parent::lang('name')	// label text
				);
			$name = $form->add(
							$formIds['name']['type'],		// type
							'name',		// id/name
							''	// default
				);
			$name->set_rule(
					array(
							'required' => array(
									'error', parent::lang('required name!'),
								),
							'regexp' => array(
									$this->getGc()->get_config('name.regexp.zebra'),	// regexp
									'error',	// error variable
									parent::lang('allowed chars').' ['.$this->getGc()->get_config('name.desc').']',	// message
								),
						)
				);
			$form->add(
					'note',			// type
					'noteName',		// id/name
					'name',			// for
					parent::lang('help').'&nbsp;'//.$this->helpButton(HELP_MSG_GROUPNAME)	// note text
				);
			
			// email
			$formIds['email'] = array('valueType' => 'string', 'type' => 'text',);
			$form->add(
					'label',		// type
					'labelEmail',	// id/name
					'email',			// for
					parent::lang('emailaddress')	// label text
				);
			$name = $form->add(
							$formIds['email']['type'],		// type
							'email',		// id/name
							''	// default
				);
			$name->set_rule(
					array(
							'required' => array(
									'error', parent::lang('required name!'),
								),
							'email' => array(
									'error',	// error variable
									parent::lang('valid email'),	// message
								),
						)
				);
			$form->add(
					'note',			// type
					'noteEmail',		// id/name
					'email',			// for
					parent::lang('help').'&nbsp;'//.$this->helpButton(HELP_MSG_GROUPNAME)	// note text
				);
			
			// groups
			$options = array();
			$config = array(
					0 => '',
					1 => '|--',
					'1+' => '|&nbsp;&nbsp;&nbsp;',
				);
			$allGroups = Group::allExistingGroups();
			usort($allGroups, array($this, 'callbackSortGroupsAlpha'));
			foreach($allGroups as $optionGroup) {
				$options[$optionGroup->getId()] = $optionGroup->nameToTextIntended($config);
			}
			$formIds['groups'] = array('valueType' => 'array', 'type' => 'select',);
			$form->add(
					'label',		// type
					'labelGroups',	// id/name
					'groups',			// for
					parent::lang('groups')	// label text
				);
			$groups = $form->add(
					$formIds['groups']['type'],	// type
					'groups[]',		// id/name
					'',	// default
					array(		// attributes
							'size' => 10,
							'multiple' => 'multiple',
						)
				);
			$groups->add_options($options);
			$form->add(
					'note',		// type
					'noteGroups',	// id/name
					'groups',		// for
					parent::lang('help').'&nbsp;'//.$this->helpButton(HELP_MSG_FIELDTYPE)	// note text
				);
			
			// submit button
			$form->add(
					'submit',		// type
					'buttonSubmitUp',	// id/name
					parent::lang('add user')	// value
				);
			
			// validate
			if($form->validate()) {
				
				// get form data
				$data = $this->getFormValues($formIds);
				
				// update user and write to db
				$user = new User(false);
				$user->change_user($uid, false, 'id');
				$user->set_userinfo(
						array(
								'username' => $data['username'],
								'password' => ($data['password'] == '' ? $user->get_userinfo('password') : md5($data['password'])),
								'name' => $data['name'],
								'email' => $data['email'],
								'active' => 1,
							)
					);
				// groups
				$newGroups = array();
				foreach($data['groups'] as $groupId) {
					$newGroups[] = new Group($groupId);
				}
				$user->set_groups($newGroups);
				$user->writeDb(DB_WRITE_NEW);
				
				// output success message
				// output template
				$sOutput = new JudoIntranetSmarty();
				$ps[] = array(
						'params' => '',
						'content' => parent::lang('successful added user:').' '.$data['name'].' ('.$user->get_id().')',
					);
				$sOutput->assign('ps', $ps);
				$content .= $sOutput->fetch('smarty.p.tpl');
			} else {
				$content .= $form->render('', true);
			}
			
			// user list
			$sUserList->assign('users', $this->getUser()->return_all_users());
			$content .= $sUserList->fetch('smarty.admin.userlist.tpl');
		}
		
		// return
		return $content;
	}
	
	
	/**
	 * groupContent() handles the administration of groups
	 * 
	 * @return string html-string with the user administration page
	 */
	private function groupContent() {
		
		// prepare variables
		$gid = $this->get('gid');
		$subaction = $this->get('subaction');
		$content = '';
		
		// prepare group list
		$sGroupList = new JudoIntranetSmarty();
		$sGroupList->assign('groups', Group::allExistingGroups());
		$sGroupList->assign('action', 'group');
		
		// check for group list or edit page
		if($subaction == 'groupedit') {
			
			// provide edit form
			// check gid
			if(Group::exists($gid) && $gid!=1) {
				
				// output template
				$sOutput = new JudoIntranetSmarty();
				
				// get group
				$group = new Group($gid);
				
				$ps[] = array(
						'params' => 'class="bold"',
						'content' => parent::lang('edit group:').' '.$group->getName(),
					);
				$sOutput->assign('ps', $ps);
				// edit form
				// form
				$form = new Zebra_Form(
						'groupEdit',			// id/name
						'post',						// method
						'administration.php?id=user&action=group&subaction=groupedit&gid='.$gid		// action
					);
				// set language
				$form->language('deutsch');
				// set docktype xhtml
				$form->doctype('xhtml');
				
				// name
				$formIds['name'] = array('valueType' => 'string', 'type' => 'text',);
				$form->add(
						'label',		// type
						'labelName',	// id/name
						'name',			// for
						parent::lang('group name')	// label text
					);
				$name = $form->add(
								$formIds['name']['type'],		// type
								'name',		// id/name
								$group->getName()	// default
					);
				$name->set_rule(
						array(
								'required' => array(
										'error', parent::lang('required name!'),
									),
								'regexp' => array(
										$this->getGc()->get_config('name.regexp.zebra'),	// regexp
										'error',	// error variable
										parent::lang('allowed chars').' ['.$this->getGc()->get_config('name.desc').']',	// message
									),
							)
					);
				$form->add(
						'note',			// type
						'noteName',		// id/name
						'name',			// for
						parent::lang('help').'&nbsp;'//.$this->helpButton(HELP_MSG_GROUPNAME)	// note text
					);
				
				// subgroup of
				$options = array();
				$config = array(
						0 => '',
						1 => '|--',
						'1+' => '|&nbsp;&nbsp;&nbsp;',
					);
				$allGroups = Group::allExistingGroups();
				usort($allGroups, array($this, 'callbackSortGroupsAlpha'));
				foreach($allGroups as $optionGroup) {
					if($optionGroup->getId() != $gid) {
						$options[$optionGroup->getId()] = $optionGroup->nameToTextIntended($config);
					}
				}
				$formIds['subgroupOf'] = array('valueType' => 'int', 'type' => 'select',);
				$form->add(
						'label',		// type
						'labelSubgroupOf',	// id/name
						'subgroupOf',			// for
						parent::lang('parent group')	// label text
					);
				$subgroupOf = $form->add(
						$formIds['subgroupOf']['type'],	// type
						'subgroupOf',		// id/name
						$group->getParent(),	// default
						array(		// attributes
								'size' => 10,
							)
					);
				$subgroupOf->add_options($options);
				$subgroupOf->set_rule(
						array(
								'required' => array(
										'error', parent::lang('required paren group!')
									),
							)
					);
				$form->add(
						'note',		// type
						'noteSubgroupOf',	// id/name
						'subgroupOf',		// for
						parent::lang('help').'&nbsp;'//.$this->helpButton(HELP_MSG_FIELDTYPE)	// note text
					);
				
				// submit button
				$form->add(
						'submit',		// type
						'buttonSubmitUp',	// id/name
						parent::lang('save group')	// value
					);
				
				// validate
				if($form->validate()) {
					
					// get form data
					$data = $this->getFormValues($formIds);
					
					// update group and write to db
					$group->update(
							array(
									'name' => $data['name'],
									'parent' => $data['subgroupOf'],
									'valid' => 1,
								)
						);
					$group->writeDb();
					
					// output success message
					$ps[] = array(
							'params' => '',
							'content' => parent::lang('successful saved group:').' '.$data['name'].' ('.$group->getId().')',
						);
					$sOutput->assign('ps', $ps);
					$content .= $sOutput->fetch('smarty.p.tpl');
				} else {
					$content .= $sOutput->fetch('smarty.p.tpl').$form->render('', true);
				}
			} else {
				
				// error
				$errno = $this->getError()->error_raised('GidNotExists','groupContent',$gid);
				$this->getError()->handle_error($errno);
				return $this->getError()->to_html($errno);
			}
		} elseif($subaction == 'groupdelete') {
			
			// provide delete form
			// check gid
			if(Group::exists($gid) && $gid!=1) {
				
				// check usage
				if(!Group::isUsed($gid)) {
					
					// delete form
					// smarty-templates
					$sConfirmation = new JudoIntranetSmarty();
					
					// form
					$form = new Zebra_Form(
						'formConfirm',			// id/name
						'post',				// method
						'administration.php?id=user&action=group&subaction=groupdelete&gid='.$gid		// action
					);
					// set language
					$form->language('deutsch');
					// set docktype xhtml
					$form->doctype('xhtml');
					
					// add button
					$form->add(
						'submit',		// type
						'buttonSubmit',	// id/name
						parent::lang('delete'),	// value
						array('title' => parent::lang('delete'))
					);
					
					// smarty-link
					$link = array(
									'params' => 'class="submit"',
									'href' => 'administration.php?id=user&action=group',
									'title' => parent::lang('cancel'),
									'content' => parent::lang('cancel')
								);
					$sConfirmation->assign('link', $link);
					$sConfirmation->assign('spanparams', 'id="cancel"');
					$sConfirmation->assign('message', parent::lang('do you want to completely remove this group?').'&nbsp;');//.$this->helpButton(HELP_MSG_DELETE));
					$sConfirmation->assign('form', $form->render('', true));
					
					// validate
					if($form->validate()) {
						
						$sConfirmation->assign('message', parent::lang('successful deleted group!'));
						$sConfirmation->assign('form', '');
						
						$group = new Group($gid);
						$group->delete();
					}
					
					// smarty return
					$content = $sConfirmation->fetch('smarty.confirmation.tpl');
				} else {
					
					// error
					$errno = $this->getError()->error_raised('ObjectInUse', 'groupContent', $gid);
					$this->getError()->handle_error($errno);
					return $this->getError()->to_html($errno);
				}
			} else {
				
				// error
				$errno = $this->getError()->error_raised('GidNotExists','groupContent',$gid);
				$this->getError()->handle_error($errno);
				return $this->getError()->to_html($errno);
			}
		} else {
			
			// provide form and list groups
			// form
			$form = new Zebra_Form(
					'groupNew',			// id/name
					'post',						// method
					'administration.php?id=user&action=group'		// action
				);
			// set language
			$form->language('deutsch');
			// set docktype xhtml
			$form->doctype('xhtml');
			
			// name
			$formIds['name'] = array('valueType' => 'string', 'type' => 'text',);
			$form->add(
					'label',		// type
					'labelName',	// id/name
					'name',			// for
					parent::lang('group name')	// label text
				);
			$name = $form->add(
							$formIds['name']['type'],		// type
							'name'		// id/name
				);
			$name->set_rule(
					array(
							'required' => array(
									'error', parent::lang('required group name!'),
								),
							'regexp' => array(
									$this->getGc()->get_config('name.regexp.zebra'),	// regexp
									'error',	// error variable
									parent::lang('class.AdministrationView#groupContent#form#rule.regexp.allowedChars').' ['.$this->getGc()->get_config('name.desc').']',	// message
								),
						)
				);
			$form->add(
					'note',			// type
					'noteName',		// id/name
					'name',			// for
					parent::lang('help').'&nbsp;'//.$this->helpButton(HELP_MSG_GROUPNAME)	// note text
				);
			
			// subgroup of
			$options = array();
			$config = array(
					0 => '',
					1 => '|--',
					'1+' => '|&nbsp;&nbsp;&nbsp;',
				);
			$groups = Group::allExistingGroups();
			usort($groups, array($this, 'callbackSortGroupsAlpha'));
			foreach($groups as $group) {
				$options[$group->getId()] = $group->nameToTextIntended($config);
			}
			$formIds['subgroupOf'] = array('valueType' => 'int', 'type' => 'select',);
			$form->add(
					'label',		// type
					'labelSubgroupOf',	// id/name
					'subgroupOf',			// for
					parent::lang('parent group')	// label text
				);
			$subgroupOf = $form->add(
					$formIds['subgroupOf']['type'],	// type
					'subgroupOf',		// id/name
					1,			// default
					array(		// attributes
							'size' => 10,
						)
				);
			$subgroupOf->add_options($options);
			$subgroupOf->set_rule(
					array(
							'required' => array(
									'error', parent::lang('required parent group!')
								),
						)
				);
			$form->add(
					'note',		// type
					'noteSubgroupOf',	// id/name
					'subgroupOf',		// for
					parent::lang('help').'&nbsp;'//.$this->helpButton(HELP_MSG_FIELDTYPE)	// note text
				);
			
			// submit button
			$form->add(
					'submit',		// type
					'buttonSubmitUp',	// id/name
					parent::lang('add group')	// value
				);
			
			// validate
			if($form->validate()) {
				
				// get form data
				$data = $this->getFormValues($formIds);
				
				// create new group and write to db
				$newGroup = new Group();
				$newGroup->update(
						array(
								'name' => $data['name'],
								'parent' => $data['subgroupOf'],
								'valid' => 1,
							)
					);
				$newId = $newGroup->writeDb();
				
				// output success message
				$sOutput = new JudoIntranetSmarty();
				$sOutput->assign('params', '');
				$sOutput->assign('content', parent::lang('successful added group:').' '.$data['name'].' ('.$newId.')');
				$content .= $sOutput->fetch('smarty.p.tpl');
			} else {
				$content .= $form->render('', true);
			}
			
			// group list
			$sGroupList->assign('groups', Group::allExistingGroups());
			$content .= $sGroupList->fetch('smarty.admin.grouplist.tpl');
		}
		
		// return
		return $content;
	}
	
	
	/**
	 * permissionContent() handles the administration of permissions
	 * 
	 * @return string html-string with the user administration page
	 */
	private function permissionContent() {
		
		// check for navi list or permission page
		$nid = $this->get('nid');
		$forbiddenNids = array(1, 2, 3, 45);
		if($nid === false) {
			
			// navi list
			// get and sort navi entries
			$sql = 'SELECT `id`
					FROM `navi`
					WHERE `parent`=0
						AND `valid`=1
					';
			$naviEntries = Db::arrayValue($sql, MYSQL_ASSOC);
			
			if(!is_array($naviEntries)) {
				$errno = $this->getError()->error_raised('MysqlError', Db::$error, Db::$statement);
				$this->getError()->handle_error($errno);
			}
			
			// prepare navi tree and sort by position
			$navi = array();
			foreach($naviEntries as $naviId) {
				
				$navi[] = new Navi($naviId['id']);
			}
			usort($navi, array($this, 'callbackSortNavi'));
			
			// group list
			$sNaviList = new JudoIntranetSmarty();
			$sNaviList->assign('action', 'permission');
			$sNaviList->assign('navi', $navi);
			$sNaviList->assign('entriesNotShown', $forbiddenNids);
			return $sNaviList->fetch('smarty.admin.navilist.tpl');
		} else {
			
			// check if navi entry exists
			if(Navi::exists($nid) && !in_array($nid, $forbiddenNids)) {
				
				// get navi object
				$navi = new Navi($nid);
				$parentNavi = null;
				if($navi->getParent() != 0) {
					$parentNavi = new Navi($navi->getParent());
				}
				
				// get and sort groups
				$groups = Group::allExistingGroups();
				$groups[0] = Group::fakePublic();
				usort($groups, array($this, 'callbackSortGroupsAlpha'));
				
				// form
				$form = new Zebra_Form(
						'naviPermission',			// id/name
						'post',						// method
						'administration.php?id=user&action=permission&nid='.$nid		// action
					);
				// set language
				$form->language('deutsch');
				// set docktype xhtml
				$form->doctype('xhtml');
				
				// upper submit button
				$form->add(
						'submit',		// type
						'buttonSubmitUp',	// id/name
						parent::lang('save')	// value
					);
				
				// walk through navi and add to form
				$formIds = array();
				foreach($groups as $group) {
					if($group->getId() != 1) {
						$this->addPermissionEntry($form, $group, $formIds);
					}
				}
				
				// lower submit button
				$form->add(
						'submit',		// type
						'buttonSubmitLow',	// id/name
						parent::lang('save')	// value
					);
				
				// validate
				if($form->validate()) {
					
					// get form permissions
					$permissions = $this->getFormPermissions($formIds);
					
					// write permissions
					$navi->dbDeletePermission();
					$navi->dbWritePermission($permissions);
					
					// output success message
					$sOutput = new JudoIntranetSmarty();
					$data = array(
							array('params' => 'class="bold"', 'content' => (!is_null($parentNavi) ? parent::lang($parentNavi->getName()).' &rarr; ' : '').parent::lang($navi->getName()).' ('.$navi->getRequiredPermission().') '),
							array('params' => '', 'content' => parent::lang('successful saved permissions')),
						);
					$sOutput->assign('ps', $data);
					return $sOutput->fetch('smarty.p.tpl');
				} else {
					
					// output form
					$sOutput = new JudoIntranetSmarty();
					$data = array(
							array('params' => 'class="bold"', 'content' => (!is_null($parentNavi) ? parent::lang($parentNavi->getName()).' &rarr; ' : '').parent::lang($navi->getName()).' ('.$navi->getRequiredPermission().') '),
							array('params' => '', 'content' => $form->render('', true)),
						);
					$sOutput->assign('ps', $data);
					return $sOutput->fetch('smarty.p.tpl');
				}
			} else {
				
				// error
				$errno = $this->getError()->error_raised('NidNotExists','permissionContent',$nid);
				$this->getError()->handle_error($errno);
				return $this->getError()->to_html($errno);
			}
		}
	}
	
	
	/**
	 * addPermissionEntry() adds the radios to the $form
	 * 
	 * @param object $form the zebra_form object to add the permission radios to
	 * @param object $group the group object to add
	 * @param array $formIds array to collect the the names of the form elements
	 */
	private function addPermissionEntry(&$form, $group, &$formIds) {
		
		// prepare form
		$radioName = 'group_'.$group->getId();
		
		// prepare images
		// read
		$imgReadText = parent::lang('permission: read/list');
		$imgRead = array(
				'params' => 'class="iconRead clickable" title="'.$imgReadText.'" onclick="selectRadio(\''.$radioName.'_r\')"',
				'src' => 'img/permissions_read.png',
				'alt' => $imgReadText,
			);
		$sImgReadTemplate = new JudoIntranetSmarty();
		$sImgReadTemplate->assign('img', $imgRead);
		
		// edit
		$imgEditText = parent::lang('permission: edit');
		$imgEdit = array(
				'params' => 'class="iconEdit clickable" title="'.$imgEditText.'" onclick="selectRadio(\''.$radioName.'_w\')"',
				'src' => 'img/permissions_edit.png',
				'alt' => $imgEditText,
			);
		$sImgEditTemplate = new JudoIntranetSmarty();
		$sImgEditTemplate->assign('img', $imgEdit);
		
		// prepare clear radio link
		$sImgClearTemplate = new JudoIntranetSmarty();
		$img = array(
				'params' => 'class="clickable" onclick="clearRadio(\''.$radioName.'\')" title="'.parent::lang('remove permissions').'"',
				'src' => 'img/permissions_delete.png',
				'alt' => parent::lang('remove permissions'),
			);
		$sImgClearTemplate->assign('img', $img);
		
		// add radios
		$formIds[$radioName] = array('valueType' => 'int', 'type' => 'radios', 'default' => 1);
		$form->add(
				'label',		// type
				'label_'.$radioName,	// id/name
				$radioName,		// for
				$group->getName()	// label text
			);
		$form->add(
				$formIds[$radioName]['type'],	// type
				$radioName,						// id/name
				array(				// values
						'r' => $sImgReadTemplate->fetch('smarty.img.tpl'),
						'w' => $sImgEditTemplate->fetch('smarty.img.tpl'),
					),
				$group->permissionFor('navi', $this->get('nid'))	// default
			);
		$form->add(
				'note',			// type
				'note_'.$radioName,	// id/name
				$radioName,		// for
				parent::lang('completely remove permission on this navi entry').':&nbsp;'.$sImgClearTemplate->fetch('smarty.img.tpl')	// note text
			);
	}
	
	
	/**
	 * createNewYear() handles the creation of a new year
	 * 
	 * @return string html-string with the new year creation page
	 */
	private function createNewYear() {
		
		// smarty caption
		$this->getTpl()->assign('caption', parent::lang('Create new year'));
		
		// prepare template
		$sCreateNewYear = new JudoIntranetSmarty();
		
		// get actual year from database
		$dbYear = Db::singleValue('
			SELECT DISTINCT `year`
			FROM `judo`
			ORDER BY `year` DESC
			LIMIT 1
		');
		if($dbYear === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// prepare new year
		$newYear = $dbYear + 1;
		// get db values of actual year
		$usertableListing = new AdministrationUsertableFieldListing();
		$dbValues = $usertableListing->listingAsArray(array('orderBy' => '','limit' => '',));
		// insert new values
		$result = null;
		foreach($dbValues as $value) {
			
			// set valid 0
			$value['valid'] = 'false';
			// set new year
			$value['year'] = $newYear;
			
			// create row
			$result = $usertableListing->createRow($value);
		}
		
		// check result
		if($result === JTABLE_NOT_AUTHORIZED) {
			throw new NotAuthorizedException($this);
		}
		
		// create message
		// assign variables
		$sCreateNewYear->assign('messageType', 'messageInfo');
		$sCreateNewYear->assign('messageCaption', _l('New year created'));
		$sCreateNewYear->assign('messageMessage', _l('Successfully created year #?year', array('year' => $newYear,)));
		$sCreateNewYear->assign('messageValue', '');
		$sCreateNewYear->assign('messageActions', array());
		
		// fetch content
		return $sCreateNewYear->fetch('smarty.message.tpl');
		
	}
}



?>
