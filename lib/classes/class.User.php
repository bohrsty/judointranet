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
	private $used;
	
	/*
	 * getter/setter
	 */
	public function get_id(){
		return $this->id;
	}
	public function set_id($id) {
		$this->id = $id;
	}
	public function getId(){
		return $this->id;
	}
	public function setId($id) {
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
	public function set_userinfo($userinfo, $value='') {
		
		// check if $userinfo is array
		if(is_array($userinfo)) {
			
			// check if length of array match actual userinfo
			if(count($this->userinfo) == count($userinfo)) {
				
				// override complete userinfo
				$this->userinfo = $userinfo;
			} else {
				
				// update changed values
				$actualUserinfo = $this->userinfo;
				foreach($userinfo as $infoName => $infoValue) {
					$actualUserinfo[$infoName] = $infoValue;
				}
				// write updated userinfo
				$this->userinfo = $actualUserinfo;
			}
		} else {
			
			// get actual userinfo
			$actualUserinfo = $this->userinfo;
			
			// override single row
			$actualUserinfo[$userinfo] = $value;
			$this->userinfo = $actualUserinfo;
		}
	}
	public function getUsed(){
		return $this->used;
	}
	public function setUsed($used) {
		$this->used = $used;
	}
	
	/*
	 * constructor/destructor
	 */
	public function __construct($globalUser = true) {
		
		// check if to create local user
		if($globalUser === true) {
			
			// initalize $_SESSION
			$_SESSION['user'] = null;
		}
		
		// set userid default to 0
		$this->set_id(0);
		
		// set loginstatus
		$this->set_loggedin(false);
		
		// set lang
		$this->set_lang('de_DE');
		
		// set login_message
		$this->set_login_message('please log on');
		
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
	 * @param object $view the main view to act directly on templates etc.
	 * @return string html-string of logout-message
	 */
	public function logout(&$view) {
		
		// smarty-template
		$sLogout = new JudoIntranetSmarty();
		
		// prepare public userinfo
		$userinfo = array(
				'name' => 'Public',
				'username' => 'public',
				'password' => '',
				'email' => '',
				'active' => 1,
				'last_modified' => '',
			);
		
		// set user-properties to public access
		$this->set_id(0);
		$this->set_groups(array());
		$this->set_loggedin(false);
		$this->set_login_message('please log on');
		$this->set_userinfo($userinfo);
		
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
		$sLogout->assign('caption', _l('logout'));
		$sLogout->assign('message', _l('successfully logged off'));
		$sLogout->assign('form', '');
		
		// set redirect
		$view->jsRedirectTimeout('index.php?id=login');
		
		// return
		return $sLogout->fetch('smarty.login.tpl');
	}
	
	
	
	
	
	
	
	/**
	 * checkLogin($username, $password) checks username and password against db
	 * 
	 * @param string $username the given username
	 * @param string $password the password to check
	 * @return bool false if login failed (reason in $this->login_message), true if successful
	 */
	public function checkLogin($username, $password) {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql-statement
		$sql = 'SELECT u.password,u.active
				FROM user AS u
				WHERE u.username = \''.$db->real_escape_string($username).'\'';
		
		// execute statement
		$result = $db->query($sql);
		
		// get data
		$user = array();
		if($result) {
			if($result->num_rows > 0) {
				$user = $result->fetch_array(MYSQLI_ASSOC);
			}
		} else {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// check if user exists
		if(count($user) !== 0) {
			
			// check active and password
			if($user['active'] == 0) {
				
				// set message and return false
				$this->set_login_message('user not active');
				return false;
			} elseif($user['password'] != password_hash(md5($password), PASSWORD_BCRYPT)) {
				
				// set message and return false
				$this->set_login_message('wrong password');
				return false;
			} else {
				
				// username and password correct, return true
				return true;
			}
		} else {
			
			// set message and return false
			$this->set_login_message('username not exists');
			return false;
		}
	}
	
	
	
	
	
	
	
	/**
	 * change_user($value, $loggedin, $field) sets the information of the given userid from db
	 * 
	 * @param mixed $value value for $field
	 * @param bool $loggedin new loginstatus of the user
	 * @param string $field database field to change user to $value
	 * @return void
	 */
	public function change_user($value, $loggedin, $field = 'username') {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql-statement
		$sql = 'SELECT *
				FROM user AS u
				WHERE u.'.$db->real_escape_string($field).' = \''.$db->real_escape_string($value).'\'';
		
		// execute statement
		$result = $db->query($sql);
		
		// check result
		if($result) {
			$db_result = $result->fetch_array(MYSQLI_ASSOC);
		} else {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// set id and infos
		$this->set_id($db_result['id']);
		unset($db_result['id']);
		$this->set_userinfo($db_result);
		
		// set groups
		$this->set_groups($this->dbReadGroups());
		
		// set loginstatus
		$this->set_loggedin($loggedin);
		
		
		$this->setUsed(User::isUsed($this->get_id()));
		
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
			$groups = array(0 => _l('any (public access)'));
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
		while(list($g_id,$name,$sortable) = $result->fetch_array(MYSQLI_NUM)) {
			
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
		while(list($username) = $result->fetch_array(MYSQLI_NUM)) {
			
			// safe object in array
			$user = new User(false);
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
		$allGroups[0] = Group::fakePublic();
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
			while(list($id) = $result->fetch_array(MYSQLI_NUM)) {
				$groups[] = new Group($id);
			}
		} else {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// return
		return $groups;
	}
	
	
	/**
	 * hasPermission($itemTable, $ItemId, $mode='r') returns true if the user has permission
	 * (given in $mode [r=read, w=edit]), false otherwise
	 * 
	 * @param string $itemTable table of the item to be checked
	 * @param int $itemId id of the item in the $table
	 * @param string $mode which permission the user needs (r=read, w=edit)
	 * @return boolean true if the user has permission, false otherwise
	 */
	public function hasPermission($itemTable, $itemId, $mode='r') {
		
		// get own groups
		$ownGroups = $this->allGroups();
		
		// admin has allways permission
		if(isset($ownGroups[1])) {
			return true;
		}
		
		// get db object
		$db = Db::newDb();
		
		// get group ids
		$groupIds = implode(',', array_keys($ownGroups));
		
		// prepare mode
		$sqlMode = '(`mode`=\'r\' OR `mode`=\'w\')';
		if($mode == 'w') {
			$sqlMode = '`mode`=\'w\'';
		}
		
		// prepare sql statement to get permission of the given entry
		$sql = 'SELECT *
				FROM permissions
				WHERE item_table=\''.$db->real_escape_string($itemTable).'\'
					AND item_id=\''.$db->real_escape_string($itemId).'\'
					AND (user_id=\''.$db->real_escape_string($this->get_id()).'\'
					OR (group_id IN ('.$db->real_escape_string($groupIds).')))
					AND '.$sqlMode;
		
		// execute statement
		$result = $db->query($sql);
		
		// close db
		$db->close();
		
		// get data
		$items = array();
		if($result) {
			return $result->num_rows > 0;
		} else {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// default return false
		return false;
	}
	
	
	/**
	 * permittedItems($itemTable, $mode='r') returns an array containing the
	 * ids of the items the user has permission to
	 * 
	 * @param string $itemTable table of the item to be checked
	 * @param string $mode which permission the user needs (r=read, w=edit)
	 * @param string $dateFrom if filtered by date the "from" date
	 * @param string $dateTo if filtered by date the "to" date
	 * @return array array containing the item ids the user has permission to
	 */
	public function permittedItems($itemTable, $mode, $dateFrom=null, $dateTo=null) {
		
		// get own groups
		$ownGroups = $this->allGroups();
		
		// get group ids
		$groupIds = implode(',', array_keys($ownGroups));
		
		// prepare return
		$itemIds = array();
		
		// prepare sql statement to get permission of the given entry
		// prepare mode
		$sqlMode = 'AND `p`.`mode`=\'r\'';
		if($mode == 'w') {
			$sqlMode = 'AND (`p`.`mode`=\'r\' OR `p`.`mode`=\'w\')';
		}
		// prepare date
		$sqlDate = '';
		if(!is_null($dateFrom) && !is_null($dateTo)) {
			$sqlDate = (isset($ownGroups[1]) ? ' WHERE' : ' AND').' (`t`.`date` BETWEEN \''.$dateFrom.'\' AND \''.$dateTo.'\' OR `t`.`end_date` BETWEEN \''.$dateFrom.'\' AND  \''.$dateTo.'\' OR \''.$dateFrom.'\' BETWEEN `t`.`date` AND `t`.`end_date` OR \''.$dateTo.'\' BETWEEN `t`.`date` AND `t`.`end_date`)';
		}
		// admin is permitted to anything
		if(isset($ownGroups[1])) {
			
			$sql = 'SELECT t.id
				FROM `'.$itemTable.'` AS t'.$sqlDate;
			
			$adminResult = Db::arrayValue($sql, MYSQLI_ASSOC);
			
			// get data
			if(is_array($adminResult)) {
				foreach($adminResult as $id) {
					$itemIds[] = $id['id'];
				}
			} else {
				$n = null;
				throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
			}
		} else {
			
			// switch by $itemTable
			if($itemTable == 'file') {
				
				// calendar
				$sql = '
						SELECT DISTINCT `f`.`id`
						FROM `file` AS `f`,
							(SELECT DISTINCT `t`.`id`
							FROM `permissions` AS `p`, `calendar` AS `t`
							WHERE `p`.`item_id`=`t`.`id`
								AND `p`.`item_table`=\'calendar\'
								AND (`p`.`user_id`=\'#?\'
								OR (`p`.`group_id` IN (#?)))
								'.$sqlMode.'
							) `cid`
						WHERE `f`.`cached`=CONCAT(\'calendar|\',`cid`.`id`)
					';
				$cachedCalendarResult = Db::arrayValue(
					$sql,
					MYSQLI_ASSOC,
					array(
							$this->get_id(),
							$groupIds,
						)
				);
				if($cachedCalendarResult === false) {
					$n = null;
					throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
				}
				// protocol
				$sql = '
						SELECT DISTINCT `f`.`id`
						FROM `file` AS f,
							(SELECT DISTINCT `t`.`id`
							FROM `permissions` AS p, `protocol` AS t
							WHERE `p`.`item_id`=`t`.`id`
								AND `p`.`item_table`=\'protocol\'
								AND (`p`.`user_id`=\'#?\'
								OR (`p`.`group_id` IN (#?)))
								'.$sqlMode.'
							) pid
						WHERE `f`.`cached`=CONCAT(\'protocol|\',`pid`.`id`)
					';
				$cachedProtocolResult = Db::arrayValue(
					$sql,
					MYSQLI_ASSOC,
					array(
							$this->get_id(),
							$groupIds,
						)
				);
				
				// get data
				if(is_array($cachedCalendarResult)) {
					foreach($cachedCalendarResult as $id) {
						$itemIds[] = $id['id'];
					}
				} else {
					$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
				}
				if(is_array($cachedProtocolResult)) {
					foreach($cachedProtocolResult as $id) {
						$itemIds[] = $id['id'];
					}
				} else {
					$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
				}
			} elseif($itemTable == 'result') {
				
				$sql = '
						SELECT DISTINCT `r`.`id`
						FROM `result` AS `r`,
							(SELECT DISTINCT `t`.`id`
							FROM `permissions` AS `p`, `calendar` AS `t`
							WHERE `p`.`item_id`=`t`.`id`
								AND `p`.`item_table`=\'calendar\'
								AND (`p`.`user_id`=\'#?\'
								OR (`p`.`group_id` IN (#?)))
								'.$sqlMode.'
							) `cid`
						WHERE `r`.`calendar_id`=`cid`.`id`
					';
				$resultResult = Db::arrayValue(
					$sql,
					MYSQLI_ASSOC,
					array(
							$this->get_id(),
							$groupIds,
						)
				);
				
				// get data
				if(is_array($resultResult)) {
					foreach($resultResult as $id) {
						$itemIds[] = $id['id'];
					}
				} else {
					$n = null;
					throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
				}
			}
			
			// uploaded files and other objects
			$sql = '
				SELECT DISTINCT `t`.`id`
				FROM `permissions` AS p, `#?` AS t
				WHERE `p`.`item_id`=`t`.`id`
					AND `p`.`item_table`=\'#?\'
					AND (`p`.`user_id`=\'#?\'
					OR (`p`.`group_id` IN (#?)))
					'.$sqlMode.$sqlDate;
			$itemResult = Db::arrayValue(
				$sql,
				MYSQLI_ASSOC,
				array(
						$itemTable,
						$itemTable,
						$this->get_id(),
						$groupIds,
					)
			);
			
			// get data
			if(is_array($itemResult)) {
				foreach($itemResult as $id) {
					if(!in_array($id['id'], $itemIds)) {
						$itemIds[] = $id['id'];
					}
				}
			} else {
				$n = null;
				throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
			}
		}
		
		// return
		return $itemIds;
	}
	
	
	/**
	 * isMemberOf($groupId) returns true if the user is member of the given $groupId
	 * false otherwise
	 * 
	 * @param int $groupId the id of the group that membership is tested
	 * @return bool true if the user is member of $groupId, false otherwise
	 */
	public function isMemberOf($groupId) {
		
		// get own groups
		$ownGroups = $this->get_groups();
		
		// walk through own groups
		foreach($ownGroups as $ownGroup) {
			
			// get group incl. subgroups as array
			$allGroups = $ownGroup->allGroups();
			
			// return true if id is in array
			if(isset($allGroups[$groupId])) {
				return true;
			}
		}
		
		// return false if not in any array
		return false;
	}
	
	
	/**
	 * isAdmin() returns true if the user is member of admin group, false otherwise
	 * 
	 * @return bool true if the user is member of admin group, false otherwise
	 */
	public function isAdmin() {
		
		// check membership
		return $this->isMemberOf(1);
	}
	
	
	/**
	 * writeDb($mode) writes the actual data of the user back to the database
	 * 
	 * @param int $mode indicates new or update
	 * @return void
	 */
	public function writeDb($mode = DB_WRITE_UPDATE) {
		
		// check $mode
		if($mode == DB_WRITE_NEW) {
			
			$sql = 'INSERT INTO `user`
						(`id`,`username`,`password`,`name`,`email`,`active`,`last_modified`)
					VALUES
						 (NULL,\'#?\',\'#?\',\'#?\',\'#?\',#?,CURRENT_TIMESTAMP)';
			
			// execute statement
			$result = Db::executeQuery(
				$sql,
				array(
						$this->get_userinfo('username'),
						$this->get_userinfo('password'),
						$this->get_userinfo('name'),
						$this->get_userinfo('email'),
						$this->get_userinfo('active'),
					)
			);
		} elseif($mode == DB_WRITE_UPDATE) {
			
			$sql = 'UPDATE `user`
					SET
						`username`=\'#?\',
						`password`=\'#?\',
						`name`=\'#?\',
						`email`=\'#?\',
						`active`=#?,
						`last_modified`=CURRENT_TIMESTAMP
					WHERE `id`=#?';
			
			// execute statement
			$result = Db::executeQuery(
				$sql,
				array(
						$this->get_userinfo('username'),
						$this->get_userinfo('password'),
						$this->get_userinfo('name'),
						$this->get_userinfo('email'),
						$this->get_userinfo('active'),
						$this->get_id(),
					)
			);
		}
		
		// set new id
		if($this->get_id() == 0) {
			$this->set_id(Db::$insertId);
		}
		
		// get data
		if(!$result) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// remove group membership from db and write new
		$sql = 'DELETE FROM `user2groups`
				WHERE `user_id`=#?';
			
		// execute statement
		$result = Db::executeQuery(
			$sql,
			array(
					$this->get_id(),
				)
		);
		if(!$result) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}

		$groupValues = '';
		foreach($this->get_groups() as $ownGroup) {
			$groupValues .= '('.$this->get_id().','.$ownGroup->getId().',CURRENT_TIMESTAMP),';
		}
		if($groupValues != '') {
			$groupValues = substr($groupValues, 0, -1);
			$sql = 'INSERT INTO `user2groups`
						(`user_id`,`group_id`,`last_modified`)
					VALUES
						'.$groupValues;
				
			// execute statement
			$result = Db::executeQuery(
				$sql
			);
			if(!$result) {
				$n = null;
				throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
			}
		}
		
		// return
		return $this->get_id();
	}
	
	
	/**
	 * exists($uid) checks if a user with the given $uid exists in database
	 * 
	 * @param int $uid the id to be checked for existance
	 * @return bool true if user exists, false otherwise
	 */
	public static function exists($uid) {
		
		// prepare sql
		$sql = '
				SELECT COUNT(*)
				FROM `user`
				WHERE `id`=#?
				';
		
		// get data
		$data = Db::singleValue($sql, array($uid));
		
		if(!is_null($data)) {
			return $data > 0;
		} else {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
	}
	
	
	/**
	 * delete() deletes the user from database
	 * 
	 * @return void
	 */
	public function delete() {
		
		// delete from database
		$sql = '
			DELETE FROM `user`
				WHERE `id`=#?
		';
		
		$result = Db::executeQuery($sql, 
			array($this->get_id(),)
		);
		
		if(!$result) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// remove group membership from db
		$sql = 'DELETE FROM `user2groups`
				WHERE `user_id`=#?';
			
		// execute statement
		$result = Db::executeQuery(
			$sql,
			array($this->get_id(),)
		);
		
		if(!$result) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
	}
	
	/**
	 * isUsed($uid) checks if a user with the given $uid is used in permission
	 * or group table
	 * 
	 * @param int $uid the id of the user to be checked
	 * @return bool true if is used, false otherwise
	 */
	public static function isUsed($uid) {
		
		// get usage
		$sql = '
			SELECT COUNT(*)
			FROM `permissions`
			WHERE `user_id`=#?
		';
		// get data
		$usedPerm = Db::singleValue($sql, array($uid));
		
		if(is_null($usedPerm)) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		$sql = '
			SELECT COUNT(*)
			FROM `user2groups`
			WHERE `user_id`=#?
		';
		// get data
		$usedGroups = Db::singleValue($sql, array($uid));
		
		if(is_null($usedGroups)) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// return
		return $usedPerm + $usedGroups > 0;
	}
}



?>
