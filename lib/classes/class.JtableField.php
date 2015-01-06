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
	private $quote;
	private $validate;
	private $childTable;
	
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
	public function getChildTable() {
		return $this->childTable;
	}
	public function setChildTable($childTable) {
		$this->childTable = $childTable;
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
		// prepare child table
		$this->setChildTable(false);
		
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
	/**
	 * asJavaScriptConfig() returns the object as java script config string
	 * 
	 * @return string jTable as java script config
	 */
	public function asJavaScriptConfig() {
		
		// generate "java script"
		$jScript = '{';
		
		// prepare JSON value check
		$servicesJson = new Services_JSON();
		
		// walk through settings
		foreach($this->settings as $setting => $nameValue) {
			
			// check $setting value and name for valid JSON characters, if not display and child table
			$name = $servicesJson->encode($setting);
			if($setting == 'display' && $this->getChildTable() === true) {
				$value = $nameValue;
			} else {
				$value = $servicesJson->encode($nameValue);
			}
			
			// check quoting
			if(	isset($this->quote[$setting])
				&& $this->quote[$setting] === false
				&& !($setting == 'display'
				&& $this->getChildTable() === true)) {
				$jScript .= $name.':'.(gettype($this->settings[$setting]) == 'string' ? substr($value, 1, -1) : $value);
			} else {
				$jScript .= $name.':'.$value;
			}
			
			// add ','
			$jScript .= ',';
		}
		
		// close }
		$jScript = substr($jScript, 0, -1);
		$jScript .= '}';
		
		// return
		return $jScript;
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
	public function setType($value = 'text', $options = array(), $optionsSorting = null) {
		
		// check type
		if($value == 'combobox') {
			$this->setOptions($options, $optionsSorting);
		}
		if($value == 'checkbox'){
			$this->setValues($options, $optionsSorting);
		}
		$this->settings['type'] = $value;
	}
	
	public function setOptions($value = array(), $optionsSorting = null) {
		foreach($value as $k => $v) {
			$this->settings['options'][] = array('Value' => $k, 'DisplayText' => $v);
		}
		if(!is_null($optionsSorting)) {
			$this->settings['optionsSorting'] = $optionsSorting;
		}
	}
	
	public function setValues($value = array()) {
		$this->settings['values'] = $value;
	}
	
	public function setSorting($value = true) {
		$this->settings['sorting'] = $value;
	}
	
	public function setWidth($value = '10%') {
		$this->settings['width'] = $value;
	}
	
	public function setDisplay($value = 'function(data){}', $quote = false) {
		$this->settings['display'] = $value;
		$this->quote['display'] = $quote;
	}
	
	// set validation rules
	public function validateAgainst($rules) {
		
		// check rules
		if($rules != '') {
			
			// add rules to addClass
			$this->addInputClass('validate['.$rules.']');
			
			// set validate
			$this->setValidate(true);
		}
	}
	
	public function addInputClass($value) {
		
		// check if already set
		if(isset($this->settings['inputClass']) && $this->settings['inputClass'] != '') {
			$this->settings['inputClass'] .= ' '.$value;
		} else {
			$this->settings['inputClass'] = $value;
		}
	}
	
	public function addChildTable($subTable, $img, $containerId) {
		
		// put jscript function together
		$display = 'function (parentData) { var img = $(\''.$img.'\'); img.click(function () { $(\'#'.$containerId.'\').jtable(\'openChildTable\', img.closest(\'tr\'), '.$subTable->asJavaScriptConfig().', function (data) { data.childTable.jtable(\'load\'); }); }); return img; }';
		// set display
		$this->setDisplay($display);
		$this->setChildTable(true);
	}
}

?>
