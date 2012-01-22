<?php


/**
 * class User implements the properties of a user
 */
class User extends Object {
	
	/*
	 * class-variables
	 */
	private $id;
	private $groups;
	private $loggedin;
	private $lang;
	private $login_message;
	private $userinfo;
	
	/*
	 * getter/setter
	 */
	private function get_id(){
		return $this->id;
	}
	private function set_id($id) {
		$this->id = $id;
	}
	private function get_groups(){
		return $this->groups;
	}
	private function set_groups($groups) {
		$this->groups = $groups;
	}
	private function get_loggedin(){
		return $this->loggedin;
	}
	private function set_loggedin($loggedin) {
		$this->loggedin = $loggedin;
	}
	private function get_lang(){
		return $this->lang;
	}
	private function set_lang($lang) {
		$this->lang = $lang;
	}
	private function get_login_message(){
		return $this->login_message;
	}
	private function set_login_message($login_message) {
		$this->login_message = $login_message;
	}
	private function get_userinfo(){
		return $this->userinfo;
	}
	private function set_userinfo($userinfo) {
		$this->userinfo = $userinfo;
	}
	
	/*
	 * constructor/destructor
	 */
	public function __construct() {
		
		// set userid 0 per default
		$this->set_id(0);
		
		// set loginstatus
		$this->set_loggedin(false);
		
		// read groups
		$this->set_groups($this->read_groups());
		
		// set lang
		$this->set_lang('de_DE');
		
		// set login_message
		$this->set_login_message('class.User#login#message#default');
	}
	
	/*
	 * methods
	 */
	/**
	 * userid returns the id of this user-object
	 * 
	 * @return int id of the user
	 */
	public function userid() {
		
		// return id
		return $this->get_id();
	}
	
	
	
	
	
	
	
	/**
	 * groups returns an array of group-ids that is this user member of
	 * 
	 * @return array array containing group-ids this user is member of
	 */
	public function groups() {
		
		// return groups
		return $this->get_groups();
	}
	
	
	
	
	
	
	
	/**
	 * read_groups reads the group-memberships from db and returns their ids
	 * as an array
	 * 
	 * @return array array containing group-ids this user is member of
	 */
	private function read_groups() {
		
		// prepare return
		$groups = array(0);
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql-statement
		$sql = "SELECT ug.group_id
				FROM user2group AS ug
				WHERE ug.user_id = '".$this->get_id()."'";
		
		// execute statement
		$result = $db->query($sql);
		
		// if no result, only public access
		if($result->num_rows != 0) {
		
			// fetch result
			while(list($group) = $result->fetch_array(MYSQL_NUM)) {
				$groups[] = $group;
			}
		}
		
		// return array
		return $groups;
	}
	
	
	
	
	
	
	
	/**
	 * change_lang sets the language of this user
	 * 
	 * @param string $lang language-representation (i.e. de_DE)
	 */
	public function change_lang($lang) {
		
		// set language
		$this->set_lang($lang);
	}
	
	
	
	
	
	
	
	/**
	 * return_lang sets the language of this user
	 * 
	 * @return sting string representing the actual language of this user
	 */
	public function return_lang() {
		
		// retur  language
		return $this->get_lang();
	}
	
	
	
	
	
	
	
	/**
	 * loggedin returns the loginstatus of the user
	 * 
	 * @return bool returns true if loggedin, false if not
	 */
	public function loggedin() {
		
		// return status
		return $this->get_loggedin();
	}
	
	
	
	
	
	
	
	/**
	 * logout logs the user out and sets all properties back to public access,
	 * returns logout-message
	 * 
	 * @return string html-string of logout-message
	 */
	public function logout() {
		
		// get template
		try {
			$logout_message = new HtmlTemplate('templates/div.logout.tpl');
		} catch(Exception $e) {
			$GLOBALS['Error']->handle_error($e);
		}
		
		// set user-properties to public access
		$this->set_id(0);
		$this->set_groups(array(0));
		$this->set_loggedin(false);
		$this->set_login_message('class.User#login#message#default');
		$this->set_userinfo(array());
		
		// cleanup session
		foreach($_SESSION as $name => $session) {
			
			// check if $_SESSION['user']
			if($name != 'user') {
				unset($_SESSION[$name]);
			}
		}
		
		// logout-message
		// set contents
		$contents = array(
						'p.caption' => parent::lang('class.User#logout#logout#caption'),
						'p.message' => parent::lang('class.User#logout#logout#message')
					);
		
		// return html
		return $logout_message->parse($contents);
	}
	
	
	
	
	
	
	
	/**
	 * check_login checks username and password against db
	 * 
	 * @param string $username the given username
	 * @return mixed false if login failed, userid of loggedin user if successful
	 */
	public function check_login($username) {
		
		// prepare return
		$return = '';
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql-statement
		$sql = "SELECT u.password,u.active
				FROM user AS u
				WHERE u.username = '$username'";
		
		// execute statement
		$result = $db->query($sql);
		
		// get result
		if($result->num_rows > 0) {
			return $result->fetch_array(MYSQL_ASSOC);
		} else {
			return false;
		}
	}
	
	
	
	
	
	
	
	/**
	 * change_user sets the information of the given userid from db
	 * 
	 * @param int $username username of the user
	 * @param bool $loggedin new loginstatus of the user
	 * @return void
	 */
	public function change_user($username,$loggedin) {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql-statement
		$sql = "SELECT u.id,u.name
				FROM user AS u
				WHERE u.username = '$username'";
		
		// execute statement
		$result = $db->query($sql);
		
		// set id and infos
		$db_result = $result->fetch_array(MYSQL_ASSOC);
		$this->set_id($db_result['id']);
		unset($db_result['id']);
		$this->set_userinfo($db_result);
		
		// set groups
		$this->set_groups($this->read_groups());
		
		// set loginstatus
		$this->set_loggedin($loggedin);
		
	}
	
	
	
	
	
	
	
	/**
	 * put_login_message sets the login message from external
	 * 
	 * @param string $login_message string to set as loginmessage
	 * @return void
	 */
	public function put_login_message($login_message) {
		$this->set_login_message($login_message);
	}
	
	
	
	
	
	
	
	/**
	 * return_login_message returns the login message to external
	 * 
	 * @return string actual loginmessage as string
	 */
	public function return_login_message() {
		return $this->get_login_message();
	}
	
	
	
	
	
	
	
	/**
	 * return_userinfo returns the asked info from $userinfo
	 * 
	 * @param string $info name of the info to be returned
	 * @return string asked userinfo, if exists, false otherwise
	 */
	public function return_userinfo($name) {
		
		// check if id
		if($name == 'id') {
			return $this->get_id();
		} else {
			
			// get info
			$info = $this->get_userinfo();
			// check if index exists
			if(isset($info[$name])) {
				return $info[$name];
			} else{
				return false;
			}
		}
	}
	
	
	
	
	
	
	
	/**
	 * return_all_groups returns an array of group-ids and their names
	 * 
	 * @return array array containing all group-ids and names
	 */
	public static function return_all_groups() {
		
		// prepare return
		$groups = array(0 => parent::lang('class.User#return_all_groups#rights#public.access'));
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql-statement
		$sql = "SELECT `g`.`id`,`g`.`name`
				FROM `group` AS g";
		
		// execute statement
		$result = $db->query($sql);
		
		// fetch result
		while(list($id,$name) = $result->fetch_array(MYSQL_NUM)) {
			
			// add to array
			$groups[$id] = $name;
		}
 			
		// sort
		sort($groups,SORT_LOCALE_STRING);
		
		// return
		return $groups;
	}
}



?>
