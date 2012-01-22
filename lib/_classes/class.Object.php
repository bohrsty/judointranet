<?php


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
	
	/*
	 * constructor/destructor
	 */
	public function __construct() {
		
		// set config if not exists
		if(!isset($_SESSION['GC']) || isset($_GET['rc'])) {
			$_SESSION['GC'] = new Config();
		}
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
	  						'postvalue' => '{^[a-zA-Z0-9äöüÄÖÜß\.\-_\+!§\$%&/()=`´;:\*#~\?ß<>| ]*$}'
	  	//					'text' => '{^[a-zA-Z0-9äöüÄÖÜß\.,\-_\+!§\$%&/()=`´;:\*#~\?ß<>| ]*$}',
	  	//					'text_nt' => '{^[a-zA-Z0-9äöüÄÖÜß\.,\-_\+!§\$%&/()=`´;:\*#~\?ß<>| \n\r\t]*$}s',
	  	//					'datum' => '/^[0123]?\d\.[012]?\d\.\d{4}$/',
	  	//					'zahl' => '/^[0-9]*$/'
		);
	  
		// check if not permitted chars
		if(!preg_match($regexp[$type],$value)) {
	
			// nonconform return false
			return false;
		} else {
	
			// correct return value
			return $value;
		}
	}
	
	
	
	
	
	
	
	/**
	 * lang reads the translated string for the given marker and returns it
	 * expects $_SESSION['lang'] to be set to lang-code (i.e. "de_DE")
	 * 
	 * @param string $string string to be parsed and "translated", splitmarker "#"
	 * @return string translated value of the string
	 */
	protected static function lang($string) {
		
		// split string
		$i = explode('#',$string,4);
		
		// import lang-file
		if(is_file('cnf/lang/lang.'.$_SESSION['user']->return_lang().'.php')) {
			include('cnf/lang/lang.'.$_SESSION['user']->return_lang().'.php');
		} else {
			return '[language "'.$_SESSION['user']->return_lang().'" not found]';
		}
		
		// check if is translated
		if(!isset($lang[$i[0]][$i[1]][$i[2]][$i[3]])) {
			return $string.' not translated';
		} else {
			return $lang[$i[0]][$i[1]][$i[2]][$i[3]];
		}
	}
}



?>
