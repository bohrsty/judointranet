<?php


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
							'id' => crc32('MainView'), // 2349400854
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
						'id' => crc32('MainView|logout'), // 447709445
						'show' => true
					),
					array(
						'getid' => 'user', 
						'name' => 'class.PageView#navi#secondlevel#user',
						'id' => crc32('MainView|user'), // 858116738
						'show' => false
					)
				);
		} else {
			
			// add login
			array_unshift($navi['secondlevel'],
					array(
						'getid' => 'login', 
						'name' => 'class.PageView#navi#secondlevel#login',
						'id' => crc32('MainView|login'), // 2785044012
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
						
						// set contents
						// title
						$this->add_output(array('title' => $this->title(parent::lang('class.MainView#init#login#title'))));
						// main-content
						$this->add_output(array('main' => $this->login()));
						// navi
						$this->add_output(array('navi' => $this->navi(basename($_SERVER['SCRIPT_FILENAME']))));
						// jquery
						$this->add_output(array('jquery' => $this->get_jquery()));
						
					break;
					
					case 'logout':
						
						// set contents
						// title
						$this->add_output(array('title' => $this->title(parent::lang('class.MainView#init#logout#title'))));
						// main-content
						$this->add_output(array('main' => $_SESSION['user']->logout()));
						$this->put_userinfo();
						// navi
						$this->add_output(array('navi' => $this->navi(basename($_SERVER['SCRIPT_FILENAME']))));
						// jquery
						$this->add_output(array('jquery' => $this->get_jquery()));
						
					break;
					
					case 'user':
						
						// set contents
						// title
						$this->add_output(array('title' => $this->title(parent::lang('class.MainView#init#user#title'))));
						// main-content
						$this->add_output(array('main' => $this->user()));
						// navi
						$this->add_output(array('navi' => $this->navi(basename($_SERVER['SCRIPT_FILENAME']))));
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
				$this->add_output(array('title' => $this->title(parent::lang('class.MainView#init#Error#NotAuthorized'))));
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
			$this->add_output(array('title' => $this->title(parent::lang('class.MainView#init#default#title')))); 
			// default-content
			$this->add_output(array('main' => '<h2>default content</h2>'));
			// navi
			$this->add_output(array('navi' => $this->navi(basename($_SERVER['SCRIPT_FILENAME']))));
			// jquery
			$this->add_output(array('jquery' => $this->get_jquery()));
		}
		
		// add head
		$this->add_output(array('head' => $this->get_head()));
	}
	
	
	
	
	
	
	
	/**
	 * login checks username and password and set the loginstatus appropriate,
	 * returns login-form or loggedin-message
	 * 
	 * @return string the html-string of login-form or message
	 */
	private function login() {
		
		// get templates
		try {
			$login_message = new HtmlTemplate('templates/div.login.tpl');
		} catch(Exception $e) {
			$GLOBALS['Error']->handle_error($e);
		}
		
		// decode uri
		$uri = 'index.php';
		$r = '';
		if($this->get('r') !== false) {
			$uri = base64_decode($this->get('r'));
			$r = '&r='.$this->get('r');
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
		
		// prepare message array
		$contents = array(
						'p.caption' => parent::lang('class.MainView#login#message#caption'),
						'p.message' => '',
						'p.form' => ''
					);
		
		// validate
		if($form->validate()) {
			
			// login and redirect
			$_SESSION['user']->change_user($username->getValue(),true);
			header('Location:'.$uri);
			exit();
		} else {
			
			// set message and form
			$contents['p.message'] = parent::lang($_SESSION['user']->get_login_message());
			$contents['p.form'] = $form->render($renderer);
		}
		
		
		// return
		return $login_message->parse($contents);
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
		
		// read templates
		try {
			$hx = new HtmlTemplate('templates/hx.tpl');
		} catch(Exception $e) {
			$GLOBALS['Error']->handle_error($e);
		}
		
		// prepare return
		$return = '';
		
		// check login
		if($_SESSION['user']->get_loggedin()) {
		
			// set caption
			$return .= $hx->parse(array(
					'hx.x' => 2,
					'hx.params' => '',
					'hx.content' => parent::lang('class.MainView#user#caption#general').' '.$_SESSION['user']->get_userinfo('name')
				));
				
			// check action
			if($this->get('action') == 'passwd') {
				
				// set caption
				$return .= $hx->parse(array(
						'hx.x' => 3,
						'hx.params' => '',
						'hx.content' => parent::lang('class.MainView#user#caption#passwd')
					));
				
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
					
					// set message
					$return .= $this->p('',parent::lang('class.MainView#user#validate#passwdChanged'));
				} else {
					$return .= $form->render($renderer);
				}
			} else {
				$return .= 'default content';
			}
		} else {
			
			// not authorized
			$errno = $GLOBALS['Error']->error_raised('NotAuthorized','entry:'.$this->get('id'),$this->get('id'));
			$GLOBALS['Error']->handle_error($errno);
			$return = $GLOBALS['Error']->to_html($errno);
		}
		
		// return
		return $return;
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
