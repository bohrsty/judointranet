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
define("JUDOINTRANET","secured");



/**
 * loads the class-definition of given class from lib
 */
// register autoload
spl_autoload_register(
	function ($class) {
	
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
);






?>