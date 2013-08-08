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
 * class Config implements global configuration
 */
class Config extends Object {
	
	/*
	 * class-variables
	 */
	private $config;
	private $sessionconfig;
	
	/*
	 * getter/setter
	 */
	public function get_config($name = ''){
		
		// check name
		if($name == '') {
			return $this->config;
		} else {
			
			// prepare return
			$value = false;
			
			// get config
			$config = $this->config;
			$sessionconfig = $this->sessionconfig;
			
			// check if $name exists
			if(isset($config[$name])){
				$value = $config[$name];
			}
			if(isset($sessionconfig[$name])) {
				$value = $sessionconfig[$name];
			}
			
			// return
			return $value;
		}
	}
	public function set_config($config) {
		$this->config = $config;
	}
	public function get_sessionconfig(){
		return $this->sessionconfig;
	}
	public function set_sessionconfig($sessionconfig) {
		$this->sessionconfig = $sessionconfig;
	}
	
	/*
	 * constructor/destructor
	 */
	public function __construct() {
		
		// set initial values
		$this->read_config();
		$this->read_config_file();
		$this->set_sessionconfig(array());
	}
	
	/*
	 * methods
	 */
	/**
	 * read_config reads the config from database and stores it in an array
	 * 
	 * @return void
	 */
	private function read_config() {
		
		// prepare config
		$config = array();
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql-statement
		$sql = "SELECT c.name,c.value
				FROM config AS c";
		
		// execute statement
		$result = $db->query($sql);
		
		// fetch result
		while(list($name,$value) = $result->fetch_array(MYSQL_NUM)) {
			$config[$name] = $value;
		}
		
		// set config
		$this->set_config($config);
	}
	
	
	
	
	
	
	
	
	/**
	 * put_config puts the given configname and -value in the session-
	 * config
	 * 
	 * @param string $name name of the sessionconfigitem
	 * @param mixed $value value of the sessionconfigitem
	 * @return void
	 */
	public function put_config($name,$value) {
		
		// get sessionconfig
		$config = $this->get_sessionconfig();
		
		// add config
		$config[$name] = $value;
		
		// set config
		$this->set_sessionconfig($config);
	}
	
	
	
	
	
	
	
	
	/**
	 * read_config_file reads the configured parts from the configfile
	 * 
	 * @return void
	 */
	public function read_config_file() {
		
		// get config
		$config = $this->get_config();
		
		// read default configuration from ini-file
		if(is_file('cnf/default.ini') && is_readable('cnf/default.ini')) {
			$default = parse_ini_file('cnf/default.ini',true);
			
			// merge arrays
			foreach($default as $parts) {
				$config = array_merge($config,$parts);
			}
		}
		
		// read configuration from ini-file
		if(is_file('cnf/config.ini') && is_readable('cnf/config.ini')) {
			$file = parse_ini_file('cnf/config.ini',true);
			
			// merge arrays
			foreach($file as $parts) {		
				$config = array_merge($config,$parts);
			}
		}
		
		// set config
		$this->set_config($config);
	}
}



?>
