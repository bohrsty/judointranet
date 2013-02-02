<?php
/*
 * define constant to check in each .php-file
 */
define("JUDOINTRANET","secured");



/**
 * loads the class-definition of given class from lib
 */
function __autoload($class) {

	// check PEAR libraries
	$sysPath = explode(":", DEFAULT_INCLUDE_PATH);
	foreach($sysPath as $folder) {
		
		// check HTML/QuickForm2
		if(is_file("$folder/HTML/QuickForm2.php")) {
			break;
		} else {
			continue;
		}
		die("\"HTML/QuickForm2\" from Pear not found, please install \"HTML/QuickForm2\" >= 2.0.0");
	}
	
	// load HTML2PDF
	if($class == 'HTML2PDF') {
		include_once('lib/html2pdf/html2pdf.class.php');
	} elseif($class == 'Smarty') {
		include_once('lib/smarty/libs/Smarty.class.php');
	} elseif(substr($class,0,6) == 'Smarty') {
		include_once('lib/smarty/libs/sysplugins/'.strtolower($class).'.php');
	} elseif(substr($class,0,4) == 'HTML') {
		
		// load quickform
		// explode _
		$parts = explode('_',$class);
		$path = '';
		for($i=0;$i<count($parts);$i++) {
			if($i == count($parts)-1) {
				$path .= $parts[$i].'.php';
			} else {
				$path .= $parts[$i].'/';
			}
		}
		
		// include
		include_once($path);
	} else {
	
		// load classes
		include_once('lib/classes/class.'.$class.'.php');
	}
}






?>