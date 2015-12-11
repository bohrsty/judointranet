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
 
 
/*
 * define constant to check in each .php-file
 */
define('JUDOINTRANET', 'secured');

/*
 * set session name
 */
session_name('JudoIntranet');

/*
 * define code version
 */
define('CONF_GLOBAL_VERSION', '023');

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
// write to db
define('DB_WRITE_NEW', 1);
define('DB_WRITE_UPDATE', 2);
// exception handling
define('HANDLE_EXCEPTION_VIEW', 1);
define('HANDLE_EXCEPTION_JSON', 2);
// jtable check return
define('JTABLE_NOT_AUTHORIZED', 1);
define('JTABLE_ROW_NOT_EXISTS', 2);




/**
 * loads the class-definition of given class from lib
 */
include_once(JIPATH.'/lib/classes/Exceptions.php');

// register autoload
spl_autoload_register(
	function ($class) {
		
		// load interfaces
		if(substr($class, -9) == 'Interface') {
			include_once(JIPATH.'/lib/interfaces/iface.'.substr($class, 0, -9).'.php');
		// load Services_JSON
		} elseif($class == 'Services_JSON') {
			include_once(JIPATH.'/lib/Services_JSON/json.php');
		// load HTML2PDF
		} elseif($class == 'HTML2PDF') {
			include_once(JIPATH.'/lib/html2pdf/html2pdf.class.php');
		// load smarty
		} elseif($class == 'Smarty') {
			include_once(JIPATH.'/lib/smarty/libs/Smarty.class.php');
		// load smarty plugins
		} elseif(substr($class,0,6) == 'Smarty') {
			include_once(JIPATH.'/lib/smarty/libs/sysplugins/'.strtolower($class).'.php');
		// load Zebra_Form
		} elseif($class == 'Zebra_Form') {
			include_once(JIPATH.'/lib/zebra_form/Zebra_Form.php');
		// load FPDI
		} elseif($class == 'FPDI') {
			include_once(JIPATH.'/lib/FPDI/fpdi.php');
		} elseif(substr($class, 0, 4) == 'fpdi') {
			include_once(JIPATH.'/lib/FPDI/'.$class.'.php');
		} elseif($class == 'FPDF_TPL') {
			include_once(JIPATH.'/lib/FPDI/fpdf_tpl.php');
		} elseif(substr($class,0,4) == 'pdf_') {
			include_once(JIPATH.'/lib/FPDI/'.$class.'.php');
		// load PHPExcel
		} elseif(substr($class,0,8) == 'PHPExcel') {
			// get path parts
			$pathParts = explode('_', $class);
			// get path
			$path = implode('/', $pathParts);
			include_once(JIPATH.'/lib/PHPExcel/Classes/'.$path.'.php');
		// load classes
		} else {
			include_once(JIPATH.'/lib/classes/class.'.$class.'.php');
		}
	}
);


// check versions if not api
if(basename($_SERVER['SCRIPT_FILENAME']) != 'internal.php') {
	$dbVersion = checkDbVersion();
	if($dbVersion != VERSION_EQUAL && basename($_SERVER['SCRIPT_NAME']) != 'setup.php') {
		
		// redirect to setup
		header('Location: setup.php');
	}
}


/*
 * methods used before objects are instanciated
 */
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
	$return = $result->fetch_array(MYSQL_NUM);
	$db->close();
	
	// set version number globally
	$_SESSION['setup']['dbVersion'] = false;
	if(!is_null($return)) {
		$_SESSION['setup']['dbVersion'] = (int)$return[0];
	}
	
	// check action
	if(isset($_SESSION['setup']['action']) && $_SESSION['setup']['action'] == 'install') {
		return VERSION_DO_INSTALL;
	} elseif(isset($_SESSION['setup']['action']) && $_SESSION['setup']['action'] == 'upgrade') {
		return VERSION_DO_UPGRADE;
	}
	
	// check version
	if(is_null($return) || (int)$return[0] > (int)CONF_GLOBAL_VERSION) {
		return VERSION_LOWER;
	} elseif((int)$return[0] < (int)CONF_GLOBAL_VERSION) {
		return VERSION_HIGHER;
	} elseif((int)$return[0] == (int)CONF_GLOBAL_VERSION) {
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
 * @return void
 */
function handleExceptions($e, $outputType) {
	
	// determine type of exception
	switch(get_class($e)) {
		case 'SmartyException':
		case 'SmartyCompilerException':
		case 'HTML2PDF_exception':
		case 'PHPExcel_Reader_Exception':
		case 'GmagickException':
			
			// check $outputType
			if($outputType == HANDLE_EXCEPTION_JSON) {
				echo json_encode(array(
						'Result' => 'ERROR',
						'Message' => $e->__toString(),
					));
			} else {
				echo '<table>'.$e->xdebug_message.'</table>';
			}
		break;
		
		default:
			if($e instanceof CustomException) {
				$e->errorMessage($outputType);
			} else {
				echo $e;
			}
		break;
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