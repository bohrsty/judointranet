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
	private $get;
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
	public function get_get(){
		return $this->get;
	}
	public function set_get($get) {
		$this->get = $get;
	}
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
		$this->read_globals();
		$this->set_output(array());
		$this->set_jquery('');
		$this->set_head('');
		$this->setHelpmessages('');
		$this->setHelpids('');
		
		// set userinfos if logged in
		$this->put_userinfo();
		
		// initialize help
		$GLOBALS['help'] = new Help($this);
	}
	
	/*
	 * methods
	 */
	/**
	 * read_globals checks the $_GET- and $POST-entrys against allowed-values
	 * 
	 * @return void
	 */
	private function read_globals() {
		
		// walk through $_GET if defined
		$get = null;
		if(isset($_GET)) {
			
			foreach($_GET as $get_entry => $get_value) {
				
				// check the value
				$value = $this->check_valid_chars('getvalue',$get_value);
				if($value === false) {
					
					// handle error
					$errno = $this->getError()->error_raised('GETInvalidChars','entry:'.$get_entry,$get_entry);
					throw new Exception('GETInvalidChars',$errno);
				} else {
					
					// store value
					$get[$get_entry] = array($get_value,null);
				}
			}
		}
		
		// set class-variables
		$this->set_get($get);
	}
	
	
	
	
	
	
	/**
	 * get returns the value of $_GET[$var] if set
	 * 
	 * @param string $var text of key in $_GET-array
	 * @return string value of the $_GET-key, or false if not set
	 */
	public function get($var) {
		
		// check if key is set
		$get = $this->get_get();
		if(isset($get[$var])) {
			return $get[$var][0];
		} else {
			return false;
		}
	}
	
	
	
	
	
	
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
					'title' => parent::lang('class.AdministrationView#list_table_content#pages#page').' '.$i,
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
				parent::lang('class.AdministrationView#list_table_content#pages#to')." $last ".
				parent::lang('class.AdministrationView#list_table_content#pages#of')." $rows)";
		
		// assign "pages"
		$pagelinks['pages'] = parent::lang('class.AdministrationView#list_table_content#pages#pages');
		
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
	 * quickform2AddPermissions($form) adds the choose-permissions dialog to the given $form
	 * using jquery-ui dialog
	 * 
	 * @param object $form the quickform2 form object the permissions will be added
	 * @return string HTML code to toggle permissions dialog
	 */
	protected function quickform2AddPermissions(&$form) {
		
		// prepare form group "id"
		$formHtmlId = 'permissions';
		
		// prepare clear radio
		$this->tpl->assign('permissionJs', true);
		
		// prepare dialog link
		$sSpanLinkDialog = new JudoIntranetSmarty();
		$link = array(
				'params' => 'id="togglePermissions" class="spanLink"',
				'title' => parent::lang('class.PageView#quickform2AddPermissions#togglePermissions#title'),
				'content' => parent::lang('class.PageView#quickform2AddPermissions#togglePermissions#name'),
//				'help' => $this->getHelp()->getMessage(HELP_MSG_CALENDARLISTSORTLINKS),
			);
		$sSpanLinkDialog->assign('link', $link);
		
		// prepare group element
		$permission = $form->addElement('group', $formHtmlId, array());
		$permission->setSeparator('<br />');
		$permission->setLabel($sSpanLinkDialog->fetch('smarty.spanLinkHelp.tpl'));
		
		// add headlines
		// prepare images
		// read
		$imgReadText = parent::lang('class.PageView#quickform2AddPermissions#permissions#read');
		$imgRead = array(
				'params' => 'class="iconRead" title="'.$imgReadText.'"',
				'src' => 'img/permissions_read.png',
				'alt' => $imgReadText,
			);
		$sImgReadTemplate = new JudoIntranetSmarty();
		$sImgReadTemplate->assign('img', $imgRead);
		// edit
		$imgEditText = parent::lang('class.PageView#quickform2AddPermissions#permissions#edit');
		$imgEdit = array(
				'params' => 'class="iconEdit" title="'.$imgEditText.'"',
				'src' => 'img/permissions_edit.png',
				'alt' => $imgEditText,
			);
		$sImgEditTemplate = new JudoIntranetSmarty();
		$sImgEditTemplate->assign('img', $imgEdit);
		// prepare headline text
		$headlineText = array(
				'params' => 'class="headlineText"',
				'content' => parent::lang('class.PageView#quickform2AddPermissions#permissions#headLine'),
			);
		$sHeadlineTextTemplate = new JudoIntranetSmarty();
		$sHeadlineTextTemplate->assign('span', $headlineText);
		// add to form/group
		$groupHeadlines = $permission->addElement('group', 'headlines')
										->setName('headLines');
		$groupHeadlines->addElement('static', 'headlineRead')
							->setContent($sImgReadTemplate->fetch('smarty.img.tpl'));
		$groupHeadlines->addElement('static', 'headlineEdit')
							->setContent($sImgEditTemplate->fetch('smarty.img.tpl'));
		$groupHeadlines->addElement('static', 'headlineText')
							->setContent($sHeadlineTextTemplate->fetch('smarty.span.tpl'));
		
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
				$radioName = $group->getId();
				
				// prepare clear radio link
				$sSpanLinkClearRadio = new JudoIntranetSmarty();
				$link = array(
						'params' => 'class="spanLink" onclick="clearRadio(\''.$radioName.'\')"',
						'title' => parent::lang('class.PageView#quickform2AddPermissions#clearRadio#title'),
						'content' => '<img src="img/permissions_delete.png" alt="'.parent::lang('class.PageView#quickform2AddPermissions#clearRadio#name').'" />',
					);
				$sSpanLinkClearRadio->assign('link', $link);
				
				// add group
				$group1 = $permission->addElement('group', 'group-'.$radioName)
										->setName($radioName);
				
				$group1->addElement('static', 'clear-'.$radioName)
						->setContent($sSpanLinkClearRadio->fetch('smarty.spanLinkHelp.tpl'));
						
				$group1->addElement('radio', $radioName.'-r', array('value' => 'r'));
				$group1->addElement('radio', $radioName.'-w', array('value' => 'w'));
				
				// prepare group name
				$groupName = array(
						'params' => 'class="groupName"',
						'content' => $group->getName(),
					);
				$sGroupNameTemplate = new JudoIntranetSmarty();
				$sGroupNameTemplate->assign('span', $groupName);
				// set group name
				$group1->addElement('static', 'name-'.$radioName)
						->setContent($sGroupNameTemplate->fetch('smarty.span.tpl'));
			}
		}
		
		// add jquery-ui dialog
		$dialog = array(
			'dialogClass' => $formHtmlId.'-0',
			'openerClass' => 'togglePermissions',
			'autoOpen' => 'false',
			'effect' => 'slide',
			'duration' => 300,
			'modal' => 'true',
			'closeText' => parent::lang('class.PageView#quickform2AddPermissions#dialog#closeText'),
			'height' => 400,
			'maxHeight' => 400,
			'width' => 750,
			'title' => parent::lang('class.PageView#quickform2AddPermissions#dialog#title'),
		);
		// smarty jquery
		$sJsToggleSlide = new JudoIntranetSmarty();
		$sJsToggleSlide->assign('dialog', $dialog);
		$this->add_jquery($sJsToggleSlide->fetch('smarty.js-dialog.tpl'));
	}
}



?>
