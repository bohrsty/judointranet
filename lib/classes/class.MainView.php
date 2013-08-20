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
 * class CalendarView implements the control of the calendar-page
 */
class MainView extends PageView {
	
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
							'name' => 'class.MainView#connectnavi#firstlevel#name',
							'file' => 'index.php',
							'position' => 0,
							'class' => 'MainView',
							'id' => md5('MainView'), // ffe65f439f54bfbd4437df5967d4c173
							'show' => true
						),
						'secondlevel' => array(
						)
					);
		
		// login or logout
		if($_SESSION['user']->get_loggedin()) {
			
			// add logout
			array_unshift($navi['secondlevel'],
					array(
						'getid' => 'logout', 
						'name' => 'class.PageView#navi#secondlevel#logout',
						'id' => md5('MainView|logout'), // 2440e505211f609c568a2a0e811b1636
						'show' => true
					),
					array(
						'getid' => 'user', 
						'name' => 'class.PageView#navi#secondlevel#user',
						'id' => md5('MainView|user'), // 23b195e85c4e452b9990b75f64d9a4a3
						'show' => false
					)
				);
		} else {
			
			// add login
			array_unshift($navi['secondlevel'],
					array(
						'getid' => 'login', 
						'name' => 'class.PageView#navi#secondlevel#login',
						'id' => md5('MainView|login'), // a2f4f271c394ad7472ee4e600d3df345
						'show' => true
					)
				);
		}
		
		// return array
		return $navi;
	}
	
	
	
	
	
	
	
	/**
	 * init chooses the functionality by using $_GET['id']
	 * 
	 * @return void
	 */
	public function init() {
		
		// set pagename
		$this->tpl->assign('pagename',parent::lang('class.MainView#page#init#name'));
		
		// init helpmessages
		$this->initHelp();
		
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
					
					case 'login':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.MainView#init#login#title')));
						$this->tpl->assign('main', $this->login());
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('hierselect', false);
						
					break;
					
					case 'logout':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.MainView#init#logout#title')));
						$this->tpl->assign('main', $_SESSION['user']->logout());
						$this->tpl->assign('jquery', false);
						$this->tpl->assign('hierselect', false);
						
					break;
					
					case 'user':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.MainView#init#user#title')));
						$this->tpl->assign('main', $this->user());
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('hierselect', true);
						
					break;
					
					default:
						
						// id set, but no functionality
						$errno = $GLOBALS['Error']->error_raised('GETUnkownId','entry:'.$this->get('id'),$this->get('id'));
						$GLOBALS['Error']->handle_error($errno);
						$this->add_output(array('main' => $GLOBALS['Error']->to_html($errno)),true);
						
						// smarty
						$this->tpl->assign('title', '');
						$this->tpl->assign('main', $GLOBALS['Error']->to_html($errno));
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('hierselect', false);
					break;
				}
			} else {
				
				// error not authorized
				$errno = $GLOBALS['Error']->error_raised('NotAuthorized','entry:'.$this->get('id'),$this->get('id'));
				$GLOBALS['Error']->handle_error($errno);
				
				// smarty
				$this->tpl->assign('title', $this->title(parent::lang('class.MainView#init#Error#NotAuthorized')));
				$this->tpl->assign('main', $GLOBALS['Error']->to_html($errno));
				$this->tpl->assign('jquery', true);
				$this->tpl->assign('hierselect', false);
			}
		} else {
			
			// id not set
			// smarty-title
			$this->tpl->assign('title', $this->title(parent::lang('class.MainView#init#default#title'))); 
			// smarty-main
			$this->tpl->assign('main', $this->defaultContent());
			// smarty-jquery
			$this->tpl->assign('jquery', true);
			// smarty-hierselect
			$this->tpl->assign('hierselect', false);
		}
		
		// global smarty
		$this->showPage();
	}
	
	
	
	
	
	
	
	/**
	 * login checks username and password and set the loginstatus appropriate,
	 * returns login-form or loggedin-message
	 * 
	 * @return string the html-string of login-form or message
	 */
	private function login() {
		
		// smarty-template
		$sLogin = new JudoIntranetSmarty();
		
		// decode uri
		$uri = 'index.php';
		$r = '';
		if($this->get('r') !== false) {
			$uri = base64_decode($this->get('r'));
			$r = '&amp;r='.$this->get('r');
		}
		
		// formular		
		$form = new HTML_QuickForm2(
								'login',
								'post',
								array(
									'name' => 'login',
									'action' => 'index.php?id=login'.$r
								)
							);
		
		// renderer
		$renderer = HTML_QuickForm2_Renderer::factory('default');
		$renderer->setOption('required_note',parent::lang('class.MainView#login#form#requiredNote'));
		
		// elements
		// username
		$username = $form->addElement('text','username')->setLabel(parent::lang('class.MainView#login#form#username').':');
		$username->addRule('required',parent::lang('class.MainView#login#rule#required.username'));
//		$username->addRule('regexp','');
		
		// password
		$password = $form->addElement('password','password')->setLabel(parent::lang('class.MainView#login#form#password').':');		
		$password->addRule('required',parent::lang('class.MainView#login#rule#required.password'));
//		$password->addRule('regexp','');
		
		// submit-button
		$form->addElement('submit','submit',array('value' => parent::lang('class.MainView#login#form#loginButton')));
		
		// callback
		$form->addRule('callback','Authentifizierung fehlgeschlagen',array('callback' => array($this,'callback_check_login')));
		
		// smarty-mesage
		$sLogin->assign('caption', parent::lang('class.MainView#login#message#caption'));
		
		// validate
		if($form->validate()) {
			
			// login and redirect
			$_SESSION['user']->change_user($username->getValue(),true);
			header('Location:'.$uri);
			exit();
		} else {
			
			// smarty message and form
			$sLogin->assign('message', parent::lang($_SESSION['user']->get_login_message()).'&nbsp;'.$GLOBALS['help']->getMessage(HELP_MSG_LOGIN, array($_SESSION['user']->get_login_message() => '')));
			$sLogin->assign('form', $form->render($renderer));
		}
		
		
		// return smarty
		return $sLogin->fetch('smarty.login.tpl');
	}
	
	
	
	
	
	
	/**
	 * callback_check_login checks the given infos against the user
	 * 
	 * @param array $args data from quickform to check
	 */
	public function callback_check_login($args) {
		
		// check if user exists
		$user = $_SESSION['user']->check_login($args['username']);
		if($user !== false) {
			
			// check active and password
			if($user['active'] == 0) {
				
				// set message and return false
				$_SESSION['user']->set_login_message('class.MainView#callback_check_login#message#UserNotActive');
				return false;
			} elseif($user['password'] != md5($args['password'])) {
				
				// set message and return false
				$_SESSION['user']->set_login_message('class.MainView#callback_check_login#message#WrongPassword');
				return false;
			} else {
				
				// username and password correct, return true
				return true;
			}
		} else {
			
			// set message and return false
			$_SESSION['user']->set_login_message('class.MainView#callback_check_login#message#UserNotExist');
			return false;
		}
	}
	
	
	
	
	
	
	/**
	 * user controles the actions for usersettings
	 * 
	 * @return string the html-string of usersettings-page
	 */
	private function user() {
		
		// smarty-template
		$sUserPasswd = new JudoIntranetSmarty();
		
		// prepare return
		$return = '';
		
		// check login
		if($_SESSION['user']->get_loggedin()) {
		
			// smarty
			$sUserPasswd->assign('pagecaption', parent::lang('class.MainView#user#caption#general').' '.$_SESSION['user']->get_userinfo('name'));
				
			// check action
			if($this->get('action') == 'passwd') {
				
				// smarty
				$sUserPasswd->assign('section', parent::lang('class.MainView#user#caption#passwd'));
				
				// prepare form
				$form = new HTML_QuickForm2(
						'passwd',
						'post',
						array(
							'name' => 'passwd',
							'action' => 'index.php?id=user&action=passwd'
						)
					);
				
				// add elementgroup
				$passwd = $form->addElement('group','password',array());
				// add fields
				$passwd1 = $passwd->addElement('password','password1',array());
				$passwd2 = $passwd->addElement('password','password2',array());
				// add label
				$passwd->setLabel(parent::lang('class.MainView#user#passwd#label').':');
				// submit-button
				$form->addSubmit('submit',array('value' => parent::lang('class.MainView#user#passwd#submitButton')));
				// renderer
				$renderer = HTML_QuickForm2_Renderer::factory('default');
				$renderer->setOption('required_note',parent::lang('class.MainView#user#form#requiredNote'));
				// add rules
				$passwd->addRule('required',parent::lang('class.MainView#user#rule#required'));
				$passwd->addRule('callback',parent::lang('class.MainView#user#rule#checkPasswd'),array($this,'callback_check_passwd'));			
				
				// validate
				if($form->validate()) {
					
					// get values
					$data = $form->getValue();
					
					// get db-object
					$db = Db::newDb();
					
					// prepare sql-statement
					$sql = "UPDATE user
							SET password='".md5($data['password']['password1'])."'
							WHERE id=".$_SESSION['user']->get_id();
					
					// execute statement
					$result = $db->query($sql);
					
					// smarty message
					$sUserPasswd->assign('message', parent::lang('class.MainView#user#validate#passwdChanged'));
				} else {
					
					// smarty form and return
					$sUserPasswd->assign('form', $form->render($renderer));
				}
				return $sUserPasswd->fetch('smarty.user.passwd.tpl');
			} else {
				return 'default content';
			}
		} else {
			
			// not authorized
			$errno = $GLOBALS['Error']->error_raised('NotAuthorized','entry:'.$this->get('id'),$this->get('id'));
			$GLOBALS['Error']->handle_error($errno);
			return $GLOBALS['Error']->to_html($errno);
		}
	}
	
	
	
	
	
	
	/**
	 * callback_check_passwd checks the given passwords for identity
	 * 
	 * @param array $args data from quickform to check
	 */
	public function callback_check_passwd($args) {
		
		// check passwords
		if($args['password1'] === $args['password2']) {
			return true;
		} else {
			return false;
		}
		
	}
}



?>
