<?php


/**
 * class ProocolView implements the control of the protocol-page
 */
class ProtocolView extends PageView {
	
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
							'name' => 'class.ProtocolView#connectnavi#firstlevel#name',
							'file' => 'protocol.php',
							'position' => 4,
							'class' => get_class(),
							'id' => crc32('ProtocolView'), // 87925365
							'show' => true
						),
						'secondlevel' => array(
							1 => array(
								'getid' => 'listall', 
								'name' => 'class.ProtocolView#connectnavi#secondlevel#listall',
								'id' => crc32('ProtocolView|listall'), // 667344926
								'show' => true
							),
							0 => array(
								'getid' => 'new', 
								'name' => 'class.ProtocolView#connectnavi#secondlevel#new',
								'id' => crc32('ProtocolView|new'), // 143669502
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
				if($navi['secondlevel'][$i]['getid'] == $this->get('id')) {
					
					// store id and  break
					$naviid = $navi['secondlevel'][$i]['id'];
					break;
				}
			}
			
			// check if naviid is member of authorized entries
			if(in_array($naviid,$rights)) {
				
				switch($this->get('id')) {
					
					case 'listall':
						
						// set contents
						// title
						$this->add_output(array('title' => $this->title(parent::lang('class.ProtocolView#init#listall#title'))));
						// navi
						$this->add_output(array('navi' => $this->navi(basename($_SERVER['SCRIPT_FILENAME']))));
						// main-content
						$this->add_output(array('main' => $this->listall()));
						// jquery
						$this->add_output(array('jquery' => $this->get_jquery()));
					break;
					
					case 'new':
						
						// set contents
						// title
						$this->add_output(array('title' => $this->title(parent::lang('class.ProtocolView#init#my#title'))));
						// navi
						$this->add_output(array('navi' => $this->navi(basename($_SERVER['SCRIPT_FILENAME']))));
						// main-content
						$this->add_output(array('main' => $this->my()));
						// jquery
						$this->add_output(array('jquery' => $this->get_jquery()));
					break;
					
					default:
						
						// id set, but no functionality
						$errno = $GLOBALS['Error']->error_raised('GETUnkownId','entry:'.$this->get('id'),$this->get('id'));
						$GLOBALS['Error']->handle_error($errno);
						$this->add_output(array('main' => $GLOBALS['Error']->to_html($errno)),true);
						// jquery
						$this->add_output(array('jquery' => $this->get_jquery()));
					break;
				}
			} else {
				
				// error not authorized
				// set contents
				// title
				$this->add_output(array('title' => $this->title(parent::lang('class.ProtocolView#init#Error#NotAuthorized'))));
				// navi
				$this->add_output(array('navi' => $this->navi(basename($_SERVER['SCRIPT_FILENAME']))));
				// main content
				$errno = $GLOBALS['Error']->error_raised('NotAuthorized','entry:'.$this->get('id'),$this->get('id'));
				$GLOBALS['Error']->handle_error($errno);
				$this->add_output(array('main' => $GLOBALS['Error']->to_html($errno)),true);
				// jquery
				$this->add_output(array('jquery' => $this->get_jquery()));
			}
		} else {
			
			// id not set
			// title
			$this->add_output(array('title' => $this->title(parent::lang('class.ProtocolView#init#default#title')))); 
			// default-content
			$this->add_output(array('main' => $this->default_content()));
			// navi
			$this->add_output(array('navi' => $this->navi(basename($_SERVER['SCRIPT_FILENAME']))));
			// jquery
			$this->add_output(array('jquery' => $this->get_jquery()));
		}
		
		// add head
		$this->add_output(array('head' => $this->get_head()));
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
		
		// get templates
		// hx
		try {
			$hx = new HtmlTemplate('templates/hx.tpl');
		} catch(Exception $e) {
			$GLOBALS['Error']->handle_error($e);
		}
		// p
		try {
			$p = new HtmlTemplate('templates/p.tpl');
		} catch(Exception $e) {
			$GLOBALS['Error']->handle_error($e);
		}
		
		// prepare headline
		$return .= $hx->parse(array(
						'hx.x' => 2,
						'hx.parameters' => '',
						'hx.content' => parent::lang('class.ProtocolView#default_content#headline#text')
					));
		
		// return
		return $return;
	}
	
	
	
	
	
	

// continue
}



?>
