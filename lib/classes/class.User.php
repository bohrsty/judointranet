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
	public function get_userinfo($name){
		
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
		
		// set userid default to 0
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
		$groups = array(0 => 0);
		
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
				$groups[] = (int) $group;
			}
			
			// free result
			$result->close();
			
			// get membergroups
			$sql = "SELECT gg.g_id,gg.member_id
					FROM group2group AS gg";
			
			// execute
			$result = $db->query($sql);
			
			// fetch result
			$rec_groups = $groups;
			$members = array();
			while(list($g_id,$member_id) = $result->fetch_array(MYSQL_NUM)) {
				$members[$g_id][] = $member_id;
			}
			
			// find members
			for($i=1;$i<count($groups);$i++) {
				$this->list_groups_rec($rec_groups,$members,$groups[$i]);
			}
			
			// merge results
			$groups = array_merge($groups,$rec_groups);
			// unique array
			$groups = array_values(array_unique($groups,SORT_NUMERIC));
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
		
		// read config again
		$_SESSION['GC'] = new Config();
		
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
		$this->set_groups($this->read_groups());
		
		// set loginstatus
		$this->set_loggedin($loggedin);
		
	}
	
	
	
	
	
	
	
	/**
	 * return_all_groups returns an array of the users group-ids and their names
	 * 
	 * @param string $param returns all groups if admin, sortable if sort
	 * @return array array containing all group-ids and names
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
	 * list_groups_rec sets the membergroups for the given $group using $members
	 * into $groups using recursion
	 * 
	 * @param array $groups referenced array to add the membergroups
	 * @param array $members array containing all memberships
	 * @param int $group group to find membergroups
	 */
	private function list_groups_rec(&$groups,$members,$group) {
		
		// find $group in $members and recurse
		if(isset($members[$group])) {
			for($i=0;$i<count($members[$group]);$i++) {
				$this->list_groups_rec($groups,$members,$members[$group][$i]);				
				$groups[] = $members[$group][$i];
			}
		} else {
			$groups[] = $group;
		}
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
}



?>
