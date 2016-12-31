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
 * class Db extends mysqli with automatic connection
 */
class Db {
	
	/*
	 * variables
	 */
	protected static $config;
	public static $error;
	public static $insertId;
	public static $statement;
	public static $num_rows;
	
	/*
	 * constructor/destructor
	 */
	
	/*
	 * method
	 */
	/**
	 * newDb($die) checks the required config items and returns a mysqli object in case of
	 * successful connection to database. if $die is false it does not end the script
	 * with the configured message but returns a error code
	 * 
	 * 1 = configuration files not readable/accessible
	 * 2 = required configuration items not set
	 * 4 = database connection failed
	 * 
	 * @param bool $die the script dies with the according error message, if true, continues and returns the error code, if false
	 * @return mixed mysqli object if connection succeeds, error code in case of error
	 */
	public static function newDb($die = true) {
		
		// prepare error code
		$errorCode = 0;
		
		// get configuration
		if(		is_file(JIPATH.'/cnf/default.ini')
				&& is_readable(JIPATH.'/cnf/default.ini')
				&& is_file(JIPATH.'/cnf/config.ini')
				&& is_readable(JIPATH.'/cnf/config.ini')) {
			$default = parse_ini_file(JIPATH.'/cnf/default.ini',true);
			$config = parse_ini_file(JIPATH.'/cnf/config.ini',true);
			
			// merge arrays
			self::$config = array_merge($default,$config);
			
		} else {
			
			// die?
			if($die === false) {
				$errorCode += DB_CONF_NOT_ACCESSIBLE;
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
		}
		
		// check if mysqli object already exists in this run
		if(isset($GLOBALS['db']) && $GLOBALS['db'] instanceof mysqli) {
			return $GLOBALS['db'];
		}
		
		// connect to db
		$db = null;
		if(		isset(self::$config['db']['host'])
				&& isset(self::$config['db']['username'])
				&& isset(self::$config['db']['password'])
				&& isset(self::$config['db']['database'])) {
			
			// create mysqli object
			$db = @new mysqli(self::$config['db']['host'],self::$config['db']['username'],self::$config['db']['password'],self::$config['db']['database']);
			
			// check connection
			if($db->connect_error) {
				
				// die?
				if($die === false) {
					$errorCode += DB_CONNECTION_FAILED;
					$_SESSION['setup']['dbConnectError'] = $db->connect_error;
				} else {
					// error
					$message = "<html>";
					$message .= "<head>\n<title>ERROR - Database connection failed</title>\n</head>";
					$message .= "<body>\n<div style=\"font-family: sans-serif; margin: 150px auto; width: 400px; height: 300px; border: 1px dashed red; padding: 5px;\"><h3 style=\"color: red;\">ERROR</h3><p>Database connection failed.<br />
											Please inform your administrator.<br /></p>[ConnectionFailed: \"".$db->connect_error."\"]</div>\n</body>";
					$message .= "</html>";
					
					// die
					die($message);
				}
			} else {
				$db->set_charset('utf8');
				return $db;
			}
		} else {
			
			// die?
				if($die === false) {
					$errorCode += DB_CONF_NOT_SET;
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
		
		// return error code, if reached
		return $errorCode;
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
		$return = $result->fetch_array(MYSQLI_NUM);
		$db->close();
		return $return[0];
	}
	
	
	/**
	 * errorBitmask($errorCode) analyses the errorCode bitmask and returns an array
	 * with the according flags
	 * 
	 * @param int $errorCode bitmask of the connection errors
	 * @return array array containing the flags of the errorCode bitmask
	 */
	public static function errorBitmask($errorCode) {
		
		// set flags
		$flags[DB_CONF_NOT_ACCESSIBLE] = ($errorCode & (1 << 0)) != 0;
		$flags[DB_CONF_NOT_SET] = ($errorCode & (1 << 1)) != 0;
		$flags[DB_CONNECTION_FAILED] = ($errorCode & (1 << 2)) != 0;
		
		// return
		return $flags;
	}
	
	
	/**
	 * isEmpty() checks if the database is empty
	 * 
	 * @return bool true if database is empty, false otherwise
	 */
	public static function isEmpty() {
		
		// get db-object
		$db = DB::newDB();
		
		// prepare sql-statement
		$sql = 'SHOW TABLES';
		
		// execute
		$result = $db->query($sql);
		
		// check num_rows
		if($result->num_rows > 0) {
			return false;
		} else {
			return true;
		}
	}
	
	
	/**
	 * executeQuery($sql, $args) executes the given $sql query
	 * 
	 * @param string $sql the statement with placeholders
	 * @param array $args the values of the placeholders for the statement
	 * @return bool true in case of success, false otherwise
	 */
	public static function executeQuery($sql, $args=array()) {
		
		// check if multiple statements
		if(strpos($sql, ';') !== false) {
			
			// explode statements
			$querys = explode(';', $sql);
			
			// walk through statements
			for($i=0; $i < count($querys) -1; $i++) {
				
				// get result
				$result = self::resultValue($querys[$i]);
					
				// check for errors
				if(!$result) {
					return false;
				}
				
				// close result
				if($result instanceof mysqli_result) {
					$result->close();
				}
			}
		} else {
			
			// get result
			$result = self::resultValue($sql, $args);
				
			// check for errors
			if(!$result) {
				return false;
			}
			
			// close and return
			if($result instanceof mysqli_result) {
				$result->close();
			}
		}
		
		return true;
	}
	
	
	/**
	 * singleValue($sql, $args) executes the given $sql query and returns the only
	 * value
	 * 
	 * @param string $sql the statement with placeholders
	 * @param array $args the values of the placeholders for the statement
	 * @return mixed the only (or first) value the statement produces 
	 */
	public static function singleValue($sql, $args=array()) {
		
		// get result
		$result = self::resultValue($sql, $args);
				
		// check for errors
		if(!$result) {
			return false;
		}
		
		// fetch result
		$value = null;
		list($value) = $result->fetch_array(MYSQLI_NUM);
		
		// close and return
		$result->close();
		return $value;
	}
	
	
	/**
	 * arrayValue($sql, $arrayType, $args) executes the given $sql query and returns all
	 * values as array
	 * 
	 * @param string $sql the statement with placeholders
	 * @param int $arrayType indicates the array type (MYSQLI_ASSOC, MYSQLI_NUM or MYSQL_BOTH)
	 * @param array $args the values of the placeholders for the statement
	 * @return array all values as array the statement produces 
	 */
	public static function arrayValue($sql, $arrayType=MYSQLI_NUM, $args=array()) {
		
		// get result
		$result = self::resultValue($sql, $args);
				
		// check for errors
		if(!$result) {
			return false;
		}
		
		//fetch result
		$values = false;
		if($result->num_rows == 0) {
			$values = array();
		} else {
			while($row = $result->fetch_array($arrayType)) {
				$values[] = $row;
			}
		}
		
		// close and return
		$result->close();
		return $values;
	}
	
	
	/**
	 * resultValue($sql, $args) executes the given $sql query and returns the
	 * mysqli resultset
	 * 
	 * @param string $sql the statement with placeholders
	 * @param array $args the values of the placeholders for the statement
	 * @return object the mysqli resultset the statement produces 
	 */
	public static function resultValue($sql, $args=array()) {
		
		// get object
		$db = self::newDb();
		
		// check #?-placeholder
		if(strpos($sql, '#?') !== false || count($args) == 0) {
			
			// prepare sql and replace #?-placeholder
			$sqlParts = explode('#?', $sql);
			
			// check number of placeholders against $args
			if(count($args) < count($sqlParts) -1) {
				self::$error = 'Too few arguments for found #?-placeholder!';
				return false;
			}
			
			// replace #?-placeholders
			$i=0;
			$sql = '';
			// ensure following $arg numbering
			$args = array_merge($args);
			while($i < count($sqlParts) -1) {
				
				$sql .= $sqlParts[$i];
				$sql .= $db->real_escape_string($args[$i]);
				$i++;
			}
			// add remaining part
			$sql .= $sqlParts[$i];
		}
		
		// set statement for debug and error messages
		self::$statement = $sql;
		
		// get result
		$result = $db->query($sql);
		
		// check for errors
		if(!$result) {
			self::$error = $db->error;
			return false;
		}
		
		// set num_rows
		self::$num_rows = (isset($result->num_rows) ? $result->num_rows : null);
		// set insert_id
		self::$insertId = (isset($db->insert_id) ? $db->insert_id : null);
		
		// return
		return $result;
	}
	
	
	/**
	 * tableExists($table) checks if the given $table exists in database
	 * 
	 * @param string $table name of the table to be checked for existance
	 * @return bool true if table exists, false otherwise
	 */
	public static function tableExists($table) {
		
		// prepare statement
		$sql = '	SELECT COUNT(*)
					FROM `information_schema`.`tables`
					WHERE `table_schema`=\'#?\' AND `table_name`=\'#?\'';
		
		// get value and return
		return (self::singleValue($sql, array(self::$config['db']['database'], $table)) > 0);
		
	}
	
	
	/**
	 * columnExists($table, $column) checks if the given $column exists in given $table
	 * 
	 * @param string $table name of the table to be checked for existance of column
	 * @param string $column name of the column to be checked for existance
	 * @return bool true if table exists, false otherwise
	 */
	public static function columnExists($table, $column) {
		
		// prepare statement
		$sql = '	SELECT COUNT(*)
					FROM `information_schema`.`columns`
					WHERE `table_schema`=\'#?\' AND `table_name`=\'#?\' AND `column_name`=\'#?\'';
		
		// get value and return
		return (self::singleValue($sql, array(self::$config['db']['database'], $table, $column)) > 0);
		
	}
	
	
	/**
	 * columnType($table, $column) returns the type ofthe given $column exists in given $table
	 * 
	 * @param string $table name of the table the type to be returned
	 * @param string $column name of the column the type is to be returned
	 * @return string the type of the column
	 */
	public static function columnType($table, $column) {
		
		// prepare statement
		$sql = '	SELECT `data_type`
					FROM `information_schema`.`columns`
					WHERE `table_schema`=\'#?\' AND `table_name`=\'#?\' AND `column_name`=\'#?\'';
		
		// get value and return
		return strtoupper(self::singleValue($sql, array(self::$config['db']['database'], $table, $column)));
		
	}
	
	
	/**
	 * rowExists($table, $column, $row) checks if the given $row exists in given $table
	 * where $column=$row
	 * 
	 * @param string $table name of the table to be checked for existance of row
	 * @param string $column name of the column used in where clause
	 * @param string $row value (i.e. id) used in where clause
	 * @return bool true if table exists, false otherwise
	 */
	public static function rowExists($table, $column, $row) {
		
		// prepare statement
		$sql = '	SELECT COUNT(*)
					FROM `#?`
					WHERE `#?`=#?';
		
		// get value and return
		return (self::singleValue($sql, array($table, $column, $row)) > 0);
	}
	
	
	/**
	 * uniqueKeyExists($table, $keyName) checks if the given $keyName exists in given $table
	 * 
	 * @param string $table name of the table to be checked for existance of key
	 * @param string $keyName name of the to be checked for existance
	 * @return bool true if key exists, false otherwise
	 */
	public static function uniqueKeyExists($table, $keyName) {
		
		// prepare statement
		$sql = 'SHOW KEYS FROM `#?` WHERE `Key_name` = \'#?\'';
		
		// get value and return
		$result = self::executeQuery($sql, array($table, $keyName));
		return self::$num_rows > 0;
	}
	
	
	/** 
	 * isTableEmpty($table) checks if the given $table is empty
	 * 
	 * @param string $table name of the table to be checked if empty
	 * @return bool true if table is empty, false otherwise
	 */
	public static function isTableEmpty($table) {
		
		// prepare statement
		$sql = '	SELECT COUNT(*)
					FROM `#?`';
		
		// get value and return
		return (self::singleValue($sql, array($table)) > 0);
		
	}
	
	
	/**
	 * realEscapeString($string) uses real_escape_string() of mysqli to escape $string
	 * 
	 * @param mixed $string string to be escaped
	 * @return string the escaped string
	 */
	public static function realEscapeString($string) {
		
		// get db-object
		$db = DB::newDB();
		
		// return escaped string
		return $db->real_escape_string($string);
		
	}
}


?>
