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
 * class Jtable implements the settings for jTables
 */
class JtableField extends Object {
	
	
	/*
	 * class-variables
	 */
	private $name;
	private $settings;
	private $validate;
	
	/*
	 * getter/setter
	 */
	public function getName() {
		return $this->name;
	}
	public function setName($name) {
		$this->name = $name;
	}
	public function getValidate() {
		return $this->validate;
	}
	public function setValidate($validate) {
		$this->validate = $validate;
	}
	
	/*
	 * constructor/destructor
	 */
	public function __construct($name) {
		
		// setup parent
		parent::__construct();
		
		// set name
		$this->setName($name);
		
		// prepare settings
		$this->settings = array();
		// prepare validation
		$this->setValidate(false);
		
		// set default values
		$this->setList();
		$this->setCreate();
		$this->setEdit();
		$this->setKey();
	}
	
	/*
	 * methods
	 */
	/**
	 * asArray() returns the field as associative array to be used in json_encode()
	 * 
	 * @return array field as array
	 */
	public function asArray() {
		return $this->settings;
	}
	
	
	// set title of table
	public function setTitle($title) {
		$this->settings['title'] = $title;
	}
	
	// set list, create and edit
	public function setList($value = true) {
		$this->settings['list'] = $value;
	}
	
	public function setCreate($value = true) {
		$this->settings['create'] = $value;
	}
	
	public function setEdit($value = true) {
		$this->settings['edit'] = $value;
	}
	
	// set whether field is key
	public function setKey($value = false) {
		$this->settings['key'] = $value;
	}
	
	// set type and options
	public function setType($value = 'text') {
		$this->settings['type'] = $value;
	}
	
	public function setOptions($value = array()) {
		$this->settings['options'] = $value;
	}
	
	public function setSorting($value = true) {
		$this->settings['sorting'] = $value;
	}
	
	public function setWidth($value = '10%') {
		$this->settings['width'] = $value;
	}
	
	// set validation rules
	public function validateAgainst($rules) {
		
		// check rules
		if($rules != '') {
			
			// add rules to addClass
			$this->settings['inputClass'] = 'validate['.$rules.']';
			
			// set validate
			$this->setValidate(true);
		}
	}
}

?>
