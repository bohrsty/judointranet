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

use Symfony\Component\HttpFoundation\Session\Session;
 
/*
 * define constant to check in each .php-file
 */
define('JUDOINTRANET', 'secured');

/*
 * create session
 */
$session = new Session();
$session->setName('JudoIntranet');
$session->start();

/*
 * determine app path
 */
$scriptPath = dirname(realpath($_SERVER['SCRIPT_FILENAME']));
while(!is_dir($scriptPath.'/lib') && !is_file($scriptPath.'/administration.php')) {
	$scriptPath = dirname($scriptPath);
}
define('JIPATH', $scriptPath);
unset($scriptPath);

/*
 * get composer.json as array
 */
$composerJsonString = file_get_contents(JIPATH.'/composer.json');
$composerJson = json_decode($composerJsonString, true);

/*
 * define code version
 */
define('CONF_GLOBAL_VERSION', $composerJson['version']);

/*
 * define constants
 */
// db error codes
define('DB_CONF_NOT_ACCESSIBLE', 1);
define('DB_CONF_NOT_SET', 2);
define('DB_CONNECTION_FAILED', 4);
// version check
define('VERSION_DO_INSTALL', 0);
define('VERSION_DO_UPGRADE', 1);
define('VERSION_EQUAL', 2);
define('VERSION_LOWER', 3);
define('VERSION_HIGHER', 4);
define('VERSION_NO_CONFIG', 5);
define('VERSION_DB_ERROR', 6);
define('VERSION_EMPTY_DB', 7);
define('VERSION_ERROR', 255);
// helpmessages
define('HELP_MSG_ABOUT', 1);
define('HELP_MSG_FIELDDATE', 2);
define('HELP_MSG_FIELDNAME', 3);
define('HELP_MSG_FIELDSHORTNAME', 4);
define('HELP_MSG_FIELDTYPE', 5);
define('HELP_MSG_FIELDCONTENT', 6);
define('HELP_MSG_FIELDSORT', 7);
define('HELP_MSG_FIELDISPUBLIC', 8);
define('HELP_MSG_CALENDARNEW', 9);
define('HELP_MSG_CALENDARLISTALL', 10);
define('HELP_MSG_CALENDARLISTADMIN', 11);
define('HELP_MSG_DELETE', 12);
define('HELP_MSG_CALENDARLISTSORTLINKS', 13);
define('HELP_MSG_FIELDTEXT', 14);
define('HELP_MSG_LOGIN', 15);
define('HELP_MSG_FIELDCHECKBOX', 16);
define('HELP_MSG_FIELDDBSELECT', 17);
define('HELP_MSG_FIELDDBHIERSELECT', 18);
define('HELP_MSG_ADMINUSERTABLESELECT', 19);
define('HELP_MSG_ADMINUSERTABLETASKS', 20);
define('HELP_MSG_FILELISTALL', 21);
define('HELP_MSG_FILELISTADMIN', 22);
define('HELP_MSG_FILEUPLOAD', 23);
define('HELP_MSG_FIELDFILE', 24);
define('HELP_MSG_PROTOCOLLISTALL', 25);
define('HELP_MSG_PROTOCOLLISTADMIN', 26);
define('HELP_MSG_FIELDALLTEXT', 27);
define('HELP_MSG_FIELDPRESET', 28);
define('HELP_MSG_PROTOCOLNEW', 29);
define('HELP_MSG_PROTOCOLCORRECT', 30);
define('HELP_MSG_PROTOCOLCORRECTABLE', 31);
define('HELP_MSG_PROTOCOLCORRECTORS', 32);
define('HELP_MSG_PROTOCOLDECISIONS', 33);
define('HELP_MSG_PROTOCOLDIFF', 34);
define('HELP_MSG_PROTOCOLDIFFLIST', 35);
define('HELP_MSG_RESULTIMPORTER', 36);
define('HELP_MSG_RESULTDESC', 37);
define('HELP_MSG_ACCOUNTINGRESULTS', 38);
define('HELP_MSG_FIELDCITY', 39);
define('HELP_MSG_ADMINNEWYEAR', 40);
define('HELP_MSG_RESULTLISTALL', 41);
define('HELP_MSG_RESULTLIST', 42);
define('HELP_MSG_ACCOUNTINGSETTINGSCOSTS', 43);
define('HELP_MSG_RESULTLISTADMIN', 44);
define('HELP_MSG_ISTEAM', 45);
define('HELP_MSG_CALENDARCALENDAR', 46);
define('HELP_MSG_FIELDCOLOR', 47);
define('HELP_MSG_FIELDISEXTERNAL', 48);
define('HELP_MSG_MANAGESCHOOLHOLIDAYS', 49);
define('HELP_MSG_MANAGESCHOOLHOLIDAYSYEAR', 50);
define('HELP_MSG_TRIBUTENEW', 51);
define('HELP_MSG_TRIBUTELISTALL', 52);
define('HELP_MSG_TRIBUTEEDIT', 53);
define('HELP_MSG_FILELOGO', 54);
// write to db
define('DB_WRITE_NEW', 1);
define('DB_WRITE_UPDATE', 2);
// exception handling
define('HANDLE_EXCEPTION_VIEW', 1);
define('HANDLE_EXCEPTION_JSON', 2);
// jtable check return
define('JTABLE_NOT_AUTHORIZED', 1);
define('JTABLE_ROW_NOT_EXISTS', 2);


// check versions if not api
if(isset($_SERVER['REQUEST_URI'])) {
	if(basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) != 'internal.php') {
		$dbVersion = checkDbVersion();
		if($dbVersion != VERSION_EQUAL && basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) != 'setup.php') {
			
			// redirect to setup
			header('Location: setup.php', true);
			exit;
		}
	}
}


/*
 * methods used before objects are instanciated
 */
/**
 * getVersionAsInt($mixed) checks if the version $mixed contains ".", removes them and
 * returns the version as integer
 * 
 * @param mixed $mixed the version to check and convert
 * @return int the version as integer
 */
function getVersionAsInt($mixed) {
	
	if(strpos($mixed, '.') === false) {
		$version = (int)$mixed;
	} else {
		$version = str_replace('.', '', $mixed);
	}
	return $version;
}


/**
 * checkDbVersion() checks the code version against the db version and returns a
 * constant VERSION_xxx
 * 
 * @return int VERSION_xxx constant indicating the state of the version check
 */
function checkDbVersion() {
	
	// get db-object
	$db = Db::newDB(false);
	
	// check return
	if(!$db instanceof mysqli) {
		
		// analyze bitmask of $db
		$errorFlags = Db::errorBitmask($db);
		
		// return
		if($errorFlags[DB_CONF_NOT_ACCESSIBLE] || $errorFlags[DB_CONF_NOT_SET]) {
			
			// reset action
			if(isset($_SESSION['setup']['action'])) {unset($_SESSION['setup']['action']);}
			return VERSION_NO_CONFIG;
		} elseif($errorFlags[DB_CONNECTION_FAILED]) {
			
			// reset action
			if(isset($_SESSION['setup']['action'])) {unset($_SESSION['setup']['action']);}
			return VERSION_DB_ERROR;
		}
	}
	
	// check if database is empty
	if(Db::isEmpty() === true && !isset($_SESSION['setup']['action'])) {
		
		// reset action
		if(isset($_SESSION['setup']['action'])) {unset($_SESSION['setup']['action']);}
		return VERSION_EMPTY_DB;
	}
	
	// check action
	if(isset($_SESSION['setup']['action']) && $_SESSION['setup']['action'] == 'install') {
		return VERSION_DO_INSTALL;
	} elseif(isset($_SESSION['setup']['action']) && $_SESSION['setup']['action'] == 'upgrade') {
		return VERSION_DO_UPGRADE;
	}
	
	// prepare sql-statement
	$sql = 'SELECT `value`
			FROM `config`
			WHERE `name`=\'global.version\'';
	
	// execute
	$result = $db->query($sql);
	
	// check result
	if(!$result) {
		
		// set mysql error
		$_SESSION['setup']['dbConnectError'] = $db->error;
		
		// reset action
		if(isset($_SESSION['setup']['action'])) {unset($_SESSION['setup']['action']);}
		return VERSION_DB_ERROR;
	}
	
	// fetch result, close db and return
	$return = $result->fetch_array(MYSQLI_NUM);
	$db->close();
	
	// set version number globally
	$_SESSION['setup']['dbVersion'] = false;
	if(!is_null($return)) {
		$_SESSION['setup']['dbVersion'] = $return[0];
	}
	
	// check action
	if(isset($_SESSION['setup']['action']) && $_SESSION['setup']['action'] == 'install') {
		return VERSION_DO_INSTALL;
	} elseif(isset($_SESSION['setup']['action']) && $_SESSION['setup']['action'] == 'upgrade') {
		return VERSION_DO_UPGRADE;
	}
	
	// prepare db version
	$dbVersion = getVersionAsInt($return[0]);
	// prepare code version
	$codeVersion = getVersionAsInt(CONF_GLOBAL_VERSION);
	// check version
	if(is_null($return) || (int)$dbVersion > (int)$codeVersion) {
		return VERSION_LOWER;
	} elseif((int)$dbVersion < (int)$codeVersion) {
		return VERSION_HIGHER;
	} elseif((int)$dbVersion == (int)$codeVersion) {
		return VERSION_EQUAL;
	}
	
	// error
	return VERSION_ERROR;
}


/**
 * handleExceptions($e, $outputType) shows the error messages of $e according to $outputType,
 * no objects other than Exceptions (and their childs) allowed in this function
 * 
 * @param Exception $e the thrown exception object to be handled
 * @param int $outputType type of the error message (i.e. HTML or JSON output)
 * @param bool $show if true echoes the error, if false return it
 * @return void
 */
function handleExceptions($e, $outputType, $show = true) {
	
	// prepare return
	$return = '';
	
	// determine type of exception
	switch(get_class($e)) {
		case 'SmartyException':
		case 'SmartyCompilerException':
		case 'HTML2PDF_exception':
		case 'PHPExcel_Reader_Exception':
		case 'GmagickException':
			
			// check $outputType
			if($outputType == HANDLE_EXCEPTION_JSON) {
				$return = json_encode(array(
						'Result' => 'ERROR',
						'Message' => $e->__toString(),
					));
			} else {
				$return = '<table>'.$e->xdebug_message.'</table>';
			}
		break;
		
		default:
			if($e instanceof CustomException) {
				$return = $e->errorMessage($outputType, $show);
			} else {
				$return = $e;
			}
		break;
	}
	
	// check return or echo
	if($show === true) {
		echo $return;
	} else {
		return $return;
	}
}


/**
 * _l($string) translates $string in the user choosen language
 * 
 * @param string $string string to translate
 * @param array $replacements associative array where #?$key in $string will be replaced by $value
 * @return string translation of $string
 */
function _l($string, $replacements = array()) {
	
	// check user
	$locale = 'de_DE';
	if(Object::staticGetUser()) {
		$locale = Object::staticGetUser()->get_lang();
	}
	
	// check if already included locale
	if(!isset($GLOBALS['lang'][$locale])) {
		
		// import lang-file
		if(is_file(JIPATH.'/cnf/lang/lang.'.$locale.'.php')) {
			include(JIPATH.'/cnf/lang/lang.'.$locale.'.php');
			$GLOBALS['lang'][$locale] = $lang;
		} else {
			return htmlentities('[language "'.$locale.'" not found]', ENT_QUOTES, 'UTF-8');
		}
	}
	
	// check if is translated
	$translation = htmlentities($string, ENT_QUOTES, 'UTF-8');
	if(isset($GLOBALS['lang'][$locale][$string])) {
		$translation = $GLOBALS['lang'][$locale][$string];
	}
	
	// replace
	if(count($replacements) > 0) {
		foreach($replacements as $key => $value) {
			$translation = str_replace('#?'.$key, $value, $translation);
		}
	}
	
	// return
	return $translation;
}

?>