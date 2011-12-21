<?php


/**
 * class Db extends mysqli with automatic connection
 */
class Db {
	
	/*
	 * constructor/destructor
	 */
	
	public static function newDb() {
		
		// get configuration
		$config = parse_ini_file('cnf/config.ini',true);
		
		// connect to db
		$db = new mysqli($config['db']['host'],$config['db']['username'],$config['db']['password'],$config['db']['database']);
		$db->set_charset('utf8');
				
		return $db;
	}
}



?>
