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
			$this->getError()->handle_error($e);
		}
	}
	
	/*
	 * methods
	 */
//	/**
//	 * navi knows about the functionalities used in navigation returns an array
//	 * containing first- and second-level-navientries
//	 * 
//	 * @return array contains first- and second-level-navientries
//	 */
//	public static function connectnavi() {
//		
//		// set first- and secondlevel names and set secondlevel $_GET['id']-values
//		static $navi = array();
//		
//		$navi = array(
//						'firstlevel' => array(
//							'name' => 'class.InventoryView#connectnavi#firstlevel#name',
//							'file' => 'inventory.php',
//							'position' => 3,
//							'class' => get_class(),
//							'id' => md5('InventoryView'), // f32d321bb51244f1e09cfd0f34c82bda
//							'show' => true
//						),
//						'secondlevel' => array(
//							1 => array(
//								'getid' => 'listall', 
//								'name' => 'class.InventoryView#connectnavi#secondlevel#listall',
//								'id' => md5('InventoryView|listall'), // 4c13dc7e14dd5fe1ade036aac60f64c4
//								'show' => true
//							),
//							0 => array(
//								'getid' => 'my', 
//								'name' => 'class.InventoryView#connectnavi#secondlevel#my',
//								'id' => md5('InventoryView|my'), // 1b7715352a02ff2cdd753e7b23fa46c4
//								'show' => true
//							),
//							2 => array(
//								'getid' => 'give', 
//								'name' => 'class.InventoryView#connectnavi#secondlevel#give',
//								'id' => md5('InventoryView|give'), // 1b26a57943c16402c4b206936e1fc44a
//								'show' => false
//							),
//							3 => array(
//								'getid' => 'take', 
//								'name' => 'class.InventoryView#connectnavi#secondlevel#take',
//								'id' => md5('InventoryView|take'), // e1181951b8950761f24c0d6c1dedb269
//								'show' => false
//							),
//							4 => array(
//								'getid' => 'cancel', 
//								'name' => 'class.InventoryView#connectnavi#secondlevel#cancel',
//								'id' => md5('InventoryView|cancel'), // 4a90ccaf9dd0b1359fa550eafa77e0e0
//								'show' => false
//							),
//							5 => array(
//								'getid' => 'details', 
//								'name' => 'class.InventoryView#connectnavi#secondlevel#details',
//								'id' => md5('InventoryView|details'), // 10fbb6433764f41e4d40b53a511da245
//								'show' => false
//							),
//							6 => array(
//								'getid' => 'movement', 
//								'name' => 'class.InventoryView#connectnavi#secondlevel#movement',
//								'id' => md5('InventoryView|movement'), // 237ff1df758a293404b01433488ed577
//								'show' => false
//							)
//						)
//					);
//		
//		// return array
//		return $navi;
//	}
	
	
	
	
	
	
	
	/**
	 * init chooses the functionality by using $_GET['id']
	 * 
	 * @return void
	 */
	public function init() {
		
		// set pagename
		$this->tpl->assign('pagename',parent::lang('class.InventoryView#page#init#name'));
		
		// init helpmessages
		$this->initHelp();
		
		// switch $_GET['id'] if set
		if($this->get('id') !== false) {
			
			// check permissions
			$naviId = Navi::idFromFileParam(basename($_SERVER['SCRIPT_FILENAME']), $this->get('id'));
			if($this->getUser()->hasPermission('navi', $naviId)) {
				
				switch($this->get('id')) {
					
					case 'listall':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.InventoryView#init#listall#title')));
						$this->tpl->assign('main', $this->listall());
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('hierselect', false);
					break;
					
					case 'my':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.InventoryView#init#my#title')));
						$this->tpl->assign('main', $this->my());
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('hierselect', false);
					break;
					
					case 'give':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.InventoryView#init#give#title')));
						$this->tpl->assign('main', $this->give($this->get('did')));
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('hierselect', true);
					break;
					
					
					case 'take':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.InventoryView#init#take#title')));
						$this->tpl->assign('main', $this->take($this->get('did')));
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('hierselect', true);
					break;
					
					case 'cancel':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.InventoryView#init#cancel#title')));
						$this->tpl->assign('main', $this->cancel($this->get('did')));
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('hierselect', true);
					break;
					
					case 'details':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.InventoryView#init#details#title')));
						$this->tpl->assign('main', $this->details($this->get('did')));
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('hierselect', false);
					break;
					
					case 'movement':
						
						// smarty
						$this->tpl->assign('title', $this->title(parent::lang('class.InventoryView#init#movement#title')));
						$this->tpl->assign('main', $this->movement($this->get('mid')));
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('hierselect', true);
					break;
					
					default:
						
						// id set, but no functionality
						$errno = $this->getError()->error_raised('GETUnkownId','entry:'.$this->get('id'),$this->get('id'));
						$this->getError()->handle_error($errno);
						
						// smarty
						$this->tpl->assign('title', '');
						$this->tpl->assign('main', $this->getError()->to_html($errno));
						$this->tpl->assign('jquery', true);
						$this->tpl->assign('hierselect', false);
					break;
				}
			} else {
				
				$errno = $this->getError()->error_raised('NotAuthorized','entry:'.$this->get('id'),$this->get('id'));
				$this->getError()->handle_error($errno);
				
				// smarty
				$this->tpl->assign('title', $this->title(parent::lang('class.InventoryView#init#Error#NotAuthorized')));
				$this->tpl->assign('main', $this->getError()->to_html($errno));
				$this->tpl->assign('jquery', true);
				$this->tpl->assign('hierselect', false);
			}
		} else {
			
			// id not set
			// smarty-title
			$this->tpl->assign('title', $this->title(parent::lang('class.InventoryView#init#default#title'))); 
			// smarty-main
			$this->tpl->assign('main', $this->defaultContent());
			// smarty-jquery
			$this->tpl->assign('jquery', true);
			// smarty-hierselect
			$this->tpl->assign('hierselect', false);
		}
		
		// global smarty
		$this->showPage('smarty.main.tpl');
	}
	
	
	
	
	
	
	
	/**
	 * listall lists all inventoryentries, shows only entrys for which
	 * the user has sufficient rights
	 * 
	 * @return string html-string with the output
	 */
	private function listall() {
		
		// pagecaption
		$this->tpl->assign('pagecaption',parent::lang('class.InventoryView#page#caption#listall'));
		
		// get db-object
		$db = Db::newDb();
		
		// prepare return
		$output = $tr_out = $th_out = '';
		
		// read all entries
		$entries = Inventory::returnInventories(true);
		
		// smarty-template
		$sL = new JudoIntranetSmarty();
		
		// prepare th
		$th = array(
				array(
						'class' => 'name',
						'content' => parent::lang('class.InventoryView#listall#TH#name')
					),
				array(
						'class' => 'number',
						'content' => parent::lang('class.InventoryView#listall#TH#number')
					),
				array(
						'class' => 'owner',
						'content' => parent::lang('class.InventoryView#listall#TH#owner')
					),
				array(
						'class' => 'status',
						'content' => parent::lang('class.InventoryView#listall#TH#status')
					)
			);
		$sL->assign('th', $th);
		
		// walk through entries
		$data = array();
		$counter = 0;
		foreach($entries as $no => $entry) {
			
			// check if valid
			if($entry->get_valid() == 1) {
			
				// odd or even
				if($counter%2 == 0) {
					// even
					$tr_params = ' class="inventory.listall.tr even"';
				} else {
					// odd
					$tr_params = ' class="inventory.listall.tr odd"';
				}
				
				// get owner and status
				$user = new User(false);
				$id = $entry->get_id();
				$owner = '';
				$status = '';
				$owned_action = Inventory::movement_last_row($db,$id,'action');
				$owned_user = Inventory::movement_last_row($db,$id,'user_id',2);
				if($owned_action[0] == 'taken') {
					
					// taken
					$user->change_user($owned_user[0],false,'id');
					$owner = $user->get_userinfo('name');
					$status = '';
				} else {
					
					// given to
					$user->change_user($owned_user[1],false,'id');
					$owner = $user->get_userinfo('name');
					$user->change_user($owned_user[0],false,'id');
					$status = parent::lang('class.InventoryView#listall#status#givento').' '.$user->get_userinfo('name');;
				}
				
				// prepare details
				$data[$counter] = array( 
						'name' => array(
							'href' => 'inventory.php?id=details&did='.$entry->get_id(),
							'title' => parent::lang('class.InventoryView#listall#title#details'),
							'content' => $entry->get_name()
						),
						'number' => $entry->get_inventory_no(),
						'owner' => $owner,
						'status' => $status
					);
				
				// increment counter
				$counter++;
			} else {
				
				// deleted items
			}
		}
		$sL->assign('data', $data);
		
		// return
		return $sL->fetch('smarty.inventory.listall.tpl');
	}
	
	
	
	
	
	
	
	/**
	 * my creates the "my"-form and handle its response
	 * 
	 * @return string html-string with the "my"-form
	 */
	private function my() {
		
		// pagecaption
		$this->tpl->assign('pagecaption',parent::lang('class.InventoryView#page#caption#my'));
		
		// prepare return
		$output = $tr_out = $th_out = '';
		
		// read all entries
		$entries = Inventory::returnInventories();
		
		// smarty-template
		$sM = new JudoIntranetSmarty();
		
		// prepare th
		$th = array(
				'name' => array(
						'class' => 'name',
						'content' => parent::lang('class.InventoryView#my#TH#name')
					),
				'number' => array(
						'class' => 'number',
						'content' => parent::lang('class.InventoryView#my#TH#number')
					)
			);
			
		// if loggedin show admin links
		$sM->assign('loggedin', $this->getUser()->get_loggedin());
		if($this->getUser()->get_loggedin() === true) {
			$th['admin'] = array(
					'class' => 'admin',
					'content' => parent::lang('class.InventoryView#my#TH#admin')
				);
		}
		$sM->assign('th', $th);
		
		// walk through entries
		$data = array();
		$counter = 0;
		foreach($entries as $no => $entry) {
			
			// check if valid
			if($entry->get_valid() == 1) {
			
				// prepare details
				$data[$counter]['name'] = array(
						'href' => 'inventory.php?id=details&did='.$entry->get_id(),
						'title' => parent::lang('class.InventoryView#my#title#details'),
						'content' => $entry->get_name()
					);
				$data[$counter]['number'] = $entry->get_inventory_no();
					
				// add admin
				// prepare exchange-link
				if($entry->get_owned() == 'taken') {
					$data[$counter]['admin'] = array(
							'href' => 'inventory.php?id=give&did='.$entry->get_id(),
							'title' => parent::lang('class.InventoryView#my#title#give'),
							'content' => parent::lang('class.InventoryView#my#content#give')
						);
				} elseif($entry->get_owned() == 'givento') {
					$data[$counter]['admin'] = array(
							'href' => 'inventory.php?id=cancel&did='.$entry->get_id(),
							'title' => parent::lang('class.InventoryView#my#title#cancel'),
							'content' => parent::lang('class.InventoryView#my#content#cancel')
						);
				} else {
					$data[$counter]['admin'] = array(
						'href' => 'inventory.php?id=take&did='.$entry->get_id(),
						'title' => parent::lang('class.InventoryView#my#title#take'),
						'content' => parent::lang('class.InventoryView#my#content#take')
					);
				}
				
				// increment counter
				$counter++;
			} else {
				
				// deleted items
			}
		}
		$sM->assign('data', $data);
		
		// return
		return $sM->fetch('smarty.inventory.my.tpl');
	}
	
	
	
	
	
	
	
	/**
	 * give creates the form to give an inventoryitem to somebody else
	 * 
	 * @param int $did entry-id for the inventoryitem
	 * @return string html-string with the form
	 */
	private function give($did) {
	
		// check rights
		if($this->getUser()->hasPermission('inventory', $did)) {
			
			// pagecaption
			$this->tpl->assign('pagecaption',parent::lang('class.InventoryView#page#caption#give'));
				
			// get inventory-object
			$inventory = new Inventory($did);
			
			// check owned
			if($inventory->get_owned() == 'taken') {
				
				// smarty-template
				$sG = new JudoIntranetSmarty();
				
				// prepare return
				$return = '';
				
				// get preset
				$preset = $inventory->get_preset();
				
				// get fields
				$fields = $preset->get_fields();
				
				// add headline
				$sG->assign('caption', parent::lang('class.InventoryView#give#page#headline').': '.$inventory->get_name().' ('.$inventory->get_inventory_no().')');
				// add accessory info
				$sG->assign('inventoryinfo', parent::lang('class.InventoryView#give#page#accessory.required'));
				
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
				$users = $this->getUser()->return_all_users(array($this->getUser()->get_userinfo('username')));
				foreach($users as $user) {
					
					// put id and name in options-array
					$users_options[$user->get_userinfo('username')] = $user->get_userinfo('name');
				}
				// remove admin
				unset($users_options['admin']);
				
				$give_to = $form->addElement('select','give_to',array());
				$give_to->setLabel(parent::lang('class.InventoryView#give#page#objectinfo.head').$inventory->get_name().' ('.$inventory->get_inventory_no().')'.parent::lang('class.InventoryView#give#page#objectinfo.tail').':');
				$give_to->loadOptions($users_options);
				$give_to->addRule('required',parent::lang('class.InventoryView#entry#rule#required.give_to'));
				$give_to->addRule('callback',parent::lang('class.InventoryView#entry#rule#check.give_to'),array($this,'callback_check_select'));
				
				// generate field-quickform and add to form
				foreach($fields as $field) {
					
					// generate quickform
					$field->read_quickform();
					
					// add to form
					$form->appendChild($field->get_quickform());
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
					$insert_id = $this->movement_to_db('given',$inventory->get_id(),$givento_user->userid());
					// accessory to db
					$this->values_to_db($insert_id,$fields,$values);
					
					// headline
					$sG->assign('action', $inventory->get_name().' ('.$inventory->get_inventory_no().')'.parent::lang('class.InventoryView#give#page#headline.givento').$givento_user->get_userinfo('name'));
					
					// accessory
					$sG->assign('accessoryaction', parent::lang('class.InventoryView#give#page#accessory.given'));
					
					// walk through fields
					$data = array();
					foreach($fields as $field) {
						
						// check value
						if(isset($values['inventory-'.$field->get_id()])) {
							$field_value = $values['inventory-'.$field->get_id()];
						} else {
							$field_value = 0;
						}
						// return field and value as HTML
						$field->value($field_value);
						$data[] = $field->value_to_html();
					}
					$sG->assign('form', '');
					$sG->assign('data', $data);
				} else {
					$sG->assign('form', $form->render($renderer));
				}
				
				// return
				return $sG->fetch('smarty.inventory.takegive.tpl');
			} else {
				
				// error
				$errno = $this->getError()->error_raised('NotOwned',$this->get('id'),$did);
				$this->getError()->handle_error($errno);
				return $this->getError()->to_html($errno);
			}
		} else {
			
			// error
			$errno = $this->getError()->error_raised('NotAuthorized',$this->get('id'),$did);
			$this->getError()->handle_error($errno);
			return $this->getError()->to_html($errno);
		}
	}
	
	
	
	
	
	
	
	/**
	 * take creates the form to take an inventoryitem from somebody else
	 * 
	 * @param int $did entry-id for the inventoryitem
	 * @return string html-string with the form
	 */
	private function take($did) {
	
		// check rights
		if($this->getUser()->hasPermission('inventory', $did)) {
			
			// pagecaption
			$this->tpl->assign('pagecaption',parent::lang('class.InventoryView#page#caption#take'));
			
			// get db-object
			$db = Db::newDb();
			
			// get inventory-object
			$inventory = new Inventory($did);
			
			// check owned
			if($inventory->get_owned() == 'given') {
				
				// smarty-template
				$sT = new JudoIntranetSmarty();
				
				// prepare return
				$return = '';
				
				// get preset
				$preset = $inventory->get_preset();
				
				// get fields
				$fields = $preset->get_fields();
				
				// add headline
				$sT->assign('caption', parent::lang('class.InventoryView#take#page#headline').': '.$inventory->get_name().' ('.$inventory->get_inventory_no().')');
				
				// add take from
				$movements = Inventory::movement_last_row($db,$inventory->get_id(),'user_id',2);
				$user = new User();
				$user->change_user($movements[1],false,'id');
				$sT->assign('takefrom', parent::lang('class.InventoryView#take#page#TakeFrom').': '.$user->get_userinfo('name'));
				// add accessory info
				$sT->assign('accessoryinfo', parent::lang('class.InventoryView#take#page#accessory.required'));
				
				// formular
				$form = new HTML_QuickForm2(
										'inventory_take',
										'post',
										array(
											'name' => 'inventory_take',
											'action' => 'inventory.php?id=take&did='.$this->get('did')
										)
									);
				// renderer
				$renderer = HTML_QuickForm2_Renderer::factory('default');
				$renderer->setOption('required_note',parent::lang('class.InventoryView#entry#form#requiredNote'));
				
				// generate field-quickform and add to form
				foreach($fields as $field) {
					
					// check if given
					if($inventory->movement_last_accessories($field) === true || $field->get_type() == 'text') {
					
						// generate quickform
						$field->read_quickform();
					} else {
						
						// generate quickform
						$field->read_quickform(array('disabled' => 'disabled'));
					}
					
					// add to form
					$form->appendChild($field->get_quickform());
				}
				
				// submit-button
				$form->addSubmit('submit',array('value' => parent::lang('class.InventoryView#take#form#submitButton')));
				
				// validate
				if($form->validate()) {
					
					// values
					$values = $form->getValue();
					
					// write to db
					$insert_id = $this->movement_to_db('taken',$inventory->get_id(),$this->getUser()->userid());
					// accessory to db
					$this->values_to_db($insert_id,$fields,$values);
					
					// headline
					$sT->assign('action', $inventory->get_name().' ('.$inventory->get_inventory_no().') '.parent::lang('class.InventoryView#take#page#headline.taken'));
					
					// accessory
					$sT->assign('accessoryaction', parent::lang('class.InventoryView#take#page#accessory.taken'));
					
					// walk through fields
					$data = array();
					foreach($fields as $field) {
						
						// check value
						if(isset($values['inventory-'.$field->get_id()])) {
							$field_value = $values['inventory-'.$field->get_id()];
						} else {
							$field_value = 0;
						}
						// return field and value as HTML
						$field->value($field_value);
						$data[] = $field->value_to_html();
					}
					$sT->assign('form', '');
					$sT->assign('data', $data);
				} else {
					$sT->assign('form', $form->render($renderer));
				}
				
				// return
				return $sT->fetch('smarty.inventory.takegive.tpl');
			} else {
				
				// error
				$errno = $this->getError()->error_raised('NotGivenTo',$this->get('id'),$did);
				$this->getError()->handle_error($errno);
				return $this->getError()->to_html($errno);
			}
		} else {
			
			// error
			$errno = $this->getError()->error_raised('NotAuthorized',$this->get('id'),$did);
			$this->getError()->handle_error($errno);
			return $this->getError()->to_html($errno);
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
		if($this->getUser()->hasPermission('inventory', $did)) {
			
			// pagecaption
			$this->tpl->assign('pagecaption',parent::lang('class.InventoryView#page#caption#cancel'));
				
			// get inventory-object
			$inventory = new Inventory($did);
			
			// get preset
			$preset = $inventory->get_preset();
			
			// get fields
			$fields = $preset->get_fields();
			
			// check owned
			if($inventory->get_owned() == 'givento') {
				
				// smarty-template
				$sC = new JudoIntranetSmarty();
				
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
				
				// smarty-link
				$link = array(
						'params' => '',
						'href' => 'inventory.php?id=my',
						'title' => parent::lang('class.InventoryView#cancel#title#cancel'),
						'content' => parent::lang('class.InventoryView#cancel#form#cancel')
					);
				$sC->assign('link', $link);
				$sC->assign('spanparams', 'id="cancel"');
				$sC->assign('message', parent::lang('class.InventoryView#cancel#message#confirm'));
				$sC->assign('form', $form);
				
				// validate
				if($form->validate()) {
				
					// smarty
					$sC->assign('message', parent::lang('class.InventoryView#cancel#message#done'));
					$sC->assign('form', '');
					
					// movement to db
					$insert_id = $this->movement_to_db('taken',$inventory->get_id());
					// get values of last movement and values to db
					$last_values = $inventory->movement_last_values();
					$this->values_to_db($insert_id,$fields,$last_values);
					
				}
				
				// return
				return $sC->fetch('smarty.confirmation.tpl');
			} else {
				
				// error
				$errno = $this->getError()->error_raised('NotGiven',$this->get('id'),$did);
				$this->getError()->handle_error($errno);
				return $this->getError()->to_html($errno);
			}
		} else {
			
			// error
			$errno = $this->getError()->error_raised('NotAuthorized',$this->get('id'),$did);
			$this->getError()->handle_error($errno);
			return $this->getError()->to_html($errno);
		}
	}
	
	
	
	
	
	
	
	/**
	 * details returns the movement details of a inventory-entry as html
	 * 
	 * @param int $did entry-id for inventory
	 * @return string html-string with the details of the inventory entry
	 */
	private function details($did) {
	
		// check rights
		if($this->getUser()->hasPermission('inventory', $did)) {
			
			// pagecaption
			$this->tpl->assign('pagecaption',parent::lang('class.InventoryView#page#caption#details'));
				
			// get invetory-object
			$inventory = new Inventory($did);
			
			// get preset
			$preset = $inventory->get_preset();
			
			// get fields
			$fields = $preset->get_fields();
			
			// smarty-template
			$sD = new JudoIntranetSmarty();
			// smarty
			$sD->assign('caption', $inventory->get_name().' ('.$inventory->get_inventory_no().')');
			$sD->assign('accessorylist', parent::lang('class.InventoryView#details#accessories#list'));
			$accessories = '';
			foreach($fields as $field) {
				
				// check type
				if($field->get_type() != 'text') {
					$accessories .= $field->get_name().', ';
				}
			}
			$accessories = substr($accessories,0,-2);
			$sD->assign('accessories', $accessories);
			
			// get movements
			$movements = $this->get_movements($inventory);
			$sD->assign('data', $movements);
			
			// return
			return $sD->fetch('smarty.inventory.details.tpl');
		} else {
			
			// error
			$errno = $this->getError()->error_raised('NotAuthorized',$this->get('id'),$did);
			$this->getError()->handle_error($errno);
			return $this->getError()->to_html($errno);
		}
	}
	
	
	
	
	
	
	
	/**
	 * movement returns the details of a movement-entry as html
	 * 
	 * @param int $mid entry-id for the movement
	 * @return string html-string with the details of the movement entry
	 */
	private function movement($mid) {
	
		// get db-object
		$db = Db::newDb();
		
		// get movement details
		// prepare sql-statement
		$sql = "SELECT m.inventory_id
				FROM inventory_movement AS m
				WHERE m.id = $mid";
		
		// execute
		$result = $db->query($sql);
		
		// fetch result
		list($inventory_id) = $result->fetch_array(MYSQL_NUM);
		
		// get invetory-object
		$inventory = new Inventory($inventory_id);
		
		// get preset
		$preset = $inventory->get_preset();
		
		// get fields
		$fields = $preset->get_fields();
		
		// check rights
		if($this->getUser()->hasPermission('inventory', $inventory->get_id())) {
			
			//smarty-template
			$sM = new JudoIntranetSmarty();
			
			// prepare sql
			$sql = "SELECT m.id,m.user_id,m.action,m.date_time
					FROM inventory_movement AS m
					WHERE m.inventory_id=".$inventory->get_id()."
					ORDER BY m.date_time ASC";
			
			// execute
			$result = $db->query($sql);
			
			// fetch result
			$i = 0;
			$movements_data = array();
			while(list($m_id,$m_user_id,$m_action,$m_date_time) = $result->fetch_array(MYSQL_NUM)) {
				$movements_data[$i]['id'] = $m_id;
				$movements_data[$i]['user_id'] = $m_user_id;
				$movements_data[$i]['action'] = $m_action;
				$movements_data[$i]['date_time'] = $m_date_time;
				$i++;
			}
			
			// get actual movement data
			$data = array();
			for($i=0;$i<count($movements_data); $i++) {
				
				// check actual mid and previous
				if($movements_data[$i]['id'] == $mid) {
					$data[0]['id'] = $movements_data[$i]['id'];
					$data[0]['user_id'] = $movements_data[$i]['user_id'];
					$data[0]['action'] = $movements_data[$i]['action'];
					$data[0]['date_time'] = $movements_data[$i]['date_time'];
					
					// check first
					if($i != 0) {
						$data[1]['id'] = $movements_data[$i-1]['id'];
						$data[1]['user_id'] = $movements_data[$i-2]['user_id'];
						$data[1]['action'] = $movements_data[$i-1]['action'];
					}
				}
			}
			
			$sM->assign('inventory', parent::lang('class.InventoryView#movement#hx#movement').$inventory->get_name().' ('.$inventory->get_inventory_no().')');
			$sM->assign('date', parent::lang('class.InventoryView#movement#hx#at').date('d.m.Y',strtotime($data[0]['date_time'])));
			$back = array(
					'href' => 'javascript:history.back(1)',
					'title' => parent::lang('class.InventoryView#movement#back#title'),
					'content' => parent::lang('class.InventoryView#movement#back#name')
				);
			$sM->assign('back', $back);
			
			foreach($data as $movement) {
				
				// get user
				$user = new User(false);
				$user->change_user($movement['user_id'],false,'id');
				
				// prepare fields
				$fields_out = array();
				foreach($fields as $field) {
					
					// get values
					$data = array(
							'table' => 'inventory_movement',
							'table_id' => $movement['id'],
							'field_id' => $field->get_id());
					$field->read_value($data);
					$fields_out[] = $field->value_to_html();
				}
				$sM->assign('data', $fields_out);
				$sM->assign('user', parent::lang('class.InventoryView#movement#fields#'.$movement['action']).' '.$user->get_userinfo('name'));
			}
			
			// return
			return $sM->fetch('smarty.inventory.movement.tpl');
		} else {
			
			// error
			$errno = $this->getError()->error_raised('NotAuthorized',$this->get('id'),$mid);
			$this->getError()->handle_error($errno);
			return $this->getError()->to_html($errno);
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
			$userid = $this->getUser()->userid();
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
			$fieldid = $field->get_id();
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
	
	
	
	
	
	
	
	/**
	 * get_movements returns the htmlstring of the movements
	 * 
	 * @param object $inventory the inventory object
	 * @return string html of the movement list
	 */
	private function get_movements($inventory) {
		
		// get id
		$id = $inventory->get_id();
		
		// get preset
		$preset = $inventory->get_preset();
		
		// get fields
		$fields = $preset->get_fields();
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql-statement
		$sql = "SELECT u.name,m.id,m.date_time
				FROM user AS u, inventory_movement AS m
				WHERE m.action = 'taken'
				AND m.inventory_id = $id
				AND u.id = m.user_id
				ORDER BY m.date_time DESC";
		
		// execute
		$result = $db->query($sql);
		
		$movements = array();
		while(list($name,$movement_id,$date_time) = $result->fetch_array(MYSQL_NUM)) {
			
			// smarty
			$movements[] = array(
					'href' => 'inventory.php?id=movement&mid='.$movement_id,
					'title' => parent::lang('class.InventoryView#get_movements#date#title'),
					'content' => date('d.m.Y',strtotime($date_time)),
					'name' => $name
				);
		}
		
		// return
		return $movements;
	}
}



?>
