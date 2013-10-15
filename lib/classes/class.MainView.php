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
		$this->tpl->assign('pagename',parent::lang('class.MainView#page#init#name'));
		
		// init helpmessages
		$this->initHelp();
		
		// switch $_GET['id'] if set
		if($this->get('id') !== false) {
			
			switch($this->get('id')) {
				
				case 'login':
					
					// smarty
					$this->tpl->assign('title', $this->title(parent::lang('class.MainView#init#login#title')));
					$this->tpl->assign('main', $this->login());
					$this->tpl->assign('zebraform', true);
					
				break;
				
				case 'logout':
					
					// smarty
					$this->tpl->assign('title', $this->title(parent::lang('class.MainView#init#logout#title')));
					$this->tpl->assign('main', $this->getUser()->logout());
					
				break;
				
				case 'user':
					
					// smarty
					$this->tpl->assign('title', $this->title(parent::lang('class.MainView#init#user#title')));
					$this->tpl->assign('main', $this->user());
					$this->tpl->assign('zebraform', true);
					
				break;
				
				default:
					
					// id set, but no functionality
					$errno = $this->getError()->error_raised('GETUnkownId','entry:'.$this->get('id'),$this->get('id'));
					$this->getError()->handle_error($errno);
					$this->add_output(array('main' => $this->getError()->to_html($errno)),true);
					
					// smarty
					$this->tpl->assign('title', '');
					$this->tpl->assign('main', $this->getError()->to_html($errno));
				break;
			}
		} else {
			
			// id not set
			// smarty-title
			$this->tpl->assign('title', $this->title(parent::lang('class.MainView#init#default#title'))); 
			// smarty-main
			$this->tpl->assign('main', $this->defaultContent());
		}
		
		// global smarty
		$this->showPage('smarty.main.tpl');
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
			$r = '&r='.$this->get('r');
		}
		$loginUri = 'index.php?id=login'.$r;
		
		$form = new Zebra_Form(
				'login',					// id/name
				'post',						// method
				'index.php?id=login'.$r		// action
			);
		// set language
		$form->language('deutsch');
		// set docktype xhtml
		$form->doctype('xhtml');
		
		// elements
		// username
		$form->add(
				'label',			// type
				'labelUsername',	// id/name
				'username',			// for
				parent::lang('class.MainView#login#form#username'),	// label text
				array('inside' => true,)	// label inside
			);
		$username = $form->add(
				'text',			// type
				'username'		// id/name
			);
		$username->set_rule(
				array(
					'required' => array(
						'error', parent::lang('class.MainView#login#rule#required.username'),
					),
				)
			);
		
		// password
		$form->add(
				'label',			// type
				'labelPassword',	// id/name
				'password',			// for
				parent::lang('class.MainView#login#form#password'),	// label text
				array('inside' => true,)	// label inside
			);
		$password = $form->add(
				'password',		// type
				'password'		// id/name
			);
		$password->set_rule(
				array(
					'required' => array(
						'error', parent::lang('class.MainView#login#rule#required.password'),
					),
				)
			);
		
		// submit-button
		$form->add(
				'submit',		// type
				'buttonSubmit',	// id/name
				parent::lang('class.MainView#login#form#loginButton')	// value
			);
		
		// smarty-mesage
		$sLogin->assign('caption', parent::lang('class.MainView#login#message#caption'));
		
		// validate
		if($form->validate()) {
			if($this->getUser()->checkLogin($this->post('username'), $this->post('password'))) {
				
				// login and redirect
				$this->getUser()->change_user($this->post('username'),true);
				header('Location:'.$uri);
				exit();
			} else {
				
				// login failed and redirect
				header('Location:'.$loginUri);
				exit();
			}
		} else {
			
			// smarty message and form
			$sLogin->assign('message', parent::lang($this->getUser()->get_login_message()).'&nbsp;'.$this->getHelp()->getMessage(HELP_MSG_LOGIN, array($this->getUser()->get_login_message() => '')));
			$sLogin->assign('form', $form->render('', true));
		}		
		
		// return smarty
		return $sLogin->fetch('smarty.login.tpl');
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
		if($this->getUser()->get_loggedin()) {
		
			// smarty
			$sUserPasswd->assign('pagecaption', parent::lang('class.MainView#user#caption#general').' '.$this->getUser()->get_userinfo('name'));
				
			// check action
			if($this->get('action') == 'passwd') {
				
				// smarty
				$sUserPasswd->assign('section', parent::lang('class.MainView#user#caption#passwd'));
				
				// prepare form
				$form = new Zebra_Form(
					'passwd',					// id/name
					'post',						// method
					'index.php?id=user&action=passwd'	// action
				);
				
		// TODO: add complexity check
				// password
				$form->add(
						'label',			// type
						'labelPassword',	// id/name
						'password',			// for
						parent::lang('class.MainView#user#passwd#label'),	// label text
						array('inside' => true,)	// label inside
					);
				$password = $form->add(
						'password',		// type
						'password'		// id/name
					);
				$password->set_rule(
						array(
							'required' => array(
								'error', parent::lang('class.MainView#user#rule#required'),
							),
						)
					);
				// passwordConfirm
				$form->add(
						'label',				// type
						'labelPasswordConfirm',	// id/name
						'passwordConfirm',				// for
						parent::lang('class.MainView#user#passwd#labelConfirm'),	// label text
						array('inside' => true,)	// label inside
					);
				$passwordConfirm = $form->add(
						'password',			// type
						'passwordConfirm'	// id/name
					);
				$passwordConfirm->set_rule(
						array(
							'required' => array(
								'error', parent::lang('class.MainView#user#rule#required'),
							),
							'compare' => array(
								'password', 'error', parent::lang('class.MainView#user#rule#checkPasswd'),
								
							),
						)
					);
				
				// submit-button
				$form->add(
						'submit',		// type
						'buttonSubmit',	// id/name
						parent::lang('class.MainView#user#passwd#submitButton')	// value
					);
				
				
				// validate
				if($form->validate()) {
					
					// get db-object
					$db = Db::newDb();
					
					// prepare sql-statement
					$sql = 'UPDATE user
							SET password=\''.md5($db->real_escape_string($this->post('password'))).'\'
							WHERE id=\''.$db->real_escape_string($this->getUser()->get_id()).'\'';
					
					// execute statement
					$result = $db->query($sql);
					
					// get data
					if(!$result) {
						$errno = self::getError()->error_raised('MysqlError', $db->error);
						self::getError()->handle_error($errno);
					}
					
					// smarty message
					$sUserPasswd->assign('message', parent::lang('class.MainView#user#validate#passwdChanged'));
				} else {
					
					// smarty form and return
					$sUserPasswd->assign('form', $form->render('', true));
				}
				return $sUserPasswd->fetch('smarty.user.passwd.tpl');
			} else {
				return 'default content';
			}
		} else {
			
			// not authorized
			$errno = $this->getError()->error_raised('NotAuthorized','entry:'.$this->get('id'),$this->get('id'));
			$this->getError()->handle_error($errno);
			return $this->getError()->to_html($errno);
		}
	}
}



?>
