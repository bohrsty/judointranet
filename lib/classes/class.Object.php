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
		
		// set default-time-zone
		date_default_timezone_set($_SESSION['GC']->get_config('default_time_zone'));
		
		// set locale
		setlocale(LC_ALL, $_SESSION['GC']->get_config('locale'));
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
		if(is_file('cnf/lang/lang.'.$_SESSION['user']->get_lang().'.php')) {
			include('cnf/lang/lang.'.$_SESSION['user']->get_lang().'.php');
		} else {
			return '[language "'.$_SESSION['user']->get_lang().'" not found]';
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
	 */
	public function callback_check_select($args) {
		
		// check values
		if($args == '0') {
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
