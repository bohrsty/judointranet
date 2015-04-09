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
 * class Object is father-class of all other objects
 */
class Object {
	
	/*
	 * class-variables
	 */
	private $get;
	private static $staticGet;
	private $post;
	
	
	/*
	 * getter/setter
	 */
	public function getGc() {
		return $GLOBALS['GC'];
	}
	public static  function staticGetGc() {
		return $GLOBALS['GC'];
	}
	public function setGc($gc) {
		$GLOBALS['GC'] = $gc;
	}
	public function getUser() {
		return $_SESSION['user'];
	}
	public static function staticGetUser() {
		return $_SESSION['user'];
	}
	public function setUser($user=null) {
		
		if(is_null($user)) {
			if(!isset($_SESSION['user'])) {
				// initialize user
				$_SESSION['user'] = false;
				$_SESSION['user'] = new User();
			}
		} else {
			$_SESSION['user'] = $user;
		}
	}
	public function getError() {
		return $GLOBALS['error'];
	}
	public function setError($error) {
		$GLOBALS['error'] = $error;
	}
	public function get_get(){
		return $this->get;
	}
	public static function staticGetGet(){
		return self::$staticGet;
	}
	public function set_get($get) {
		$this->get = $get;
	}
	public static function staticSetGet($get) {
		self::$staticGet = $get;
	}
	public function getPost(){
		return $this->post;
	}
	public function setPost($post) {
		$this->post = $post;
	}
	
	/*
	 * constructor/destructor
	 */
	public function __construct() {
		
		// set config
		$GLOBALS['GC'] = new Config();
		
		// set default-time-zone
		date_default_timezone_set($this->getGc()->get_config('default_time_zone'));
		
		// set locale
		setlocale(LC_ALL, $this->getGc()->get_config('locale'));
		
		// set user
		$this->setUser();
		
		// set error
		$this->setError(new Error());
		
		// read $_GET and $_POST
		$this->readGlobals();
	}
	
	/*
	 * methods
	 */
	/**
	 * check_valid_chars checks an given string by given type using regexp
	 * 
	 * @param string $type type of string to choose regexp
	 * @param string $value string to check
	 * 
	 * @return mixed false if not permitted chars, the value else
	 */
	protected function check_valid_chars($type,$value) {
		
		// regexps
		$regexp = array(
							'apirequest' => '/^[a-zA-Z0-9äöüÄÖÜß\.\-_\+\/=%\?& ]*$/',
	  						'getvalue' => '/^[a-zA-Z0-9äöüÄÖÜß\.\-_\+\/=% ]*$/',
	  						'postvalue' => '/^[a-zA-Z0-9äöüÄÖÜß\.,\-_\+!§\$%&\/()\[\]\{\}=`´;:\*#~\?<>|"@ \n\r\t]*$/',
		);
		
		// check if $value is string
		if(is_string($value) || is_numeric($value)) {
			
			// check if not permitted chars
			if(!preg_match($regexp[$type],$value)) {
		
				// nonconform return false
				return false;
			}
		}
	
		// correct return value
		return $value;
	}
	
	
	
	
	
	
	
	/**
	 * lang($string, $plaintext) reads the translated string for the given marker and returns it
	 * expects $_SESSION['lang'] to be set to lang-code (i.e. "de_DE")
	 * 
	 * @param string $string string to be parsed and "translated", splitmarker "#"
	 * @param bool $plaintext if true, human readable plaintext is used for translation
	 * @return string translated value of the string
	 */
	public static function lang($string, $plaintext=true) {
		
		return _l($string);
	}
	
	
	
	
	
	
	
	/**
	 * callback_check_date checks if a correct date is selected
	 * 
	 * @param array $args arguments to check
	 * @return bool true, if ok, false otherwise
	 */
	public function callback_check_date($args) {
		
		// check values
		if(!preg_match('/^\d\d\d\d-\d\d-\d\d$/',$args)) {
			return false;
		} else {
			
			// get date-parts
			$day = date('d',strtotime($args));
			$month = date('m',strtotime($args));
			$year = date('Y',strtotime($args));
			return checkdate($month,$day,$year);
		}
	}
	
	
	
	
	
	
	
	/**
	 * callback_check_select checks if a value other than 0 is selected
	 * 
	 * @param array $args arguments to check
	 * @return bool true, if ok, false otherwise
	 * @deprecated 13.11.2013
	 */
	public function callback_check_select($args) {
		
		// check values
		if($args == '0') {
			return false;
		}
		return true;
	}
	
	
	/**
	 * callbackCheckSelect($args) checks if a value other than '' is selected
	 * 
	 * @param array $args arguments to check
	 * @return bool true, if $args is not empty, false otherwise
	 */
	public function callbackCheckSelect($args) {
		
		// check values
		if($args == '') {
			return false;
		}
		return true;
	}
	
	
	/**
	 * callback_check_hierselect checks if a value other than 0 is selected
	 * 
	 * @param array $args arguments to check
	 * @return bool true, if ok, false otherwise
	 */
	public function callback_check_hierselect($args) {
		
		// check values
		if($args[0] == '0' || $args[1] == '0') {
			return false;
		}
		return true;
	}
	
	
	/**
	 * callbackCheckHierselect($value, $arg) checks if a value other than '' in post($arg)
	 * is selected, if some value is selected, check if $value is other than ''
	 * 
	 * @param string $value value of the second select element
	 * @param string $arg id of the first select element
	 * @return bool true, if post($arg) is other than '' and $value not '', false otherwise
	 */
	public function callbackCheckHierselect($value, $arg='') {
		
		// check if first is selected
		if($this->post($arg) != '') {
			// if is, second has to have value other than ''
			if($value != '') {
				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}
	}
	
	
	/**
	 * callbackCheckRequired($value, $arg) checks if $value and post($arg) not ''
	 * 
	 * @param string $value value of the textarea element
	 * @param string $arg id of the select element
	 * @return bool true, if $value and post($arg) is not ''
	 */
	public function callbackCheckRequired($value, $arg) {
		
		// check if select id is set
		if($this->post($arg) !== false) {
			// if is, both needs to be other than ''
			if($value != '' && $this->post($arg) != '') {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	
	
	
	
	
	
	/**
	 * replace_umlaute replaces german umlaute in $string
	 * 
	 * @param string $string string containing umlaute to be replaced
	 * @return string string where the umlaute are replaced
	 */
	public function replace_umlaute($string) {
		
		// replacement table
		$table = array(
			'ä' => 'ae',
			'ö' => 'oe',
			'ü' => 'ue',
			'Ä' => 'Ae',
			'Ö' => 'Oe',
			'Ü' => 'Ue',
			'ß' => 'ss',
			' ' => '_',
		);
		
		// replace
		foreach($table as $char => $replacement) {
			$string = str_replace($char,$replacement,$string);
		}
		
		// return
		return $string;
	}
	
	
	/**
	 * readGlobals() checks the $_GET- and $POST-entrys against allowed-values
	 * 
	 * @return void
	 */
	private function readGlobals() {
		
		// walk through $_GET if defined
		$get = null;
		if(isset($_GET)) {
			
			foreach($_GET as $get_entry => $get_value) {
				
				// check the value
				$value = $this->check_valid_chars('getvalue',$get_value);
				if($value === false) {
					throw new GetInvalidCharsException($message = $get_entry);
				} else {
					
					// store value
					$get[$get_entry] = array($get_value,null);
				}
			}
		}
		
		// set class-variables
		$this->set_get($get);
		self::staticSetGet($get);
		
		// walk through $_POST if defined
		$post = null;
		if(isset($_POST)) {
			
			foreach($_POST as $postKey => $postValue) {
				
				// check the value
				$value = $this->check_valid_chars('postvalue',$postValue);
				if($value === false) {
					throw new PostInvalidCharsException($message = $postKey);
				} else {
					
					// store value
					$post[$postKey] = array($postValue,null);
				}
			}
		}
		
		// set class-variables
		$this->setPost($post);
	}
	
	
	/**
	 * get returns the value of $_GET[$var] if set
	 * 
	 * @param string $var text of key in $_GET-array
	 * @return string value of the $_GET-key, or false if not set
	 */
	public function get($var) {
		
		// check if key is set
		$get = $this->get_get();
		if(isset($get[$var])) {
			return $get[$var][0];
		} else {
			return false;
		}
	}
	
	
	/**
	 * statidGet() returns the value of $_GET[$var] if set (for use in static context)
	 * 
	 * @param string $var text of key in $_GET-array
	 * @return string value of the $_GET-key, or false if not set
	 */
	public static function staticGet($var) {
		
		// check if key is set
		$get = self::staticGetGet();
		if(isset($get[$var])) {
			return $get[$var][0];
		} else {
			return false;
		}
	}
	
	
	/**
	 * post() returns the value of $_POST[$var] if set
	 * 
	 * @param string $var text of key in $_POST array
	 * @return string value of the $_POST key, or false if not set
	 */
	public function post($var) {
		
		// check if key is set
		$post = $this->getPost();
		if(isset($post[$var])) {
			return $post[$var][0];
		} else {
			return false;
		}
	}
	
	
	/**
	 * isDemoMode() checks whether the system runs in demo mode or not
	 * 
	 * @return bool true if system in demo mode, false otherwise
	 */
	public function isDemoMode() {
		return $this->getGc()->get_config('global.systemDemo') == 1;
	}
	
	
	/**
	 * callbackSortNavi($first, $second) compares two Navi objects by position (for usort)
	 * 
	 * @param object $first first navi entry
	 * @param object $second second navi entry
	 * @return int -1 if $first<$second, 0 if equal, 1 if $first>$second
	 */
	public function callbackSortNavi($first, $second) {
		
		// compare position
		if($first->getPosition() < $second->getPosition()) {
			return -1;
		}
		if($first->getPosition() == $second->getPosition()) {
			return 0;
		}
		if($first->getPosition() > $second->getPosition()) {
			return 1;
		}
	}
	
	
	/**
	 * callbackSortGroupsAlpha($first, $second) compares two Group objects alphabetically
	 * by name (for usort)
	 * 
	 * @param object $first first group entry
	 * @param object $second second group entry
	 * @return int -1 if $first<$second, 0 if equal, 1 if $first>$second
	 */
	public function callbackSortGroupsAlpha($first, $second) {
		
		// compare name
		return strnatcasecmp($first->getName(), $second->getName());
	}
	
	
	/**
	 * __toString() returns an string representation of this object
	 * 
	 * @return string string representation of this object
	 */
	public function __toString() {
		return get_class($this);
	}
	
	
	/**
	 * getRandomId() generates a random string and returns it
	 * 
	 * @return string generated random string
	 */
	public static function getRandomId() {
		
		return base_convert(mt_rand(10000000, 99999999), 10, 36);
	}
	
	
	/**
	 * getTableConfig($table) retrieves any configuration settings for $table and returns it
	 * as array
	 * 
	 * @param string $table name of the table
	 * @return array array of arrays containing the configuration settings
	 */
	protected function getTableConfig($table) {
		
		// get config from db
		$tableConfigJson = $this->getGc()->get_config('tableConfig.'.$table);
		
		// get array from json if exists
		$tableConfigArray = array();
		if($tableConfigJson !== false) {
			$tableConfigArray = json_decode($tableConfigJson, true);
		} else {
			return false;
		}
		
		// prepare return array, add cols and orderBy
		$tableConfig['cols'] = $tableConfigArray['cols'];
		$tableConfig['orderBy'] = $tableConfigArray['orderBy'];
		$tableConfig['fieldType'] = $tableConfigArray['fieldType'];
		
		// walk through foreign keys
		$foreignKeys = array();
		if(count($tableConfigArray['fk']) > 0) {
			foreach($tableConfigArray['fk'] as $fk => $sql) {
				
				// get sql result for fk
				$fkArray = Db::arrayValue($sql, MYSQL_ASSOC);
				
				if(!is_array($fkArray)) {
					$n = null;
					throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
				}
				
				// put in array for select option
				$fkStart = array('' => _l('- choose -'));
				foreach($fkArray as $values) {
					
					$foreignKeys[$fk][$values['id']] = $values['readable_name'];
				}
				$foreignKeys[$fk] = $fkStart + $foreignKeys[$fk];
			}
		}
		
		// add foreign keys and return config
		$tableConfig['fk'] = $foreignKeys;

		return $tableConfig;
	}
	
	
	/**
	 * debugAll() returns true if debug mode is on, false otherweise
	 * 
	 * @return bool true if debug mode is on, false otherwise
	 */
	public function debugAll() {
		return file_exists(JIPATH.'/DEBUG_ALL') === true;
	}
	public static function staticDebugAll() {
		return file_exists(JIPATH.'/DEBUG_ALL') === true;
	}
}



?>
