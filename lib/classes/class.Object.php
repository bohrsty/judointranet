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
	
	
	/*
	 * getter/setter
	 */
	public function getGc() {
		return $GLOBALS['GC'];
	}
	public function setGc($gc) {
		$GLOBALS['GC'] = $gc;
	}
	public function getUser() {
		return $_SESSION['user'];
	}
	public function setUser($user=null) {
		
		if(is_null($user)) {
			if(!isset($_SESSION['user'])) {
				// initialize user
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
	public function getHelp() {
		return $GLOBALS['help'];
	}
	public function setHelp($help) {
		$GLOBALS['help'] = $help;
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
		//					'userpass' => '/^[a-zA-Z0-9\.\-_]+$/',
	  						'getvalue' => '/^[a-zA-Z0-9\.\-_\+\/=]*$/',
	  						'postvalue' => '/^[a-zA-Z0-9äöüÄÖÜß\.,\-_\+!§\$%&\/()\[\]\{\}=`´;:\*#~\?<>|"@ \n\r\t]*$/'
	  	//					'text' => '{^[a-zA-Z0-9äöüÄÖÜß\.,\-_\+!§\$%&/()=`´;:\*#~\?ß<>| ]*$}',
	  	//					'text_nt' => '{^[a-zA-Z0-9äöüÄÖÜß\.,\-_\+!§\$%&/()=`´;:\*#~\?ß<>| \n\r\t]*$}s',
	  	//					'datum' => '/^[0123]?\d\.[012]?\d\.\d{4}$/',
	  	//					'zahl' => '/^[0-9]*$/'
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
	 * lang reads the translated string for the given marker and returns it
	 * expects $_SESSION['lang'] to be set to lang-code (i.e. "de_DE")
	 * 
	 * @param string $string string to be parsed and "translated", splitmarker "#"
	 * @return string translated value of the string
	 */
	public static function lang($string) {
		
		// split string
		$i = explode('#',$string,4);
		
		// check user
		$lang = 'de_DE';
		if(self::getUser()) {
			$lang = self::getUser()->get_lang();
		}
		
		// import lang-file
		if(is_file('cnf/lang/lang.'.$lang.'.php')) {
			include('cnf/lang/lang.'.$lang.'.php');
		} else {
			return '[language "'.$lang.'" not found]';
		}
		
		// check if is translated
		if(!isset($lang[$i[0]][$i[1]][$i[2]][$i[3]])) {
			return $string.' not translated';
		} else {
			return $lang[$i[0]][$i[1]][$i[2]][$i[3]];
		}
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
			'ß' => 'ss'
		);
		
		// convert to utf8
		$string = utf8_encode($string);		
		
		// replace
		foreach($table as $char => $replacement) {
//			echo "$char => $replacement";
			$string = str_replace($char,$replacement,$string);
//			$string = preg_replace('/ü/',$replacement,$string);
		}
		
		// return
		return $string;
	}
}



?>
