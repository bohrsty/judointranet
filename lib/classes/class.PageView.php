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
	private $output;
	private $jquery;
	private $head;
	private $helpmessages;
	private $helpids;
	// smarty
	protected $tpl;
	
	/*
	 * getter/setter
	 */
	public function get_output(){
		return $this->output;
	}
	public function set_output($output) {
		$this->output = $output;
	}
	public function get_jquery(){
		return $this->jquery;
	}
	public function set_jquery($jquery) {
		$this->jquery = $jquery;
	}
	public function get_head(){
		return $this->head;
	}
	public function set_head($head) {
		$this->head = $head;
	}
	public function getHelpmessages(){
		return $this->helpmessages;
	}
	public function setHelpmessages($helpmessages) {
		$this->helpmessages = $helpmessages;
	}
	public function getHelpids($complete=false){
		
		if($complete === true) {
			return '['.$this->helpids.']';
		} else {
			return $this->helpids;
		}
	}
	public function setHelpids($helpids) {
		$this->helpids = $helpids;
	}
	
	/*
	 * constructor/destructor
	 */
	public function __construct() {
		
		// parent constructor
		parent::__construct();
		
		// initialize error-handling
		$GLOBALS['error'] = new Error();
		
		// init smarty
		$this->tpl = new JudoIntranetSmarty();
		
		// set class-variables
		$this->set_output(array());
		$this->set_jquery('');
		$this->set_head('');
		$this->setHelpmessages('');
		$this->setHelpids('');
		
		// set userinfos if logged in
		$this->put_userinfo();
		
		// initialize help
		$GLOBALS['help'] = new Help($this);
		
		// set logo
		$this->tpl->assign('systemLogo', 'img/'.$this->getGc()->get_config('global.systemLogo'));
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
		
		// get jquery
		$jquery = $this->get_jquery();
		
		// add or replace
		if($reset === true) {
			
			// replace
			$jquery = $content."\n";
		} else {
			
			// add
			$jquery .= $content."\n";
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
	 * addHelpmessages() adds the given string to $helpmessages
	 * 
	 * @param string $content content to be added to $helpmessages
	 */
	public function addHelpmessages($id, $content) {
		
		// get help components
		$helpmessages = $this->getHelpmessages();
		$helpids = $this->getHelpids();
		
		// add and set back
		$this->setHelpmessages($helpmessages.$content."\n");
		if($this->getHelpids() == '') {
			$this->setHelpids('\''.$id.'\'');
		} else {
			$this->setHelpids($helpids.', \''.$id.'\'');
		}
	}
	
	
	
	
	
	/**
	 * title combines the title-prefix and the given title and returns it
	 * 
	 * @param string $title title to be combined
	 * @return string combined title and prefix
	 */
	protected function title($title) {
		
		// return combined prefix and title
		return parent::lang('class.PageView#title#prefix#title').' '.$title;
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
		$sUsersettings = new JudoIntranetSmarty();
		$sJsToggleSlide = new JudoIntranetSmarty();
		
		// check if userinfo exists and set to output
		$name = $this->getUser()->get_userinfo('name');
		if($this->getUser()->get_loggedin() !== false) {
			
			// smarty-link
			$spanUserLink = array(
					'params' => 'id="toggleUsersettings"',
					'title' => parent::lang('class.PageView#put_userinfo#logininfo#toggleUsersettings'),
					'content' => $name,	
				);
			$sUserLink->assign('span', $spanUserLink);
			$link = $sUserLink->fetch('smarty.span.tpl');
			
			// smarty-usersettings
			$usersettings = array(0 => array(	
					'params' => 'class="usersettings"',
					'href' => 'index.php?id=user&action=passwd',
					'title' => parent::lang('class.PageView#put_userinfo#usersettings#passwd.title'),
					'content' => parent::lang('class.PageView#put_userinfo#usersettings#passwd')
				),
				1 => array(	
					'params' => 'class="usersettings"',
					'href' => 'index.php?id=user&action=data',
					'title' => parent::lang('class.PageView#put_userinfo#usersettings#data.title'),
					'content' => parent::lang('class.PageView#put_userinfo#usersettings#data')
				),
				2 => array(
					'params' => 'class="usersettings"',
					'href' => 'index.php?id=logout',
					'title' => parent::lang('class.PageView#put_userinfo#usersettings#logout.title'),
					'content' => parent::lang('class.PageView#put_userinfo#usersettings#logout')
				));
			$sUsersettings->assign('us', $usersettings);
			
			// smarty jquery
			$sJsToggleSlide->assign('id', '#toggleUsersettings');
			$sJsToggleSlide->assign('toToggle', '#usersettings');
			$sJsToggleSlide->assign('time', '');
			$this->add_jquery($sJsToggleSlide->fetch('smarty.js-toggleSlide.tpl'));
			
			// smarty return
			return parent::lang('class.PageView#put_userinfo#logininfo#LoggedinAs').' '.$link.' ('.$this->getUser()->get_userinfo('username').')'.$sUsersettings->fetch('smarty.usersettings.tpl');
		} else {
			// smarty return
			return parent::lang('class.PageView#put_userinfo#logininfo#NotLoggedin');
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
	protected function showPage($template) {
		
		// head
		$this->tpl->assign('head', $this->get_head());
		
		// manualjquery
		$this->tpl->assign('manualjquery', $this->get_jquery());
		
		// navi
		$navi = '';
		$file = basename($_SERVER['SCRIPT_FILENAME']);
		$param = $this->get('id');
		$naviItems = $this->naviFromDb();
		// walk through $naviItems
		foreach($naviItems as $naviItem) {
			$navi .= $naviItem->output($file, $param).PHP_EOL;
		}
		$this->tpl->assign('navigation', $navi);
		
		// logininfo
		$this->tpl->assign('logininfo', $this->put_userinfo());
		
		// help messages
		$this->tpl->assign('helpmessages', $this->getHelpmessages());
		$this->tpl->assign('helpids', $this->getHelpids(true));
		
		// smarty-display
		$this->tpl->display($template);
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
				'dialogClass' => $this->getGc()->get_config('help.dialogClass'),
				'effect' => $this->getGc()->get_config('help.effect'),
				'effectDuration' => $this->getGc()->get_config('help.effectDuration'),
				'closeText' => parent::lang('class.PageView#showPage#helpMessages#closeText'),
			);
		$this->tpl->assign('help', $help);
		
		// assign about
		$helpabout = $this->getHelp()->getMessage(HELP_MSG_ABOUT, array('version' => $this->getGc()->get_config('global.version')));
		$this->tpl->assign('helpabout', $helpabout);
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
					'title' => parent::lang('class.AdministrationView#listTableContent#pages#page').' '.$i,
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
				parent::lang('class.AdministrationView#listTableContent#pages#to')." $last ".
				parent::lang('class.AdministrationView#listTableContent#pages#of')." $rows)";
		
		// assign "pages"
		$pagelinks['pages'] = parent::lang('class.AdministrationView#listTableContent#pages#pages');
		
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
				$naviItems[] = new Navi($naviId);
			}
		} else {
			$errno = $this->getError()->error_raised('MysqlError', $db->error);
			$this->getError()->handle_error($errno);
		}
		
		// return
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
		$return = array();
		$formIds = array();
		
		// prepare clear radio
		$this->tpl->assign('permissionJs', true);
		// prepare tabs
		$this->tpl->assign('tabsJs', true);
		
		
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
				$imgReadText = parent::lang('class.PageView#zebraAddPermissions#permissions#read');
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
				$imgEditText = parent::lang('class.PageView#zebraAddPermissions#permissions#edit');
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
						'params' => 'class="clickable" onclick="clearRadio(\''.$radioName.'\')" title="'.parent::lang('class.PageView#zebraAddPermissions#clearRadio#title').'"',
						'src' => 'img/permissions_delete.png',
						'alt' => parent::lang('class.PageView#zebraAddPermissions#clearRadio#name'),
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
						parent::lang('class.PageView#zebraAddPermissions#clearRadio#note').':&nbsp;'.$sImgClearRadio->fetch('smarty.img.tpl')	// note text
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
	 * @return array array containing $key => $value of $_POST
	 */
	protected function getFormValues($formIds) {
		
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
}



?>
