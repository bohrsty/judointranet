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
 * interface WebserviceJob defines the webservice job classes
 */
interface WebserviceJobInterface {
	
	/**
	 * loadJobConfig($config) loads the given job config into the object
	 *
	 * @param array the config array
	 * @return void
	 */
	public function loadJobConfig($config);
	
	
	/**
	 * runJob() executes the steps to run the job
	 *
	 * @return array the result of the job as array for the AJAX call
	 */
	public function runJob();
	
	
	/**
	 * newJob() creates a new job and saves it to the database
	 * 
	 * @param array $args array of arguments
	 */
	public function newJob($args);
	
	
	/**
	 * resultToHtml() returns the webservice result as HTML string
	 * 
	 * @param array $result the result array
	 */
	public static function resultToHtml($result);
	
	
	/**
	 * addMarks() returns the webservice result as "mark" (HTML) string
	 * 
	 * @param array $result the result array
	 * @param bool $html indicates if returned test should be HTML or plain text
	 */
	public static function addMarks($result, $html);
}

?>
