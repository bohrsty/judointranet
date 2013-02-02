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
							'id' => md5('ProtocolView'), // 3adfd8a4d24ba4849ddeb8d7f06e1828
							'show' => true
						),
						'secondlevel' => array(
							1 => array(
								'getid' => 'listall', 
								'name' => 'class.ProtocolView#connectnavi#secondlevel#listall',
								'id' => md5('ProtocolView|listall'), // b98ac7ca180fa172d86affb97bda7590
								'show' => true
							),
							0 => array(
								'getid' => 'new', 
								'name' => 'class.ProtocolView#connectnavi#secondlevel#new',
								'id' => md5('ProtocolView|new'), // a2c6bee54b75fe9498fb32c75950cf61
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
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.ProtocolView#init#listall#title')));
						$this->tpl->assign('main', $this->listall());
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('hierselect', true);
					break;
					
					case 'new':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.ProtocolView#init#my#title')));
						$this->tpl->assign('main', $this->new_entry());
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('hierselect', true);
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
				$this->tpl->assign('title', $this->title(parent::lang('class.ProtocolView#init#Error#NotAuthorized')));
				$this->tpl->assign('main', $GLOBALS['Error']->to_html($errno));
				$this->tpl->assign('jquery', true);
				$this->tpl->assign('hierselect', false);
			}
		} else {
			
			// id not set
			// smarty-title
			$this->tpl->assign('title', $this->title(parent::lang('class.ProtocolView#init#default#title'))); 
			// smarty-main
			$this->tpl->assign('main', $this->defaultContent());
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
		$this->tpl->display('smarty.main.tpl');
	}
	
	
	
	
	
	
	
	/**
	 * default_content returns the html-content to be displayed on page without
	 * parameters or functions
	 * 
	 * @return string html-content as default content
	 */
	protected function defaultContent() {
		
		// smarty-template
		$sD = new JudoIntranetSmarty();
		
		// smarty
		$sD->assign('caption', parent::lang('class.ProtocolView#defaultContent#headline#text'));
		$text[] = array(
				'caption' => '',
				'text' => ''
			);
		$sD->assign('text', $text);
		
		// return
		return $sD->fetch('smarty.default.content.tpl');
	}
	
	
	
	
	
	

// continue
}



?>
