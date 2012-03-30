<?php


/**
 * class InventoryView implements the control of the inventory-page
 */
class InventoryView extends PageView {
	
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
							'name' => 'class.InventoryView#connectnavi#firstlevel#name',
							'file' => 'inventory.php',
							'position' => 3,
							'class' => get_class(),
							'id' => crc32('InventoryView') // 3652205019
						),
						'secondlevel' => array(
							1 => array(
								'getid' => 'listall', 
								'name' => 'class.InventoryView#connectnavi#secondlevel#listall',
								'id' => crc32('InventoryView|listall'), // 2615517752
								'show' => true
							),
							0 => array(
								'getid' => 'my', 
								'name' => 'class.InventoryView#connectnavi#secondlevel#my',
								'id' => crc32('InventoryView|my'), // 521760874
								'show' => true
							),
							2 => array(
								'getid' => 'give', 
								'name' => 'class.InventoryView#connectnavi#secondlevel#give',
								'id' => crc32('InventoryView|give'), // 3119052612
								'show' => false
							),
							3 => array(
								'getid' => 'take', 
								'name' => 'class.InventoryView#connectnavi#secondlevel#take',
								'id' => crc32('InventoryView|take'), // 171651729
								'show' => false
							),
							4 => array(
								'getid' => 'cancel', 
								'name' => 'class.InventoryView#connectnavi#secondlevel#cancel',
								'id' => crc32('InventoryView|cancel'), // 2421882413
								'show' => false
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
						$this->add_output(array('title' => $this->title(parent::lang('class.CalendarView#init#listall#title'))));
						// navi
						$this->add_output(array('navi' => $this->navi(basename($_SERVER['SCRIPT_FILENAME']))));
						// main-content
						$this->add_output(array('main' => $this->listall()));
					break;
					
					case 'my':
						
						// set contents
						// title
						$this->add_output(array('title' => $this->title(parent::lang('class.InventoryView#init#my#title'))));
						// navi
						$this->add_output(array('navi' => $this->navi(basename($_SERVER['SCRIPT_FILENAME']))));
						// main-content
						$this->add_output(array('main' => $this->my()));
					break;
					
					case 'give':
						
						// set contents
						// title
						$this->add_output(array('title' => $this->title(parent::lang('class.InventoryView#init#give#title'))));
						// navi
						$this->add_output(array('navi' => $this->navi(basename($_SERVER['SCRIPT_FILENAME']))));
						// main-content
						$this->add_output(array('main' => $this->give($this->get('did'))));
					break;
					
					case 'cancel':
						
						// set contents
						// title
						$this->add_output(array('title' => $this->title(parent::lang('class.InventoryView#init#cancel#title'))));
						// navi
						$this->add_output(array('navi' => $this->navi(basename($_SERVER['SCRIPT_FILENAME']))));
						// main-content
						$this->add_output(array('main' => $this->cancel($this->get('did'))));
					break;
					
					case 'details':
						
						// set contents
						// title
						$this->add_output(array('title' => $this->title(parent::lang('class.CalendarView#init#details#title'))));
						// navi
						$this->add_output(array('navi' => $this->navi(basename($_SERVER['SCRIPT_FILENAME']))));
						// main-content
						// if cid does not exist, error
						if(Calendar::check_id($this->get('cid'))) {
							$this->add_output(array('main' => $this->details($this->get('cid'))));
						} else {
							
							// error
							$errno = $GLOBALS['Error']->error_raised('CidNotExists','details',$this->get('cid'));
							$GLOBALS['Error']->handle_error($errno);
							$this->add_output(array('main' => $GLOBALS['Error']->to_html($errno)),true);
						}
					break;
					
					default:
						
						// id set, but no functionality
						$errno = $GLOBALS['Error']->error_raised('GETUnkownId','entry:'.$this->get('id'),$this->get('id'));
						$GLOBALS['Error']->handle_error($errno);
						$this->add_output(array('main' => $GLOBALS['Error']->to_html($errno)),true);
					break;
				}
			} else {
				
				// error not authorized
				// set contents
				// title
				$this->add_output(array('title' => $this->title(parent::lang('class.InventoryView#init#Error#NotAuthorized'))));
				// navi
				$this->add_output(array('navi' => $this->navi(basename($_SERVER['SCRIPT_FILENAME']))));
				// main content
				$errno = $GLOBALS['Error']->error_raised('NotAuthorized','entry:'.$this->get('id'),$this->get('id'));
				$GLOBALS['Error']->handle_error($errno);
				$this->add_output(array('main' => $GLOBALS['Error']->to_html($errno)),true);
			}
		} else {
			
			// id not set
			// title
			$this->add_output(array('title' => $this->title(parent::lang('class.InventoryView#init#default#title')))); 
			// default-content
			$this->add_output(array('main' => '<h2>default content</h2>'));
			// navi
			$this->add_output(array('navi' => $this->navi(basename($_SERVER['SCRIPT_FILENAME']))));
		}
	}
	
	
	
	
	
	
	
	/**
	 * listall lists all inventoryentries, shows only entrys for which
	 * the user has sufficient rights
	 * 
	 * @return string html-string with the output
	 */
	private function listall() {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare return
		$output = $tr_out = $th_out = '';
		
		// read all entries
		$entries = $this->read_all_entries(true);
				
		// get templates
		// a
		try {
			$a = new HtmlTemplate('templates/a.tpl');
		} catch(Exception $e) {
			$GLOBALS['Error']->handle_error($e);
		}
		// table
		try {
			$table = new HtmlTemplate('templates/table.tpl');
		} catch(Exception $e) {
			$GLOBALS['Error']->handle_error($e);
		}
		// tr
		try {
			$tr = new HtmlTemplate('templates/tr.tpl');
		} catch(Exception $e) {
			$GLOBALS['Error']->handle_error($e);
		}
		// th
		try {
			$th = new HtmlTemplate('templates/th.tpl');
		} catch(Exception $e) {
			$GLOBALS['Error']->handle_error($e);
		}
		// td
		try {
			$td = new HtmlTemplate('templates/td.tpl');
		} catch(Exception $e) {
			$GLOBALS['Error']->handle_error($e);
		}
		// img
		try {
			$img = new HtmlTemplate('templates/img.tpl');
		} catch(Exception $e) {
			$GLOBALS['Error']->handle_error($e);
		}
		
		// prepare th
		$th_out .= $th->parse(array( // name
				'th.params' => ' class="name"',
				'th.content' => parent::lang('class.InventoryView#listall#TH#name')
			));
		$th_out .= $th->parse(array( // number
				'th.params' => ' class="number"',
				'th.content' => parent::lang('class.InventoryView#listall#TH#number')
			));
		$th_out .= $th->parse(array( // owner
				'th.params' => ' class="owner"',
				'th.content' => parent::lang('class.InventoryView#listall#TH#owner')
			));
		$th_out .= $th->parse(array( // status
				'th.params' => ' class="status"',
				'th.content' => parent::lang('class.InventoryView#listall#TH#status')
			));
		
		// parse tr for th
		$tr_out .= $tr->parse(array(
				'tr.params' => '',
				'tr.content' => $th_out)
			);
		
		// walk through entries
		$counter = 0;
		foreach($entries as $no => $entry) {
			
			// check if valid
			if($entry->return_valid() == 1) {
			
				// odd or even
				if($counter%2 == 0) {
					// even
					$tr_params = ' class="inventory.listall.tr even"';
				} else {
					// odd
					$tr_params = ' class="inventory.listall.tr odd"';
				}
				
				// get owner and status
				$user = new User();
				$id = $entry->return_id();
				$owner = '';
				$status = '';
				$owned_action = Inventory::movement_last_row($db,$id,'action');
				$owned_user = Inventory::movement_last_row($db,$id,'user_id',2);
				if($owned_action[0] == 'taken') {
					
					// taken
					$user->change_user($owned_user[0],false,'id');
					$owner = $user->return_userinfo('name');
					$status = '';
				} else {
					
					// given to
					$user->change_user($owned_user[1],false,'id');
					$owner = $user->return_userinfo('name');
					$user->change_user($owned_user[0],false,'id');
					$status = parent::lang('class.InventoryView#listall#status#givento').' '.$user->return_userinfo('name');;
				}
				
				// prepare details
				$detail_a = $a->parse(array(
						'a.params' => '',
						'a.href' => 'inventory.php?id=details&did='.$entry->return_id(),
						'a.title' => parent::lang('class.InventoryView#listall#title#details'),
						'a.content' => $entry->return_name()
					));
				
				// prepare td
				$td_out = $td->parse(array( // name
						'td.params' => ' class="name"',
						'td.content' => $detail_a
					));
				$td_out .= $td->parse(array( // number
						'td.params' => '',
						'td.content' => $entry->return_inventory_no()
					));
				$td_out .= $td->parse(array( // owner
						'td.params' => '',
						'td.content' => $owner
					));
				$td_out .= $td->parse(array( // status
						'td.params' => '',
						'td.content' => $status
					));
					
				// prepare tr
				$tr_out .= $tr->parse(array(
						'tr.params' => $tr_params,
						'tr.content' => $td_out
					));
				
				// increment counter
				$counter++;
			} else {
				
				// deleted items
			}
		}
		
		// complete table
		$output = $table->parse(array(	'table.params' => ' id="inventory.listall"',
										'table.content' => $tr_out));
		
		// return
		return $output;
	}
	
	
	
	
	
	
	
	/**
	 * read_all_entries get all inventory-entries from db for that the actual
	 * user has sufficient rights. returns an array with inventory-objects
	 * 
	 * @param bool $all all the user has rights to, if true, only owned, if false
	 * @return array all entries as inventory-objects
	 */
	private function read_all_entries($all = false) {
		
		// prepare return
		$inventory_entries = array();
				
		// get ids
		if($all) {
			$inventory_ids = Inventory::return_my_inventories();
		} else {
			$inventory_ids = Inventory::return_inventories();
		}
		
		// create inventory-objects
		foreach($inventory_ids as $index => $id) {
			$inventory_entries[] = new Inventory($id);
		}
		
		// return calendar-objects
		return $inventory_entries;
	}
	
	
	
	
	
	
	
	/**
	 * my creates the "my"-form and handle its response
	 * 
	 * @return string html-string with the "my"-form
	 */
	private function my() {
		
		// prepare return
		$output = $tr_out = $th_out = '';
		
		// read all entries
		$entries = $this->read_all_entries();
				
		// get templates
		// a
		try {
			$a = new HtmlTemplate('templates/a.tpl');
		} catch(Exception $e) {
			$GLOBALS['Error']->handle_error($e);
		}
		// table
		try {
			$table = new HtmlTemplate('templates/table.tpl');
		} catch(Exception $e) {
			$GLOBALS['Error']->handle_error($e);
		}
		// tr
		try {
			$tr = new HtmlTemplate('templates/tr.tpl');
		} catch(Exception $e) {
			$GLOBALS['Error']->handle_error($e);
		}
		// th
		try {
			$th = new HtmlTemplate('templates/th.tpl');
		} catch(Exception $e) {
			$GLOBALS['Error']->handle_error($e);
		}
		// td
		try {
			$td = new HtmlTemplate('templates/td.tpl');
		} catch(Exception $e) {
			$GLOBALS['Error']->handle_error($e);
		}
		// img
		try {
			$img = new HtmlTemplate('templates/img.tpl');
		} catch(Exception $e) {
			$GLOBALS['Error']->handle_error($e);
		}
		
		// prepare th
		$th_out .= $th->parse(array( // name
				'th.params' => ' class="name"',
				'th.content' => parent::lang('class.InventoryView#my#TH#name')
			));
		$th_out .= $th->parse(array( // number
				'th.params' => ' class="number"',
				'th.content' => parent::lang('class.InventoryView#my#TH#number')
			));
		// if loggedin show admin links
		if($_SESSION['user']->loggedin() === true) {
			$th_out .= $th->parse(array( // admin
					'th.params' => ' class="admin"',
					'th.content' => parent::lang('class.InventoryView#my#TH#admin')
				));
		}
		
		// parse tr for th
		$tr_out .= $tr->parse(array(
				'tr.params' => '',
				'tr.content' => $th_out)
			);
		
		// walk through entries
		$counter = 0;
		foreach($entries as $no => $entry) {
			
			// check if valid
			if($entry->return_valid() == 1) {
			
				// odd or even
				if($counter%2 == 0) {
					// even
					$tr_params = ' class="inventory.my.tr even"';
				} else {
					// odd
					$tr_params = ' class="inventory.my.tr odd"';
				}
				
				// prepare details
				$detail_a = $a->parse(array(
						'a.params' => '',
						'a.href' => 'inventory.php?id=details&did='.$entry->return_id(),
						'a.title' => parent::lang('class.InventoryView#my#title#details'),
						'a.content' => $entry->return_name()
					));
				
				// prepare td
				$td_out = $td->parse(array( // name
						'td.params' => ' class="name"',
						'td.content' => $detail_a
					));
				$td_out .= $td->parse(array( // number
						'td.params' => '',
						'td.content' => $entry->return_inventory_no()
					));
					
				// add admin
				// prepare exchange-link
				if($entry->return_owned() == 'taken') {
					
					$a_out = $a->parse(array(
						'a.params' => '',
						'a.href' => 'inventory.php?id=give&did='.$entry->return_id(),
						'a.title' => parent::lang('class.InventoryView#my#title#give'),
						'a.content' => parent::lang('class.InventoryView#my#content#give')
					));
				} elseif($entry->return_owned() == 'givento') {
				
					$a_out = $a->parse(array(
						'a.params' => '',
						'a.href' => 'inventory.php?id=cancel&did='.$entry->return_id(),
						'a.title' => parent::lang('class.InventoryView#my#title#cancel'),
						'a.content' => parent::lang('class.InventoryView#my#content#cancel')
					));
				} else {
					$a_out = $a->parse(array(
						'a.params' => '',
						'a.href' => 'inventory.php?id=take&did='.$entry->return_id(),
						'a.title' => parent::lang('class.InventoryView#my#title#take'),
						'a.content' => parent::lang('class.InventoryView#my#content#take')
					));
				}
					
				// prepare td
				$td_out .= $td->parse(array( // admin
						'td.params' => ' class="admin"',
						'td.content' => $a_out
					));
				
				// prepare tr
				$tr_out .= $tr->parse(array(
						'tr.params' => $tr_params,
						'tr.content' => $td_out
					));
				
				// increment counter
				$counter++;
			} else {
				
				// deleted items
			}
		}
		
		// complete table
		$output = $table->parse(array(	'table.params' => ' id="inventory.my"',
										'table.content' => $tr_out));
		
		// return
		return $output;
	}
	
	
	
	
	
	
	
	/**
	 * give creates the form to give an inventoryitem to somebody else
	 * 
	 * @param int $did entry-id for the inventoryitem
	 * @return string html-string with the form
	 */
	private function give($did) {
	
		// check rights
		if(Rights::check_rights($did,'inventory')) {
				
			// get calendar-object
			$inventory = new Inventory($did);
			
			// check owned
			if($inventory->return_owned() == 'taken') {
				
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
				
				// prepare return
				$return = '';
				
				// get preset
				$preset = $inventory->return_preset();
				
				// get fields
				$fields = $preset->return_fields();
				
				// add headline
				$return .= $hx->parse(array(
								'hx.x' => '2',
								'hx.parameters' => '',
								'hx.content' => parent::lang('class.InventoryView#give#page#headline').': '.$inventory->return_name().' ('.$inventory->return_inventory_no().')'
							));
				// add accessory info
				$return .= $p->parse(array(
								'parameters' => '',
								'text' => parent::lang('class.InventoryView#give#page#accessory.required')
							));
				
				// formular
				$form = new HTML_QuickForm2(
										'inventory_give',
										'post',
										array(
											'name' => 'inventory_give',
											'action' => 'inventory.php?id=give&did='.$this->get('did')
										)
									);
				// renderer
				$renderer = HTML_QuickForm2_Renderer::factory('default');
				$renderer->setOption('required_note',parent::lang('class.InventoryView#entry#form#requiredNote'));
				
				// add user-selection
				// get users
				$users_options = array('--');
				$users = $_SESSION['user']->return_all_users(array($_SESSION['user']->return_userinfo('username')));
				foreach($users as $user) {
					
					// put id and name in options-array
					$users_options[$user->return_userinfo('username')] = $user->return_userinfo('name');
				}
				$give_to = $form->addElement('select','give_to',array());
				$give_to->setLabel(parent::lang('class.InventoryView#give#page#objectinfo.head').$inventory->return_name().' ('.$inventory->return_inventory_no().')'.parent::lang('class.InventoryView#give#page#objectinfo.tail').':');
				$give_to->loadOptions($users_options);
				$give_to->addRule('required',parent::lang('class.InventoryView#entry#rule#required.give_to'));
				$give_to->addRule('callback',parent::lang('class.InventoryView#entry#rule#check.give_to'),array($this,'callback_check_select'));
				
				// generate field-quickform and add to form
				foreach($fields as $field) {
					
					// generate quickform
					$field->read_quickform();
					
					// add to form
					$form->appendChild($field->return_quickform());
				}
				
				// submit-button
				$form->addSubmit('submit',array('value' => parent::lang('class.InventoryView#give#form#submitButton')));
				
				// validate
				if($form->validate()) {
					
					// values
					$values = $form->getValue();
					
					// get user
					$givento_user = new User();
					$givento_user->change_user($values['give_to'],false);
					
					// write to db
					$insert_id = $this->movement_to_db('given',$inventory->return_id(),$givento_user->userid());
					// accessory to db
					$this->values_to_db($insert_id,$fields,$values);
					
					// headline
					$return = $hx->parse(array(
								'hx.x' => '3',
								'hx.parameters' => '',
								'hx.content' => $inventory->return_name().' ('.$inventory->return_inventory_no().')'.parent::lang('class.InventoryView#give#page#headline.givento').$givento_user->return_userinfo('name')
							));
					
					// accessory
					$return .= $p->parse(array(
								'parameters' => '',
								'text' => parent::lang('class.InventoryView#give#page#accessory.given')
							));
					
					// walk through fields
					foreach($fields as $field) {
						
						// check value
						if(isset($values['inventory-'.$field->return_id()])) {
							$field_value = $values['inventory-'.$field->return_id()];
						} else {
							$field_value = 0;
						}
						// return field and value as HTML
						$return .= $field->value_to_html($p,$field_value);
					}
				} else {
					$return .= $form->render($renderer);
				}
				
				// return
				return $return;
			} else {
				
				// error
				$errno = $GLOBALS['Error']->error_raised('NotOwned',$this->get('id'),$did);
				$GLOBALS['Error']->handle_error($errno);
				return $GLOBALS['Error']->to_html($errno);
			}
		} else {
			
			// error
			$errno = $GLOBALS['Error']->error_raised('NotAuthorized',$this->get('id'),$did);
			$GLOBALS['Error']->handle_error($errno);
			return $GLOBALS['Error']->to_html($errno);
		}
	}
	
	
	
	
	
	
	
	/**
	 * cancel cancels a movement on an inventory object
	 * 
	 * @param int $did entry-id for the inventoryitem
	 * @return string html-string with the form
	 */
	private function cancel($did) {
	
		// check rights
		if(Rights::check_rights($did,'inventory')) {
				
			// get calendar-object
			$inventory = new Inventory($did);
			
			// check owned
			if($inventory->return_owned() == 'givento') {
				
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
				// confirmation
				try {
				$confirmation = new HtmlTemplate('templates/div.confirmation.tpl');
				} catch(Exception $e) {
					$GLOBALS['Error']->handle_error($e);
				}
				// a
				try {
					$a = new HtmlTemplate('templates/a.tpl');
				} catch(Exception $e) {
					$GLOBALS['Error']->handle_error($e);
				}
				
				// prepare return
				$return = '';
				
				$form = new HTML_QuickForm2(
										'confirm',
										'post',
										array(
											'name' => 'confirm',
											'action' => 'inventory.php?id=cancel&did='.$did
										)
									);
				
				// add button
				$form->addElement('submit','yes',array('value' => parent::lang('class.InventoryView#cancel#form#yes')));
				
				// prepare cancel
				$cancel_a = $a->parse(array(
						'a.params' => '',
						'a.href' => 'inventory.php?id=my',
						'a.title' => parent::lang('class.InventoryView#cancel#title#cancel'),
						'a.content' => parent::lang('class.InventoryView#cancel#form#cancel')
					));
				$cancel = $p->parse(array(
						'params' => '',
						'text' => $cancel_a
				));
				
				// set output
				$return = $confirmation->parse(array(
						'p.message' => parent::lang('class.InventoryView#cancel#message#confirm'),
						'p.form' => $form."\n".$cancel
					));
				
				// validate
				if($form->validate()) {
				
					// set output
					$return = $hx->parse(array(
							'hx.content' => parent::lang('class.InventoryView#cancel#message#done'),
							'hx.x' => '3',
							'hx.parameters' => ''
						));
					
					// movement to db
					$insert_id = $this->movement_to_db('taken',$inventory->return_id());
				}
				
				// return
				return $return;
			} else {
				
				// error
				$errno = $GLOBALS['Error']->error_raised('NotGiven',$this->get('id'),$did);
				$GLOBALS['Error']->handle_error($errno);
				return $GLOBALS['Error']->to_html($errno);
			}
		} else {
			
			// error
			$errno = $GLOBALS['Error']->error_raised('NotAuthorized',$this->get('id'),$did);
			$GLOBALS['Error']->handle_error($errno);
			return $GLOBALS['Error']->to_html($errno);
		}
	}
	
	
	
	
	
	
	
	/**
	 * details returns the details of a calendar-entry as html-string
	 * 
	 * @param int $cid entry-id for calendar
	 * @return string html-string with the details of the calendar entry
	 */
	private function details($cid) {
	
		// check rights
		if(Rights::check_rights($cid,'calendar',true)) {
				
			// get calendar-object
			$calendar = new Calendar($cid);
			
			// read template
			try {
				$calendar_details = new HtmlTemplate('templates/calendar.details.tpl');
			} catch(Exception $e) {
				$GLOBALS['Error']->handle_error($e);
			}
			
			// return html-string
			return $calendar->details_to_html($calendar_details);
		} else {
			
			// error
			$errno = $GLOBALS['Error']->error_raised('NotAuthorized','entry:'.$this->get('id'),$this->get('id'));
			$GLOBALS['Error']->handle_error($errno);
			return $GLOBALS['Error']->to_html($errno);
		}
	}
	
	
	
	
	
	
	
	/**
	 * movement_to_db writes the movement of the inventory object to the db
	 * 
	 * @param string $action the processed action given or taken
	 * @param int $inventoryid id of the processed inventory object
	 * @param int $userid id of the user (givento or own if taken)
	 * @return int id of inserted values
	 */
	private function movement_to_db($action,$inventoryid,$userid = 0) {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare date
		$now = date('Y-m-d H:i:s');
		
		// check action
		if($action == 'given') {
		
			// prepare sql-statement
			$sql = "INSERT INTO inventory_movement (id,inventory_id,date_time,action,user_id)
					VALUES (NULL,$inventoryid,'$now','$action',$userid)";
		} else {
			
			// userid
			$userid = $_SESSION['user']->userid();
			// prepare sql-statement
			$sql = "INSERT INTO inventory_movement (id,inventory_id,date_time,action,user_id)
					VALUES (NULL,$inventoryid,'$now','$action',$userid)";
		}
		
		// execute
		$result = $db->query($sql);
		
		// return
		return $db->insert_id;
	}
	
	
	
	
	
	
	
	/**
	 * values_to_db writes the values of the inventory object accessories to the db
	 * 
	 * @param int $insert_id the id of the inserted movement
	 * @param array $fields array of the inventory objects fields
	 * @param array $values array of values to the fields
	 * @return void
	 */
	private function values_to_db($insert_id,$fields,$values) {
		
		// get db-object
		$db = Db::newDb();
		
		// walk through the fields
		foreach($fields as $field) {
			
			// get fieldid and according value
			$fieldid = $field->return_id();
			// if set
			if(isset($values['inventory-'.$fieldid])) {
				$value = $values['inventory-'.$fieldid];
			} else {
				$value = 0;
			}
			
			// prepare sql-statement
			$sql = "INSERT INTO value (id,table_name,table_id,field_id,value)
					VALUES (NULL,'inventory_movement',$insert_id,$fieldid,'$value')";
			
			// execute
			$result = $db->query($sql);
		}		
	}
}



?>
