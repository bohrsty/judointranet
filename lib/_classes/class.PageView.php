<?php


/**
 * class PageView implements the control of a page
 */
class PageView extends Object {
	
	/*
	 * class-variables
	 */
	private $get;
//	private $post;
	private $output;
	
	/*
	 * getter/setter
	 */
	private function get_get(){
		return $this->get;
	}
	private function set_get($get) {
		$this->get = $get;
	}
//	private function get_post(){
//		return $this->post;
//	}
//	private function set_post($post) {
//		$this->post = $post;
//	}
	private function get_output(){
		return $this->output;
	}
	private function set_output($output) {
		$this->output = $output;
	}
	
	/*
	 * constructor/destructor
	 */
	public function __construct() {
		
		// initialize error-handling
		$GLOBALS['Error'] = new Error();
		
		// initialize user
		if(!isset($_SESSION['user'])) {
			$_SESSION['user'] = new User();
		}
		
		// set class-variables
		$this->read_globals();
		$this->set_output(array());
		
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
		
		// walk through $_POST if defined
//		$post = null;
//		if(isset($_POST)) {
//			
//			foreach($_POST as $post_entry => $post_value) {
//				
//				// check value
//				$value = $this->check_valid_chars('postvalue',$post_value);
//				$errno = null;
//				if($value === false) {
//					
//					// handle error
//					$errno = $GLOBALS['Error']->error_raised('POSTInvalidChars','entry:'.$post_entry,$post_entry);
//				}
//				
//				// store value
//				$post[$post_entry] = array($post_value,$errno);
//			}
//		}
		
		// set class-variables
		$this->set_get($get);
//		$this->set_post($post);
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
	 * post returns the value of $_POST[$var] if set
	 * 
	 * @param string $var text of key in $_POST-array
	 * @return string value of the $_POST-key, or false if not set
	 */
//	public function post($var) {
//		
//		// check if key is set
//		$post = $this->get_post();
//		if(isset($post[$var])) {
//			return $post[$var];
//		} else {
//			return false;
//		}
//	}
	
	
	
	
	
	
	/**
	 * output prints the content of $output to browser
	 * 
	 * @return string the html-content of $output
	 */
	public function output() {
		
		// read template
		try {
			$page = new HtmlTemplate('templates/page.tpl');
		} catch(Exception $e) {
			$GLOBALS['Error']->handle_error($e);
		}
		
		// parse content
		$output = $page->parse($this->get_output());
		
		// print output
		print($output);
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
	 * title combines the title-prefix and the given title and returns it
	 * 
	 * @param string $title title to be combined
	 * @return string combined title and prefix
	 */
	protected function title($title) {
		
		// return combined prefix and title
		return $this->lang('class.PageView#title#prefix#title').' '.$title;
	}
	
	
	
	
	
	/**
	 * navi
	 */
	protected function navi($file) {
		
		// temp array for reading firstlevel filenames
		$filenames = array(
						'index.php',
						'calendar.php');
		
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
		
		// prepare output
		$output = '';
		// get templates
		// firstlevel
		try {
			$navi_0 = new HtmlTemplate('templates/navi.0.tpl');
		} catch(Exception $e) {
			$GLOBALS['Error']->handle_error($e);
		}
		// secondlevel active
		try {
			$navi_1a = new HtmlTemplate('templates/navi.1a.tpl');
		} catch(Exception $e) {
			$GLOBALS['Error']->handle_error($e);
		}
		// secondlevel inactive
		try {
			$navi_1i = new HtmlTemplate('templates/navi.1i.tpl');
		} catch(Exception $e) {
			$GLOBALS['Error']->handle_error($e);
		}
		
		// get authorized navi-entries
		$navi_entries = Rights::get_authorized_entries('navi');
		
		// walk through $naviitems and build navi
		for($i=0;$i<count($naviitems);$i++) {
			
			// simplify
			$firstlevel = $naviitems[$i]['firstlevel'];
			
			// check rights
			if(!in_array(crc32($firstlevel['class']),$navi_entries)) {
				continue;
			}
			
			// set firstlevel
			$output .= $navi_0->parse(array(
										'navi.0.href' => $firstlevel['file'],
										'navi.0.alt' => $this->lang('class.'.$firstlevel['class'].'#connectnavi#firstlevel#name'),
										'navi.0.name' => $this->lang($firstlevel['name'])));
			
			// walk through secondlevel
			$secondlevel = $naviitems[$i]['secondlevel'];
			for($j=0;$j<count($secondlevel);$j++){
				
				// check rights
				if(!in_array(crc32($firstlevel['class'].'|'.$secondlevel[$j]['getid']),$navi_entries)) {
					continue;
				}
				
				// check active or inactive
				if($file == $naviitems[$i]['firstlevel']['file'] && $this->get('id') == $secondlevel[$j]['getid']) {
					
					// active
					$active = array(
								'navi.1a.href' => $firstlevel['file'].'?id='.$secondlevel[$j]['getid'],
								'navi.1a.alt' => $this->lang($secondlevel[$j]['name']),
								'navi.1a.name' => $this->lang($secondlevel[$j]['name']));
					// if login, add base64-encoded uri
					if($secondlevel[$j]['getid'] == 'login' && $this->get('id') != 'login' && $this->get('id') != 'logout') {
						$active['navi.1a.href'] = $firstlevel['file'].'?id='.$secondlevel[$j]['getid'].'&r='.base64_encode($_SERVER['REQUEST_URI']);
					}
					$output .= $navi_1a->parse($active);
				} else {
					
					// inactive
					$inactive = array(
									'navi.1i.href' => $firstlevel['file'].'?id='.$secondlevel[$j]['getid'],
									'navi.1i.alt' => $this->lang($secondlevel[$j]['name']),
									'navi.1i.name' => $this->lang($secondlevel[$j]['name']));
					// if login, add base64-encoded uri
					if($secondlevel[$j]['getid'] == 'login' && $this->get('id') != 'login' && $this->get('id') != 'logout') {
						$inactive['navi.1i.href'] = $firstlevel['file'].'?id='.$secondlevel[$j]['getid'].'&r='.base64_encode($_SERVER['REQUEST_URI']);
					}
					$output .= $navi_1i->parse($inactive);
				}
			}
		}
		
		// return
		return $output;
	}
	
	
	
	
	
	
	
	
	/**
	 * p parses the given text and parameters in standard-p-tag
	 * 
	 * @param string $param parameters for p-tag
	 * @param string $string string to be parsed in p-tag
	 * @return string in p-tag parsed string and parameters
	 */
	protected function p($param,$string) {
		
		// get standard p-template
		try {
			$p = new HtmlTemplate('templates/p.tpl');
		} catch(Exception $e) {
			$GLOBALS['Error']->handle_error($e);
		}
		
		// add " " if param not empty
		if($param != '') {
			$param = ' '.$param;
		}
		
		// prepare contents
		$contents = array();
		$contents['parameters'] = $param;
		$contents['text'] = $string;
		
		return $p->parse($contents)."\n";
	}
	
	
	
	
	
	
	
	
	
	/**
	 * put_userinfo sets the userinfo of the actual user on page
	 * 
	 * @return void
	 */
	protected function put_userinfo() {
		
		// check if userinfo exists and set to output
		$name = $_SESSION['user']->return_userinfo('name');
		if($name !== false) {
			$this->add_output(array('logininfo' => $name),true);
		} else {
			$this->add_output(array('logininfo' => $this->lang('class.PageView#PageView#logininfo#NotLoggedin')),true);
		}
	}
}



?>
