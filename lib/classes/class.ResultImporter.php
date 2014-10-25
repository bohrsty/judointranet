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
 * class ResultImporter implements the representation of a result importer object
 */
class ResultImporter extends Object {
	
	/*
	 * class-variables
	 */
	private $fileContent;
	private $resultStore;
	// register import modules
	const modules = 'Mm5export,Blafasel';
	
	
	/*
	 * getter/setter
	 */
	public function getFileContent(){
		return $this->fileContent;
	}
	public function setFileContent($fileContent) {
		$this->fileContent = $fileContent;
	}
	public function getResultStore(){
		return $this->resultStore;
	}
	public function setResultStore($resultStore) {
		$this->resultStore = $resultStore;
	}
	
	
	/*
	 * constructor/destructor
	 */
	public function __construct() {
		
		// parent constructor
		parent::__construct();
		
	}
	
	
	/*
	 * methods
	 */
	/**
	 * factory($fileName, $type) creates and returns a new ResultImporter* object according
	 * to $type with the given $fileName
	 * 
	 * @param string $type type of the importer object
	 * @param string $fileName name of the file to be imported
	 * @return object ResultImporter* object with the given data
	 */
	public static function factory($fileName, $type='') {
		
		// check $type to decide which object to create
		// prepare type
		$type = ucfirst(strtolower($type));
		$class = 'ResultImporter'.$type;

		if($type != '' && (class_exists($class, false)) || @class_exists($class)) {
			$importer = new $class();
		} else {
			$importer = new ResultImporter();
		}
		
		// set data
		// fileContent
		$importer->setFileContent(file($fileName, FILE_IGNORE_NEW_LINES));
		
		// resultStore
		$importer->setResultStore(array());
		
		// return object
		return $importer;
	}
	
	
	/**
	 * returnModules() retrurns an array of defined and registered import modules with its
	 * translated name
	 * 
	 * @return array defined and registered import modules
	 */
	public static function returnModules() {
		
		// prepare return
		$modules = array();
		
		// walk through modules
		foreach(explode(',', self::modules) as $module) {
			if(@class_exists('ResultImporter'.$module)) {
				$modules[$module] = parent::lang('ResultImporter'.$module, true);
			}
		}
		
		// return
		return $modules;
	}
}