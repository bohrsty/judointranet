<?php
// secure against direct execution
if(!defined("JUDOINTRANET")) {die("Cannot be executed directly! Please use index.php.");}


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
							),
							2 => array(
								'getid' => 'showdecisions', 
								'name' => 'class.ProtocolView#connectnavi#secondlevel#showdecisions',
								'id' => md5('ProtocolView|showdecisions'), // 7a3a2cfd86522105318cefe3928ecde8
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
		$this->tpl->assign('pagename',parent::lang('class.ProtocolView#page#init#name'));
		
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
						$this->tpl->assign('title', $this->title(parent::lang('class.ProtocolView#init#title#listall')));
						$this->tpl->assign('main', $this->listall());
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('hierselect', false);
						$this->tpl->assign('tinymce', false);
					break;
					
					case 'new':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.ProtocolView#init#title#new')));
						$this->tpl->assign('main', $this->new_entry());
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('hierselect', true);
						$this->tpl->assign('tinymce', true);
					break;
					
					case 'details':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.ProtocolView#init#title#details')));
						$this->tpl->assign('main', $this->details($this->get('pid')));
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('hierselect', false);
						$this->tpl->assign('tinymce', false);
					break;
					
					case 'edit':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.ProtocolView#init#title#edit')));
						$this->tpl->assign('main', $this->edit($this->get('pid')));
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('hierselect', true);
						$this->tpl->assign('tinymce', true);
					break;
					
					case 'show':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.ProtocolView#init#title#show')));
						$this->tpl->assign('main', $this->show($this->get('pid')));
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('hierselect', false);
						$this->tpl->assign('tinymce', false);
					break;
					
					case 'topdf':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.ProtocolView#init#title#topdf')));
						$this->tpl->assign('main', $this->topdf($this->get('pid')));
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('hierselect', false);
						$this->tpl->assign('tinymce', false);
					break;
					
					case 'delete':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.ProtocolView#init#title#topdf')));
						$this->tpl->assign('main', $this->delete($this->get('pid')));
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('hierselect', false);
						$this->tpl->assign('tinymce', false);
					break;
					
// TODO
// DISABLED UNTIL IMPLEMENTATION OF correct()
//					case 'correct':
//						
//						// smarty
//						$this->tpl->assign('title', $this->title(parent::lang('class.ProtocolView#init#title#correct')));
//						$this->tpl->assign('main', $this->correct($this->get('pid')));
//						$this->tpl->assign('jquery', true);
//						$this->tpl->assign('hierselect', false);
//						$this->tpl->assign('tinymce', false);
//					break;
					
					case 'showdecisions':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.ProtocolView#init#title#decisions')));
						$this->tpl->assign('main', $this->decisions($this->get('pid')));
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('hierselect', false);
						$this->tpl->assign('tinymce', false);
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
						$this->tpl->assign('tinymce', false);
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
				$this->tpl->assign('tinymce', false);
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
			// smarty-tiny_mce
			$this->tpl->assign('tinymce', false);
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
	
	
	
	
	
	
	
	/**
	 * listall lists all protocol entries (paged)
	 * shows only entrys for which the user has sufficient rights
	 * 
	 * @return void
	 */
	private function listall() {
		
		// pagecaption
		$this->tpl->assign('pagecaption',parent::lang('class.ProtocolView#page#caption#listall'));
		
		// read all entries
		$entries = $this->read_all_entries();
				
		// smarty-templates
		$sListall = new JudoIntranetSmarty();
		
		// smarty
		$sTh = array(
				'date' => parent::lang('class.ProtocolView#listall#TH#date'),
				'type' => parent::lang('class.ProtocolView#listall#TH#type'),
				'location' => parent::lang('class.ProtocolView#listall#TH#location'),
				'show' => parent::lang('class.ProtocolView#listall#TH#show'),
				'admin' => parent::lang('class.ProtocolView#listall#TH#admin')
			);

		$sListall->assign('th', $sTh);
		// loggedin? admin links
		$sListall->assign('loggedin', $_SESSION['user']->get_loggedin());
		
		// walk through entries
		$counter = 0;
		// smarty
		$sList = array();
		foreach($entries as $no => $entry) {
			
			// check if valid
			if($entry->get_valid() == 1) {
				
				// smarty
				$sList[$counter] = array(
						'date' => array(
								'href' => 'protocol.php?id=details&pid='.$entry->get_id(),
								'title' => parent::lang('class.ProtocolView#listall#title#date'),
								'date' => $entry->get_date('d.m.Y')
							),
						'type' => $entry->get_type(),
						'location' => $entry->get_location()
						
					);
				
				// show
				$sList[$counter]['show'][] = array(
						'href' => 'protocol.php?id=show&pid='.$entry->get_id(),
						'title' => parent::lang('class.ProtocolView#listall#title#ProtShow'),
						'src' => 'img/prot_details.png',
						'alt' => parent::lang('class.ProtocolView#listall#alt#ProtShow'),
						'show' => true
					);
				$sList[$counter]['show'][] = array(
						'href' => 'protocol.php?id=topdf&pid='.$entry->get_id(),
						'title' => parent::lang('class.ProtocolView#listall#title#ProtPDF'),
						'src' => 'img/prot_pdf.png',
						'alt' => parent::lang('class.ProtocolView#listall#alt#ProtPDF'),
						'show' => true
					);
					
				// add admin
				
				// if user is loggedin add admin-links
				if($_SESSION['user']->get_loggedin() === true) {
					
					// edit and delete only for author
					if($_SESSION['user']->get_userinfo('name') == $entry->get_owner()) {
						
						// smarty
						// edit
						$sList[$counter]['admin'][] = array(
								'href' => 'protocol.php?id=edit&pid='.$entry->get_id(),
								'title' => parent::lang('class.ProtocolView#listall#title#edit'),
								'src' => 'img/prot_edit.png',
								'alt' => parent::lang('class.ProtocolView#listall#alt#edit')
							);
						// delete
						$sList[$counter]['admin'][] = array(
								'href' => 'protocol.php?id=delete&pid='.$entry->get_id(),
								'title' => parent::lang('class.ProtocolView#listall#title#delete'),
								'src' => 'img/prot_delete.png',
								'alt' => parent::lang('class.ProtocolView#listall#alt#delete')
							);
					}
					
					// correction
					if($entry->get_correctable() == 1 && $_SESSION['user']->get_userinfo('name') != $entry->get_owner()) {
						
						// delete
						$sList[$counter]['admin'][] = array(
								'href' => 'protocol.php?id=correct&pid='.$entry->get_id(),
								'title' => parent::lang('class.ProtocolView#listall#title#correct'),
								'src' => 'img/prot_correct.png',
								'alt' => parent::lang('class.ProtocolView#listall#alt#correct')
							);
					}
					
				} else {
					
					// smarty
					$sList[$counter]['admin'][] = array(
							'href' => '',
							'title' => '',
							'src' => '',
							'alt' => ''
						);
				}
				
				// increment counter
				$counter++;

			} else {
				
				// deleted items
			}
		}
		
		// smarty
		$sListall->assign('list', $sList);
		
		// smarty-return
		return $sListall->fetch('smarty.protocol.listall.tpl');
	}
	
	
	
	
	
	
	
	/**
	 * read_all_entries get all protocol-entries from db for that the actual
	 * user has sufficient rights. returns an array with protocol-objects
	 * 
	 * @return array all entries as calendar-objects
	 */
	private function read_all_entries() {
		
		// prepare return
		$protocol_entries = array();
				
		// get ids
		$protocol_ids = Protocol::return_protocols();
		
		// create protocol-objects
		foreach($protocol_ids as $index => $id) {
			$protocol_entries[] = new Protocol($id);
		}
		
		// sort protocol-entries
		usort($protocol_entries,array($this,'callback_compare_protocols'));
		
		// return protocol-objects
		return $protocol_entries;
	}
	
	
	
	
	
	
	
	/**
	 * callback_compare_protocols compares two protocol-objects by date (for uksort)
	 * 
	 * @param object $first first protocol-objects
	 * @param object $second second protocol-object
	 * @return int -1 if $first<$second, 0 if equal, 1 if $first>$second
	 */
	public function callback_compare_protocols($first,$second) {
	
		// compare dates
		if($first->get_date() < $second->get_date()) {
			return -1;
		}
		if($first->get_date() == $second->get_date()) {
			return 0;
		}
		if($first->get_date() > $second->get_date()) {
			return 1;
		}
	}
	
	
	
	
	
	
	
	/**
	 * new_entry creates the "new-entry"-form and handle its response
	 * 
	 * @return string html-string with the "new-entry"-form
	 */
	private function new_entry() {
		
		// pagecaption
		$this->tpl->assign('pagecaption',parent::lang('class.ProtocolView#page#caption#new_entry'));
		
		// smarty-templates
		$sD = new JudoIntranetSmarty();
		
		// prepare return
		$return = '';
		
		// formular
		$form = new HTML_QuickForm2(
								'newProtocol',
								'post',
								array(
									'name' => 'newProtocol',
									'action' => 'protocol.php?id=new'
								)
							);
		
		$now = date('Y-m-d');
		$form->addDataSource(new HTML_QuickForm2_DataSource_Array(array('date' => $now)));
		
		// renderer
		$renderer = HTML_QuickForm2_Renderer::factory('default');
		$renderer->setOption('required_note',parent::lang('class.ProtocolView#entry#form#requiredNote'));
		
		// elements
		// preset
		$options = array(0 => '--')+Preset::read_all_presets('protocol');
		$preset = $form->addElement('select','preset');
		$preset->setLabel(parent::lang('class.ProtocolView#entry#form#preset').':');
		$preset->loadOptions($options);
		$preset->addRule('required',parent::lang('class.ProtocolView#entry#rule#required.preset'));
		$preset->addRule('callback',parent::lang('class.ProtocolView#entry#rule#check.select'),array($this,'callback_check_select'));
		
		
		// date
		$date = $form->addElement('text','date',array());
		$date->setLabel(parent::lang('class.ProtocolView#entry#form#date').':');
		// rule
		$date->addRule('required',parent::lang('class.ProtocolView#entry#rule#required.date'));
		$date->addRule('callback',parent::lang('class.ProtocolView#entry#rule#check.date'),array($this,'callback_check_date'));
		// add jquery-datepicker
		// smarty
		$sD->assign('elementid', 'date-0');
		$sD->assign('dateFormat', 'yy-mm-dd');
		$sD->assign('dateValue', $now);
		$this->add_jquery($sD->fetch('smarty.js-datepicker.tpl'));
		
		// type
		$options = array_merge(array(0 => '--'),Protocol::return_types());
		$type = $form->addElement('select','type');
		$type->setLabel(parent::lang('class.ProtocolView#entry#form#type').':');
		$type->loadOptions($options);
		$type->addRule('required',parent::lang('class.ProtocolView#entry#rule#required.type'));
		$type->addRule('callback',parent::lang('class.ProtocolView#entry#rule#check.select'),array($this,'callback_check_select'));
		
		// location
		$location = $form->addElement('text','location');
		$location->setLabel(parent::lang('class.ProtocolView#entry#form#location').':');
		$location->addRule('required',parent::lang('class.ProtocolView#entry#rule#required.location'));
		$location->addRule(
					'regex',
					parent::lang('class.ProtocolView#entry#rule#regexp.allowedChars').' ['.$_SESSION['GC']->get_config('name.desc').']',
					$_SESSION['GC']->get_config('name.regexp'));
		
		// member0
		$member = $form->addElement('text','member0');
		$member->setLabel(parent::lang('class.ProtocolView#entry#form#member0').':');
		$member->addRule(
						'regex',
						parent::lang('class.ProtocolView#entry#rule#regexp.allowedChars').' ['.$_SESSION['GC']->get_config('text.desc').']',
						$_SESSION['GC']->get_config('text.regexp'));
		
		// member1
		$member = $form->addElement('text','member1');
		$member->setLabel(parent::lang('class.ProtocolView#entry#form#member1').':');
		$member->addRule(
						'regex',
						parent::lang('class.ProtocolView#entry#rule#regexp.allowedChars').' ['.$_SESSION['GC']->get_config('text.desc').']',
						$_SESSION['GC']->get_config('text.regexp'));
		
		// member2
		$member = $form->addElement('text','member2');
		$member->setLabel(parent::lang('class.ProtocolView#entry#form#member2').':');
		$member->addRule(
						'regex',
						parent::lang('class.ProtocolView#entry#rule#regexp.allowedChars').' ['.$_SESSION['GC']->get_config('text.desc').']',
						$_SESSION['GC']->get_config('text.regexp'));
		
		// recorder
		$recorder = $form->addElement('text','recorder');
		$recorder->setLabel(parent::lang('class.ProtocolView#entry#form#recorder').':');
		$recorder->addRule('required',parent::lang('class.ProtocolView#entry#rule#required.recorder'));
		$recorder->addRule(
					'regex',
					parent::lang('class.ProtocolView#entry#rule#regexp.allowedChars').' ['.$_SESSION['GC']->get_config('name.desc').']',
					$_SESSION['GC']->get_config('name.regexp'));
		
		// protocol text
		$protocolTA = $form->addElement('textarea','protocol');
		$protocolTA->setLabel(parent::lang('class.ProtocolView#entry#form#protocol').':');
		$protocolTA->addRule(
						'regex',
						parent::lang('class.ProtocolView#entry#rule#regexp.allowedChars').' ['.$_SESSION['GC']->get_config('textarea.desc').']',
						$_SESSION['GC']->get_config('textarea.regexp'));
		// js tiny_mce
		$tmce = array(
				'element' => 'protocol-0',
				'css' => 'templates/protocols/tmce_'.$_SESSION['GC']->get_config('tmce.default.css').'.css',
				'transitem' => parent::lang('class.ProtocolView#new_entry#tmce#item'),
				'transdecision' => parent::lang('class.ProtocolView#new_entry#tmce#decision')
			);
		// smarty
		$this->tpl->assign('tmce',$tmce);		
		
		// select rights
		$options = $_SESSION['user']->return_all_groups('sort');
		$rights = $form->addElement('select','rights',array('multiple' => 'multiple','size' => 5));
		$rights->setLabel(parent::lang('class.ProtocolView#entry#form#rights').':');
		$rights->loadOptions($options);
		
		
		// checkbox public
		$public = $form->addElement('checkbox','public');
		$public->setLabel(parent::lang('class.ProtocolView#entry#form#public').':');
		
		
		// submit-button
		$form->addElement('submit','submit',array('value' => parent::lang('class.ProtocolView#entry#form#submitButton')));
		
		// validate
		if($form->validate()) {
			
			// get form data
			$data = $form->getValue();
				
			// check $data['rights']
			if(!isset($data['rights']))
			{
				$data['rights'] = array();
			}
			
			// merge with own groups, add admin
			$data['rights'] = array_merge($data['rights'],$_SESSION['user']->get_groups(),array(1));
			
			// add public access
			$kPublicAccess = array_search(0,$data['rights']);
			if($kPublicAccess === false && isset($data['public']) && $data['public'] == 1) {
				$data['rights'][] = 0;
			} elseif($kPublicAccess !== false && !isset($data['public'])) {
				unset($data['rights'][$kPublicAccess]);
			}
			
			$right_array = array(
								'action' => 'new',
								'new' => $data['rights']);
			
			$protocol = new Protocol(array(
								'date' => $data['date'],
								'type' => $data['type'],
								'location' => $data['location'],
								'member' => $data['member0'].'|'.$data['member1'].'|'.$data['member2'],
								'protocol' => $data['protocol'],
								'preset' => $data['preset'],
								'owner' => $_SESSION['user']->get_id(),
								'recorder' => $data['recorder'],
								'rights' => $right_array,
								'valid' => 1,
								'correctable' => 0
								)
				);
				
			// write to db
			$protocol->writeDb();
			
			// smarty
			$sCD = new JudoIntranetSmarty();
			$sCD->assign('data', $protocol->details());
			return $sCD->fetch('smarty.protocol.details.tpl');
		} else {
			return $form->render($renderer);
		}
	}
	
	
	
	
	
	
	
	/**
	 * details returns the details of a protocol-entry as html-string
	 * 
	 * @param int $pid entry-id for protocol
	 * @return string html-string with the details of the protocol entry
	 */
	private function details($pid) {
	
		// pagecaption
		$this->tpl->assign('pagecaption',parent::lang('class.ProtocolView#page#caption#details'));
		
		// check rights
		if(Rights::check_rights($pid,'protocol',true)) {
				
			// get protocol-object
			$protocol = new Protocol($pid);
			
			// smarty-template
			$sPD = new JudoIntranetSmarty();
			
			// smarty
			$sPD->assign('data', $protocol->details());
			
			// prepare links
			// show
			$links[] = array(
					'href' => 'protocol.php?id=show&pid='.$protocol->get_id(),
					'title' => parent::lang('class.ProtocolView#details#show#title'),
					'name' => parent::lang('class.ProtocolView#details#show#name')
				);
			// decisions
			$links[] = array(
					'href' => 'protocol.php?id=showdecisions&pid='.$protocol->get_id(),
					'title' => parent::lang('class.ProtocolView#details#decisions#title'),
					'name' => parent::lang('class.ProtocolView#details#decisions#name')
				);
			// topdf
			$links[] = array(
					'href' => 'protocol.php?id=topdf&pid='.$protocol->get_id(),
					'title' => parent::lang('class.ProtocolView#details#topdf#title'),
					'name' => parent::lang('class.ProtocolView#details#topdf#name')
				);
			$sPD->assign('links',$links);
			
			return $sPD->fetch('smarty.protocol.details.tpl');
		} else {
			
			// error
			$errno = $GLOBALS['Error']->error_raised('NotAuthorized','entry:'.$this->get('id'),$this->get('id'));
			$GLOBALS['Error']->handle_error($errno);
			return $GLOBALS['Error']->to_html($errno);
		}
	}
	
	
	
	
	
	
	
	/**
	 * edit returns the protocoltext as html-string for the edit page
	 * 
	 * @param int $pid entry-id for protocol
	 * @return string html-string with the protocoltext
	 */
	private function edit($pid) {
	
		// check rights
		if(Rights::check_rights($pid,'protocol',true)) {
			
			// pagecaption
			$this->tpl->assign('pagecaption',parent::lang('class.ProtocolView#page#caption#edit'));
			
			// get protocol-object
			$protocol = new Protocol($pid);
			
			// smarty-templates
			$sD = new JudoIntranetSmarty();
			
			// get rights
			$pRights = $protocol->get_rights()->get_rights();
			// check public access
			$kPublicAccess = array_search(0,$pRights);
			$publicAccess = false;
			if($kPublicAccess !== false) {
				$publicAccess = true;
				unset($pRights[$kPublicAccess]);
			}
			
			// formular
			$form = new HTML_QuickForm2(
									'editProtocol',
									'post',
									array(
										'name' => 'editProtocol',
										'action' => 'protocol.php?id=edit&pid='.$pid
									)
								);
			
			$datasource = array(
					'date' => $protocol->get_date('Y-m-d'),
					'type' => $protocol->get_type('i'),
					'location' => $protocol->get_location(),
					'member0' => $protocol->get_member(false,0),
					'member1' => $protocol->get_member(false,1),
					'member2' => $protocol->get_member(false,2),
					'protocol' => $protocol->get_protocol(),
					'preset' => $protocol->get_preset()->get_id(),
					'recorder' => $protocol->get_recorder(),
					'correctionGroup' => array('correction' => $protocol->get_correctable())
				);
			
			// add public access
			if($publicAccess) {
				$datasource['public'] = 1;
			}
			// add datasource
			$form->addDataSource(new HTML_QuickForm2_DataSource_Array($datasource));
				
			
			// renderer
			$renderer = HTML_QuickForm2_Renderer::factory('default');
			$renderer->setOption('required_note',parent::lang('class.ProtocolView#entry#form#requiredNote'));
			
// TODO
// DISABLED UNTIL IMPLEMENTATION OF correct()
//			// elements
//			// correction MOVE TO EDIT
//			$correctionGroup = $form->addElement('group','correctionGroup');
//			$correctionGroup->setLabel(parent::lang('class.ProtocolView#entry#form#correction').':');
//			// checkbox
//			$correction = $correctionGroup->addElement('checkbox','correction');
			
			// preset
			$options = array(0 => '--')+Preset::read_all_presets('protocol');
			$preset = $form->addElement('select','preset');
			$preset->setLabel(parent::lang('class.ProtocolView#entry#form#preset').':');
			$preset->loadOptions($options);
			$preset->addRule('required',parent::lang('class.ProtocolView#entry#rule#required.preset'));
			$preset->addRule('callback',parent::lang('class.ProtocolView#entry#rule#check.select'),array($this,'callback_check_select'));
			
			
			// date
			$date = $form->addElement('text','date',array());
			$date->setLabel(parent::lang('class.ProtocolView#entry#form#date').':');
			// rule
			$date->addRule('required',parent::lang('class.ProtocolView#entry#rule#required.date'));
			$date->addRule('callback',parent::lang('class.ProtocolView#entry#rule#check.date'),array($this,'callback_check_date'));
			// add jquery-datepicker
			// smarty
			$sD->assign('elementid', 'date-0');
			$sD->assign('dateFormat', 'yy-mm-dd');
			$sD->assign('dateValue', $protocol->get_date('y-m-d'));
			$this->add_jquery($sD->fetch('smarty.js-datepicker.tpl'));
			
			// type
			$options = array_merge(array(0 => '--'),Protocol::return_types());
			$type = $form->addElement('select','type');
			$type->setLabel(parent::lang('class.ProtocolView#entry#form#type').':');
			$type->loadOptions($options);
			$type->addRule('required',parent::lang('class.ProtocolView#entry#rule#required.type'));
			$type->addRule('callback',parent::lang('class.ProtocolView#entry#rule#check.select'),array($this,'callback_check_select'));
			
			// location
			$location = $form->addElement('text','location');
			$location->setLabel(parent::lang('class.ProtocolView#entry#form#location').':');
			$location->addRule('required',parent::lang('class.ProtocolView#entry#rule#required.location'));
			$location->addRule(
						'regex',
						parent::lang('class.ProtocolView#entry#rule#regexp.allowedChars').' ['.$_SESSION['GC']->get_config('name.desc').']',
						$_SESSION['GC']->get_config('name.regexp'));
			
			// member0
			$member = $form->addElement('text','member0');
			$member->setLabel(parent::lang('class.ProtocolView#entry#form#member0').':');
			$member->addRule(
							'regex',
							parent::lang('class.ProtocolView#entry#rule#regexp.allowedChars').' ['.$_SESSION['GC']->get_config('text.desc').']',
							$_SESSION['GC']->get_config('text.regexp'));
			
			// member1
			$member = $form->addElement('text','member1');
			$member->setLabel(parent::lang('class.ProtocolView#entry#form#member1').':');
			$member->addRule(
							'regex',
							parent::lang('class.ProtocolView#entry#rule#regexp.allowedChars').' ['.$_SESSION['GC']->get_config('text.desc').']',
							$_SESSION['GC']->get_config('text.regexp'));
			
			// member2
			$member = $form->addElement('text','member2');
			$member->setLabel(parent::lang('class.ProtocolView#entry#form#member2').':');
			$member->addRule(
							'regex',
							parent::lang('class.ProtocolView#entry#rule#regexp.allowedChars').' ['.$_SESSION['GC']->get_config('text.desc').']',
							$_SESSION['GC']->get_config('text.regexp'));
			
			// recorder
			$recorder = $form->addElement('text','recorder');
			$recorder->setLabel(parent::lang('class.ProtocolView#entry#form#recorder').':');
			$recorder->addRule('required',parent::lang('class.ProtocolView#entry#rule#required.recorder'));
			$recorder->addRule(
						'regex',
						parent::lang('class.ProtocolView#entry#rule#regexp.allowedChars').' ['.$_SESSION['GC']->get_config('name.desc').']',
						$_SESSION['GC']->get_config('name.regexp'));
			
			// protocol text
			$protocolTA = $form->addElement('textarea','protocol');
			$protocolTA->setLabel(parent::lang('class.ProtocolView#entry#form#protocol').':');
			$protocolTA->addRule(
							'regex',
							parent::lang('class.ProtocolView#entry#rule#regexp.allowedChars').' ['.$_SESSION['GC']->get_config('textarea.desc').']',
							$_SESSION['GC']->get_config('textarea.regexp'));
			// js tiny_mce
			$tmce = array(
					'element' => 'protocol-0',
					'css' => 'templates/protocols/tmce_'.$protocol->get_preset()->get_path().'.css',
					'transitem' => parent::lang('class.ProtocolView#new_entry#tmce#item'),
					'transdecision' => parent::lang('class.ProtocolView#new_entry#tmce#decision')
				);
			// smarty
			$this->tpl->assign('tmce',$tmce);		
			
			// select rights
			$options = $_SESSION['user']->return_all_groups('sort');
			$rights = $form->addElement('select','rights',array('multiple' => 'multiple','size' => 5));
			$rights->setLabel(parent::lang('class.ProtocolView#entry#form#rights').':');
			$rights->loadOptions($options);
			
			
			// checkbox public
			$public = $form->addElement('checkbox','public');
			$public->setLabel(parent::lang('class.ProtocolView#entry#form#public').':');
			
			
			// submit-button
			$form->addElement('submit','submit',array('value' => parent::lang('class.ProtocolView#entry#form#submitButton')));
			
			// validate
			if($form->validate()) {
				
				// get form data
				$data = $form->getValue();
				
				// set owner
				$data['owner'] = $protocol->get_owner();
					
				// check $data['rights']
				if(!isset($data['rights']))
				{
					$data['rights'] = array();
				}
				
				// merge with own groups, add admin
				$data['rights'] = array_merge($data['rights'],$_SESSION['user']->get_groups(),array(1));
				
				// add public access
				$kPublicAccess = array_search(0,$data['rights']);
				if($kPublicAccess === false && isset($data['public']) && $data['public'] == 1) {
					$data['rights'][] = 0;
				} elseif($kPublicAccess !== false && !isset($data['public'])) {
					unset($data['rights'][$kPublicAccess]);
				}
				
				// check correctable
				if(!isset($data['correctionGroup']['correction'])) {
					$data['correctionGroup']['correction'] = 0;
				}
				
				$protocolUpdate = array(
									'date' => $data['date'],
									'type' => $data['type'],
									'location' => $data['location'],
									'member' => $data['member0'].'|'.$data['member1'].'|'.$data['member2'],
									'protocol' => $data['protocol'],
									'preset' => new Preset($data['preset'],'protocol',$protocol->get_id()),
									'recorder' => $data['recorder'],
									'rights' => $data['rights'],
									'correctable' => $data['correctionGroup']['correction'],
									'owner' => $data['owner'],
									'valid' => 1
					);
				
				// update protocol
				$protocol->update($protocolUpdate);
					
				// write to db
				$protocol->writeDb('update');
				
				// smarty
				$sCD = new JudoIntranetSmarty();
				$sCD->assign('data', $protocol->details());
				return $sCD->fetch('smarty.protocol.details.tpl');
			} else {
				return $form->render($renderer);
			}
		} else {
			
			// error
			$errno = $GLOBALS['Error']->error_raised('NotAuthorized','entry:'.$this->get('id'),$this->get('id'));
			$GLOBALS['Error']->handle_error($errno);
			return $GLOBALS['Error']->to_html($errno);
		}
	}
	
	
	
	
	
	
	
	/**
	 * show returns the protocoltext as html-string
	 * 
	 * @param int $pid entry-id for protocol
	 * @return string html-string with the protocoltext
	 */
	private function show($pid) {
	
		// pagecaption
		$this->tpl->assign('pagecaption',parent::lang('class.ProtocolView#page#caption#show'));
		
		// check rights
		if(Rights::check_rights($pid,'protocol',true)) {
			
			// smarty
			$sP = new JudoIntranetSmarty();

			// get protocol
			$protocol = new Protocol($pid);
			
			// prepare marker-array
			$infos = array(
					'version' => date('dmy')
				);
			
			// add calendar-fields to array
			$protocol->addMarks($infos,false);
			
			// add tmce-css
			$fh = fopen('templates/protocols/tmce_'.$protocol->get_preset()->get_path().'.css','r');
			$css = fread($fh,filesize('templates/protocols/tmce_'.$protocol->get_preset()->get_path().'.css'));
			fclose($fh);
			$infos['tmceStyles'] = $css;
			
			// smarty
			$sP->assign('p', $infos);
			// check marks in values
			foreach($infos as $k => $v) {
				
				if(preg_match('/\{\$p\..*\}/U', $v)) {
					$infos[$k] = $sA->fetch('string:'.$v);
				}
			}
			
			// decision link
			$decisionLink = array(
									"href" => "protocol.php?id=showdecisions&pid=$pid",
									"title" => parent::lang('class.ProtocolView#show#decisionLink#title'),
									"text" => parent::lang('class.ProtocolView#show#decisionLink#text'),
									"number" => $protocol->hasDecisions() 
								);
			
			// smarty
			$sP->assign('p', $infos);
			$div_out = $sP->fetch('templates/protocols/'.$protocol->get_preset()->get_path().'.tpl');
			
			// smarty
			$sPd = new JudoIntranetSmarty();
			$sPd->assign('decisionlink', $decisionLink);
			$sPd->assign('page', $div_out);
			return $sPd->fetch('smarty.protocol.show.tpl');
		} else {
			
			// error
			$errno = $GLOBALS['Error']->error_raised('NotAuthorized','entry:'.$this->get('id'),$this->get('id'));
			$GLOBALS['Error']->handle_error($errno);
			return $GLOBALS['Error']->to_html($errno);
		}
	}
	
	
	
	
	
	
	
	/**
	 * topdf returns the protocol as pdf
	 * 
	 * @param int $pid entry-id for protocol
	 * @return string pdf of the protocol
	 */
	private function topdf($pid) {
	
		// pagecaption
		$this->tpl->assign('pagecaption',parent::lang('class.ProtocolView#page#caption#topdf'));
		
		// check rights
		if(Rights::check_rights($pid,'protocol',true)) {
			
			// smarty
			$sP = new JudoIntranetSmarty();

			// get protocol
			$protocol = new Protocol($pid);
			
			// prepare marker-array
			$infos = array(
					'version' => date('dmy')
				);
			
			// add calendar-fields to array
			$protocol->addMarks($infos,false);
			
			// add tmce-css
			$fh = fopen('templates/protocols/tmce_'.$protocol->get_preset()->get_path().'.css','r');
			$css = fread($fh,filesize('templates/protocols/tmce_'.$protocol->get_preset()->get_path().'.css'));
			fclose($fh);
			$infos['tmceStyles'] = $css;
			
			// smarty
			$sP->assign('p', $infos);
			// check marks in values
			foreach($infos as $k => $v) {
				
				if(preg_match('/\{\$p\..*\}/U', $v)) {
					$infos[$k] = $sP->fetch('string:'.$v);
				}
			}
			
			// smarty
			$sP->assign('p', $infos);
			$pdf_out = $sP->fetch('templates/protocols/'.$protocol->get_preset()->get_path().'.tpl');			
			
			// replace <p></p> to <div></div> for css use with HTML2PDF
			$pdf_out = preg_replace('/<p class="tmceItem">(.*)<\/p>/U','<div class="tmceItem">$1</div>',$pdf_out);
			$pdf_out = preg_replace('/<p class="tmceDecision">(.*)<\/p>/U','<div class="tmceDecision">$1</div>',$pdf_out);
			
			// get HTML2PDF-object
			$pdf = new HTML2PDF('P', 'A4', 'de', true, 'UTF-8', array(0, 0, 0, 0));
			
			// convert
			$pdf->writeHTML($pdf_out, false);
			
			// output
			$pdf_filename = $this->replace_umlaute(html_entity_decode($sP->fetch('string:'.$protocol->get_preset()->get_filename()),ENT_XHTML,'ISO-8859-1'));
			$pdf->Output($pdf_filename,'D');
		} else {
			
			// error
			$errno = $GLOBALS['Error']->error_raised('NotAuthorized','entry:'.$this->get('id'),$this->get('id'));
			$GLOBALS['Error']->handle_error($errno);
			return $GLOBALS['Error']->to_html($errno);
		}
	}
	
	
	
	
	
	
	
	/**
	 * delete handles the deletion of the protocol
	 * 
	 * @param int $pid entry-id for protocol
	 * @return string html of the deletion page
	 */
	private function delete($pid) {
	
		// pagecaption
		$this->tpl->assign('pagecaption',parent::lang('class.ProtocolView#page#caption#correct'));
		
		// check rights
		if(Rights::check_rights($pid,'protocol',true)) {
			
			// smarty-templates
			$sConfirmation = new JudoIntranetSmarty();
			
			$form = new HTML_QuickForm2(
									'confirm',
									'post',
									array(
										'name' => 'confirm',
										'action' => 'protocol.php?id=delete&pid='.$pid
									)
								);
			
			// add button
			$form->addElement('submit','yes',array('value' => parent::lang('class.ProtocolView#delete#form#yes')));
			
			// smarty-link
			$link = array(
							'params' => '',
							'href' => 'protocol.php?id=listall',
							'title' => parent::lang('class.ProtocolView#delete#cancel#title'),
							'content' => parent::lang('class.ProtocolView#delete#cancel#form')
						);
			$sConfirmation->assign('link', $link);
			$sConfirmation->assign('spanparams', 'id="cancel"');
			$sConfirmation->assign('message', parent::lang('class.ProtocolView#delete#message#confirm'));
			$sConfirmation->assign('form', $form);
			
			// validate
			if($form->validate()) {
			
				// get calendar-object
				$protocol = new Protocol($pid);
				
				// disable entry
				$protocol->update(array('valid' => 0));
				
				// smarty
				$sConfirmation->assign('message', parent::lang('class.ProtocolView#delete#message#done'));
				$sConfirmation->assign('form', '');
				
				// write entry
				try {
					$protocol->writeDb('update');
				} catch(Exception $e) {
					$GLOBALS['Error']->handle_error($e);
					return $GLOBALS['Error']->to_html($e);
				}
			}
			
			// smarty return
			return $sConfirmation->fetch('smarty.confirmation.tpl');
		} else {
			
			// error
			$errno = $GLOBALS['Error']->error_raised('NotAuthorized','entry:'.$this->get('id'),$this->get('id'));
			$GLOBALS['Error']->handle_error($errno);
			return $GLOBALS['Error']->to_html($errno);
		}
	}

	
	
	
	
	
	
	
	/**
	 * correct handles the corrections of the protocol
	 * 
	 * @param int $pid entry-id for protocol
	 * @return string html of the correction page
	 */
	private function correct($pid) {
	
		// pagecaption
		$this->tpl->assign('pagecaption',parent::lang('class.ProtocolView#page#caption#correct'));
		
		// check rights
		if(Rights::check_rights($pid,'protocol',true)) {
// TODO			
			
		} else {
			
			// error
			$errno = $GLOBALS['Error']->error_raised('NotAuthorized','entry:'.$this->get('id'),$this->get('id'));
			$GLOBALS['Error']->handle_error($errno);
			return $GLOBALS['Error']->to_html($errno);
		}
	}

	
	
	
	
	
	
	
	/**
	 * decissions shows the decissions of this or all protocols
	 * 
	 * @param int $pid entry-id for protocol
	 * @return string html of the decissions page
	 */
	private function decisions($pid) {
	
		// pagecaption
		$this->tpl->assign('pagecaption',parent::lang('class.ProtocolView#page#caption#decisions'));
		
		// check rights
		if(Rights::check_rights($pid,'protocol',true) || $pid == false) {
			
			// prepare template
			$sD = new JudoIntranetSmarty();
			
			// check pid all or single
			if($pid === false) {
				
				// get protocol ids
				$pids = Protocol::return_protocols();
				
				// create protocol objects to sort
				$protocols = array();
				foreach($pids as $pid) {
					$protocols[] = new Protocol($pid);
				}
				
				// sort array by protocols date
				usort($protocols,array($this,'callback_compare_protocols'));
				
				// walk through ids
				$counter = 0;
				foreach($protocols as $protocol) {
					
					// assign data
					$data[$counter] = array(	'date' => $protocol->get_date('d.m.Y'),
												'type' => $protocol->get_type(),
												'location' => $protocol->get_location(),
												'decisions' => $this->parseHtml($protocol->get_protocol(),'<p class="tmceDecision">|</p>'));
					
					// check if protocol has decisions
					if(count($data[$counter]['decisions']) == 0) {
						unset($data[$counter]);
					}
					$data = array_merge($data);
					
					// add to template
					$sD->assign('data',$data);
					
					// increment counter
					$counter++;
				}
			} else {
				
				// get protocol object
				$protocol = new Protocol($pid);
								
				// assign data
				$data[] = array(	'date' => $protocol->get_date('d.m.Y'),
									'type' => $protocol->get_type(),
									'location' => $protocol->get_location(),
									'decisions' => $this->parseHtml($protocol->get_protocol(),'<p class="tmceDecision">|</p>'));
				
				// add to template
				$sD->assign('data',$data);
			}
			
				// return
				return $sD->fetch('smarty.protocol.showdecisions.tpl');
		} else {
			
			// error
			$errno = $GLOBALS['Error']->error_raised('NotAuthorized','entry:'.$this->get('id'),$this->get('id'));
			$GLOBALS['Error']->handle_error($errno);
			return $GLOBALS['Error']->to_html($errno);
		}
	}

	
	
	
	
	
	
	
	/**
	 * parseHtml parses $text and returns an array containing the text between $tag
	 * 
	 * @param string $text the HTML text to be parsed
	 * @param string $tag the complete HTML tag (open and close, devided by |)
	 * @return array array containing the text between the given HTML tags
	 */
	private function parseHtml($text,$tag) {
	
		// split tag
		list($open,$close) = explode("|",$tag);
		
		// match text
		$matches = array();
		$preg = "|$open(.*)$close|U";
		$result = preg_match_all($preg,$text,$matches);
		
		// return
		return $matches[1];
	}
}



?>
