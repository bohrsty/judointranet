<?php


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
	
	/*
	 * constructor/destructor
	 */
	public function __construct() {
		
		// parent constructor
		parent::__construct();
		
		// initialize error-handling
		$GLOBALS['Error'] = new Error();
		
		// initialize user
		if(!isset($_SESSION['user'])) {
			$_SESSION['user'] = new User();
		}
		
		// init smarty
		$this->tpl = new JudoIntranetSmarty();
		
		// set class-variables
		$this->read_globals();
		$this->set_output(array());
		$this->set_jquery('');
		$this->set_head('');
		
		// set userinfos if logged in
		$this->put_userinfo();		
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
					$errno = $GLOBALS['Error']->error_raised('GETInvalidChars','entry:'.$get_entry,$get_entry);
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
	 * navi
	 */
	protected function navi($file) {
		
		// read php-files from /
		$filenames = array();
		$dh = opendir($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['GC']->get_config('relative_path'));

		while($entry = readdir($dh)) {

			// check if file, .php-extension and !test.php
			if(is_file($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['GC']->get_config('relative_path').$entry) 
					&& substr($entry,-4) == '.php' 
					&& $entry != 'test.php') {
				$filenames[] = $entry;
			}
		}
		closedir($dh);
		
		// get class-names from filelist
		for($i=0;$i<count($filenames);$i++) {
			
			// only use files excluding "index.php"
			if($filenames[$i] != 'index.php') {
				
				// remove extension and set naviitem
				$classname = ucfirst(substr($filenames[$i],0,-4)).'View';
				$navi = $classname::connectnavi();
				// check if array
				if(!is_array($navi)) {
					$errno = $GLOBALS['Error']->error_raised('CannotGetNavi','class:'.$classname);
					$GLOBALS['Error']->handle_error($errno);
				}
				$naviitems[$navi['firstlevel']['position']] = $navi;
			} else {
				
				// set navi for index-page
				$navi = MainView::connectnavi();
				// check if array
				if(!is_array($navi)) {
					$errno = $GLOBALS['Error']->error_raised('CannotGetNavi','class:MainView');
					$GLOBALS['Error']->handle_error($errno);
				}
				$naviitems[$navi['firstlevel']['position']] = $navi;
			}
		}

		// firstlevel
		// get authorized navi-entries
		$navi_entries = Rights::get_authorized_entries('navi');
		
		// prepare data for smarty
		$data = array();
		
		// walk through $naviitems and build navi
		for($i=0;$i<count($naviitems);$i++) {
			
			// simplify
			$firstlevel = $naviitems[$i]['firstlevel'];
			
			// check rights
			if(!in_array(md5($firstlevel['class']),$navi_entries)) {
				continue;
			}
			
			// check visibility
			if($firstlevel['show'] === false) {
				continue;
			}
			
			// set firstlevel
			// smarty
			$data[] = array(
					'level' => 0,
					'href' => $firstlevel['file'],
					'title' => parent::lang('class.'.$firstlevel['class'].'#connectnavi#firstlevel#name'),
					'content' => parent::lang($firstlevel['name'])
				);
			
			// walk through secondlevel
			$secondlevel = $naviitems[$i]['secondlevel'];
			for($j=0;$j<count($secondlevel);$j++){
				
				// check rights
				if(!in_array(md5($firstlevel['class'].'|'.$secondlevel[$j]['getid']),$navi_entries)) {
					continue;
				}
				
				// check visibility
				if($secondlevel[$j]['show'] === false) {
					continue;
				}
				
				// smarty
				$data[] = array(
						'level' => 1,
						'href' => ($secondlevel[$j]['getid'] == 'login' && $this->get('id') != 'login' && $this->get('id') != 'logout') ? $firstlevel['file'].'?id='.$secondlevel[$j]['getid'].'&r='.base64_encode($_SERVER['REQUEST_URI']) : $firstlevel['file'].'?id='.$secondlevel[$j]['getid'],
						'title' => parent::lang($secondlevel[$j]['name']),
						'content' => parent::lang($secondlevel[$j]['name']),
						'id' => $secondlevel[$j]['getid'],
						'file' => $firstlevel['file']
					);
				
			}
		}
		
		// return
		return $data;
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
		$sA = new JudoIntranetSmarty();
		$sUsersettings = new JudoIntranetSmarty();
		$sJsToggleSlide = new JudoIntranetSmarty();
		
		// check if userinfo exists and set to output
		$name = $_SESSION['user']->get_userinfo('name');
		if($name !== false) {
			
			// smarty-link
			$sA->assign('params','id="toggleUsersettings"');
			$sA->assign('href', '#');
			$sA->assign('title', parent::lang('class.PageView#put_userinfo#logininfo#toggleUsersettings'));
			$sA->assign('content', $name);
			$link = $sA->fetch('smarty.a.tpl');
			
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
			return parent::lang('class.PageView#put_userinfo#logininfo#LoggedinAs').' '.$link.' ('.$_SESSION['user']->get_userinfo('username').')'.$sUsersettings->fetch('smarty.usersettings.tpl');
		} else {
			// smarty return
			return parent::lang('class.PageView#put_userinfo#logininfo#NotLoggedin');
		}
	}
}



?>
