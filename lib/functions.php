<?php

/**
 * loads the class-definition of given class from lib
 */
function __autoload($class) {

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