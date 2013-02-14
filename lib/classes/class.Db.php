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
		if(		is_file('cnf/default.ini')
				&& is_readable('cnf/default.ini')
				&& is_file('cnf/config.ini')
				&& is_readable('cnf/config.ini')) {
			$default = parse_ini_file('cnf/default.ini',true);
			$config = parse_ini_file('cnf/config.ini',true);
			
			// merge arrays
			$config = array_merge($default,$config);
		} else {
			
			// error
			$message = "<html>";
			$message .= "<head>\n<title>ERROR - Database connection failed</title>\n</head>";
			$message .= "<body>\n<div style=\"font-family: sans-serif; margin: 150px auto; width: 400px; height: 300px; border: 1px dashed red; padding: 5px;\"><h3 style=\"color: red;\">ERROR</h3><p>Database connection failed.<br />
									Please inform your administrator.<br /></p>[NoFile|NotReadable: \"default.ini|config.ini\"]</div>\n</body>";
			$message .= "</html>";
			
			// die
			die($message);
		}
		
		// connect to db
		$db = null;
		if(		isset($config['db']['host'])
				&& isset($config['db']['username'])
				&& isset($config['db']['password'])
				&& isset($config['db']['database'])) {
			$db = @new mysqli($config['db']['host'],$config['db']['username'],$config['db']['password'],$config['db']['database']);
			
			// check connection
			if($db->connect_error) {
				// error
				$message = "<html>";
				$message .= "<head>\n<title>ERROR - Database connection failed</title>\n</head>";
				$message .= "<body>\n<div style=\"font-family: sans-serif; margin: 150px auto; width: 400px; height: 300px; border: 1px dashed red; padding: 5px;\"><h3 style=\"color: red;\">ERROR</h3><p>Database connection failed.<br />
										Please inform your administrator.<br /></p>[ConnectionFailed: \"".$db->connect_error."\"]</div>\n</body>";
				$message .= "</html>";
				
				// die
				die($message);
			} else {
				$db->set_charset('utf8');
				return $db;
			}
		} else {
			
			// error
			$message = "<html>";
			$message .= "<head>\n<title>ERROR - Database connection failed</title>\n</head>";
			$message .= "<body>\n<div style=\"font-family: sans-serif; margin: 150px auto; width: 400px; height: 300px; border: 1px dashed red; padding: 5px;\"><h3 style=\"color: red;\">ERROR</h3><p>Database connection failed.<br />
									Please inform your administrator.<br /></p>[ConfigNotSet]</div>\n</body>";
			$message .= "</html>";
			
			// die
			die($message);
		}
	}
	
	
	
	
	
	
	
	
	/**
	 * returnValueById returns the value in col $col from $table by $id
	 * 
	 * @param int $id id of the row to be resolved
	 * @param string $table tablename to be resolved from
	 * @param string $col name of the col to be returned from
	 */
	public static function returnValueById($id, $table, $col) {
		
		// get db-object
		$db = DB::newDB();
		
		// prepare sql-statement
		$sql = "SELECT $col
				FROM $table
				WHERE id=$id";
		
		// execute
		$result = $db->query($sql);
		
		// fetch result, close db and return
		$return = $result->fetch_array(MYSQL_NUM);
		$db->close();
		return $return[0];
	}
}



?>
