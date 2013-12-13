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
	
		// load HTML2PDF
		if($class == 'HTML2PDF') {
			include_once('lib/html2pdf/html2pdf.class.php');
		// load smarty
		} elseif($class == 'Smarty') {
			include_once('lib/smarty/libs/Smarty.class.php');
		// load smarty plugins
		} elseif(substr($class,0,6) == 'Smarty') {
			include_once('lib/smarty/libs/sysplugins/'.strtolower($class).'.php');
		// load Zebra_Form
		} elseif($class == 'Zebra_Form') {
			include_once('lib/zebra_form/Zebra_Form.php');
		} else {
		
			// load classes
			include_once('lib/classes/class.'.$class.'.php');
		}
	}
);



/*
 * constants
 */
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



?>