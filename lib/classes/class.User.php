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
	public function get_id(){
		return $this->id;
	}
	public function set_id($id) {
		$this->id = $id;
	}
	public function get_groups(){
		return $this->groups;
	}
	public function set_groups($groups) {
		$this->groups = $groups;
	}
	public function get_loggedin(){
		return $this->loggedin;
	}
	public function set_loggedin($loggedin) {
		$this->loggedin = $loggedin;
	}
	public function get_lang(){
		return $this->lang;
	}
	public function set_lang($lang) {
		$this->lang = $lang;
	}
	public function get_login_message(){
		return $this->login_message;
	}
	public function set_login_message($login_message) {
		$this->login_message = $login_message;
	}
	public function get_userinfo($name=''){
		
		// check name
		if($name == '') {
			return $this->userinfo;
		} else {
			// check if id
			if($name == 'id') {
				return $this->get_id();
			} else {
				
				// get info
				$info = $this->userinfo;
				// check if index exists
				if(isset($info[$name])) {
					return $info[$name];
				} else{
					return false;
				}
			}
		}
	}
	public function set_userinfo($userinfo) {
		$this->userinfo = $userinfo;
	}
	
	/*
	 * constructor/destructor
	 */
	public function __construct() {
		
		// set error
		$GLOBALS['error'] = new Error();
		
		// initalize $_SESSION
		$_SESSION['user'] = null;
		
		// set userid default to 0
		$this->set_id(0);
		
		// set loginstatus
		$this->set_loggedin(false);
		
		// read groups
		$this->set_groups($this->dbReadGroups());
		
		// set lang
		$this->set_lang('de_DE');
		
		// set login_message
		$this->set_login_message('class.User#login#message#default');
		
		// set userinfo
		$userinfo = array(
				'name' => 'Public',
				'username' => 'public',
			);
		$this->set_userinfo($userinfo);
	}
	
	/*
	 * methods
	 */
	/**
	 * userid returns the id of this user-object
	 * 
	 * @return int id of the user
	 * @deprecated - 23.09.2013
	 */
	public function userid() {
		
		// return id
		return $this->get_id();
	}
	
	
	
	
	
	
	
	/**
	 * groups returns an array of group-ids that is this user member of
	 * 
	 * @return array array containing group-ids this user is member of
	 * @deprecated - 23.09.2013
	 */
	public function groups() {
		
		// return groups
		return $this->get_groups();
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
	 * logout logs the user out and sets all properties back to public access,
	 * returns logout-message
	 * 
	 * @return string html-string of logout-message
	 */
	public function logout() {
		
		// smarty-template
		$sLogout = new JudoIntranetSmarty();
		
		// set user-properties to public access
		$this->set_id(0);
		$this->set_groups(array());
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
		
		// read config again
		$this->setGc(new Config());
		
		// logout-message
		// smarty
		$sLogout->assign('caption', parent::lang('class.User#logout#logout#caption'));
		$sLogout->assign('message', parent::lang('class.User#logout#logout#message'));
		$sLogout->assign('form', '');
		
//		// return
		// smarty
		return $sLogout->fetch('smarty.login.tpl');
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
	 * @param mixed $value value for $field
	 * @param bool $loggedin new loginstatus of the user
	 * @param string $field database field to change user to $value
	 * @return void
	 */
	public function change_user($value,$loggedin,$field = 'username') {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql-statement
		$sql = "SELECT u.id,u.name,u.username
				FROM user AS u
				WHERE u.$field = '$value'";
		
		// execute statement
		$result = $db->query($sql);
		
		// set id and infos
		$db_result = $result->fetch_array(MYSQL_ASSOC);
		$this->set_id($db_result['id']);
		unset($db_result['id']);
		$this->set_userinfo($db_result);
		
		// set groups
		$this->set_groups($this->dbReadGroups());
		
		// set loginstatus
		$this->set_loggedin($loggedin);
		
	}
	
	
	
	
	
	
	
	/**
	 * return_all_groups returns an array of the users group-ids and their names
	 * 
	 * @param string $param returns all groups if admin, sortable if sort
	 * @return array array containing all group-ids and names
	 * @deprecated - 25.09.2013
	 */
	public function return_all_groups($param='') {
		
		// prepare return
		if($param != 'sort') {
			$groups = array(0 => parent::lang('class.User#return_all_groups#rights#public.access'));
		} else {
			$groups = array();
		}
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql-statement
		$sql = "SELECT `g`.`id`,`g`.`name`,`g`.`sortable`
				FROM `group` AS g";
		
		// execute statement
		$result = $db->query($sql);
		
		// fetch result	
		while(list($g_id,$name,$sortable) = $result->fetch_array(MYSQL_NUM)) {
			
			// check if sortable
			if($param == 'sort') {
				
				// add only sortable
				if($sortable == 1) {
					$groups[$g_id] = $name;
				}
				
				// set return
				$return = $groups;
			} else {
				$groups[$g_id] = $name;
			}
		}
		
		// sort
		asort($groups,SORT_LOCALE_STRING);
		
 		// check admin
 		if($param == 'admin') {
 			
 			// return all groups
 			$return = $groups;
 		} elseif($param != 'sort') {
 			
 			// return own groups
 			// get own group-ids
 			$mygroups = $this->get_groups();
			
 			// walk through $mygroups
 			$owngroups = array();
 			foreach($mygroups as $group) {
 				$owngroups[$group] = $groups[$group];
 			}
 			
 			// sort
 			asort($owngroups,SORT_LOCALE_STRING);
 			
			$return = $owngroups; 			
 		}
		
		// return
		return $return;
	}
	
	
	
	
	
	
	
	/**
	 * return_all_users returns all users from db as array containing
	 * user-objects
	 * 
	 * @param array $exclude array containing usernames not to include in list
	 * @return array array containing all user-objects
	 */
	public function return_all_users($exclude = array()) {
		
		// prepare return
		$users = array();
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql-statement
		$sql = "SELECT u.username
				FROM user AS u";
		
		// execute statement
		$result = $db->query($sql);
		
		//fetch result
		while(list($username) = $result->fetch_array(MYSQL_NUM)) {
			
			// safe object in array
			$user = new User();
			$user->change_user($username,false);
			
			// exclude
			if(!in_array($username,$exclude)) {
			
				$users[] = $user;
			}
		}
		
		// return
		return $users;
	}
	
	
	/**
	 * allGroups() returns an array containing all groups this user is member from
	 * with the id as key
	 * 
	 * @return array array containing all groups this user is member from
	 */
	public function allGroups() {
		
		// walk through groups
		$allGroups = array(Group::fakePublic());
		// check size of array (= no groups)
		if(count($this->get_groups()) > 0) {
			foreach($this->get_groups() as $group) {
				$allGroups += $group->allGroups();
			}
		}
		
		// return
		return $allGroups;
	}
	
	
	/**
	 * dbReadGroups() reads the group-memberships from db and returns the corresponding
	 * group objects as an array
	 * 
	 * @return array array containing group objects this user is member of
	 */
	private function dbReadGroups() {
		
		// get db object
		$db = Db::newDb();
		
		// prepare sql statement to get group membership
		$sql = 'SELECT group_id
				FROM user2groups
				WHERE user_id=\''.$db->real_escape_string($this->get_id()).'\'';
		
		// execute statement
		$result = $db->query($sql);
		
		// get data
		$groups = array();
		if($result) {
			while(list($id) = $result->fetch_array(MYSQL_NUM)) {
				$groups[] = new Group($id);
			}
		} else {
			$errno = $this->getError()->error_raised('MysqlError', $db->error);
			$this->getError()->handle_error($errno);
		}
		
		// return
		return $groups;
	}
}



?>
