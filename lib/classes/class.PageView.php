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
 * class PageView implements the control of a page
 */
class PageView extends Object {
	
	/*
	 * class-variables
	 */
	
	/*
	 * getter/setter
	 */
	public function get_output(){
		return $GLOBALS['output'];
	}
	public function set_output($output) {
		return $GLOBALS['output'] = $output;
	}
	public function get_jquery(){
		return $GLOBALS['jquery'];
	}
	public function set_jquery($jquery) {
		return $GLOBALS['jquery'] = $jquery;
	}
	public function get_head(){
		return $GLOBALS['head'];
	}
	public function set_head($head) {
		return $GLOBALS['head'] = $head;
	}
	public function getTpl() {
		return $GLOBALS['tpl'];
	}
	public function getAddedWebserviceRunner() {
		return $GLOBALS['addedWebserviceRunner'];
	}
	public function setAddedWebserviceRunner($addedWebserviceRunner) {
		return $GLOBALS['addedWebserviceRunner'] = $addedWebserviceRunner;
	}
	public function getJsTranslation(){
		return $GLOBALS['jsTranslation'];
	}
	public function setJsTranslation($jsTranslation) {
		return $GLOBALS['jsTranslation'] = $jsTranslation;
	}
	
	/*
	 * constructor/destructor
	 */
	public function __construct() {
		
		// parent constructor
		parent::__construct();
		
		// init smarty
		if(!isset($GLOBALS['tpl'])) {
			$GLOBALS['tpl'] = new JudoIntranetSmarty();
		}
		
		// set class-variables
		if(!isset($GLOBALS['output'])) {
			$this->set_output(array());
		}
		if(!isset($GLOBALS['jquery'])) {
			$this->set_jquery('');
		}
		if(!isset($GLOBALS['head'])) {
			$this->set_head('');
		}
		if(!isset($GLOBALS['addedWebserviceRunner'])) {
			$this->setAddedWebserviceRunner(false);
		}
		if(!isset($GLOBALS['jsTranslation'])) {
			$this->setJsTranslation(array());
		}
		
		// set logo
		$this->getTpl()->assign('systemLogo', 'img/'.$this->getGc()->get_config('global.systemLogo'));
		
		// assign language
		$shortLang = explode('_', $this->getUser()->get_lang());
		$this->getTpl()->assign('lLang', $this->getUser()->get_lang());
		$this->getTpl()->assign('sLang', $shortLang[0]);
		
		// check if webservice runner is added and add if not
		if($this->getUser()->get_loggedin() === true) {
			$this->addWebserviceRunner();
		}
	}
	
	/*
	 * methods
	 */
	/**
	 * add_output adds the given string to $output
	 * 
	 * @param array $content content to be added to $output
	 * @param bool $reset replaces the output if true, adds if false
	 */
	public function add_output($content,$reset=false) {
		
		// get output
		$output = $this->get_output();
		
		// add or replace
		if($reset === true) {
			
			// walk through array (replace)
			foreach($content as $name => $value) {
				$output[$name] = $value;
			}
		} else {
			
			// walk through array (add)
			foreach($content as $name => $value) {
				if(isset($output[$name])) {
					$output[$name] .= $value;
				} else {
					$output[$name] = $value;
				}
			}
		}
		
		// set output
		$this->set_output($output);
	}
	
	
	
	
	
	
	/**
	 * add_jquery adds the given string to $output
	 * 
	 * @param string $content content to be added to $jquery
	 * @param bool $reset replaces the output if true, adds if false
	 */
	public function add_jquery($content,$reset=false) {
		
		// minimize javascript (remove \n)
		$javaScript = '';
		$lines = explode("\n", $content);
		foreach($lines as $line) {
			
			// remove whitespaces
			$line = trim($line);
			// add to string
			$javaScript .= $line;
		}
		
		// get jquery
		$jquery = $this->get_jquery();
		
		// add or replace
		if($reset === true) {
			
			// replace
			$jquery = $javaScript."\n";
		} else {
			
			// add
			$jquery .= $javaScript."\n";
		}
		
		// set jquery
		$this->set_jquery($jquery);
	}
	
	
	
	
	
	
	/**
	 * add_heads adds the given string to $head
	 * 
	 * @param string $content content to be added to $head
	 */
	public function add_head($content) {
		
		// get head
		$head = $this->get_head();
		
		// add and set back
		$this->set_head($head.$content."\n");
	}
	
	
	
	
	
	/**
	 * title combines the title-prefix and the given title and returns it
	 * 
	 * @param string $title title to be combined
	 * @return string combined title and prefix
	 */
	public function title($title) {
		
		// return combined prefix and title
		return _l('JudoIntranet').' '.$title;
	}
	
	
	
	
	
	
	
	
	/**
	 * p parses the given text and parameters in standard-p-tag
	 * 
	 * @param string $param parameters for p-tag
	 * @param string $string string to be parsed in p-tag
	 * @return string in p-tag parsed string and parameters
	 */
	protected function p($param,$string) {
		
		// smarty
		$sP = new JudoIntranetSmarty();
		
		// prepare contents
		// smarty
		$sP->assign('params', $param);
		$sP->assign('content', $string);

		// return
		return $sP->fetch('smarty.p.tpl');
	}
	
	
	
	
	
	
	
	
	
	/**
	 * put_userinfo sets the userinfo of the actual user on page
	 * 
	 * @return void
	 */
	protected function put_userinfo() {
		
		// smarty-templates
		$sUserLink = new JudoIntranetSmarty();
		$sLogoutLink = new JudoIntranetSmarty();
		$sLogoutImg = new JudoIntranetSmarty();
		$sUsersettings = new JudoIntranetSmarty();
		// jquery element ids
		$toToggle = 'usersettings';
		$id = 'toggleUsersettings';
		
		// check if userinfo exists and set to output
		$name = $this->getUser()->get_userinfo('name');
		if($this->getUser()->get_loggedin() !== false) {
			
			// smarty-link
			$spanUserLink = array(
					'params' => 'id="'.$id.'"',
					'title' => _l('toggle usersettings'),
					'content' => $name,	
				);
			$sUserLink->assign('span', $spanUserLink);
			$link = $sUserLink->fetch('smarty.span.tpl');
			
			// logout img and link
			$logoutArray = array(
					'params' => '',
					'src' => 'img/logout.png',
					'alt' => _l('logout'),
				);
			$sLogoutImg->assign('img', $logoutArray);
			$logoutImg = $sLogoutImg->fetch('smarty.img.tpl');
			
			$sLogoutLink->assign('params', '');
			$sLogoutLink->assign('href', 'index.php?id=logout');
			$sLogoutLink->assign('title', _l('logout'));
			$sLogoutLink->assign('content', $logoutImg._l('logout'));
			$logout = $sLogoutLink->fetch('smarty.a.tpl');
			
			// smarty-usersettings
			$usersettings = array(
					0 => array(	
							'params' => 'class="'.$toToggle.'"',
							'href' => 'index.php?id=user&action=passwd',
							'title' => _l('change password'),
							'content' => _l('change password')
						),
					1 => array(	
							'params' => 'class="'.$toToggle.'"',
							'href' => 'index.php?id=user&action=data',
							'title' => _l('change usersettings'),
							'content' => _l('change usersettings')
						),
				);
			
			$sUsersettings->assign('us', $usersettings);
			
			// smarty jquery
			$this->getTpl()->assign('usersettingsJsId', '#'.$id);
			$this->getTpl()->assign('usersettingsJsToToggle', '#'.$toToggle);
			$this->getTpl()->assign('usersettingsJsTime', '');
			
			// smarty return
			return _l('logged in as').' '.$link.' ('.$this->getUser()->get_userinfo('username').') '.$logout.$sUsersettings->fetch('smarty.usersettings.tpl');
		} else {
			// smarty return
			return _l('not loggedin');
		}
	}
	
	
	
	
	
	
	
	
	
	/**
	 * defaultContent returns the content if nothing else is given
	 * 
	 * @return string default content as html-string
	 */
	protected function defaultContent() {
		
		// smatry-template
		$sD = new JudoIntranetSmarty();
					
		// return
		return $sD->fetch('smarty.default.content.tpl');
	}
	
	
	
	
	
	
	
	
	
	/**
	 * showPage() sets some global template variables and displays the page
	 * 
	 * @param string $template name of the template file to use
	 * @return void
	 */
	public function showPage($template) {
		
		// logininfo
		$this->getTpl()->assign('logininfo', $this->put_userinfo());
		
		// head
		$this->getTpl()->assign('head', $this->get_head());
		
		// manualjquery
		$this->getTpl()->assign('manualjquery', $this->get_jquery());
		
		// javascript translation
		$this->getTpl()->assign('globalTranslation', json_encode($this->getJsTranslation()));
		
		// navi
		$navi = '';
		$file = basename($_SERVER['SCRIPT_FILENAME']);
		$param = $this->get('id');
		$naviItems = $this->naviFromDb();
		$active = 0;
		
		// get navi position for fileParam
		$naviPosition = array();
		foreach($naviItems as $position => $naviItem) {
			list($positionFile, $positionParam) = explode('|',$naviItem->getFileParam());
			$naviPosition[$positionFile] = $position;
		}
		
		// walk through $naviItems
		for($i=0; $i<count($naviItems); $i++){
			
			// check active
			list($thisFile, $thisParam) = explode('|',$naviItems[$i]->getFileParam());
			// check different php files
			if($file == 'announcement.php') {
				$active = $naviPosition['calendar.php'];
			} else {
				if($thisFile == $file) {
					$active = $i;
				}
			}
			
			// generate HTML
			$navi .= $naviItems[$i]->output($file, $param).PHP_EOL;
		}
		$this->getTpl()->assign('accordionActive', $active);
		$this->getTpl()->assign('navigation', $navi);
		
		// check config for accordion
		if($this->getGc()->get_config('navi.style') == 'accordion') {
			$this->getTpl()->assign('accordionJs', true);
		}
		
		// smarty-display
		$this->getTpl()->display($template);
	}
	
	
	
	
	
	
	
	
	
	/**
	 * initHelp() sets the global helpmessages
	 * 
	 * @return void
	 */
	protected function initHelp() {
		
		// help messages
		$help = array(
				'buttonClass' => $this->getGc()->get_config('help.buttonClass'),
				'effect' => $this->getGc()->get_config('help.effect'),
				'effectDuration' => $this->getGc()->get_config('help.effectDuration'),
				'closeText' => _l('close'),
			);
		$this->getTpl()->assign('help', $help);
		
		// assign about
		$helpabout = $this->helpButton(HELP_MSG_ABOUT);
		$this->getTpl()->assign('helpabout', $helpabout);
	}
	
	
	
	
	
	
	
	
	
	/**
	 * pageLinks() generates the information of the pages to be used in "smarty.pagelinks.tpl"
	 * template
	 * 
	 * @param string $table table name for which the result should be paged
	 * @param int $page actual page
	 * @return array array containing the information of the generated pages
	 */
	protected function pageLinks($table, $page) {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare statement
		$sql = "SELECT COUNT(*)
				FROM $table";
		
		// execute
		$result = $db->query($sql);
		
		// fetch rows
		list($rows) = $result->fetch_array(MYSQL_NUM);
		
		// get total pages
		$pagesize = $this->getGc()->get_config('pagesize');
		$total_pages = ceil($rows / $pagesize);
		
		$pagelinks = array();
		for($i=1;$i<=$total_pages;$i++) {
			
			// check if active page
			$params = 'class="pagelinks"';
			if($i == $page || ($page === false && $i == 1)) {
				$params = 'class="pagelinks active"';
			}
			$pagelinks['links'][] = array(
					'params' => $params,
					'href' => '&page='.$i,
					'title' => _l('page').' '.$i,
					'content' => $i
				);
		}
		
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
		
		// assign "from - to - of"
		$pagelinks['toof'] = " (".($page * $pagesize + 1)." ".
				_l('page to')." $last ".
				_l('of pages')." $rows)";
		
		// assign "pages"
		$pagelinks['pages'] = _l('pages');
		
		// return
		return array($page, $pagelinks);
	}
	
	
	/**
	 * naviFromDb() reads all navigation trees from database
	 * 
	 * @return array array containing the navigation subtrees as objects
	 */
	private function naviFromDb() {
		
		// get db object
		$db = Db::newDb();
		
		// prepare sql statement to get subgroups
		$sql = 'SELECT id
				FROM navi
				WHERE `parent`=\''.$db->real_escape_string(0).'\'
				AND `show`=\''.$db->real_escape_string(1).'\'';
		
		// execute statement
		$result = $db->query($sql);
		
		// close db
		$db->close();
		
		// get data
		$naviItems = array();
		if($result) {
			
			while(list($naviId) = $result->fetch_array(MYSQL_NUM)) {
				
				// get navi object
				$tempNavi = new Navi($naviId);
				
				// check permissions
				if($tempNavi->subItemsPermitted()) {
					$naviItems[] = $tempNavi;
				}
			}
		} else {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// sort by position and return
		usort($naviItems, array($this, 'callbackSortNavi'));
		return $naviItems;
	}
	
	
	/**
	 * zebraAddPermissions($form, $values) adds the choose-permissions dialog to the
	 * given $form and sets their values if any
	 * 
	 * @param object $form the zebra_form object the permissions will be added
	 * @param string $itemTable name of the table (object type)
	 * @param int $itemId id of the item the permissions will be added
	 * @return array containing the modified $form object and an array containing the header etc.
	 */
	protected function zebraAddPermissions(&$form, $itemTable, $itemId=0) {
		
		// prepare return
		$return = array(
				'iconRead' => array(),
				'iconEdit' => array(),
			);
		$formIds = array();
		
		// prepare clear radio
		$this->getTpl()->assign('permissionJs', true);
		// prepare tabs
		$this->getTpl()->assign('tabsJs', true);
		
		
		// get groups
		$allGroups = $this->getUser()->allGroups();
		$groups = $allGroups;
		
		// walk through groups
		foreach($groups as $group) {
			
			// exclude public, admin and own groups if not admin
			if(($this->getUser()->isAdmin() 
					&& $group->getId() != 0 
					&& $group->getId() != 1) 
				|| ($group->getId() != 0 
					&& $group->getId() != 1 
					&& $this->getUser()->isMemberOf($group->getId()) === false)) {
				
				// set id name
				$radioName = 'group_'.$group->getId();
				$formIds[$radioName] = array('valueType' => 'int', 'type' => 'radios', 'default' => 1);
				
				// prepare images
				// read
				$imgReadText = _l('read/list');
				$imgRead = array(
						'params' => 'class="iconRead clickable" title="'.$imgReadText.'" onclick="selectRadio(\''.$radioName.'_r\')"',
						'src' => 'img/permissions_read.png',
						'alt' => $imgReadText,
					);
				$sImgReadTemplate = new JudoIntranetSmarty();
				$sImgReadTemplate->assign('img', $imgRead);
				// add to $return
				$return['iconRead'][$radioName] = $sImgReadTemplate->fetch('smarty.img.tpl');
				
				// edit
				$imgEditText = _l('edit');
				$imgEdit = array(
						'params' => 'class="iconEdit clickable" title="'.$imgEditText.'" onclick="selectRadio(\''.$radioName.'_w\')"',
						'src' => 'img/permissions_edit.png',
						'alt' => $imgEditText,
					);
				$sImgEditTemplate = new JudoIntranetSmarty();
				$sImgEditTemplate->assign('img', $imgEdit);
				// add to $return
				$return['iconEdit'][$radioName] = $sImgEditTemplate->fetch('smarty.img.tpl');
				
				// prepare clear radio link
				$sImgClearRadio = new JudoIntranetSmarty();
				$img = array(
						'params' => 'class="clickable" onclick="clearRadio(\''.$radioName.'\')" title="'._l('remove permissions').'"',
						'src' => 'img/permissions_delete.png',
						'alt' => _l('remove permissions'),
					);
				$sImgClearRadio->assign('img', $img);
				
				// add radios
				$form->add(
						'label',		// type
						'label'.ucfirst($radioName),	// id/name
						$radioName,		// for
						$group->getName()	// label text
					);
				$form->add(
						$formIds[$radioName]['type'],	// type
						$radioName,						// id/name
						array(				// values
								'r' => 'r',
								'w' => 'w',
							),
						$group->permissionFor($itemTable, $itemId)	// default
					);
				$form->add(
						'note',			// type
						'note'.ucfirst($radioName),	// id/name
						$radioName,		// for
						_l('completely remove permissions from group').':&nbsp;'.$sImgClearRadio->fetch('smarty.img.tpl')	// note text
					);
			}
		}
		
		// add to $return
		$return['form'] = $form;
		$return['formIds'] = $formIds;
		
		// return
		return $return;
	}
	
	
	/**
	 * getFormValues($formIds) extracts the values for the given keys in $formIds out of $_POST
	 * 
	 * @param array $formIds array containing the form element ids to get from $_POST
	 * @param array $fileUpload array containing the information of uploaded files
	 * @param bool $delete indicates if the temp file is deleted after getting its content
	 * @return array array containing $key => $value of $_POST
	 */
	protected function getFormValues($formIds, $fileUpload=null, $delete=true) {
		
		// prepare return
		$data = array();
			
		// walk through keys
		foreach($formIds as $formId => $settings) {
			
			// check if id is set
			if($this->post($formId) === false) {
				
				// check for post values with name addition
				// check hierselect
				if($settings['type'] == 'hierselect') {
					
					// first
					$data[$formId][0] = ($this->post($formId.'-1') !== false ? $this->post($formId.'-1') : '');
					// second
					$data[$formId][1] = ($this->post($formId.'-2') !== false ? $this->post($formId.'-2') : '');
				// check text with defaults
				} elseif($settings['type'] == 'fieldtext') {
					
					// manual
					$data[$formId]['manual'] = ($this->post($formId.'-manual') !== false ? $this->post($formId.'-manual') : '');
					// defaults
					$data[$formId]['defaults'] = ($this->post($formId.'-defaults') !== false ? $this->post($formId.'-defaults') : '');
				} else {
				
					// switch type to set default
					switch($settings['valueType']) {
						
						case 'int':
							$data[$formId] = 0;
						break;
						
						case 'array';
							$data[$formId] = array();
						break;
						
						
						case 'file':
							
							// check $fileUpload
							if(!is_null($fileUpload)) {
								
								// get file content
								$tempFilename = $fileUpload[$formId]['path'].$fileUpload[$formId]['file_name'];
								$fp = fopen($tempFilename, 'rb');
								$fileContent = fread($fp, filesize($tempFilename));
								fclose($fp);
								// delete from tmp/
								if($delete === true) {
									unlink($tempFilename);
								}
								
								// prepare data
								$data[$formId] = array(
										'filename' => $fileUpload[$formId]['name'],
										'mimetype' => $fileUpload[$formId]['type'],
										'fileContent' => $fileContent,
									);
								// add temp file name and path
								if($delete === false) {
									$data[$formId]['tempFilename'] = $tempFilename;
								}
							}
						break;
						
						case 'string':
						default:
							$data[$formId] = '';
						break;	
					}
				}
			} else {
				$data[$formId] = $this->post($formId);
			}
		}
		
		// return
		return $data;
	}
	
	
	/**
	 * getFormPermissions($permissionIds) extracts the values for the given keys in 
	 * $permisstionIds out of $_POST
	 * 
	 * @param array $permissionIds array containing the form element ids to get from $_POST
	 * @return array array containing $key => $value of $_POST
	 */
	protected function getFormPermissions($permissionIds) {
		
		// prepare return
		$permissions = array();
			
		// walk through keys (post)
		foreach($permissionIds as $permissionId => $settings) {
			
			// get group id
			list($temp, $groupId) = explode('_', $permissionId);
			
			// check if id is set
			if($this->post($permissionId) === false) {
				
				// set default value
				$permissions[$groupId]['group'] = new Group($groupId);
				$permissions[$groupId]['value'] = 0;
			} else {
				
				// set permission
				$permissions[$groupId]['group'] = new Group($groupId);
				$permissions[$groupId]['value'] = $this->post($permissionId);
			}
		}
		
		// walk throught own groups
		foreach($this->getUser()->get_groups() as $group) {
			
			// set permission
			$permissions[$group->getId()]['group'] = $group;
			$permissions[$group->getId()]['value'] = 'w';
		}
		
		// return
		return $permissions;
	}
	
	
	
	
	
	/**
	 * redirectTo($file, $params) redirects to the given $file with the given url $params
	 * 
	 * @param string $file php file to redirect to (without ".php")
	 * @param array $params array containing the url params with "parameter name" => "value"
	 * @return void
	 */
	protected function redirectTo($file, $params) {
		
		// build url
		$url = $file.'.php';
		if(count($params) > 0) {
			
			$url .= '?';
			foreach($params as $param => $value) {
				$url .= $param.'='.$value.'&';
			}
			$url = substr($url, 0, -1);
		}
		
		// redirect and exit script
		header('Location: '.$url);
		exit;
	}
	
	
	/**
	 * toHtml() handles the exceptions maybe thrown and calls each init() method
	 * 
	 * @return void
	 */
	final public function toHtml() {
		
		// run init
		$this->init();
	}
	
	
	/**
	 * delete handles the deletion of page child object
	 * 
	 * @param array $config config for the deletion page (translation names, links, etc.)
	 * @return string html of the deletion page
	 */
	protected function delete($config) {
		
		/*
		 * config has to contain the following entries
		 * 
		 * 'pagecaption'	-> text for page caption (for _l())
		 * 'table'			-> table of object to be deleted (also used for object creation)
		 * 'tid'			-> id of the object to be deleted
		 * 'formaction'		-> common part of the action parameter for the form (w/o tid, see before)
		 * 'cancellink'		-> url that the "cancel" button directs to
		 */
		
		// pagecaption
		$this->getTpl()->assign('pagecaption',_l($config['pagecaption']));
		
		// get object
		$class = ucfirst(strtolower($config['table']));
		$object = new $class($config['tid']);
		
		// prepare permission check
		$permissionTable = $config['table'];
		$permissionTid = $config['tid'];
		// check table
		if(strtolower($config['table']) == 'result') {
			$permissionTable = 'calendar';
			$permissionTid = $object->getCalendar()->get_id();
		} 
		
		// check rights
		if($this->getUser()->hasPermission($permissionTable, $permissionTid)) {
			
			// smarty-templates
			$sConfirmation = new JudoIntranetSmarty();
			
			// form
			$form = new Zebra_Form(
				'formConfirm',			// id/name
				'post',				// method
				$config['formaction'].$config['tid']		// action
			);
			// set language
			$form->language('deutsch');
			// set docktype xhtml
			$form->doctype('xhtml');
			
			// add button
			$form->add(
				'submit',		// type
				'buttonSubmit',	// id/name
				_l('delete'),	// value
				array('title' => _l('delete'))
			);
			
			// smarty-link
			$link = array(
							'params' => 'class="submit"',
							'href' => $config['cancellink'],
							'title' => _l('cancel'),
							'content' => _l('cancel')
						);
			$sConfirmation->assign('link', $link);
			$sConfirmation->assign('spanparams', 'id="cancel"');
			$sConfirmation->assign('message', _l('delete confirm').'&nbsp;'.$this->helpButton(HELP_MSG_DELETE));
			$sConfirmation->assign('form', $form->render('', true));
			
			// validate
			if($form->validate()) {
				
				// call deletion wrapper
				$object->deleteEntry();
				
				// smarty
				$sConfirmation->assign('message', _l('delete done'));
				$sConfirmation->assign('form', '');
			}
			
			// smarty return
			return $sConfirmation->fetch('smarty.confirmation.tpl');
		} else {
			throw new NotAuthorizedException($this);
		}
	}
	
	
	/**
	 * helpButton($hid) generates the help button and returns it
	 * 
	 * @param int $hid id of the helpmessage
	 * @return string HTML string of the button
	 */
	public function helpButton($hid) {
		
		// prepare template values
		$templateValues = array(
				'buttonClass' => $this->getGc()->get_config('help.buttonClass'),
				'imgTitle' => _l('help'),
				'messageId' => 'h'.$hid,
			);
		
		// smarty template
		$helpTemplate = new JudoIntranetSmarty();
		$helpTemplate->assign('help', $templateValues);
		
		// return button
		return $helpTemplate->fetch('smarty.help.button.tpl');
	}
	
	
	/**
	 * jsRedirectTimeout($uri, $timeout) redirects the browser to $uri per javascript timeout
	 * function
	 * 
	 * @param string $uri the uri to redirect to
	 * @param int $timeout timeout if global timeout should not be used
	 * @return void
	 */
	public function jsRedirectTimeout($uri, $timeout = null) {
		
		// get global timeout if $timeout is null
		$sTimeout = $this->getGc()->get_config('global.redirectTimeout');
		if(!is_null($timeout)) {
			$sTimeout = $timeout;
		}
		
		// assign variables
		$this->getTpl()->assign('jsRedirect', !$this->debugAll());
		$this->getTpl()->assign('jsRedirectUri', $uri);
		$this->getTpl()->assign('jsRedirectTimeout', ($sTimeout * 1000));
	}
	
	
	/**
	 * 
	 */
	private function addWebserviceRunner() {
		
		// check if already added
		if($this->getAddedWebserviceRunner() !== true) {
		
			// add jquery for webservice jobs
			$this->add_jquery('
				var message = $("<div>")
					.hide()
					.dialog({
						closeText: "'._l('close').'",
						autoOpen: false,
						modal: true,
						resizable: false,
						position: {
							my: "center top+20", 
							at: "center top", 
							of: window
						},
						width: "75%",
						buttons: [
							{
								text: "'._l('OK').'",
								click: function() {
									$(this).dialog("close");
								}
							}
						]
					});
				var icon = $("<img>")
					.css({
						"float": "left",
						"margin": "0 10px 10px 0"
					})
					.appendTo(message);
				var div = $("<div>")
					.appendTo(message);
				var interval = '.$this->getGc()->get_config('webservice.timeout').';
				var startWebserviceJobs = function() {
					setTimeout(function() {
						runWebserviceJobs();
					}, interval);
				};
				var runWebserviceJobs = function() {
					$.ajax({
						url: "api/webservice/jobs/0/run",
						cache: false,
						dataType: "json",
						success: function(response) {
							interval = '.$this->getGc()->get_config('webservice.interval').';
							if(response.result == "OK") {
								message.dialog("option", "close", function() {
									startWebserviceJobs();
								});
								icon.attr("src", "img/message_info.png")
									.attr("alt", "info")
									.attr("title", "info");
								message.dialog("option", "title", response.data.title);
								div.html(response.data.message);
								message.dialog("open");
							} else if(response.result == "ERROR") {
								icon.attr("src", "img/message_error.png")
									.attr("alt", "info")
									.attr("title", "info");
								message.dialog("option", "title", response.data.title);
								div.html(response.data.message);
								message.dialog("open");
							} else if(response.result == "SKIPPED") {
								startWebserviceJobs();
							}
							$("a[rel=\'external\']").attr("target", "_blank");
						}
					});
				};
				startWebserviceJobs();
				');
			
			// set marker that runner was added
			$this->setAddedWebserviceRunner(true);
		}
	}
	
	
	/**
	 * addJsTranslation($string) translates $string and stores it in an array for use as
	 * global translation object in javascript
	 * 
	 * @param string $string string to store as translation
	 * @return void
	 */
	public function addJsTranslation($string) {
		
		// get actual translation array
		$translation = $this->getJsTranslation();
		
		// translate $string and add
		$translation[$string] = html_entity_decode(_l($string));
		
		// reset translation array
		$this->setJsTranslation($translation);
	}
}



?>
