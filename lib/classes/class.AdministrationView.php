<?php


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
			$this->tpl->assign('main', '<h2>default content</h2>');
			// smarty-jquery
			$this->tpl->assign('jquery', true);
			// smarty-hierselect
			$this->tpl->assign('hierselect', false);
		}
		
		// global smarty
		// head
		$this->tpl->assign('head', $this->get_head());
		// manualjquery
		$this->tpl->assign('manualjquery', $this->get_jquery());
		// navi
		$this->tpl->assign('data', $this->navi(basename($_SERVER['SCRIPT_FILENAME'])));
		$this->tpl->assign('active', $this->get('id'));
		$this->tpl->assign('file', basename($_SERVER['SCRIPT_FILENAME']));
		// logininfo
		$this->tpl->assign('logininfo', $this->put_userinfo());
		
		// smarty-display
		$this->tpl->display('smarty.admin.tpl');
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
			
			// set caption
			$this->tpl->assign('caption', parent::lang('class.AdministrationView#field#caption#name.table').'"'.$this->get('field').'"');
			
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
			
			// add default content
			$content .= $this->default_content();
		
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
			
				// smarty
				$data[] = array(
						'params' => 'class="usertable"',
						'href' => 'administration.php?id='.$this->get('id').'&field='.$table,
						'title' => '\''.$table.'\''.parent::lang('class.AdministrationView#create_table_links#title#manage'),
						'content' => '\''.$table.'\''.parent::lang('class.AdministrationView#create_table_links#name#manage')
					);
			}
		}
		
		$sTl->assign('data', $data);
		
		// add slider-link
		// smarty
		$sTl->assign('title', parent::lang('class.AdministrationView#create_table_links#toggleTable#title'));
		$sTl->assign('content', parent::lang('class.AdministrationView#create_table_links#toggleTable#name'));
		$sTl->assign('params', 'id="toggleTable"');
		$sTl->assign('href', '#');
		
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
	 * default_content returns the html-content to be displayed on page without
	 * parameters or functions
	 * 
	 * @return string html-content as default content
	 */
	private function default_content() {
		
		// prepare return
		$return = '';
		
		// content
		$return .= $this->p('','default content');
					
		// return
		return $return;
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
		$link = '';
		if($table_name == 'defaults') {
			$link = 'administration.php?id='.$this->get('id');
		} else {
			$link = 'administration.php?id='.$this->get('id').'&field='.$table_name;
		}
		
		// prepare return
		$return = '';
		
		// smarty
		$newlink = array(
				'params' => 'id="newLink"',
				'href' => $link.'&action=new',
				'title' => parent::lang('class.AdministrationView#list_table_content#new#title'),
				'content' => parent::lang('class.AdministrationView#list_table_content#new#name')
			);
		$sTc->assign('newlink', $newlink);
		
		// get db-object
		$db = Db::newDb();
		
		// prepare statement
		$sql = "SELECT COUNT(*)
				FROM $table_name";
		
		// execute
		$result = $db->query($sql);
		
		// fetch rows
		list($rows) = $result->fetch_array(MYSQL_NUM);
		
		// get total pages
		$pagesize = $_SESSION['GC']->get_config('pagesize');
		$total_pages = ceil($rows / $pagesize);
		
		// generate page-links
		// smarty
		$sTc->assign('pages', parent::lang('class.AdministrationView#list_table_content#pages#pages'));
		$pagelinks = array();
		for($i=1;$i<=$total_pages;$i++) {
			
			// smarty
			$pagelinks[] = array(
					'params' => 'class="pagelinks"',
					'href' => $link.'&page='.$i,
					'title' => parent::lang('class.AdministrationView#list_table_content#pages#page').' '.$i,
					'content' => $i
				);
		}
		$sTc->assign('pl', $pagelinks);
		
		// get rows from db
		// prepare LIMIT
		if($page === false || ($page - 1) * $pagesize >= $rows) {
			$page = 0;
		} else {
			$page -= 1;
		}
		
		// add rows
		// check last
		$last = $page * $pagesize + $pagesize;
		if(($page * $pagesize + $pagesize) > $rows) {
			$last = $rows;
		}
		// smarty
		$toOf = " (".($page * $pagesize + 1)." ".
						parent::lang('class.AdministrationView#list_table_content#pages#to')." $last ".
						parent::lang('class.AdministrationView#list_table_content#pages#of')." $rows)";
		$sTc->assign('toof', $toOf);
		
		// prepare statement
		$sql = "SELECT *
				FROM $table_name
				LIMIT ".$page * $pagesize.",$pagesize";
		
		// execute
		$result = $db->query($sql);
		
		// fetch result
		$index = $index2 = 0;
		$th_out = '';
		$tr_out = '';
		
		while($row = $result->fetch_array(MYSQL_ASSOC)) {
			
			$index2 = 0;
			
			// add edit
			// smarty
			$data[$index]['td'][$index2]['edit'] = array(
					'src' => 'img/admin_edit.png',
					'alt' => parent::lang('class.AdministrationView#list_table_content#table#edit'),
					'href' => $link.'&action=edit&rid='.$row['id'],
					'title' => parent::lang('class.AdministrationView#list_table_content#table#edit').': '.$row['id']
				);
			
			// add disable/enable
			// check status
			$status = '';
			if($row['valid'] == 0) {
				$status = 'enable';
			} else {
				$status = 'disable';
			}
			// smarty
			$data[$index]['td'][$index2]['disenable'] = array(
					'src' => 'img/admin_'.$status.'.png',
					'alt' => parent::lang('class.AdministrationView#list_table_content#table#'.$status),
					'href' => $link.'&action='.$status.'&rid='.$row['id'],
					'title' => parent::lang('class.AdministrationView#list_table_content#table#'.$status).': '.$row['id']
				);
			// add delete
			// smarty
			$data[$index]['td'][$index2]['delete'] = array(
					'src' => 'img/admin_delete.png',
					'alt' => parent::lang('class.AdministrationView#list_table_content#table#delete'),
					'href' => $link.'&action=delete&rid='.$row['id'],
					'title' => parent::lang('class.AdministrationView#list_table_content#table#delete').': '.$row['id']
				);
			
			// walk through $row
			foreach($row as $name => $value) {
				
				// check category
				if($name == 'category') {
					
					// get name for category
					$cat_sql = "SELECT name FROM category WHERE id=$value";
					$cat_result = $db->query($cat_sql);
					list($value) = $cat_result->fetch_array(MYSQL_NUM);
				}
				// check index
				if($index == 0) {
					
					// check translation
					$translated_name = '';
					if(parent::lang('class.AdministrationView#tableRows#name#'.$name) != "class.AdministrationView#tableRows#name#$name not translated") {
						$translated_name = parent::lang('class.AdministrationView#tableRows#name#'.$name);
					} else {
						$translated_name = $name;
					}
					// smarty
					if($index2 == 0) {
						$data[$index]['th'][$index2]['content'] = parent::lang('class.AdministrationView#list_table_content#table#tasks');
					} else {
						$data[$index]['th'][$index2]['content'] = $translated_name;
					}
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
		
		// fetch result
		$row = $result->fetch_array(MYSQL_ASSOC);
		
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
		foreach($row as $col => $value) {
			
			// check translation
			$translated_name = '';
			if(parent::lang('class.AdministrationView#tableRows#name#'.$col) != "class.AdministrationView#tableRows#name#$col not translated") {
				$translated_col = parent::lang('class.AdministrationView#tableRows#name#'.$col);
			} else {
				$translated_col = $col;
			}
			
			// check id
			if($col != 'id') {
				
				// get fieldconfig
				// 252 = text, 253 = varchar; 1 = tinyint(boolean); 3 = int
				$field_config = $result->fetch_field_direct($i);
				
				// add field
				$field = null;
				// check category
				if($col == 'category') {
					
					// get options
					$cat_sql = "SELECT id,name FROM category WHERE valid=1";
					$cat_result = $db->query($cat_sql);
					$options = array('--');
					while(list($id,$name) = $cat_result->fetch_array(MYSQL_NUM)) {
						$options[$id] = $name;
					}
					
					// select
					$field = $form->addElement('select',$col,array());
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
					if($field_config->type == 252) {
						
						// textarea
						$field = $form->addElement('textarea',$col,array());
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
					} elseif($field_config->type == 253 || $field_config->type == 3) {
						
						// input
						$field = $form->addElement('text',$col,array());
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
					} elseif($field_config->type == 1) {
						
						// input
						$field = $form->addElement('checkbox',$col,array());
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
