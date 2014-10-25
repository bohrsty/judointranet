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
class Jtable extends Object {
	
	
	/*
	 * class-variables
	 */
	private $settingName;
	private $settingValue;
	private $settingQuote;
	private $fields;
	private $validate;
	
	/*
	 * getter/setter
	 */
	public function getValidate() {
		return $this->validate;
	}
	public function setValidate($validate) {
		$this->validate = $validate;
	}
	
	/*
	 * constructor/destructor
	 */
	public function __construct() {
		
		// setup parent
		parent::__construct();
		
		// prepare settings
		$this->settingName = array();
		$this->settingValue = array();
		$this->settingQuote = array();
		$this->fields = array();
		// prepare validation
		$this->setValidate(false);
		
		// set default settings
		$this->setSetting('columnResizable', true);
		$this->setSetting('columnSelectable', false);
		$this->setSetting('dialogShowEffect', 'fade');
		$this->setSetting('dialogHideEffect', 'fade');
		$this->setSetting('jqueryuiTheme', true);
		$this->setSetting('multiSorting', true);
		$this->setSetting('paging', true);
		$this->setSetting('pageSize', 20);
		$this->setSetting('sorting', true);
	}
	
	/*
	 * methods
	 */
	/**
	 * asJavaScriptConfig() returns the object as java script config string
	 * 
	 * @return string jTable as java script config
	 */
	public function asJavaScriptConfig() {
		
		// check validation and add settings
		if($this->getValidate() === true) {
			$this->setSetting('formCreated', 'function(event, data) { data.form.validationEngine(\'attach\', { promptPosition : \'topLeft\'}); }', false);
			$this->setSetting('formSubmitting', 'function(event, data) { return data.form.validationEngine(\'validate\'); }', false);
			$this->setSetting('formClosed', 'function(event, data) { data.form.validationEngine(\'hide\'); data.form.validationEngine(\'detach\'); }', false);
		}
		
		// generate "java script"
		$jScript = '{';
		
		// walk through settings
		foreach($this->settingName as $setting => $nameValue) {
			$jScript .= $this->getSetting($setting).',';
		}
		
		// add fields
		$jScript .= $this->getFields();
		
		// close }
		$jScript .= '}';
		
		// return
		return $jScript;
	}
	
	
	// set setting
	public function setSetting($setting, $value, $quote = true) {
		
		// set values
		$this->settingName[$setting] = $setting;
		$this->settingValue[$setting] = $value;
		$this->settingQuote[$setting] = $quote;
		
		// do not quote boolean value
		if(is_bool($value)) {
			$this->settingQuote[$setting] = false;
		}
	}
	// get setting
	public function getSetting($setting) {
		
		// check $setting value and name for valid JSON characters
		$servicesJson = new Services_JSON();
		$name = $servicesJson->encode($this->settingName[$setting]);
		$value = $servicesJson->encode($this->settingValue[$setting]);
		
		// check quoting
		if($this->settingQuote[$setting] === false) {
			return $name.':'.(gettype($this->settingValue[$setting]) == 'string' ? substr($value, 1, -1) : $value);
		} else {
			return $name.':'.$value;
		}
	}
	
	// set api urls
	public function setActions($apiBase, $provider, $create = true, $update = true, $delete = true) {
		
		// collect data for signature
		$data = array(
				'apiClass' => 'JTable',
				'apiBase' => $apiBase,
				'time' => time(),
			);
		$_SESSION['api'] = $data;
		$signedApi = base64_encode(hash_hmac('sha256', json_encode($data), $this->getGc()->get_config('global.apikey')));
		
		// set urls
		$actions = array();
		$actions['listAction'] = 'api/internal.php?signedApi='.$signedApi.'&action=list&provider='.$provider;
		if($create === true) {
			$actions['createAction'] = 'api/internal.php?signedApi='.$signedApi.'&action=create&provider='.$provider;
		}
		if($update === true) {
			$actions['updateAction'] = 'api/internal.php?signedApi='.$signedApi.'&action=update&provider='.$provider;
		}
		if($delete === true) {
			$actions['deleteAction'] = 'api/internal.php?signedApi='.$signedApi.'&action=delete&provider='.$provider;
		}
		
		// set settings
		$this->setSetting('actions', $actions, false);
	}
	
	// add fields (as associative array)
	public function addField($field) {
		
		// add field to settings
		$this->fields[$field->getName()] = $field->asArray();
		
		// check validation
		$this->setValidate($this->getValidate() || $field->getValidate());
	}
	public function getFields() {
		return '"fields":'.json_encode($this->fields);
	}
}

?>
