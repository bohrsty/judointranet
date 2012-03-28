<?php

/**
 * loads the class-definition of given class from lib
 */
function __autoload($class) {

	// load quickform
	if(substr($class,0,4) == 'HTML') {
		
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
	
		// load new classes
		include_once('lib/classes/class.'.$class.'.php');
	}
}






?>