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
			$GLOBALS['Error']->handle_error($e);
		}
	}
	
	/*
	 * methods
	 */
	/**
	 * navi knows about the functionalities used in navigation returns an array
	 * containing first- and second-level-navientries
	 * 
	 * @return array contains first- and second-level-navientries
	 */
	public static function connectnavi() {
		
		// set first- and secondlevel names and set secondlevel $_GET['id']-values
		static $navi = array();
		
		$navi = array(
						'firstlevel' => array(
							'name' => 'class.AdministrationView#connectnavi#firstlevel#name',
							'file' => 'administration.php',
							'position' => 5,
							'class' => get_class(),
							'id' => md5('AdministrationView'), // 2a9bbf011365fd7d204f6eeca370468f
							'show' => true
						),
						'secondlevel' => array(
							0 => array(
								'getid' => 'field', 
								'name' => 'class.AdministrationView#connectnavi#secondlevel#field',
								'id' => md5('AdministrationView|field'), // 8eb55c6f5a92a320407b1805b9ec01b4
								'show' => true
							),
							1 => array(
								'getid' => 'defaults', 
								'name' => 'class.AdministrationView#connectnavi#secondlevel#defaults',
								'id' => md5('AdministrationView|defaults'), // 172aec6f699d651e8dacbc09be10907a
								'show' => true
							)
						)
					);
		
		// return array
		return $navi;
	}
	
	
	
	
	
	
	
	/**
	 * init chooses the functionality by using $_GET['id']
	 * 
	 * @return void
	 */
	public function init() {
		
		// set pagename
		$this->tpl->assign('pagename',parent::lang('class.AdministrationView#page#init#name'));
		
		// init helpmessages
		$this->initHelp();
		
		// switch $_GET['id'] if set
		if($this->get('id') !== false) {
			
			// check rights
			// get class
			$class = get_class();
			// get naviitems
			$navi = $class::connectnavi();
			// get rights from db
			$rights = Rights::get_authorized_entries('navi');
			$naviid = 0;
			// walk through secondlevel-entries to find actual entry
			for($i=0;$i<count($navi['secondlevel']);$i++) {
				if(isset($navi['secondlevel'][$i]['getid']) && $navi['secondlevel'][$i]['getid'] == $this->get('id')) {
					
					// store id and  break
					$naviid = $navi['secondlevel'][$i]['id'];
					break;
				}
			}
			
			// check if naviid is member of authorized entries
			if(in_array($naviid,$rights)) {
				
				switch($this->get('id')) {
					
					case 'field':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.AdministrationView#init#title#field')));
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('hierselect', true);
						$this->tpl->assign('main', $this->field());
					break;
					
					case 'defaults':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.AdministrationView#init#title#defaults')));
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('hierselect', true);
						$this->tpl->assign('main', $this->defaults());
					break;
					
					default:
						
						// id set, but no functionality
						$errno = $GLOBALS['Error']->error_raised('GETUnkownId','entry:'.$this->get('id'),$this->get('id'));
						$GLOBALS['Error']->handle_error($errno);
						
						// smarty
						$this->tpl->assign('title', '');
						$this->tpl->assign('main', $GLOBALS['Error']->to_html($errno));
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('hierselect', false);
					break;
				}
			} else {
				
				// error not authorized
				$errno = $GLOBALS['Error']->error_raised('NotAuthorized','entry:'.$this->get('id'),$this->get('id'));
				$GLOBALS['Error']->handle_error($errno);
				
				// smarty
				$this->tpl->assign('title', $this->title(parent::lang('class.AdministrationView#init#Error#NotAuthorized')));
				$this->tpl->assign('main', $GLOBALS['Error']->to_html($errno));
				$this->tpl->assign('jquery', true);
				$this->tpl->assign('hierselect', false);
			}
		} else {
			
			// id not set
			// smarty-title
			$this->tpl->assign('title', $this->title(parent::lang('class.AdministrationView#init#default#title'))); 
			// smarty-main
			$this->tpl->assign('main', $this->defaultContent());
			// smarty-jquery
			$this->tpl->assign('jquery', true);
			// smarty-hierselect
			$this->tpl->assign('hierselect', false);
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
			if(parent::lang('class.AdministrationView#tables#name#'.$this->get('field')) != 'class.AdministrationView#tables#name#'.$this->get('field').' not translated') {
				$translatedField = parent::lang('class.AdministrationView#tables#name#'.$this->get('field'));
			} else {
				$translatedField = $this->get('field');
			}
			
			// set caption
			$this->tpl->assign('caption', parent::lang('class.AdministrationView#field#caption#name.table').$translatedField.' ("'.$this->get('field').'")');
			
			// check if 'field' exists
			if($this->check_usertable($this->get('field')) !== false) {
				
				// check if row exists
				$rid = $this->get('rid');
				if($this->row_exists($this->get('field'),$rid)) {
					
					// check $_GET['action']
					if($this->get('action') == 'new') {
						$content .= $this->new_row($this->get('field'));				
					} elseif($this->get('action') == 'edit') {
						$content .= $this->edit_row($this->get('field'),$rid);				
					} elseif($this->get('action') == 'disable') {
						
						// check if row is enabled
						if($this->is_valid($this->get('field'),$rid)) {
							
							// set valid 0
							$this->set_valid($this->get('field'),$rid,0);
							
							// list table content
							$content .= $this->list_table_content($this->get('field'),$this->get('page'));
						} else {
							
							// give link to enable
							// smarty
							$sE = new JudoIntranetSmarty();
							$sE->assign('message', parent::lang('class.AdministrationView#defaults#disable#rowNotEnabled'));
							$sE->assign('href', 'administration.php?id='.$this->get('id').'&field='.$this->get('field').'&action=enable&rid='.$rid);
							$sE->assign('title', parent::lang('class.AdministrationView#defaults#disable#rowNotEnabled.enable'));
							$sE->assign('content', parent::lang('class.AdministrationView#defaults#disable#rowNotEnabled.enable'));
							
							$content .= $sE->fetch('smarty.admin.dis-enable.tpl');
						}
					} elseif($this->get('action') == 'enable') {
						
						// check if row is disabled
						if(!$this->is_valid($this->get('field'),$rid)) {
							
							// set valid 1
							$this->set_valid($this->get('field'),$rid,1);
							
							// list table content
							$content .= $this->list_table_content($this->get('field'),$this->get('page'));
						} else {
							
							// give link to enable
							// smarty
							$sE = new JudoIntranetSmarty();
							$sE->assign('message', parent::lang('class.AdministrationView#defaults#enable#rowNotDisabled'));
							$sE->assign('href', 'administration.php?id='.$this->get('id').'&field='.$this->get('field').'&action=disable&rid='.$rid);
							$sE->assign('title', parent::lang('class.AdministrationView#defaults#enable#rowNotDisabled.disable'));
							$sE->assign('content', parent::lang('class.AdministrationView#defaults#enable#rowNotDisabled.disable'));
							
							$content .= $sE->fetch('smarty.admin.dis-enable.tpl');
						}
					} elseif($this->get('action') == 'delete') {
						
						// add form and message
						$content .= $this->delete_row($this->get('field'),$rid);
					} else {
						
						// list table content
						$content .= $this->list_table_content($this->get('field'),$this->get('page'));
					}
				} else {
					$errno = $GLOBALS['Error']->error_raised('RowNotExists',$this->get('rid'));
					$GLOBALS['Error']->handle_error($errno);
					return $GLOBALS['Error']->to_html($errno);
				}
			} else {
				$errno = $GLOBALS['Error']->error_raised('UsertableNotExists',$this->get('field'));
				$GLOBALS['Error']->handle_error($errno);
				return $GLOBALS['Error']->to_html($errno);
			}
		} else {
			
			// set caption
			$this->tpl->assign('caption', parent::lang('class.AdministrationView#field#caption#name'));
		}
		
		// smarty
		$this->tpl->assign('tablelinks', $this->create_table_links());
		
		// return
		return $content;
	}
	
	
	
	
	
	
	
	/**
	 * create_table_links creates the links to choose the table to manage
	 * 
	 * @return string html-string with the table-links
	 */
	private function create_table_links() {
		
		// smarty-templates
		$sTl = new JudoIntranetSmarty();
		
		// prepare return
		$return = '';
		
		// get usertables
		$usertables = $this->get_usertables();
		
		// create links
		$a_out = '';
		// smarty
		$sTl->assign('class', 'class="usertable"');
		foreach($usertables as $table) {
			
			// check table
			if($this->get('field') === false || $this->get('field') != $table) {
			
				// translate table name
				if(parent::lang('class.AdministrationView#tables#name#'.$table) != 'class.AdministrationView#tables#name#'.$table.' not translated') {
					$translatedTable = parent::lang('class.AdministrationView#tables#name#'.$table);
				} else {
					$translatedTable = $table;
				}
				// smarty
				$data[] = array(
						'params' => 'class="usertable"',
						'href' => 'administration.php?id='.$this->get('id').'&field='.$table,
						'title' => $translatedTable.' '.parent::lang('class.AdministrationView#create_table_links#title#manage'),
						'content' => $translatedTable.' '.parent::lang('class.AdministrationView#create_table_links#name#manage')
					);
			}
		}
		
		$sTl->assign('data', $data);
		
		// add slider-link
		// smarty
		$link = array(
				'title' => parent::lang('class.AdministrationView#create_table_links#toggleTable#title'),
				'content' => parent::lang('class.AdministrationView#create_table_links#toggleTable#name'),
				'params' => 'id="toggleTable"',
				'help' => $GLOBALS['help']->getMessage(HELP_MSG_ADMINUSERTABLESELECT),
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
	 * get_usertables returns an array containing all usertables
	 * 
	 * @return array array containing all user-editable tables
	 */
	private function get_usertables() {
		
		// get db-object
		$db = Db::newDb();
		
		// get all fields to administer
		// get systemtables
		$systemtables = explode(',',$_SESSION['GC']->get_config('systemtables'));
		
		// get user tables
		$usertables = array();
		$sql = "SHOW TABLES";
		$result = $db->query($sql);
		while(list($table) = $result->fetch_array(MYSQL_NUM)) {
			
			// check systemtable
			if(!in_array($table,$systemtables)) {
				$usertables[] = $table;
			}
		}
		
		// return
		return $usertables;
	}
	
	
	
	
	
	
	
	/**
	 * check_usertable checks if the given tablename exists in db
	 * 
	 * @return boolean true if given table exists, false otherwise
	 */
	private function check_usertable($table) {
		
		// get tables
		$usertables = $this->get_usertables();
		
		// check if $table in $usertable
		if(in_array($table,$usertables,true)) {
			return true;
		} else {
			return false;
		}
	}
	
	
	
	
	
	
	
	/**
	 * list_table_content returns the content of the table as HTML-string 
	 * 
	 * @param string $table name of the table to list
	 * @param int $page number of page to list
	 * @return string table content as HTML
	 */
	private function list_table_content($table_name,$page) {
		
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
		
		// smarty
		$newlink = array(
				'params' => 'id="newLink"',
				'href' => $preface.'&action=new',
				'title' => parent::lang('class.AdministrationView#list_table_content#new#title'),
				'content' => parent::lang('class.AdministrationView#list_table_content#new#name')
			);
		$sTc->assign('newlink', $newlink);
		
		// get db-object
		$db = Db::newDb();
		
		// get pagelinks
		list($page, $pagelinks) = $this->pageLinks($table_name, $page);
		
		// assign pagelink data
		$sTc->assign('pagelinks', $pagelinks);
		
		// check if table has "usertableShow.$table" entry
		$configUsertableCols = $_SESSION['GC']->get_config('usertableCols.'.$table_name);
		if($configUsertableCols === false || $configUsertableCols == '') {
			$usertableCols = array();
			$skipUsertableCols = false;
		} else {
			$usertableCols = explode(',', $configUsertableCols);
			$skipUsertableCols = true;
		}
				
		// prepare statement
		$pagesize = $_SESSION['GC']->get_config('pagesize');
		$sql = "SELECT *
				FROM $table_name
				LIMIT ".$page * $pagesize.",$pagesize";
		
		// execute
		$result = $db->query($sql);
		
		// fetch result
		$index = $index2 = 0;
		$data = array();
		
		// get table info
		$tinfo = $result->fetch_fields();
		
		// prepare th
		$i = 1;
		$data[0]['th'][0]['content'] = parent::lang('class.AdministrationView#list_table_content#table#tasks').'&nbsp;'.$GLOBALS['help']->getMessage(HELP_MSG_ADMINUSERTABLETASKS);
		foreach($tinfo as $col) {
			
			// check usertableCols
			if(!in_array($col->name, $usertableCols) && $skipUsertableCols) {
				continue;
			}
			// check translation
			$translated_name = '';
			if(parent::lang('class.AdministrationView#tableRows#name#'.$col->name) != "class.AdministrationView#tableRows#name#$col->name not translated") {
				$translated_name = parent::lang('class.AdministrationView#tableRows#name#'.$col->name);
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
					'alt' => parent::lang('class.AdministrationView#list_table_content#table#edit'),
					'href' => $preface.'&action=edit&rid='.$row['id'],
					'title' => parent::lang('class.AdministrationView#list_table_content#table#edit').': '.$row['id']
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
					'alt' => parent::lang('class.AdministrationView#list_table_content#table#'.$status),
					'href' => $preface.'&action='.$status.'&rid='.$row['id'],
					'title' => parent::lang('class.AdministrationView#list_table_content#table#'.$status).': '.$row['id']
				);
			// add delete
			// smarty
			$data[$index]['td'][0]['delete'] = array(
					'src' => 'img/admin_delete.png',
					'alt' => parent::lang('class.AdministrationView#list_table_content#table#delete'),
					'href' => $preface.'&action=delete&rid='.$row['id'],
					'title' => parent::lang('class.AdministrationView#list_table_content#table#delete').': '.$row['id']
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
					$validImg->assign('statusTranslated', parent::lang('class.AdministrationView#list_table_content#table#'.$actualStatus));
					$value = $validImg->fetch('smarty.admin.valid_img.tpl');
					$data[$index]['td'][$index2]['escape'] = false;
				}
				
				// smarty
				$data[$index]['td'][$index2]['content'] = $value;
				
				// increment index2
				$index2++;
			}
			
			// increment index
			$index++;
		}
		$sTc->assign('data', $data);
		
		// return
		return $sTc->fetch('smarty.admin.table_content.tpl');
	}
	
	
	
	
	
	
	
	/**
	 * is_valid returns true if given row is valid, false otherwise
	 * 
	 * @param string $table table to check
	 * @param int $rid id of row to check
	 * @return boolean true if row->valid == 1, false otherwise
	 */
	private function is_valid($table,$rid) {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare statement
		$sql = "SELECT valid FROM $table WHERE id=$rid";
		
		// execute
		$result = $db->query($sql);
		
		// fetch result
		list($valid) = $result->fetch_array(MYSQL_NUM);
		
		// return
		if($valid == 1) {
			return true;
		} else {
			return false;
		}
	}
	
	
	
	
	
	
	
	/**
	 * row_exists returns true if given row exists, false otherwise
	 * 
	 * @param string $table table to work on
	 * @param int $rid id of row to check
	 * @return boolean true if row exists, false otherwise
	 */
	private function row_exists($table,$rid) {
		
		// check if $rid given
		if($rid !== false) {
			
			// get db-object
			$db = Db::newDb();
			
			// prepare statement
			$sql = "SELECT * FROM $table WHERE id=$rid";
			
			// execute
			$result = $db->query($sql);
			
			// get num_rows
			if($result->num_rows == 1) {
				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}
	}
	
	
	
	
	
	
	
	/**
	 * set_valid sets valid for the given rid
	 * 
	 * @param string $table table to work on
	 * @param int $rid id of row to set valid
	 * @param int $valid the value to set valid to
	 */
	private function set_valid($table,$rid,$valid) {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare statement
		$sql = "UPDATE $table SET valid=$valid WHERE id=$rid";
		
		// execute
		$result = $db->query($sql);
	}
	
	
	
	
	
	
	
	/**
	 * delete_row deletes the row for the given rid
	 * 
	 * @param string $table table to work on
	 * @param int $rid id of row to be deleted
	 * @return string HTML-String containing the confirmation form or message
	 */
	private function delete_row($table,$rid) {
		
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
		$form = new HTML_QuickForm2(
								'confirm',
								'post',
								array(
									'name' => 'confirm',
									'action' => $link.'&action=delete&rid='.$rid
								)
							);
		
		// add button
		$form->addElement('submit','yes',array('value' => parent::lang('class.AdministrationView#delete_row#form#yes')));
		
		// smarty-link
			$cancellink = array(
							'params' => '',
							'href' => $link,
							'title' => parent::lang('class.AdministrationView#delete_row#cancel#title'),
							'content' => parent::lang('class.AdministrationView#delete_row#cancel#form')
						);
			$sConfirmation->assign('link', $cancellink);
			$sConfirmation->assign('spanparams', 'id="cancel"');
			$sConfirmation->assign('message', parent::lang('class.AdministrationView#delete_row#message#confirm'));
			$sConfirmation->assign('form', $form);
		
		// validate
		if($form->validate()) {
		
			// get db-object
			$db = Db::newDb();
			
			// prepare statement
			$sql = "DELETE FROM $table WHERE id=$rid";
			
			// execute
			$result = $db->query($sql);
			
			// smarty
			$sConfirmation->assign('message', parent::lang('class.AdministrationView#delete_row#message#done'));
			$sConfirmation->assign('form', '');
			
			// smarty return
			$return = $sConfirmation->fetch('smarty.confirmation.tpl');
			
			// add table content
			$return .= $this->list_table_content($table,$this->get('page'));
			
			// return
			return $return;
		} else {
			
			// smarty return
			return $sConfirmation->fetch('smarty.confirmation.tpl');
		}
	}
	
	
	
	
	
	
	
	/**
	 * edit_row edits the row for the given rid
	 * 
	 * @param string $table table to work on
	 * @param int $rid id of row to edit
	 * @return string HTML-string for the form or message
	 */
	private function edit_row($table,$rid) {
		
		// prepare return
		$return = '';
		
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
		$sql = "SELECT * FROM $table WHERE id=$rid";
		
		// execute
		$result = $db->query($sql);
		
		// fetch result
		$row = $result->fetch_array(MYSQL_ASSOC);
		
		// prepare form
		$form = new HTML_QuickForm2(
						'edit_field',
						'post',
						array(
							'name' => 'edit_field',
							'action' => $link.'&action=edit&rid='.$rid
						)
					);
		
		// renderer
		$renderer = HTML_QuickForm2_Renderer::factory('default');
		$renderer->setOption('required_note',parent::lang('class.AdministrationView#edit_row#form#requiredNote'));
		
		// get values and fields
		$i = 0;
		$datasource = array();
		$fields = array();
		foreach($row as $col => $value) {
			
			// check translation
			$translated_name = '';
			if(parent::lang('class.AdministrationView#tableRows#name#'.$col) != "class.AdministrationView#tableRows#name#$col not translated") {
				$translated_col = parent::lang('class.AdministrationView#tableRows#name#'.$col);
			} else {
				$translated_col = $col;
			}
			
			// check category
			if($col == 'category') {
				
				// get options
				$cat_sql = "SELECT id,name FROM category WHERE valid=1";
				$cat_result = $db->query($cat_sql);
				$options = array('--');
				while(list($id,$name) = $cat_result->fetch_array(MYSQL_NUM)) {
					$options[$id] = $name;
				}
				
				// add value
				$datasource[$col] = $value;
				
				// select
				$field = $form->addElement('select',$col,array());
				$field->setLabel($translated_col.':');
				
				// load options
				$field->loadOptions($options);
				
				// add rules
				if($table == 'defaults') {
					$field->addRule('required',parent::lang('class.AdministrationView#edit_row#rule#requiredSelect'));
					$field->addRule('callback',parent::lang('class.AdministrationView#edit_row#rule#checkSelect'),array($this,'callback_check_select'));
				}
			} else {
				
				// check id or valid
				if($col != 'id' && $col != 'valid') {
					
					// get fieldconfig
					// 252 = text, 253 = varchar; 1 = tinyint(boolean); 3 = int
					$field_config = $result->fetch_field_direct($i);
					
					// add value
					$datasource[$col] = $value;
					
					// add field
					$field = null;
					// check type
					if($field_config->type == 252) {
						
						// textarea
						$field = HTML_QuickForm2_Factory::createElement('textarea',$col,array());
						$field->setLabel($translated_col.':');
						
						// add rule
						$field->addRule(
							'regex',
							parent::lang('class.AdministrationView#edit_row#rule#regexp.allowedChars').' ['.$_SESSION['GC']->get_config('textarea.desc').']',
							$_SESSION['GC']->get_config('textarea.regexp'));
						// required
						if($table == 'defaults') {
							$field->addRule('required',parent::lang('class.AdministrationView#edit_row#rule#required'));
						}
					} elseif($field_config->type == 253 || $field_config->type == 3) {
						
						// input
						$field = HTML_QuickForm2_Factory::createElement('text',$col,array());
						$field->setLabel($translated_col.':');
						
						// add rule
						$field->addRule(
							'regex',
							parent::lang('class.AdministrationView#edit_row#rule#regexp.allowedChars').' ['.$_SESSION['GC']->get_config('textarea.desc').']',
							$_SESSION['GC']->get_config('textarea.regexp'));
						// required
						if($table == 'defaults') {
							$field->addRule('required',parent::lang('class.AdministrationView#edit_row#rule#required'));
						}
					} elseif($field_config->type == 1) {
						
						// input
						$field = HTML_QuickForm2_Factory::createElement('checkbox',$col,array());
						$field->setLabel($translated_col.':');
					}
					$fields[] = $field;
				}
			}
			
			// increment field-counter
			$i++;
		}
			
		// add datasource
		$form->addDataSource(new HTML_QuickForm2_DataSource_Array($datasource));
		
		// add fields
		foreach($fields as $field) {
			$form->appendChild($field);
		}
		
		// submit-button
		$form->addSubmit('submit',array('value' => parent::lang('class.AdministrationView#edit_row#form#submitButton')));
		
		// validate
		if($form->validate()) {
			
			// set output
			$return .= $this->p(' class="edit_caption"',parent::lang('class.AdministrationView#edit_row#caption#done').': "'.$rid.'"');
			
			// get data
			$data = $form->getValue();
			
			// prepare statement
			$sql = "UPDATE $table SET ";
			foreach($data as $field => $value) {
				
				// check translation
				$translated_field = '';
				if(parent::lang('class.AdministrationView#tableRows#name#'.$field) != "class.AdministrationView#tableRows#name#$field not translated") {
					$translated_field = parent::lang('class.AdministrationView#tableRows#name#'.$field);
				} else {
					$translated_field = $field;
				}
				
				// check field
				if(substr($field,0,5) != '_qf__' && $field != 'submit') {
					
					// add fields to sql
					$sql .= "$field='$value', ";
					
					// add fields to output
					$return .= $this->p('',"$translated_field = '".nl2br(htmlentities(utf8_decode($value)))."'");
				}
			}
			$sql = substr($sql,0,-2);
			$sql .= " WHERE id=$rid";
			
			// execute
			$result = $db->query($sql);
			
			// add table content
			$return .= $this->list_table_content($table,$this->get('page'));
		} else {
			$return .= $this->p('',parent::lang('class.AdministrationView#edit_row#caption#edit').': "'.$rid.'"');
			$return .= $form->render($renderer);
		}
		
		// return
		return $return;
	}
	
	
	
	
	
	
	
	/**
	 * new_row inserts a new row in $table
	 * 
	 * @param string $table table to insert row
	 * @return string HTML-string for the form or message
	 */
	private function new_row($table) {
		
		// prepare return
		$return = '';
		
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
		$sql = "SELECT * FROM $table";
		
		// execute
		$result = $db->query($sql);
		
		// table info
		$tinfo = $result->fetch_fields();
		
		// prepare form
		$form = new HTML_QuickForm2(
						'new_'.$table,
						'post',
						array(
							'name' => 'new_'.$table,
							'action' => $link.'&action=new'
						)
					);
		// add datasource (valid = 1)
		$datasource['valid'] = 1;
		$form->addDataSource(new HTML_QuickForm2_DataSource_Array($datasource));
		
		// renderer
		$renderer = HTML_QuickForm2_Renderer::factory('default');
		$renderer->setOption('required_note',parent::lang('class.AdministrationView#new_row#form#requiredNote'));
		
		// get values and fields
		$i = 0;
		$fields = array();
		
		foreach($tinfo as $col) {
			
			// check translation
			$translated_col = '';
			if(parent::lang('class.AdministrationView#tableRows#name#'.$col->name) != "class.AdministrationView#tableRows#name#$col->name not translated") {
				$translated_col = parent::lang('class.AdministrationView#tableRows#name#'.$col->name);
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
					$cat_sql = "SELECT id,name FROM category WHERE valid=1";
					$cat_result = $db->query($cat_sql);
					$options = array('--');
					while(list($id,$name) = $cat_result->fetch_array(MYSQL_NUM)) {
						$options[$id] = $name;
					}
					
					// select
					$field = $form->addElement('select',$col->name,array());
					$field->setLabel($translated_col.':');
					
					// load options
					$field->loadOptions($options);
					
					// add rules
					if($table == 'defaults') {
						$field->addRule('required',parent::lang('class.AdministrationView#new_row#rule#requiredSelect'));
						$field->addRule('callback',parent::lang('class.AdministrationView#new_row#rule#checkSelect'),array($this,'callback_check_select'));
					}
				} else {
					
					// check type
					if($col->type == 252) {
						
						// textarea
						$field = $form->addElement('textarea',$col->name,array());
						$field->setLabel($translated_col.':');
						
						// add rules
						$field->addRule(
							'regex',
							parent::lang('class.AdministrationView#new_row#rule#regexp.allowedChars').' ['.$_SESSION['GC']->get_config('textarea.desc').']',
							$_SESSION['GC']->get_config('textarea.regexp'));
						// required
						if($table == 'defaults') {
							$field->addRule('required',parent::lang('class.AdministrationView#new_row#rule#required'));
						}
					} elseif($col->type == 253 || $col->type == 3) {
						
						// input
						$field = $form->addElement('text',$col->name,array());
						$field->setLabel($translated_col.':');
						
						// add rules
						$field->addRule(
							'regex',
							parent::lang('class.AdministrationView#new_row#rule#regexp.allowedChars').' ['.$_SESSION['GC']->get_config('textarea.desc').']',
							$_SESSION['GC']->get_config('textarea.regexp'));
						// required
						if($table == 'defaults') {
							$field->addRule('required',parent::lang('class.AdministrationView#new_row#rule#required'));
						}
					} elseif($col->type == 1) {
						
						// input
						$field = $form->addElement('checkbox',$col->name,array());
						$field->setLabel($translated_col.':');
					}
				}
			}
			
			// increment field-counter
			$i++;
		}
		
		// submit-button
		$form->addSubmit('submit',array('value' => parent::lang('class.AdministrationView#new_row#form#submitButton')));
		
		// validate
		if($form->validate()) {
			
			// set output
			$return .= $this->p('class="edit_caption"',parent::lang('class.AdministrationView#new_row#caption#done'));
			
			// get data
			$data = $form->getValue();
			
			// prepare statement
			$sql = "INSERT INTO $table ";
			$sql_field = "(id,";
			$sql_value = " VALUES (NULL,";
			foreach($data as $field => $value) {
				
				// check translation
				$translated_field = '';
				if(parent::lang('class.AdministrationView#tableRows#name#'.$field) != "class.AdministrationView#tableRows#name#$field not translated") {
					$translated_field = parent::lang('class.AdministrationView#tableRows#name#'.$field);
				} else {
					$translated_field = $field;
				}
				
				// check field
				if(substr($field,0,5) != '_qf__' && $field != 'submit') {
					
					// add fields to sql
					$sql_field .= "$field,";
					$sql_value .= "'$value',";
					
					// add fields to output
					$return .= $this->p('',"$translated_field = '".nl2br(htmlentities(utf8_decode($value)))."'");
				}
			}
			$sql_field = substr($sql_field,0,-1).")";
			$sql_value = substr($sql_value,0,-1).")";
			$sql .= $sql_field.$sql_value;
			
			// execute
			$result = $db->query($sql);
			
			// add table content
			$return .= $this->list_table_content($table,$this->get('page'));
		} else {
			$return .= $this->p('',parent::lang('class.AdministrationView#new_row#caption#edit'));
			$return .= $form->render($renderer);
		}
		
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
			$this->tpl->assign('pagecaption',parent::lang('class.AdministrationView#page#caption#defaults'));
			
			// check if row exists
			if($this->row_exists('defaults',$rid) || $this->get('action') == 'new') {
				
				// check $_GET['action']
				if($this->get('action') == 'new') {
					$content .= $this->new_row('defaults');				
				} elseif($this->get('action') == 'edit') {
					$content .= $this->edit_row('defaults',$rid);
				} elseif($this->get('action') == 'disable') {
						
					// check if row is enabled
					if($this->is_valid('defaults',$rid)) {
						
						// set valid 0
						$this->set_valid('defaults',$rid,0);
						
						// list table content
						$content .= $this->list_table_content('defaults',$this->get('page'));
					} else {
						
						// give link to enable
						// smarty
						$sE = new JudoIntranetSmarty();
						$sE->assign('message', parent::lang('class.AdministrationView#defaults#disable#rowNotEnabled'));
						$sE->assign('href', 'administration.php?id='.$this->get('id').'&action=enable&rid='.$rid);
						$sE->assign('title', parent::lang('class.AdministrationView#defaults#disable#rowNotEnabled.enable'));
						$sE->assign('content', parent::lang('class.AdministrationView#defaults#disable#rowNotEnabled.enable'));
						
						$content .= $sE->fetch('smarty.admin.dis-enable.tpl');
					}
				} elseif($this->get('action') == 'enable') {
					
					// check if row is disabled
					if(!$this->is_valid('defaults',$rid)) {
						
						// set valid 1
						$this->set_valid('defaults',$rid,1);
						
						// list table content
						$content .= $this->list_table_content('defaults',$this->get('page'));
					} else {
						
						// give link to disable
						// smarty
						$sE = new JudoIntranetSmarty();
						$sE->assign('message', parent::lang('class.AdministrationView#defaults#enable#rowNotDisabled'));
						$sE->assign('href', 'administration.php?id='.$this->get('id').'&action=disable&rid='.$rid);
						$sE->assign('title', parent::lang('class.AdministrationView#defaults#enable#rowNotDisabled.disable'));
						$sE->assign('content', parent::lang('class.AdministrationView#defaults#enable#rowNotDisabled.disable'));
						
						$content .= $sE->fetch('smarty.admin.dis-enable.tpl');
					}				
				} elseif($this->get('action') == 'delete') {
					$content .= $this->delete_row('defaults',$rid);
				} else {
					$content .= $this->list_table_content('defaults',$this->get('page'));
				}
			} else {
				$errno = $GLOBALS['Error']->error_raised('RowNotExists',$this->get('rid'));
				$GLOBALS['Error']->handle_error($errno);
				return $GLOBALS['Error']->to_html($errno);
			}
		} else {
			
			// add default content
			$content .= $this->list_table_content('defaults',$this->get('page'));
		
		}
		
		// smarty
		$this->tpl->assign('caption', parent::lang('class.AdministrationView#defaults#caption#name'));
		$this->tpl->assign('tablelinks', '');
		
		// return
		return $content;
	}
}



?>
