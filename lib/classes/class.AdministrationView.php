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
 * class AdministrationView implements the control of the administration-pages
 */
class AdministrationView extends PageView {
	
	/*
	 * class-variables
	 */
	
	/*
	 * getter/setter
	 */
	
	/*
	 * constructor/destructor
	 */
	public function __construct() {
		
		// setup parent
		try {
			parent::__construct();
		} catch(Exception $e) {
			
			// handle error
			$this->getError()->handle_error($e);
		}
	}
	
	/*
	 * methods
	 */
	/**
	 * init chooses the functionality by using $_GET['id']
	 * 
	 * @return void
	 */
	public function init() {
		
		// set pagename
		$this->getTpl()->assign('pagename',parent::lang('administration'));
		
		// init helpmessages
		$this->initHelp();
		
		// switch $_GET['id'] if set
		if($this->get('id') !== false) {
			
			// check permissions
			$naviId = Navi::idFromFileParam(basename($_SERVER['SCRIPT_FILENAME']), $this->get('id'));
			if($this->getUser()->hasPermission('navi', $naviId)) {
				
				switch($this->get('id')) {
					
					case 'field':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(parent::lang('administration: manage user tables')));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
						$this->getTpl()->assign('main', $this->field());
					break;
					
					case 'defaults':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(parent::lang('administration: manage defaults')));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
						$this->getTpl()->assign('main', $this->defaults());
					break;
					
					case 'user':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(parent::lang('administration: manage users and permissions')));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
						$this->getTpl()->assign('main', $this->useradmin());
					break;
					
					case 'club':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(parent::lang('administration: manage clubs')));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
						$this->getTpl()->assign('main', $this->clubadmin());
					break;
					
					default:
						
						// id set, but no functionality
						$errno = $this->getError()->error_raised('GETUnkownId','entry:'.$this->get('id'),$this->get('id'));
						$this->getError()->handle_error($errno);
						
						// smarty
						$this->getTpl()->assign('title', '');
						$this->getTpl()->assign('main', $this->getError()->to_html($errno));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', false);
					break;
				}
			} else {
				
				// error not authorized
				throw new NotAuthorizedException($this);
			}
		} else {
			
			// id not set
			// smarty-title
			$this->getTpl()->assign('title', $this->title(parent::lang('administration'))); 
			// smarty-main
			$this->getTpl()->assign('main', $this->defaultContent());
			// smarty-jquery
			$this->getTpl()->assign('jquery', true);
			// smarty-hierselect
			$this->getTpl()->assign('zebraform', false);
		}
		
		// global smarty
		$this->showPage('smarty.admin.tpl');
	}
	
	
	
	
	
	
	
	/**
	 * field handles the administration of fields (i.e. user-defined dbs)
	 * 
	 * @return string html-string with the field-administration-page
	 */
	private function field() {
		
		// prepare content
		$content = '';
		
		// check $_GET['field']
		if($this->get('field') !== false) {
			
			// translate table name
			if(parent::lang('<b>'.$this->get('field').'</b>') != '<b>'.$this->get('field').'</b>') {
				$translatedField = parent::lang('<b>'.$this->get('field').'</b>');
			} else {
				$translatedField = $this->get('field');
			}
			
			// set caption
			$this->getTpl()->assign('caption', parent::lang('manage table').$translatedField.' ("'.$this->get('field').'")');
			
			// check if 'field' exists
			if($this->checkUsertable($this->get('field')) !== false) {
				
				$content .= $this->manageTable();
			} else {
				$errno = $this->getError()->error_raised('UsertableNotExists',$this->get('field'));
				$this->getError()->handle_error($errno);
				return $this->getError()->to_html($errno);
			}
		} else {
			
			// set caption
			$this->getTpl()->assign('caption', parent::lang('manage user tables'));
		}
		
		// smarty
		$this->getTpl()->assign('tablelinks', $this->createTableLinks());
		
		// return
		return $content;
	}
	
	
	
	
	
	
	
	/**
	 * createTableLinks() creates the links to choose the table to manage
	 * 
	 * @return string html-string with the table-links
	 */
	private function createTableLinks() {
		
		// smarty-templates
		$sTl = new JudoIntranetSmarty();
		
		// prepare return
		$return = '';
		
		// get usertables
		$usertables = $this->getUsertables();
		
		// create links
		$a_out = '';
		// smarty
		$sTl->assign('class', 'class="usertable"');
		foreach($usertables as $table) {
			
			// check table
			if($this->get('field') === false || $this->get('field') != $table) {
			
				// translate table name
				if(parent::lang('<b>'.$table.'</b>') != '<b>'.$table.'</b>') {
					$translatedTable = parent::lang('<b>'.$table.'</b>');
				} else {
					$translatedTable = '<b>'.$table.'</b>';
				}
				// smarty
				$data[] = array(
						'params' => 'class="usertable"',
						'href' => 'administration.php?id='.$this->get('id').'&field='.$table,
						'title' => $translatedTable.' '.parent::lang('manage'),
						'content' => $translatedTable.' '.parent::lang('manage')
					);
			}
		}
		
		$sTl->assign('data', $data);
		
		// add slider-link
		// smarty
		$link = array(
				'title' => parent::lang('toggle table selection'),
				'content' => parent::lang('toggle table selection'),
				'params' => 'id="toggleTable"',
				'help' => $this->helpButton(HELP_MSG_ADMINUSERTABLESELECT),
			);
		$sTl->assign('link', $link);
		
		// add jquery
		$sToggleSlide = new JudoIntranetSmarty();
		$sToggleSlide->assign('id', '#toggleTable');
		$sToggleSlide->assign('toToggle', '#tablelinks');
		$sToggleSlide->assign('time', '');
		$this->add_jquery($sToggleSlide->fetch('smarty.js-toggleSlide.tpl'));
		
		// return
		return $sTl->fetch('smarty.admin.table_links.tpl');
	}
	
	
	
	
	
	
	
	/**
	 * getUsertables() returns an array containing all usertables
	 * 
	 * @return array array containing all user-editable tables
	 */
	private function getUsertables() {
		
		// get db-object
		$db = Db::newDb();
		
		// get all fields to administer
		// get systemtables
		$systemtables = explode(',',$this->getGc()->get_config('systemtables'));
		
		// get user tables
		$usertables = array();
		
		// prepare statement
		$sql = 'SHOW TABLES';
		
		// execute query
		$result = $db->query($sql);
		
		// check result
		if($result) {
			while(list($table) = $result->fetch_array(MYSQL_NUM)) {
				// check systemtable
				if(!in_array($table,$systemtables)) {
					$usertables[] = $table;
				}
			}
		} else {
			$errno = $this->getError()->error_raised('MysqlError', $db->error, $sql);
			$this->getError()->handle_error($errno);
		}
		
		// return
		return $usertables;
	}
	
	
	
	
	
	
	
	/**
	 * checkUsertable() checks if the given tablename exists in db
	 * 
	 * @return boolean true if given table exists, false otherwise
	 */
	private function checkUsertable($table) {
		
		// get tables
		$usertables = $this->getUsertables();
		
		// check if $table in $usertable
		if(in_array($table,$usertables,true)) {
			return true;
		} else {
			return false;
		}
	}
	
	
	
	
	
	
	
	/**
	 * listTableContent($table_name, $page) returns the content of the table as HTML-string 
	 * 
	 * @param string $table name of the table to list
	 * @param int $page number of page to list
	 * @return string table content as HTML
	 */
	private function listTableContent($table_name,$page) {
		
		// smarty-template
		$sTc = new JudoIntranetSmarty();
		
		// get url-parameters
		if($table_name == 'defaults') {
			$preface = 'administration.php?id='.$this->get('id');
		} else {
			$preface = 'administration.php?id='.$this->get('id').'&field='.$table_name;
		}
		$sTc->assign('preface', $preface);
		
		// prepare return
		$return = '';
		
		// get table config
		$tableConfig = $this->getTableConfig($table_name);
		
		// smarty
		$newlink = array(
				'params' => 'id="newLink"',
				'href' => $preface.'&action=new',
				'title' => parent::lang('add new entry in table'),
				'content' => parent::lang('add new entry in table')
			);
		$sTc->assign('newlink', $newlink);
		
		// get db-object
		$db = Db::newDb();
		
		// get pagelinks
		list($page, $pagelinks) = $this->pageLinks($table_name, $page);
		
		// assign pagelink data
		$sTc->assign('pagelinks', $pagelinks);
		
		// check if table has "usertableShow.$table" entry
		$configUsertableCols = $tableConfig['cols'];
		if($configUsertableCols === false || $configUsertableCols == '') {
			$usertableCols = array();
			$skipUsertableCols = false;
		} else {
			$usertableCols = explode(',', $configUsertableCols);
			$skipUsertableCols = true;
		}
				
		// prepare statement
		$pagesize = $this->getGc()->get_config('pagesize');
		$limit = $page * $pagesize;
		$sql = 'SELECT *
				FROM '.$db->real_escape_string($table_name).'
				'.$tableConfig['orderBy'].'
				LIMIT '.$db->real_escape_string($limit).','.$db->real_escape_string($pagesize);
		
		// execute
		$result = $db->query($sql);
		
		// fetch result
		$index = $index2 = 0;
		$data = array();
		if($result) {
			// get table info
			$tinfo = $result->fetch_fields();
			
			// prepare th
			$i = 1;
			$data[0]['th'][0]['content'] = parent::lang('tasks').'&nbsp;'.$this->helpButton(HELP_MSG_ADMINUSERTABLETASKS);
			foreach($tinfo as $col) {
				
				// check usertableCols
				if(!in_array($col->name, $usertableCols) && $skipUsertableCols) {
					continue;
				}
				// check translation
				$translated_name = '';
				if(parent::lang('usertable row '.$col->name) != 'usertable row '.$col->name) {
					$translated_name = parent::lang('usertable row '.$col->name);
				} else {
					$translated_name = $col->name;
				}
				// smarty
				$data[0]['th'][$i]['content'] = $translated_name;
							
				// increment counter
				$i++;
			}
			
			while($row = $result->fetch_array(MYSQL_ASSOC)) {
				
				$index2 = 1;
				
				// add edit
				// smarty
				$data[$index]['td'][0]['edit'] = array(
						'src' => 'img/admin_edit.png',
						'alt' => parent::lang('edit'),
						'href' => $preface.'&action=edit&rid='.$row['id'],
						'title' => parent::lang('edit').': '.$row['id']
					);
				
				// add disable/enable
				// check status
				$status = '';
				if($row['valid'] == 0) {
					$status = 'enable';
					$actualStatus = 'disabled';
				} else {
					$status = 'disable';
					$actualStatus = 'enabled';
				}
				// smarty
				$data[$index]['td'][0]['disenable'] = array(
						'src' => 'img/admin_'.$status.'.png',
						'alt' => parent::lang($status),
						'href' => $preface.'&action='.$status.'&rid='.$row['id'],
						'title' => parent::lang($status).': '.$row['id']
					);
				// add delete
				// smarty
				$data[$index]['td'][0]['delete'] = array(
						'src' => 'img/admin_delete.png',
						'alt' => parent::lang('delete'),
						'href' => $preface.'&action=delete&rid='.$row['id'],
						'title' => parent::lang('delete').': '.$row['id']
					);
				
				// walk through $row
				foreach($row as $name => $value) {
					
					// check usertableCols
					if(!in_array($name, $usertableCols) && $skipUsertableCols) {
						continue;
					}
					
					// set escape to true maybe overridden later
					$data[$index]['td'][$index2]['escape'] = true;
					
					// check category
					if($name == 'category') {
						
						// get name for category
						$cat_sql = "SELECT name FROM category WHERE id=$value";
						$cat_result = $db->query($cat_sql);
						list($value) = $cat_result->fetch_array(MYSQL_NUM);
					} elseif($name == 'valid') {
						
						// set value to according img
						$validImg = new JudoIntranetSmarty();
						$validImg->assign('status', $actualStatus);
						$validImg->assign('statusTranslated', parent::lang($actualStatus));
						$value = $validImg->fetch('smarty.admin.valid_img.tpl');
						$data[$index]['td'][$index2]['escape'] = false;
					}
					
					// value
					if(isset($tableConfig['fk'][$name]) && is_array($tableConfig['fk'][$name])) {
						$data[$index]['td'][$index2]['content'] = $tableConfig['fk'][$name][$value];
					} else {
						$data[$index]['td'][$index2]['content'] = $value;
					}
					
					// increment index2
					$index2++;
				}
				
				// increment index
				$index++;
			}
		} else {
			$errno = $this->getError()->error_raised('MysqlError', $db->error, $sql);
			$this->getError()->handle_error($errno);
		}
		
		
		$sTc->assign('data', $data);
		
		// return
		return $sTc->fetch('smarty.admin.table_content.tpl');
	}
	
	
	
	
	
	
	
	/**
	 * isValid($table, $rid) returns true if given row is valid, false otherwise
	 * 
	 * @param string $table table to check
	 * @param int $rid id of row to check
	 * @return boolean true if row->valid == 1, false otherwise
	 */
	private function isValid($table,$rid) {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare statement
		$sql = 'SELECT valid
				FROM '.$db->real_escape_string($table).'
				WHERE id='.$db->real_escape_string($rid);
		
		// execute
		$result = $db->query($sql);
		
		// fetch result
		$valid = '';
		if($result) {
			list($valid) = $result->fetch_array(MYSQL_NUM);
		} else {
			$errno = $this->getError()->error_raised('MysqlError', $db->error, $sql);
			$this->getError()->handle_error($errno);
		}
		
		// return
		if($valid == 1) {
			return true;
		} else {
			return false;
		}
	}
	
	
	
	
	
	
	
	/**
	 * rowExists($table, $rid) returns true if given row exists, false otherwise
	 * 
	 * @param string $table table to work on
	 * @param int $rid id of row to check
	 * @return boolean true if row exists, false otherwise
	 */
	private function rowExists($table,$rid) {
		
		// check if $rid given
		if($rid !== false) {
			
			// get db-object
			$db = Db::newDb();
			
			// prepare statement
			$sql = 'SELECT *
					FROM '.$db->real_escape_string($table).'
					WHERE id='.$db->real_escape_string($rid);
			
			// execute
			$result = $db->query($sql);
			
			// check result
			if($result) {
				// get num_rows
				if($result->num_rows == 1) {
					return true;
				} else {
					return false;
				}
			} else {
				$errno = $this->getError()->error_raised('MysqlError', $db->error, $sql);
				$this->getError()->handle_error($errno);
			}
		} else {
			return true;
		}
	}
	
	
	
	
	
	
	
	/**
	 * setValid($table, $rid, $valid) sets valid for the given rid
	 * 
	 * @param string $table table to work on
	 * @param int $rid id of row to set valid
	 * @param int $valid the value to set valid to
	 */
	private function setValid($table,$rid,$valid) {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare statement
		$sql = 'UPDATE '.$db->real_escape_string($table).'
				SET valid=\''.$db->real_escape_string($valid).'\'
				WHERE id='.$db->real_escape_string($rid);
		
		// execute
		$result = $db->query($sql);
		
		// check result
		if(!$result) {
			$errno = $this->getError()->error_raised('MysqlError', $db->error, $sql);
			$this->getError()->handle_error($errno);
		}
	}
	
	
	
	
	
	
	
	/**
	 * deleteRow($table, $rid) deletes the row for the given rid
	 * 
	 * @param string $table table to work on
	 * @param int $rid id of row to be deleted
	 * @return string HTML-String containing the confirmation form or message
	 */
	private function deleteRow($table,$rid) {
		
		// smarty-templates
		$sConfirmation = new JudoIntranetSmarty();
		
		// get url-parameters
		$link = '';
		if($table == 'defaults') {
			$link = 'administration.php?id='.$this->get('id');
		} else {
			$link = 'administration.php?id='.$this->get('id').'&field='.$table;
		}
		
		// create form
		$form = new Zebra_Form(
				'formConfirm',	// id/name
				'post',					// method
				$link.'&action=delete&rid='.$rid		// action
			);
		// set language
		$form->language('deutsch');
		// set docktype xhtml
		$form->doctype('xhtml');
		
		// add button
		$form->add(
				'submit',		// type
				'buttonSubmit',	// id/name
				parent::lang('yes'),	// value
				array('title' => parent::lang('deletes the entry'))
			);
		
		// smarty-link
		$cancellink = array(
						'params' => 'class="submit"',
						'href' => $link,
						'title' => parent::lang('cancel'),
						'content' => parent::lang('cancel')
					);
		$sConfirmation->assign('link', $cancellink);
		$sConfirmation->assign('spanparams', 'id="cancel"');
		$sConfirmation->assign('message', parent::lang('you really want to completely delete this row'));
		$sConfirmation->assign('form', $form->render('', true));
		
		// validate
		if($form->validate()) {
		
			// get db-object
			$db = Db::newDb();
			
			// prepare statement
			$sql = 'DELETE FROM '.$db->real_escape_string($table).' WHERE id=\''.$db->real_escape_string($rid).'\'';
			
			// execute
			$result = $db->query($sql);
			
			// check result
			if(!$result) {
				$errno = $this->getError()->error_raised('MysqlError', $db->error, $sql);
				$this->getError()->handle_error($errno);
			}
			
			// smarty
			$sConfirmation->assign('message', parent::lang('successful deleted row completely'));
			$sConfirmation->assign('form', '');
			
			// smarty return
			$return = $sConfirmation->fetch('smarty.confirmation.tpl');
			
			// add table content
			$return .= $this->listTableContent($table,$this->get('page'));
			
			// return
			return $return;
		} else {
			
			// smarty return
			return $sConfirmation->fetch('smarty.confirmation.tpl');
		}
	}
	
	
	
	
	
	
	
	/**
	 * editRow($table, $rid) edits the row for the given rid
	 * 
	 * @param string $table table to work on
	 * @param int $rid id of row to edit
	 * @return string HTML-string for the form or message
	 */
	private function editRow($table,$rid) {
		
		// prepare return
		$return = '';
		
		// get table config
		$tableConfig = $this->getTableConfig($table);
		
		// get url-parameters
		$link = '';
		if($table == 'defaults') {
			$link = 'administration.php?id='.$this->get('id');
		} else {
			$link = 'administration.php?id='.$this->get('id').'&field='.$table;
		}
		
		// get db-object
		$db = Db::newDb();
		
		// prepare statement
		$sql = 'SELECT *
				FROM '.$db->real_escape_string($table).'
				WHERE id='.$db->real_escape_string($rid).'
				LIMIT 1';
		
		// execute
		$result = $db->query($sql);
		
		// table info and row
		$row = array();
		$tinfo = null;
		if($result) {
			$row = $result->fetch_array(MYSQL_ASSOC);
			$tinfo = $result->fetch_fields();
		} else {
			$errno = $this->getError()->error_raised('MysqlError', $db->error, $sql);
			$this->getError()->handle_error($errno);
		}
		
		// extract tinfo
		$tableInfo = array();
		foreach($tinfo as $col) {
			$tableInfo[$col->name] = $col;
		}
		
		// prepare form
		$form = new Zebra_Form(
				'edit'.ucfirst($table),	// id/name
				'post',					// method
				$link.'&action=edit&rid='.$rid		// action
			);
		// set language
		$form->language('deutsch');
		// set docktype xhtml
		$form->doctype('xhtml');
		
		// get values and fields
		foreach($row as $col => $value) {
			
			// check translation
			$translated_col = '';
			if(parent::lang('usertable row '.$col) != 'usertable row '.$col) {
				$translated_col = parent::lang('usertable row '.$col);
			} else {
				$translated_col = $col;
			}
			
			// check id
			if($col != 'id') {
				
				// col->type
				// 252 = text, 253 = varchar; 1 = tinyint(boolean); 3 = int
				
				// add field
				$field = null;
				// check category
				if($col == 'category') {
					
					// get options
					$cat_sql = 'SELECT id,name
								FROM category
								WHERE valid=1';
					
					// execute
					$result = $db->query($cat_sql);
					
					// get data
					$options = array();
					if($result) {
						while(list($id,$name) = $result->fetch_array(MYSQL_NUM)) {
							$options[$id] = $name;
						}
					} else {
						$errno = $this->getError()->error_raised('MysqlError', $db->error, $sql);
						$this->getError()->handle_error($errno);
					}
					
					// select
					$formIds[$col] = array('valueType' => 'int', 'type' => 'select',);
					$form->add(
							'label',		// type
							'label'.ucfirst($col),	// id/name
							$col,		// for
							$translated_col.':'	// label text
						);
					$field = $form->add(
							$formIds[$col]['type'],	// type
							$col,	// id/name
							$row[$col],			// default
							array(		// attributes
								)
						);
					$field->add_options($options);
					if($table == 'defaults') {
						$field->set_rule(
								array(
										'required' => array(
												'error', parent::lang('required field')
											),
									)
							);
					}
				} else {
					
					// check forein keys
					if(isset($tableConfig['fk'][$col]) && is_array($tableConfig['fk'][$col])) {
						
						// select
						$formIds[$col] = array('valueType' => 'int', 'type' => 'select',);
						$form->add(
								'label',		// type
								'label'.ucfirst($col),	// id/name
								$col,		// for
								$translated_col.':'	// label text
							);
						$field = $form->add(
								$formIds[$col]['type'],	// type
								$col,	// id/name
								$row[$col],			// default
								array(		// attributes
									)
							);
						$field->add_options($tableConfig['fk'][$col]);
					} else {
						
						// check type
						if($tableInfo[$col]->type == 252) {
							
							// textarea
							$formIds[$col] = array('valueType' => 'string', 'type' => 'textarea',);
							$form->add(
									'label',		// type
									'label'.ucfirst($col),	// id/name
									$col,				// for
									$translated_col,	// label text
									array('inside' => true)
								);
							$field = $form->add(
									$formIds[$col]['type'],		// type
									$col,	// id/name
									$row[$col]	// default
								);
							$rules['regexp'] = 
								array(
										$this->getGc()->get_config('textarea.regexp.zebra'),
										'error',
										parent::lang('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
									);
							if($table == 'defaults') {
								$rules['required'] = 
									array(
											'error',
											parent::lang('required field selection!'),
										);
							}
							$field->set_rule($rules);
						} elseif($tableInfo[$col]->type == 253 || $tableInfo[$col]->type == 3) {
							
							// input
							$formIds[$col] = array('valueType' => 'string', 'type' => 'text',);
							$form->add(
									'label',		// type
									'label'.ucfirst($col),	// id/name
									$col,			// for
									$translated_col,	// label text
									array('inside' => true,)	// label inside
								);
							$field = $form->add(
											$formIds[$col]['type'],		// type
											$col,		// id/name
											$row[$col]	// defaults
								);
							
							// add rules
							$rules['regexp'] = 
								array(
										$this->getGc()->get_config('textarea.regexp.zebra'),
										'error',
										parent::lang('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
									);
							if($table == 'defaults') {
								$rules['required'] = 
									array(
											'error',
											parent::lang('required field selection!'),
										);
							}
							$field->set_rule($rules);
						} elseif($tableInfo[$col]->type == 1) {
							
							// input
							$formIds[$col] = array('valueType' => 'int', 'type' => 'checkbox', 'default' => 1);
							$form->add(
									'label',		// type
									'label'.ucfirst($col),	// id/name
									$col,			// for
									$translated_col.':'	// label text
								);
							$public = $form->add(
									$formIds[$col]['type'],		// type
									$col,						// id/name
									'1',							// value
									($row[$col] == 1 ? array('checked' => 'checked') : null)	// default
								);
						}
					}
				}
			}
		}
		
		// submit-button
		$form->add(
				'submit',		// type
				'buttonSubmit',	// id/name
				parent::lang('save')	// value
			);
		
		// validate
		if($form->validate()) {
			
			// set output
			$return .= $this->p('class="edit_caption"',parent::lang('successful changed row'));
			
			// get data
			$data = $this->getFormValues($formIds);
			
			// prepare statement
			$sql = 'UPDATE '.$db->real_escape_string($table).' SET ';
			foreach($data as $field => $value) {
				
				// check translation
				$translated_field = '';
				if(parent::lang('usertable row '.$field) != 'usertable row '.$field) {
					$translated_field = parent::lang('usertable row '.$field);
				} else {
					$translated_field = $field;
				}
				
				// add fields to sql
				$sql .= $db->real_escape_string($field).'=\''.$db->real_escape_string($value).'\', ';
				
				// add fields to output
				if(isset($tableConfig['fk'][$field]) && is_array($tableConfig['fk'][$field])) {
					$return .= $this->p('', $translated_field.' = \''.nl2br(htmlentities($tableConfig['fk'][$field][$value])).'\'');
				} else {
					$return .= $this->p('', $translated_field.' = \''.nl2br(htmlentities($value)).'\'');
				}
			}
			
			$sql = substr($sql,0,-2).' WHERE id='.$db->real_escape_string($rid);
			
			// execute
			$result = $db->query($sql);
			
			// check result
			if(!$result) {
				$errno = $this->getError()->error_raised('MysqlError', $db->error, $sql);
				$this->getError()->handle_error($errno);
			}
			
			// add table content
			$return .= $this->listTableContent($table,$this->get('page'));
		} else {
			$return .= $this->p('',parent::lang('insert row'));
			$return .= $form->render('', true);
		}
		
		// close db
		$db->close();
		
		// return
		return $return;
	}
	
	
	
	
	
	
	
	/**
	 * newRow($table) inserts a new row in $table
	 * 
	 * @param string $table table to insert row
	 * @return string HTML-string for the form or message
	 */
	private function newRow($table) {
		
		// prepare return
		$return = '';
		
		// get table config
		$tableConfig = $this->getTableConfig($table);
		
		// get url-parameters
		$link = '';
		if($table == 'defaults') {
			$link = 'administration.php?id='.$this->get('id');
		} else {
			$link = 'administration.php?id='.$this->get('id').'&field='.$table;
		}
		
		// get db-object
		$db = Db::newDb();
		
		// prepare statement
		$sql = 'SELECT *
				FROM '.$db->real_escape_string($table).'
				LIMIT 1';
		
		// execute
		$result = $db->query($sql);
		
		// table info
		$tinfo = null;
		if($result) {
			$tinfo = $result->fetch_fields();
		} else {
			$errno = $this->getError()->error_raised('MysqlError', $db->error, $sql);
			$this->getError()->handle_error($errno);
		}
		
		// prepare form
		$form = new Zebra_Form(
				'new'.ucfirst($table),	// id/name
				'post',					// method
				$link.'&action=new'		// action
			);
		// set language
		$form->language('deutsch');
		// set docktype xhtml
		$form->doctype('xhtml');
		
		// get values and fields
		foreach($tinfo as $col) {
			
			// check translation
			$translated_col = '';
			if(parent::lang('usertable row '.$col->name) != 'usertable row '.$col->name) {
				$translated_col = parent::lang('usertable row '.$col->name);
			} else {
				$translated_col = $col->name;
			}
			
			// check id
			if($col->name != 'id') {
				
				// col->type
				// 252 = text, 253 = varchar; 1 = tinyint(boolean); 3 = int
				
				// add field
				$field = null;
				// check category
				if($col->name == 'category') {
					
					// get options
					$cat_sql = 'SELECT id,name
								FROM category
								WHERE valid=1';
					
					// execute
					$result = $db->query($cat_sql);
					
					// get data
					$options = array();
					if($result) {
						while(list($id,$name) = $result->fetch_array(MYSQL_NUM)) {
							$options[$id] = $name;
						}
					} else {
						$errno = $this->getError()->error_raised('MysqlError', $db->error, $sql);
						$this->getError()->handle_error($errno);
					}
					
					// select
					$formIds[$col->name] = array('valueType' => 'int', 'type' => 'select',);
					$form->add(
							'label',		// type
							'label'.ucfirst($col->name),	// id/name
							$col->name,		// for
							$translated_col.':'	// label text
						);
					$field = $form->add(
							$formIds[$col->name]['type'],	// type
							$col->name,	// id/name
							'',			// default
							array(		// attributes
								)
						);
					$field->add_options($options);
					if($table == 'defaults') {
						$field->set_rule(
								array(
										'required' => array(
												'error', parent::lang('required field')
											),
									)
							);
					}
				} else {
					
					// check forein keys
					if(isset($tableConfig['fk'][$col->name]) && is_array($tableConfig['fk'][$col->name])) {
						
						// select
						$formIds[$col->name] = array('valueType' => 'int', 'type' => 'select',);
						$form->add(
								'label',		// type
								'label'.ucfirst($col->name),	// id/name
								$col->name,		// for
								$translated_col.':'	// label text
							);
						$field = $form->add(
								$formIds[$col->name]['type'],	// type
								$col->name,	// id/name
								'',			// default
								array(		// attributes
									)
							);
						$field->add_options($tableConfig['fk'][$col->name]);
					} else {
						
						// check type
						if($col->type == 252) {
							
							// textarea
							$formIds[$col->name] = array('valueType' => 'string', 'type' => 'textarea',);
							$form->add(
									'label',		// type
									'label'.ucfirst($col->name),	// id/name
									$col->name,				// for
									$translated_col,	// label text
									array('inside' => true)
								);
							$field = $form->add(
									$formIds[$col->name]['type'],		// type
									$col->name	// id/name
								);
							$rules['regexp'] = 
								array(
										$this->getGc()->get_config('textarea.regexp.zebra'),
										'error',
										parent::lang('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
									);
							if($table == 'defaults') {
								$rules['required'] = 
									array(
											'error',
											parent::lang('required to be filled'),
										);
							}
							$field->set_rule($rules);
						} elseif($col->type == 253 || $col->type == 3) {
							
							// input
							$formIds[$col->name] = array('valueType' => 'string', 'type' => 'text',);
							$form->add(
									'label',		// type
									'label'.ucfirst($col->name),	// id/name
									$col->name,			// for
									$translated_col,	// label text
									array('inside' => true,)	// label inside
								);
							$field = $form->add(
											$formIds[$col->name]['type'],		// type
											$col->name		// id/name
								);
							
							// add rules
							$rules['regexp'] = 
								array(
										$this->getGc()->get_config('textarea.regexp.zebra'),
										'error',
										parent::lang('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
									);
							if($table == 'defaults') {
								$rules['required'] = 
									array(
											'error',
											parent::lang('required field selection!'),
										);
							}
							$field->set_rule($rules);
						} elseif($col->type == 1) {
							
							// input
							$formIds[$col->name] = array('valueType' => 'int', 'type' => 'checkbox', 'default' => 1);
							$form->add(
									'label',		// type
									'label'.ucfirst($col->name),	// id/name
									$col->name,			// for
									$translated_col.':'	// label text
								);
							$public = $form->add(
									$formIds[$col->name]['type'],		// type
									$col->name,						// id/name
									'1',							// value
									($col->name == 'valid' ? array('checked' => 'checked') : null)	// default
								);
						}
					}
				}
			}
		}
		
		// submit-button
		$form->add(
				'submit',		// type
				'buttonSubmit',	// id/name
				parent::lang('save')	// value
			);
		
		// validate
		if($form->validate()) {
			
			// set output
			$return .= $this->p('class="edit_caption"',parent::lang('successful inserted row'));
			
			// get data
			$data = $this->getFormValues($formIds);
			
			// prepare statement
			$sql = 'INSERT INTO '.$db->real_escape_string($table).' ';
			$sql_field = '(id,';
			$sql_value = ' VALUES (NULL,';
			foreach($data as $field => $value) {
				
				// check translation
				$translated_field = '';
				if(parent::lang('usertable row '.$field) != 'usertable row '.$field) {
					$translated_field = parent::lang('usertable row '.$field);
				} else {
					$translated_field = $field;
				}
				
				// add fields to sql
				$sql_field .= $db->real_escape_string($field).',';
				$sql_value .= '\''.$db->real_escape_string($value).'\',';
				
				// add fields to output
				if(isset($tableConfig['fk'][$field]) && is_array($tableConfig['fk'][$field])) {
					$return .= $this->p('', $translated_field.' = \''.nl2br(htmlentities($tableConfig['fk'][$field][$value])).'\'');
				} else {
					$return .= $this->p('', $translated_field.' = \''.nl2br(htmlentities($value)).'\'');
				}
			}
			$sql_field = substr($sql_field,0,-1).')';
			$sql_value = substr($sql_value,0,-1).')';
			$sql .= $sql_field.$sql_value;
			
			// execute
			$result = $db->query($sql);
			
			// check result
			if(!$result) {
				$errno = $this->getError()->error_raised('MysqlError', $db->error, $sql);
				$this->getError()->handle_error($errno);
			}
			
			// add table content
			$return .= $this->listTableContent($table,$this->get('page'));
		} else {
			$return .= $this->p('',parent::lang('insert row'));
			$return .= $form->render('', true);
		}
		
		// close db
		$db->close();
		
		// return
		return $return;
	}
	
	
	
	
	
	
	
	/**
	 * defaults handles the administration of the default-values
	 * 
	 * @return string html-string with the field-administration-page
	 */
	private function defaults() {
		
		// prepare content
		$content = '';
		
		$rid = $this->get('rid');
		
		// check $_GET['field']
		if($this->get('rid') !== false || $this->get('action') == 'new') {
			
			// pagecaption
			$this->getTpl()->assign('pagecaption',parent::lang('default fields'));
			
			// check if row exists
			if($this->rowExists('defaults',$rid) || $this->get('action') == 'new') {
				
				// check $_GET['action']
				if($this->get('action') == 'new') {
					$content .= $this->newRow('defaults');				
				} elseif($this->get('action') == 'edit') {
					$content .= $this->editRow('defaults',$rid);
				} elseif($this->get('action') == 'disable') {
						
					// check if row is enabled
					if($this->isValid('defaults',$rid)) {
						
						// set valid 0
						$this->setValid('defaults',$rid,0);
						
						// list table content
						$content .= $this->listTableContent('defaults',$this->get('page'));
					} else {
						
						// give link to enable
						// smarty
						$sE = new JudoIntranetSmarty();
						$sE->assign('message', parent::lang('row disabled, enable now'));
						$sE->assign('href', 'administration.php?id='.$this->get('id').'&action=enable&rid='.$rid);
						$sE->assign('title', parent::lang('enable row'));
						$sE->assign('content', parent::lang('enable row'));
						
						$content .= $sE->fetch('smarty.admin.dis-enable.tpl');
					}
				} elseif($this->get('action') == 'enable') {
					
					// check if row is disabled
					if(!$this->isValid('defaults',$rid)) {
						
						// set valid 1
						$this->setValid('defaults',$rid,1);
						
						// list table content
						$content .= $this->listTableContent('defaults',$this->get('page'));
					} else {
						
						// give link to disable
						// smarty
						$sE = new JudoIntranetSmarty();
						$sE->assign('message', parent::lang('row enabled, disable now'));
						$sE->assign('href', 'administration.php?id='.$this->get('id').'&action=disable&rid='.$rid);
						$sE->assign('title', parent::lang('disable row'));
						$sE->assign('content', parent::lang('disable row'));
						
						$content .= $sE->fetch('smarty.admin.dis-enable.tpl');
					}				
				} elseif($this->get('action') == 'delete') {
					$content .= $this->deleteRow('defaults',$rid);
				} else {
					$content .= $this->listTableContent('defaults',$this->get('page'));
				}
			} else {
				$errno = $this->getError()->error_raised('RowNotExists',$this->get('rid'));
				$this->getError()->handle_error($errno);
				return $this->getError()->to_html($errno);
			}
		} else {
			
			// add default content
			$content .= $this->listTableContent('defaults',$this->get('page'));
		
		}
		
		// smarty
		$this->getTpl()->assign('caption', parent::lang('manage defaults'));
		$this->getTpl()->assign('tablelinks', '');
		
		// return
		return $content;
	}
	
	
	/**
	 * useradmin() handles the administration of users, groups and permissions
	 * 
	 * @return string html-string with the user administration page
	 */
	private function useradmin() {
		
		// smarty caption
		$this->getTpl()->assign('caption', parent::lang('manage user, groups and permissions'));
		
		// prepare variables
		$content = '';
		$active = 0;
		
		// check action
		if($this->get('action') !== false) {
			
			// switch $_GET['action']
			switch($this->get('action')) {
				
				case 'user':
					$active = 0;
					break;
				
				case 'group':
					$active = 1;
					break;
				
				case 'permission':
					$active = 2;
					break;
				
				default:
					$active = 0;
					break;
			}
		
		} else {
			$active = 0;
		}
		
		// template
		$sUserAdmin = new JudoIntranetSmarty();
		// assign variables
		$tabsJsOptions = '{ active: '.$active.' }';
		$this->getTpl()->assign('tabsJs', true);
		$this->getTpl()->assign('userAdminJs', true);
		$this->getTpl()->assign('tabsJsOptions', $tabsJsOptions);
		// data array
		$data = array(
				0 => array(
						'tab' => parent::lang('user management'),
						'caption' => parent::lang('user managenemt'),
						'content' => $this->userContent($sUserAdmin),
						'action' => 'user',
					),
				1 => array(
						'tab' => parent::lang('group management'),
						'caption' => parent::lang('group management'),
						'content' => $this->groupContent($sUserAdmin),
						'action' => 'group',
					),
				2 => array(
						'tab' => parent::lang('permission management'),
						'caption' => parent::lang('permission management'),
						'content' => $this->permissionContent($sUserAdmin),
						'action' => 'permission',
					),
			);
		
		$sUserAdmin->assign('data', $data);
		$sUserAdmin->assign('backName', parent::lang('&lArr; back'));
		
		// fetch content
		return $sUserAdmin->fetch('smarty.admin.useradmin.tpl');
		
	}
	
	
	/**
	 * userContent() handles the administration of users
	 * 
	 * @return string html-string with the user administration page
	 */
	private function userContent() {
		
		// prepare variables
		$uid = $this->get('uid');
		$subaction = $this->get('subaction');
		$content = '';
		
		// prepare user list
		$sUserList = new JudoIntranetSmarty();
		$sUserList->assign('users', $this->getUser()->return_all_users());
		$sUserList->assign('action', 'user');
		
		// check for user list or edit page
		if($subaction == 'useredit') {
			
			// provide edit form
			// check uid
			if(User::exists($uid) && $uid!=1) {
				
				// output template
				$sOutput = new JudoIntranetSmarty();
				
				// get user
				$user = new User(false);
				$user->change_user($uid, false, 'id');
				
				$ps[] = array(
						'params' => 'class="bold"',
						'content' => parent::lang('edit user:').' '.$user->get_userinfo('name'),
					);
				$sOutput->assign('ps', $ps);
				
				// edit form
				// form
				$form = new Zebra_Form(
						'userEdit',			// id/name
						'post',						// method
						'administration.php?id=user&action=user&subaction=useredit&uid='.$uid		// action
					);
				// set language
				$form->language('deutsch');
				// set docktype xhtml
				$form->doctype('xhtml');
				
				// username
				$formIds['username'] = array('valueType' => 'string', 'type' => 'text',);
				$form->add(
						'label',		// type
						'labelUsername',	// id/name
						'username',			// for
						parent::lang('username')	// label text
					);
				$name = $form->add(
								$formIds['username']['type'],		// type
								'username',		// id/name
								$user->get_userinfo('username')	// default
					);
				$name->set_rule(
						array(
								'required' => array(
										'error', parent::lang('required username!'),
									),
								'regexp' => array(
										$this->getGc()->get_config('name.regexp.zebra'),	// regexp
										'error',	// error variable
										parent::lang('allowed chars').' ['.$this->getGc()->get_config('name.desc').']',	// message
									),
							)
					);
				$form->add(
						'note',			// type
						'noteUsername',		// id/name
						'username',			// for
						parent::lang('help').'&nbsp;'//.$this->helpButton(HELP_MSG_GROUPNAME)	// note text
					);
				
				// password
				$formIds['password'] = array('valueType' => 'string', 'type' => 'password',);
				$form->add(
						'label',			// type
						'labelPassword',	// id/name
						'password',			// for
						parent::lang('password')	// label text
					);
				$password = $form->add(
						$formIds['password']['type'],		// type
						'password',		// id/name
						'',				// value
						array('data-prefix' => 'img:img/iconTextboxPassword.png')
					);
				// passwordConfirm
				$formIds['passwordConfirm'] = array('valueType' => 'string', 'type' => 'password',);
				$form->add(
						'label',				// type
						'labelPasswordConfirm',	// id/name
						'passwordConfirm',				// for
						parent::lang('repeat password')	// label text
					);
				$passwordConfirm = $form->add(
						$formIds['passwordConfirm']['type'],			// type
						'passwordConfirm',	// id/name
						'',					// value
						array('data-prefix' => 'img:img/iconTextboxPassword.png')
					);
				$passwordConfirm->set_rule(
						array(
							'compare' => array(
								'password', 'error', parent::lang('has to be the same'),
								
							),
						)
					);
				
				// name
				$formIds['name'] = array('valueType' => 'string', 'type' => 'text',);
				$form->add(
						'label',		// type
						'labelName',	// id/name
						'name',			// for
						parent::lang('username')	// label text
					);
				$name = $form->add(
								$formIds['name']['type'],		// type
								'name',		// id/name
								$user->get_userinfo('name')	// default
					);
				$name->set_rule(
						array(
								'required' => array(
										'error', parent::lang('required name!'),
									),
								'regexp' => array(
										$this->getGc()->get_config('name.regexp.zebra'),	// regexp
										'error',	// error variable
										parent::lang('allowed chars').' ['.$this->getGc()->get_config('name.desc').']',	// message
									),
							)
					);
				$form->add(
						'note',			// type
						'noteName',		// id/name
						'name',			// for
						parent::lang('help').'&nbsp;'//.$this->helpButton(HELP_MSG_GROUPNAME)	// note text
					);
				
				// email
				$formIds['email'] = array('valueType' => 'string', 'type' => 'text',);
				$form->add(
						'label',		// type
						'labelEmail',	// id/name
						'email',			// for
						parent::lang('emailaddress')	// label text
					);
				$name = $form->add(
								$formIds['email']['type'],		// type
								'email',		// id/name
								$user->get_userinfo('email')	// default
					);
				$name->set_rule(
						array(
								'required' => array(
										'error', parent::lang('required email address!'),
									),
								'email' => array(
										'error',	// error variable
										parent::lang('valid email'),	// message
									),
							)
					);
				$form->add(
						'note',			// type
						'noteEmail',		// id/name
						'email',			// for
						parent::lang('help').'&nbsp;'//.$this->helpButton(HELP_MSG_GROUPNAME)	// note text
					);
				
				// groups
				$options = array();
				$config = array(
						0 => '',
						1 => '|--',
						'1+' => '|&nbsp;&nbsp;&nbsp;',
					);
				$allGroups = Group::allExistingGroups();
				usort($allGroups, array($this, 'callbackSortGroupsAlpha'));
				foreach($allGroups as $optionGroup) {
					$options[$optionGroup->getId()] = $optionGroup->nameToTextIntended($config);
				}
				$ownGroups = array();
				$ownGroupObjects = $user->get_groups();
				foreach($ownGroupObjects as $groupObject) {
					$ownGroups[] = $groupObject->getId();
				}
				$formIds['groups'] = array('valueType' => 'array', 'type' => 'select',);
				$form->add(
						'label',		// type
						'labelGroups',	// id/name
						'groups',			// for
						parent::lang('groups')	// label text
					);
				$groups = $form->add(
						$formIds['groups']['type'],	// type
						'groups[]',		// id/name
						$ownGroups,	// default
						array(		// attributes
								'size' => 10,
								'multiple' => 'multiple',
							)
					);
				$groups->add_options($options);
				$form->add(
						'note',		// type
						'noteGroups',	// id/name
						'groups',		// for
						parent::lang('help').'&nbsp;'//.$this->helpButton(HELP_MSG_FIELDTYPE)	// note text
					);
				
				// submit button
				$form->add(
						'submit',		// type
						'buttonSubmitUp',	// id/name
						parent::lang('save user')	// value
					);
				
				// validate
				if($form->validate()) {
					
					// get form data
					$data = $this->getFormValues($formIds);
					
					// update user and write to db
					$user->set_userinfo(
							array(
									'username' => $data['username'],
									'password' => ($data['password'] == '' ? $user->get_userinfo('password') : md5($data['password'])),
									'name' => $data['name'],
									'email' => $data['email'],
									'active' => 1,
								)
						);
					// groups
					$newGroups = array();
					foreach($data['groups'] as $groupId) {
						$newGroups[] = new Group($groupId);
					}
					$user->set_groups($newGroups);
					$user->writeDb();
					
					// output success message
					$ps[] = array(
							'params' => '',
							'content' => parent::lang('successful saved user:').' '.$data['name'].' ('.$user->get_id().')',
						);
					$sOutput->assign('ps', $ps);
					$content .= $sOutput->fetch('smarty.p.tpl');
				} else {
					$content .= $sOutput->fetch('smarty.p.tpl').$form->render('', true);
				}
			} else {
				
				// error
				$errno = $this->getError()->error_raised('UidNotExists','userContent',$uid);
				$this->getError()->handle_error($errno);
				return $this->getError()->to_html($errno);
			}
		} elseif($subaction == 'userdelete') {
			
			// provide delete form
			// check uid
			if(User::exists($uid) && $uid!=1) {
				
				// check usage
				if(!User::isUsed($uid)) {
					
					// delete form
					// smarty-templates
					$sConfirmation = new JudoIntranetSmarty();
					
					// form
					$form = new Zebra_Form(
						'formConfirm',			// id/name
						'post',				// method
						'administration.php?id=user&action=user&subaction=userdelete&uid='.$uid		// action
					);
					// set language
					$form->language('deutsch');
					// set docktype xhtml
					$form->doctype('xhtml');
					
					// add button
					$form->add(
						'submit',		// type
						'buttonSubmit',	// id/name
						parent::lang('delete'),	// value
						array('title' => parent::lang('delete'))
					);
					
					// smarty-link
					$link = array(
									'params' => 'class="submit"',
									'href' => 'administration.php?id=user&action=user',
									'title' => parent::lang('cancel'),
									'content' => parent::lang('cancel')
								);
					$sConfirmation->assign('link', $link);
					$sConfirmation->assign('spanparams', 'id="cancel"');
					$sConfirmation->assign('message', parent::lang('do you want to completely remove this user?').'&nbsp;');//.$this->helpButton(HELP_MSG_DELETE));
					$sConfirmation->assign('form', $form->render('', true));
					
					// validate
					if($form->validate()) {
						
						$sConfirmation->assign('message', parent::lang('successful removed user!'));
						$sConfirmation->assign('form', '');
						
						$user = new User(false);
						$user->change_user($uid, false, 'id');
						$user->delete();
					}
					
					// smarty return
					$content = $sConfirmation->fetch('smarty.confirmation.tpl');
				} else {
					
					// error
					$errno = $this->getError()->error_raised('ObjectInUse', 'userContent', $uid);
					$this->getError()->handle_error($errno);
					return $this->getError()->to_html($errno);
				}
			} else {
				
				// error
				$errno = $this->getError()->error_raised('UidNotExists','groupContent',$uid);
				$this->getError()->handle_error($errno);
				return $this->getError()->to_html($errno);
			}
		} else {
			
			// provide form and list users
			// form
			$form = new Zebra_Form(
					'userNew',			// id/name
					'post',						// method
					'administration.php?id=user&action=user'		// action
				);
			// set language
			$form->language('deutsch');
			// set docktype xhtml
			$form->doctype('xhtml');
			
			// username
			$formIds['username'] = array('valueType' => 'string', 'type' => 'text',);
			$form->add(
					'label',		// type
					'labelUsername',	// id/name
					'username',			// for
					parent::lang('username')	// label text
				);
			$name = $form->add(
							$formIds['username']['type'],		// type
							'username',		// id/name
							''	// default
				);
			$name->set_rule(
					array(
							'required' => array(
									'error', parent::lang('required username!'),
								),
							'regexp' => array(
									$this->getGc()->get_config('name.regexp.zebra'),	// regexp
									'error',	// error variable
									parent::lang('allowed chars').' ['.$this->getGc()->get_config('name.desc').']',	// message
								),
						)
				);
			$form->add(
					'note',			// type
					'noteUsername',		// id/name
					'username',			// for
					parent::lang('help').'&nbsp;'//.$this->helpButton(HELP_MSG_GROUPNAME)	// note text
				);
			
			// password
			$formIds['password'] = array('valueType' => 'string', 'type' => 'password',);
			$form->add(
					'label',			// type
					'labelPassword',	// id/name
					'password',			// for
					parent::lang('password')	// label text
				);
			$password = $form->add(
					$formIds['password']['type'],		// type
					'password',		// id/name
					'',				// value
					array('data-prefix' => 'img:img/iconTextboxPassword.png')
				);
			$password->set_rule(
					array(
							'required' => array(
									'error', parent::lang('required password!'),
								),
						)
				);
			// passwordConfirm
			$formIds['passwordConfirm'] = array('valueType' => 'string', 'type' => 'password',);
			$form->add(
					'label',				// type
					'labelPasswordConfirm',	// id/name
					'passwordConfirm',				// for
					parent::lang('repeat password')	// label text
				);
			$passwordConfirm = $form->add(
					$formIds['passwordConfirm']['type'],			// type
					'passwordConfirm',	// id/name
					'',					// value
					array('data-prefix' => 'img:img/iconTextboxPassword.png')
				);
			$passwordConfirm->set_rule(
					array(
						'compare' => array(
							'password', 'error', parent::lang('has to be the same'),
							
						),
					)
				);
			
			// name
			$formIds['name'] = array('valueType' => 'string', 'type' => 'text',);
			$form->add(
					'label',		// type
					'labelName',	// id/name
					'name',			// for
					parent::lang('name')	// label text
				);
			$name = $form->add(
							$formIds['name']['type'],		// type
							'name',		// id/name
							''	// default
				);
			$name->set_rule(
					array(
							'required' => array(
									'error', parent::lang('required name!'),
								),
							'regexp' => array(
									$this->getGc()->get_config('name.regexp.zebra'),	// regexp
									'error',	// error variable
									parent::lang('allowed chars').' ['.$this->getGc()->get_config('name.desc').']',	// message
								),
						)
				);
			$form->add(
					'note',			// type
					'noteName',		// id/name
					'name',			// for
					parent::lang('help').'&nbsp;'//.$this->helpButton(HELP_MSG_GROUPNAME)	// note text
				);
			
			// email
			$formIds['email'] = array('valueType' => 'string', 'type' => 'text',);
			$form->add(
					'label',		// type
					'labelEmail',	// id/name
					'email',			// for
					parent::lang('emailaddress')	// label text
				);
			$name = $form->add(
							$formIds['email']['type'],		// type
							'email',		// id/name
							''	// default
				);
			$name->set_rule(
					array(
							'required' => array(
									'error', parent::lang('required name!'),
								),
							'email' => array(
									'error',	// error variable
									parent::lang('valid email'),	// message
								),
						)
				);
			$form->add(
					'note',			// type
					'noteEmail',		// id/name
					'email',			// for
					parent::lang('help').'&nbsp;'//.$this->helpButton(HELP_MSG_GROUPNAME)	// note text
				);
			
			// groups
			$options = array();
			$config = array(
					0 => '',
					1 => '|--',
					'1+' => '|&nbsp;&nbsp;&nbsp;',
				);
			$allGroups = Group::allExistingGroups();
			usort($allGroups, array($this, 'callbackSortGroupsAlpha'));
			foreach($allGroups as $optionGroup) {
				$options[$optionGroup->getId()] = $optionGroup->nameToTextIntended($config);
			}
			$formIds['groups'] = array('valueType' => 'array', 'type' => 'select',);
			$form->add(
					'label',		// type
					'labelGroups',	// id/name
					'groups',			// for
					parent::lang('groups')	// label text
				);
			$groups = $form->add(
					$formIds['groups']['type'],	// type
					'groups[]',		// id/name
					'',	// default
					array(		// attributes
							'size' => 10,
							'multiple' => 'multiple',
						)
				);
			$groups->add_options($options);
			$form->add(
					'note',		// type
					'noteGroups',	// id/name
					'groups',		// for
					parent::lang('help').'&nbsp;'//.$this->helpButton(HELP_MSG_FIELDTYPE)	// note text
				);
			
			// submit button
			$form->add(
					'submit',		// type
					'buttonSubmitUp',	// id/name
					parent::lang('add user')	// value
				);
			
			// validate
			if($form->validate()) {
				
				// get form data
				$data = $this->getFormValues($formIds);
				
				// update user and write to db
				$user = new User(false);
				$user->change_user($uid, false, 'id');
				$user->set_userinfo(
						array(
								'username' => $data['username'],
								'password' => ($data['password'] == '' ? $user->get_userinfo('password') : md5($data['password'])),
								'name' => $data['name'],
								'email' => $data['email'],
								'active' => 1,
							)
					);
				// groups
				$newGroups = array();
				foreach($data['groups'] as $groupId) {
					$newGroups[] = new Group($groupId);
				}
				$user->set_groups($newGroups);
				$user->writeDb(DB_WRITE_NEW);
				
				// output success message
				// output template
				$sOutput = new JudoIntranetSmarty();
				$ps[] = array(
						'params' => '',
						'content' => parent::lang('successful added user:').' '.$data['name'].' ('.$user->get_id().')',
					);
				$sOutput->assign('ps', $ps);
				$content .= $sOutput->fetch('smarty.p.tpl');
			} else {
				$content .= $form->render('', true);
			}
			
			// user list
			$sUserList->assign('users', $this->getUser()->return_all_users());
			$content .= $sUserList->fetch('smarty.admin.userlist.tpl');
		}
		
		// return
		return $content;
	}
	
	
	/**
	 * groupContent() handles the administration of groups
	 * 
	 * @return string html-string with the user administration page
	 */
	private function groupContent() {
		
		// prepare variables
		$gid = $this->get('gid');
		$subaction = $this->get('subaction');
		$content = '';
		
		// prepare group list
		$sGroupList = new JudoIntranetSmarty();
		$sGroupList->assign('groups', Group::allExistingGroups());
		$sGroupList->assign('action', 'group');
		
		// check for group list or edit page
		if($subaction == 'groupedit') {
			
			// provide edit form
			// check gid
			if(Group::exists($gid) && $gid!=1) {
				
				// output template
				$sOutput = new JudoIntranetSmarty();
				
				// get group
				$group = new Group($gid);
				
				$ps[] = array(
						'params' => 'class="bold"',
						'content' => parent::lang('edit group:').' '.$group->getName(),
					);
				$sOutput->assign('ps', $ps);
				// edit form
				// form
				$form = new Zebra_Form(
						'groupEdit',			// id/name
						'post',						// method
						'administration.php?id=user&action=group&subaction=groupedit&gid='.$gid		// action
					);
				// set language
				$form->language('deutsch');
				// set docktype xhtml
				$form->doctype('xhtml');
				
				// name
				$formIds['name'] = array('valueType' => 'string', 'type' => 'text',);
				$form->add(
						'label',		// type
						'labelName',	// id/name
						'name',			// for
						parent::lang('group name')	// label text
					);
				$name = $form->add(
								$formIds['name']['type'],		// type
								'name',		// id/name
								$group->getName()	// default
					);
				$name->set_rule(
						array(
								'required' => array(
										'error', parent::lang('required name!'),
									),
								'regexp' => array(
										$this->getGc()->get_config('name.regexp.zebra'),	// regexp
										'error',	// error variable
										parent::lang('allowed chars').' ['.$this->getGc()->get_config('name.desc').']',	// message
									),
							)
					);
				$form->add(
						'note',			// type
						'noteName',		// id/name
						'name',			// for
						parent::lang('help').'&nbsp;'//.$this->helpButton(HELP_MSG_GROUPNAME)	// note text
					);
				
				// subgroup of
				$options = array();
				$config = array(
						0 => '',
						1 => '|--',
						'1+' => '|&nbsp;&nbsp;&nbsp;',
					);
				$allGroups = Group::allExistingGroups();
				usort($allGroups, array($this, 'callbackSortGroupsAlpha'));
				foreach($allGroups as $optionGroup) {
					if($optionGroup->getId() != $gid) {
						$options[$optionGroup->getId()] = $optionGroup->nameToTextIntended($config);
					}
				}
				$formIds['subgroupOf'] = array('valueType' => 'int', 'type' => 'select',);
				$form->add(
						'label',		// type
						'labelSubgroupOf',	// id/name
						'subgroupOf',			// for
						parent::lang('parent group')	// label text
					);
				$subgroupOf = $form->add(
						$formIds['subgroupOf']['type'],	// type
						'subgroupOf',		// id/name
						$group->getParent(),	// default
						array(		// attributes
								'size' => 10,
							)
					);
				$subgroupOf->add_options($options);
				$subgroupOf->set_rule(
						array(
								'required' => array(
										'error', parent::lang('required paren group!')
									),
							)
					);
				$form->add(
						'note',		// type
						'noteSubgroupOf',	// id/name
						'subgroupOf',		// for
						parent::lang('help').'&nbsp;'//.$this->helpButton(HELP_MSG_FIELDTYPE)	// note text
					);
				
				// submit button
				$form->add(
						'submit',		// type
						'buttonSubmitUp',	// id/name
						parent::lang('save group')	// value
					);
				
				// validate
				if($form->validate()) {
					
					// get form data
					$data = $this->getFormValues($formIds);
					
					// update group and write to db
					$group->update(
							array(
									'name' => $data['name'],
									'parent' => $data['subgroupOf'],
									'valid' => 1,
								)
						);
					$group->writeDb();
					
					// output success message
					$ps[] = array(
							'params' => '',
							'content' => parent::lang('successful saved group:').' '.$data['name'].' ('.$group->getId().')',
						);
					$sOutput->assign('ps', $ps);
					$content .= $sOutput->fetch('smarty.p.tpl');
				} else {
					$content .= $sOutput->fetch('smarty.p.tpl').$form->render('', true);
				}
			} else {
				
				// error
				$errno = $this->getError()->error_raised('GidNotExists','groupContent',$gid);
				$this->getError()->handle_error($errno);
				return $this->getError()->to_html($errno);
			}
		} elseif($subaction == 'groupdelete') {
			
			// provide delete form
			// check gid
			if(Group::exists($gid) && $gid!=1) {
				
				// check usage
				if(!Group::isUsed($gid)) {
					
					// delete form
					// smarty-templates
					$sConfirmation = new JudoIntranetSmarty();
					
					// form
					$form = new Zebra_Form(
						'formConfirm',			// id/name
						'post',				// method
						'administration.php?id=user&action=group&subaction=groupdelete&gid='.$gid		// action
					);
					// set language
					$form->language('deutsch');
					// set docktype xhtml
					$form->doctype('xhtml');
					
					// add button
					$form->add(
						'submit',		// type
						'buttonSubmit',	// id/name
						parent::lang('delete'),	// value
						array('title' => parent::lang('delete'))
					);
					
					// smarty-link
					$link = array(
									'params' => 'class="submit"',
									'href' => 'administration.php?id=user&action=group',
									'title' => parent::lang('cancel'),
									'content' => parent::lang('cancel')
								);
					$sConfirmation->assign('link', $link);
					$sConfirmation->assign('spanparams', 'id="cancel"');
					$sConfirmation->assign('message', parent::lang('do you want to completely remove this group?').'&nbsp;');//.$this->helpButton(HELP_MSG_DELETE));
					$sConfirmation->assign('form', $form->render('', true));
					
					// validate
					if($form->validate()) {
						
						$sConfirmation->assign('message', parent::lang('successful deleted group!'));
						$sConfirmation->assign('form', '');
						
						$group = new Group($gid);
						$group->delete();
					}
					
					// smarty return
					$content = $sConfirmation->fetch('smarty.confirmation.tpl');
				} else {
					
					// error
					$errno = $this->getError()->error_raised('ObjectInUse', 'groupContent', $gid);
					$this->getError()->handle_error($errno);
					return $this->getError()->to_html($errno);
				}
			} else {
				
				// error
				$errno = $this->getError()->error_raised('GidNotExists','groupContent',$gid);
				$this->getError()->handle_error($errno);
				return $this->getError()->to_html($errno);
			}
		} else {
			
			// provide form and list groups
			// form
			$form = new Zebra_Form(
					'groupNew',			// id/name
					'post',						// method
					'administration.php?id=user&action=group'		// action
				);
			// set language
			$form->language('deutsch');
			// set docktype xhtml
			$form->doctype('xhtml');
			
			// name
			$formIds['name'] = array('valueType' => 'string', 'type' => 'text',);
			$form->add(
					'label',		// type
					'labelName',	// id/name
					'name',			// for
					parent::lang('group name')	// label text
				);
			$name = $form->add(
							$formIds['name']['type'],		// type
							'name'		// id/name
				);
			$name->set_rule(
					array(
							'required' => array(
									'error', parent::lang('required group name!'),
								),
							'regexp' => array(
									$this->getGc()->get_config('name.regexp.zebra'),	// regexp
									'error',	// error variable
									parent::lang('class.AdministrationView#groupContent#form#rule.regexp.allowedChars').' ['.$this->getGc()->get_config('name.desc').']',	// message
								),
						)
				);
			$form->add(
					'note',			// type
					'noteName',		// id/name
					'name',			// for
					parent::lang('help').'&nbsp;'//.$this->helpButton(HELP_MSG_GROUPNAME)	// note text
				);
			
			// subgroup of
			$options = array();
			$config = array(
					0 => '',
					1 => '|--',
					'1+' => '|&nbsp;&nbsp;&nbsp;',
				);
			$groups = Group::allExistingGroups();
			usort($groups, array($this, 'callbackSortGroupsAlpha'));
			foreach($groups as $group) {
				$options[$group->getId()] = $group->nameToTextIntended($config);
			}
			$formIds['subgroupOf'] = array('valueType' => 'int', 'type' => 'select',);
			$form->add(
					'label',		// type
					'labelSubgroupOf',	// id/name
					'subgroupOf',			// for
					parent::lang('parent group')	// label text
				);
			$subgroupOf = $form->add(
					$formIds['subgroupOf']['type'],	// type
					'subgroupOf',		// id/name
					1,			// default
					array(		// attributes
							'size' => 10,
						)
				);
			$subgroupOf->add_options($options);
			$subgroupOf->set_rule(
					array(
							'required' => array(
									'error', parent::lang('required parent group!')
								),
						)
				);
			$form->add(
					'note',		// type
					'noteSubgroupOf',	// id/name
					'subgroupOf',		// for
					parent::lang('help').'&nbsp;'//.$this->helpButton(HELP_MSG_FIELDTYPE)	// note text
				);
			
			// submit button
			$form->add(
					'submit',		// type
					'buttonSubmitUp',	// id/name
					parent::lang('add group')	// value
				);
			
			// validate
			if($form->validate()) {
				
				// get form data
				$data = $this->getFormValues($formIds);
				
				// create new group and write to db
				$newGroup = new Group();
				$newGroup->update(
						array(
								'name' => $data['name'],
								'parent' => $data['subgroupOf'],
								'valid' => 1,
							)
					);
				$newId = $newGroup->writeDb();
				
				// output success message
				$sOutput = new JudoIntranetSmarty();
				$sOutput->assign('params', '');
				$sOutput->assign('content', parent::lang('successful added group:').' '.$data['name'].' ('.$newId.')');
				$content .= $sOutput->fetch('smarty.p.tpl');
			} else {
				$content .= $form->render('', true);
			}
			
			// group list
			$sGroupList->assign('groups', Group::allExistingGroups());
			$content .= $sGroupList->fetch('smarty.admin.grouplist.tpl');
		}
		
		// return
		return $content;
	}
	
	
	/**
	 * permissionContent() handles the administration of permissions
	 * 
	 * @return string html-string with the user administration page
	 */
	private function permissionContent() {
		
		// check for navi list or permission page
		$nid = $this->get('nid');
		$forbiddenNids = array(1, 2, 3, 45);
		if($nid === false) {
			
			// navi list
			// get and sort navi entries
			$sql = 'SELECT `id`
					FROM `navi`
					WHERE `parent`=0
						AND `valid`=1
					';
			$naviEntries = Db::arrayValue($sql, MYSQL_ASSOC);
			
			if(!is_array($naviEntries)) {
				$errno = $this->getError()->error_raised('MysqlError', Db::$error, Db::$statement);
				$this->getError()->handle_error($errno);
			}
			
			// prepare navi tree and sort by position
			$navi = array();
			foreach($naviEntries as $naviId) {
				
				$navi[] = new Navi($naviId['id']);
			}
			usort($navi, array($this, 'callbackSortNavi'));
			
			// group list
			$sNaviList = new JudoIntranetSmarty();
			$sNaviList->assign('action', 'permission');
			$sNaviList->assign('navi', $navi);
			$sNaviList->assign('entriesNotShown', $forbiddenNids);
			return $sNaviList->fetch('smarty.admin.navilist.tpl');
		} else {
			
			// check if navi entry exists
			if(Navi::exists($nid) && !in_array($nid, $forbiddenNids)) {
				
				// get navi object
				$navi = new Navi($nid);
				$parentNavi = null;
				if($navi->getParent() != 0) {
					$parentNavi = new Navi($navi->getParent());
				}
				
				// get and sort groups
				$groups = Group::allExistingGroups();
				$groups[0] = Group::fakePublic();
				usort($groups, array($this, 'callbackSortGroupsAlpha'));
				
				// form
				$form = new Zebra_Form(
						'naviPermission',			// id/name
						'post',						// method
						'administration.php?id=user&action=permission&nid='.$nid		// action
					);
				// set language
				$form->language('deutsch');
				// set docktype xhtml
				$form->doctype('xhtml');
				
				// upper submit button
				$form->add(
						'submit',		// type
						'buttonSubmitUp',	// id/name
						parent::lang('save')	// value
					);
				
				// walk through navi and add to form
				$formIds = array();
				foreach($groups as $group) {
					if($group->getId() != 1) {
						$this->addPermissionEntry($form, $group, $formIds);
					}
				}
				
				// lower submit button
				$form->add(
						'submit',		// type
						'buttonSubmitLow',	// id/name
						parent::lang('save')	// value
					);
				
				// validate
				if($form->validate()) {
					
					// get form permissions
					$permissions = $this->getFormPermissions($formIds);
					
					// write permissions
					$navi->dbDeletePermission();
					$navi->dbWritePermission($permissions);
					
					// output success message
					$sOutput = new JudoIntranetSmarty();
					$data = array(
							array('params' => 'class="bold"', 'content' => (!is_null($parentNavi) ? parent::lang($parentNavi->getName()).' &rarr; ' : '').parent::lang($navi->getName()).' ('.$navi->getRequiredPermission().') '),
							array('params' => '', 'content' => parent::lang('successful saved permissions')),
						);
					$sOutput->assign('ps', $data);
					return $sOutput->fetch('smarty.p.tpl');
				} else {
					
					// output form
					$sOutput = new JudoIntranetSmarty();
					$data = array(
							array('params' => 'class="bold"', 'content' => (!is_null($parentNavi) ? parent::lang($parentNavi->getName()).' &rarr; ' : '').parent::lang($navi->getName()).' ('.$navi->getRequiredPermission().') '),
							array('params' => '', 'content' => $form->render('', true)),
						);
					$sOutput->assign('ps', $data);
					return $sOutput->fetch('smarty.p.tpl');
				}
			} else {
				
				// error
				$errno = $this->getError()->error_raised('NidNotExists','permissionContent',$nid);
				$this->getError()->handle_error($errno);
				return $this->getError()->to_html($errno);
			}
		}
	}
	
	
	/**
	 * addPermissionEntry() adds the radios to the $form
	 * 
	 * @param object $form the zebra_form object to add the permission radios to
	 * @param object $group the group object to add
	 * @param array $formIds array to collect the the names of the form elements
	 */
	private function addPermissionEntry(&$form, $group, &$formIds) {
		
		// prepare form
		$radioName = 'group_'.$group->getId();
		
		// prepare images
		// read
		$imgReadText = parent::lang('permission: read/list');
		$imgRead = array(
				'params' => 'class="iconRead clickable" title="'.$imgReadText.'" onclick="selectRadio(\''.$radioName.'_r\')"',
				'src' => 'img/permissions_read.png',
				'alt' => $imgReadText,
			);
		$sImgReadTemplate = new JudoIntranetSmarty();
		$sImgReadTemplate->assign('img', $imgRead);
		
		// edit
		$imgEditText = parent::lang('permission: edit');
		$imgEdit = array(
				'params' => 'class="iconEdit clickable" title="'.$imgEditText.'" onclick="selectRadio(\''.$radioName.'_w\')"',
				'src' => 'img/permissions_edit.png',
				'alt' => $imgEditText,
			);
		$sImgEditTemplate = new JudoIntranetSmarty();
		$sImgEditTemplate->assign('img', $imgEdit);
		
		// prepare clear radio link
		$sImgClearTemplate = new JudoIntranetSmarty();
		$img = array(
				'params' => 'class="clickable" onclick="clearRadio(\''.$radioName.'\')" title="'.parent::lang('remove permissions').'"',
				'src' => 'img/permissions_delete.png',
				'alt' => parent::lang('remove permissions'),
			);
		$sImgClearTemplate->assign('img', $img);
		
		// add radios
		$formIds[$radioName] = array('valueType' => 'int', 'type' => 'radios', 'default' => 1);
		$form->add(
				'label',		// type
				'label_'.$radioName,	// id/name
				$radioName,		// for
				$group->getName()	// label text
			);
		$form->add(
				$formIds[$radioName]['type'],	// type
				$radioName,						// id/name
				array(				// values
						'r' => $sImgReadTemplate->fetch('smarty.img.tpl'),
						'w' => $sImgEditTemplate->fetch('smarty.img.tpl'),
					),
				$group->permissionFor('navi', $this->get('nid'))	// default
			);
		$form->add(
				'note',			// type
				'note_'.$radioName,	// id/name
				$radioName,		// for
				parent::lang('completely remove permission on this navi entry').':&nbsp;'.$sImgClearTemplate->fetch('smarty.img.tpl')	// note text
			);
	}
	
	
	/**
	 * manageTable() manages the actions on the table
	 * 
	 * @return string html code of the adminitration page
	 */
	private function manageTable() {
		
		// prepare return
		$content = '';
		
		// check if row exists
		$rid = $this->get('rid');
		if($this->rowExists($this->get('field'),$rid)) {
			
			// check $_GET['action']
			if($this->get('action') == 'new') {
				$content .= $this->newRow($this->get('field'));				
			} elseif($this->get('action') == 'edit') {
				$content .= $this->editRow($this->get('field'),$rid);				
			} elseif($this->get('action') == 'disable') {
				
				// check if row is enabled
				if($this->isValid($this->get('field'),$rid)) {
					
					// set valid 0
					$this->setValid($this->get('field'),$rid,0);
					
					// list table content
					$content .= $this->listTableContent($this->get('field'),$this->get('page'));
				} else {
					
					// give link to enable
					// smarty
					$sE = new JudoIntranetSmarty();
					$sE->assign('message', parent::lang('row disabled, enable now'));
					$sE->assign('href', 'administration.php?id='.$this->get('id').'&field='.$this->get('field').'&action=enable&rid='.$rid);
					$sE->assign('title', parent::lang('enable row'));
					$sE->assign('content', parent::lang('enable row'));
					
					$content .= $sE->fetch('smarty.admin.dis-enable.tpl');
				}
			} elseif($this->get('action') == 'enable') {
				
				// check if row is disabled
				if(!$this->isValid($this->get('field'),$rid)) {
					
					// set valid 1
					$this->setValid($this->get('field'),$rid,1);
					
					// list table content
					$content .= $this->listTableContent($this->get('field'),$this->get('page'));
				} else {
					
					// give link to enable
					// smarty
					$sE = new JudoIntranetSmarty();
					$sE->assign('message', parent::lang('row enabled, disable now'));
					$sE->assign('href', 'administration.php?id='.$this->get('id').'&field='.$this->get('field').'&action=disable&rid='.$rid);
					$sE->assign('title', parent::lang('disable row'));
					$sE->assign('content', parent::lang('disable row'));
					
					$content .= $sE->fetch('smarty.admin.dis-enable.tpl');
				}
			} elseif($this->get('action') == 'delete') {
				
				// add form and message
				$content .= $this->deleteRow($this->get('field'),$rid);
			} else {
				
				// list table content
				$content .= $this->listTableContent($this->get('field'),$this->get('page'));
			}
		} else {
			$errno = $this->getError()->error_raised('RowNotExists',$this->get('rid'));
			$this->getError()->handle_error($errno);
			return $this->getError()->to_html($errno);
		}
		
		// return
		return $content;
	}
	
	
	/**
	 * clubadmin() handles the administration of clubs
	 * 
	 * @return string html-string with the club administration page
	 */
	private function clubadmin() {
		
		// redirect to club table
		if($this->get('field') === false) {
			$this->redirectTo('administration', array('id' => 'club', 'field' => 'club'));
		}
		
		// set caption
		$this->getTpl()->assign('caption', parent::lang('manage table').parent::lang('<b>club</b>').' ("'.$this->get('field').'")');
		
		// return
		return $this->manageTable();
	}
	
	
	/**
	 * getTableConfig($table) retrieves any configuration settings for $table and returns it
	 * as array
	 * 
	 * @param string $table name of the table
	 * @return array array of arrays containing the configuration settings
	 */
	private function getTableConfig($table) {
		
		// get config from db
		$tableConfigJson = $this->getGc()->get_config('usertableConfig.'.$table);
		
		// get array from json if exists
		$tableConfigArray = array();
		if($tableConfigJson !== false) {
			$tableConfigArray = json_decode($tableConfigJson, true);
		} else {
			return false;
		}
		
		// prepare return array, add cols and orderBy
		$tableConfig['cols'] = $tableConfigArray['cols'];
		$tableConfig['orderBy'] = $tableConfigArray['orderBy'];
		
		// walk through foreign keys
		$foreignKeys = array();
		if(count($tableConfigArray['fk']) > 0) {
			foreach($tableConfigArray['fk'] as $fk => $sql) {
				
				// get sql result for fk
				$fkArray = Db::arrayValue($sql, MYSQL_ASSOC);
				
				if(!is_array($fkArray)) {
					$errno = $this->getError()->error_raised('MysqlError', Db::$error, Db::$statement);
					$this->getError()->handle_error($errno);
				}
				
				// put in array for select option
				foreach($fkArray as $values) {
					
					$foreignKeys[$fk][$values['id']] = $values['readable_name'];
				}
			}
		}
		
		// add foreign keys and return config
		$tableConfig['fk'] = $foreignKeys;
		return $tableConfig;
	}
}



?>
