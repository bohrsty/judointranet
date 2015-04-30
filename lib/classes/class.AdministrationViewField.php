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
 * class AdministrationViewField implements the control of the id "field" administration page
 */
class AdministrationViewField extends AdministrationView {
	
	/*
	 * class-variables
	 */
	private $smarty;
	
	/*
	 * getter/setter
	 */
	
	/*
	 * constructor/destructor
	 */
	public function __construct() {
		
		// setup parent
		parent::__construct();
		
		// create smarty object
		$this->smarty = new JudoIntranetSmarty();
		
		// activate validationEngine
		$this->getTpl()->assign('validationEngine', true);
	}
	
	
	/**
	 * show() generates the output of the page
	 * 
	 * @return string output for the page to be added to the template
	 */
	public function show() {
		
		// get all user tables
		$usertables = $this->getUsertables();
		sort($usertables);
		// add club if permitted
		if($this->getUser()->isMemberOf(2)) {
			array_unshift($usertables, 'club');
		}
		// add file_type if admin
		if($this->getUser()->isAdmin()) {
			array_unshift($usertables, 'file_type');
		}
		// add defaults
		array_unshift($usertables, 'defaults');
		
		// activate tabs
		$this->getTpl()->assign('tabsJs', true);
		
		// prepare tabs
		$tabs = array();
		foreach($usertables as $id => $name) {
			$tabs[$id] = array(
					'tab' => _l($name),
					'content' => $this->getFields($name),
				);
		}
		
		// assign
		$this->smarty->assign('data', $tabs);
		
		// return		
		return $this->smarty->fetch('smarty.admin.usertableTabs.tpl');
	}
	
	
	/**
	 * getFields($table) generates the table for the $table usertable
	 * 
	 * @param string $table name of the usertable
	 * @return string HTML string of the generated field table
	 */
	private function getFields($table) {
		
		// get table config
		$tableConfig = $this->getTableConfig($table);
		// get column names as array
		$colums = array_merge(array('id'), explode(',', $tableConfig['cols']));
		
		// define div id for container
		$containerId = 'usertable'.ucfirst($table).'Table';
		
		// prepare valid image
		$sValidImg = new JudoIntranetSmarty();
		$imgArray = array(
				'params' => 'class="icon" title="'._l('validity').'"',
				'src' => 'img/admin_enabled.png',
				'alt' => _l('validity'),
			);
		$sValidImg->assign('img', $imgArray);
		$validImg = $sValidImg->fetch('smarty.img.tpl');
		
		// get Jtable object
		$jtable = new Jtable();
		$jtable->setSetting('title', _l($table));
		$jtable->setSetting('toolbar', '{items: [{icon: \'img/jtable_refresh.png\', text: \''._l('Refresh this table').'\', click: function() {$(\'#'.$containerId.'\').jtable(\'reload\')}}]}', false);
		
		// set settings
		$jtable->setActions('administration.php', 'UsertableField', true, true, true, array('table' => $table));

		// walk through colums
		foreach($colums as $colum) {
			
			// check translation
			$translatedColumn = $colum;
			if(_l('usertable row '.$colum) != 'usertable row '.$colum) {
				$translatedColumn = _l('usertable row '.$colum);
			}
			
			// create object
			$jtField = new JtableField($colum);
			$jtField->setTitle($translatedColumn);
			
			// check id field
			if($colum == 'id') {
				$jtField->setList(false);
				$jtField->setEdit(false);
				$jtField->setCreate(false);
				$jtField->setKey(true);
			}
			
			// check valid field
			if($colum == 'valid') {
				$jtField->setTitle($validImg);
				$jtField->setWidth('0.1%');
				$jtField->setCreate(false);
				$jtField->setEdit(true);
				$jtField->setSorting(false);
				$jtField->setDisplay('function(data) {var value = data.record.valid; var status = {\'false\':\'disabled\', \'true\':\'enabled\'}; var title = {\'false\':\''._l('disabled').'\', \'true\':\''._l('enabled').'\'}; return \'<img src="img/admin_\'+status[value]+\'.png" alt="\'+title[value]+\'" title="\'+title[value]+\'" />\'; }');
				$jtField->setType('checkbox', array(
						'false' => _l('disabled'),
						'true' => _l('enabled'),
					));
			}
			
			// check foreign key field
			if(isset($tableConfig['fk'][$colum]) && is_array($tableConfig['fk'][$colum])) {
				$jtField->setType('combobox', $tableConfig['fk'][$colum]);
			}
			
			// check type of field
			if(isset($tableConfig['fieldType'][$colum]) && $tableConfig['fieldType'][$colum] != '') {
				$jtField->setType($tableConfig['fieldType'][$colum]);
				// check textarea
				if($tableConfig['fieldType'][$colum] == 'textarea') {
					$jtField->addInputClass('usertableTextarea');
				}
			}
			
			// validate field
			if($colum != 'valid') {
				$jtField->validateAgainst('required');
			}
			
			// add field
			$jtable->addField($jtField);
		}
		
		// get java script config
		$jtableJscript = $jtable->asJavaScriptConfig();
		
		// add surrounding javascript
		$jquery = '$("#'.$containerId.'").jtable('.$jtableJscript.');';
		// add to jquery
		$this->add_jquery($jquery);
		$this->add_jquery('$("#'.$containerId.'").jtable("load");');
		
		// enable jtable in template
		$this->getTpl()->assign('jtable', true);
		
		// return
		return $this->getContentBeforeTable($table).'<div id="'.$containerId.'" class="jTable"></div>';
	}
	
	
	/**
	 * getContentBeforeTable($table) generates the output of the content before the table
	 * 
	 * @param string $table name of the table
	 * @return string output for the content before the table
	 */
	private function getContentBeforeTable($table) {
		
		// prepare return
		$return = '';
		
		// check table
		if($table == 'judo' && $this->getUser()->hasPermission('navi', 58, 'w')) {
			// prepare smarty template
			$data = array(
					'p' => array(
							'params' => '',
							'contentBefore' => '',
							'contentAfter' => '&nbsp;'.$this->helpButton(HELP_MSG_ADMINNEWYEAR),
						),
					'a' => array(
							'params' => '',
							'href' => 'administration.php?id=newyear&table=judo',
							'title' => _l('Create new year'),
							'content' => _l('Create new year'),
						),
				);
			$this->smarty->assign('pa', $data);
			$return = $this->smarty->fetch('smarty.pa.tpl');
		}
		
		// return		
		return $return;
	}
}

?>
