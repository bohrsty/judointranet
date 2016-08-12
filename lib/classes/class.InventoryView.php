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
		parent::__construct();
	}
	
	/*
	 * methods
	 */
	/**
	 * init chooses the functionality by using $_GET['id']
	 * 
	 * @param bool $show uses smarty display method to show, if true, smarty fetch method if false
	 * @return void
	 */
	public function init($show = true) {
		
		// set pagename
		$this->getTpl()->assign('pagename',_l('inventory'));
		
		// init helpmessages
		$this->initHelp();
		
		// switch $_GET['id'] if set
		if($this->get('id') !== false) {
			
			// check permissions
			$naviId = Navi::idFromFileParam(self::requestedFilename(), $this->get('id'));
			if($this->getUser()->hasPermission('navi', $naviId)) {
				
				switch($this->get('id')) {
					
					case 'listall':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('inventory: listall')));
						$this->getTpl()->assign('main', $this->listall());
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('hierselect', false);
					break;
					
					case 'my':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('inventory: own objects')));
						$this->getTpl()->assign('main', $this->my());
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebfaform', false);
					break;
					
					case 'give':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('inventory: give object')));
						$this->getTpl()->assign('main', $this->give($this->get('did')));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
					break;
					
					
					case 'take':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('inventory: take object')));
						$this->getTpl()->assign('main', $this->take($this->get('did')));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
					break;
					
					case 'cancel':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('inventory: cancel give')));
						$this->getTpl()->assign('main', $this->cancel($this->get('did')));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
					break;
					
					case 'details':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('inventory: details')));
						$this->getTpl()->assign('main', $this->details($this->get('did')));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', false);
					break;
					
					case 'movement':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('inventory: transactions')));
						$this->getTpl()->assign('main', $this->movement($this->get('mid')));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
					break;
					
					default:
						// id set, but no functionality
						throw new GetUnknownIdException($this, $this->get('id'));
					break;
				}
			} else {
				
				// error not authorized
				throw new NotAuthorizedException($this);
			}
		} else {
			
			// id not set
			// smarty-title
			$this->getTpl()->assign('title', $this->title(_l('inventory'))); 
			// smarty-main
			$this->getTpl()->assign('main', $this->defaultContent());
			// smarty-jquery
			$this->getTpl()->assign('jquery', true);
			// smarty-hierselect
			$this->getTpl()->assign('zebraform', false);
		}
		
		// global smarty
		if($show === true) {
			$this->showPage('smarty.main.tpl', $show);
		} else {
			return $this->showPage('smarty.main.tpl', $show);
		}
	}
	
	
	
	
	
	
	
	/**
	 * listall lists all inventoryentries, shows only entrys for which
	 * the user has sufficient permissions
	 * 
	 * @return string html-string with the output
	 */
	private function listall() {
		
		// pagecaption
		$this->getTpl()->assign('pagecaption',_l('listall'));
		
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
						'content' => _l('object')
					),
				array(
						'class' => 'number',
						'content' => _l('inventory number')
					),
				array(
						'class' => 'owner',
						'content' => _l('owner')
					),
				array(
						'class' => 'status',
						'content' => _l('state')
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
					$status = _l('to be given to').' '.$user->get_userinfo('name');;
				}
				
				// prepare details
				$data[$counter] = array( 
						'name' => array(
							'href' => 'inventory.php?id=details&did='.$entry->get_id(),
							'title' => _l('details'),
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
		$this->getTpl()->assign('pagecaption',_l('manage own objects'));
		
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
						'content' => _l('object')
					),
				'number' => array(
						'class' => 'number',
						'content' => _l('inventory number')
					)
			);
			
		// if loggedin show admin links
		$sM->assign('loggedin', $this->getUser()->get_loggedin());
		if($this->getUser()->get_loggedin() === true) {
			$th['admin'] = array(
					'class' => 'admin',
					'content' => _l('tasks')
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
						'title' => _l('details'),
						'content' => $entry->get_name()
					);
				$data[$counter]['number'] = $entry->get_inventory_no();
					
				// add admin
				// prepare exchange-link
				if($entry->get_owned() == 'taken') {
					$data[$counter]['admin'] = array(
							'href' => 'inventory.php?id=give&did='.$entry->get_id(),
							'title' => _l('give object'),
							'content' => _l('give away')
						);
				} elseif($entry->get_owned() == 'givento') {
					$data[$counter]['admin'] = array(
							'href' => 'inventory.php?id=cancel&did='.$entry->get_id(),
							'title' => _l('cancel give object'),
							'content' => _l('cancel give')
						);
				} else {
					$data[$counter]['admin'] = array(
						'href' => 'inventory.php?id=take&did='.$entry->get_id(),
						'title' => _l('take object'),
						'content' => _l('take')
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
	
		// check permissions
		if($this->getUser()->hasPermission('inventory', $did)) {
			
			// pagecaption
			$this->getTpl()->assign('pagecaption',_l('give object'));
				
			// get inventory-object
			$inventory = new Inventory($did);
			
			// check owned
			if($inventory->get_owned() == 'taken') {
				
				// smarty-template
				$sG = new JudoIntranetSmarty();
				
				// prepare return
				$return = '';
				
				// get preset
				$preset = &$inventory->get_preset();
				// set view in preset
				$preset->setView($this);
				
				// get fields
				$fields = $preset->get_fields();
				
				// add headline
				$sG->assign('caption', _l('give object').': '.$inventory->get_name().' ('.$inventory->get_inventory_no().')');
				// add accessory info
				$sG->assign('inventoryinfo', _l('require to check given accessories'));
				
				// formular
				$form = new Zebra_Form(
						'inventoryGive',			// id/name
						'post',				// method
						'inventory.php?id=give&did='.$this->get('did')	// action
					);
				// set language
				$form->language('deutsch');
				// set docktype xhtml
				$form->doctype('xhtml');
				
				// prepare formIds
				$formIds = array();
				
				// add user-selection
				// get users
				$users = $this->getUser()->return_all_users(array($this->getUser()->get_userinfo('username')));
				foreach($users as $user) {
					
					// put id and name in options-array
					$usersOptions[$user->get_userinfo('username')] = $user->get_userinfo('name');
				}
				// remove admin
				unset($usersOptions['admin']);
				
				// add select
				$formIds['give_to'] = array('valueType' => 'int', 'type' => 'select',);
				$form->add(
						'label',		// type
						'labelGiveTo',	// id/name
						'give_to',			// for
						_l('<empty>').$inventory->get_name().' ('.$inventory->get_inventory_no().')'._l('give to').':'	// label text
					);
				$giveTo = $form->add(
						$formIds['give_to']['type'],	// type
						'give_to',		// id/name
						'',			// default
						array(		// attributes
							)
					);
				$giveTo->add_options($usersOptions);
				$giveTo->set_rule(
						array(
								'required' => array(
										'error', _l('required taking user')
									),
							)
					);
				
				// add fields to form
				foreach($fields as $field) {
					
					// set form
					$field->setForm($form);
					
					// generate zebraform
					$field->addFormElement(array(), false, $formIds);
				}
				
				// submit-button
				$form->add(
						'submit',		// type
						'buttonSubmit',	// id/name
						_l('save')	// value
					);
				
				// validate
				if($form->validate()) {
					
					// values
					$values = $this->getFormValues($formIds);
					
					// get user
					$givento_user = new User(false);
					$givento_user->change_user($values['give_to'],false);
					
					// write to db
					$insert_id = $this->movement_to_db('given',$inventory->get_id(),$givento_user->userid());
					// accessory to db
					$this->values_to_db($insert_id,$fields,$values);
					
					// headline
					$sG->assign('action', $inventory->get_name().' ('.$inventory->get_inventory_no().')'._l('given to').$givento_user->get_userinfo('name'));
					
					// accessory
					$sG->assign('accessoryaction', _l('accessories to be given'));
					
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
						$data[] = $field->valueToHtml();
					}
					$sG->assign('form', '');
					$sG->assign('data', $data);
				} else {
					$sG->assign('form', $form->render('', true));
				}
				
				// return
				return $sG->fetch('smarty.inventory.takegive.tpl');
			} else {
				throw new NotOwnedException($this, $did);
			}
		} else {
			throw new NotAuthorizedException($this);
		}
	}
	
	
	
	
	
	
	
	/**
	 * take creates the form to take an inventoryitem from somebody else
	 * 
	 * @param int $did entry-id for the inventoryitem
	 * @return string html-string with the form
	 */
	private function take($did) {
	
		// check permissions
		if($this->getUser()->hasPermission('inventory', $did)) {
			
			// pagecaption
			$this->getTpl()->assign('pagecaption',_l('take object'));
			
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
				$preset = &$inventory->get_preset();
				// set view in preset
				$preset->setView($this);
				
				// get fields
				$fields = $preset->get_fields();
				
				// add headline
				$sT->assign('caption', _l('take object').': '.$inventory->get_name().' ('.$inventory->get_inventory_no().')');
				
				// add take from
				$movements = Inventory::movement_last_row($db,$inventory->get_id(),'user_id',2);
				$user = new User(false);
				$user->change_user($movements[1],false,'id');
				$sT->assign('takefrom', _l('taken from').': '.$user->get_userinfo('name'));
				// add accessory info
				$sT->assign('accessoryinfo', _l('require to check taken accessories'));
				
				// formular
				$form = new Zebra_Form(
						'inventoryTake',	// id/name
						'post',				// method
						'inventory.php?id=take&did='.$this->get('did')	// action
					);
				// set language
				$form->language('deutsch');
				// set docktype xhtml
				$form->doctype('xhtml');
				
				// prepare formIds
				$formIds = array();
				
				// add fields to form
				foreach($fields as $field) {
					
					// set form
					$field->setForm($form);
					
					// check if given
					if($inventory->movement_last_accessories($field) === true || $field->get_type() == 'text') {
					
						// generate zebra_form
						$field->addFormElement(array(), false, $formIds);
					} else {
						
						// generate zebra_form
						$field->addFormElement(array('disabled' => 'disabled'), false, $formIds);
					}
				}
				
				// submit-button
				$form->add(
						'submit',		// type
						'buttonSubmit',	// id/name
						_l('save')	// value
					);
				
				// validate
				if($form->validate()) {
					
					// values
					$values = $this->getFormValues($formIds);
					
					// write to db
					$insert_id = $this->movement_to_db('taken',$inventory->get_id(),$this->getUser()->userid());
					// accessory to db
					$this->values_to_db($insert_id,$fields,$values);
					
					// headline
					$sT->assign('action', $inventory->get_name().' ('.$inventory->get_inventory_no().') '._l('taken'));
					
					// accessory
					$sT->assign('accessoryaction', _l('taken accessories'));
					
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
						$data[] = $field->valueToHtml();
					}
					$sT->assign('form', '');
					$sT->assign('data', $data);
				} else {
					$sT->assign('form', $form->render('', true));
				}
				
				// return
				return $sT->fetch('smarty.inventory.takegive.tpl');
			} else {
				throw new NotGivenToException($this, $did);
			}
		} else {
			throw new NotAuthorizedException($this);
		}
	}
	
	
	
	
	
	
	
	/**
	 * cancel cancels a movement on an inventory object
	 * 
	 * @param int $did entry-id for the inventoryitem
	 * @return string html-string with the form
	 */
	private function cancel($did) {
	
		// check permissions
		if($this->getUser()->hasPermission('inventory', $did)) {
			
			// pagecaption
			$this->getTpl()->assign('pagecaption',_l('cancel action'));
				
			// get inventory-object
			$inventory = new Inventory($did);
			
			// get preset
			$preset = &$inventory->get_preset();
			
			// get fields
			$fields = $preset->get_fields();
			
			// check owned
			if($inventory->get_owned() == 'givento') {
				
				// smarty-template
				$sC = new JudoIntranetSmarty();
				
				// prepare return
				$return = '';
				
				// form
				$form = new Zebra_Form(
						'formConfirm',			// id/name
						'post',				// method
						'inventory.php?id=cancel&did='.$did		// action
					);
				// set language
				$form->language('deutsch');
				// set docktype xhtml
				$form->doctype('xhtml');
				
				// add button
				$form->add(
						'submit',		// type
						'buttonSubmit',	// id/name
						_l('yes'),	// value
						array('title' => _l('cancels give'))
					);
				
				// smarty-link
				$link = array(
						'params' => 'class="submit"',
						'href' => 'inventory.php?id=my',
						'title' => _l('cancels the transaction'),
						'content' => _l('cancel')
					);
				$sC->assign('link', $link);
				$sC->assign('spanparams', 'id="cancel"');
				$sC->assign('message', _l('you really want to cancel give'));
				$sC->assign('form', $form->render('', true));
				
				// validate
				if($form->validate()) {
				
					// smarty
					$sC->assign('message', _l('successful cancel give'));
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
				throw new NotGivenException($this, $did);
			}
		} else {
			throw new NotAuthorizedException($this);
		}
	}
	
	
	
	
	
	
	
	/**
	 * details returns the movement details of a inventory-entry as html
	 * 
	 * @param int $did entry-id for inventory
	 * @return string html-string with the details of the inventory entry
	 */
	private function details($did) {
	
		// check permissions
		if($this->getUser()->hasPermission('inventory', $did)) {
			
			// pagecaption
			$this->getTpl()->assign('pagecaption',_l('details'));
				
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
			$sD->assign('accessorylist', _l('accessories'));
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
			throw new NotAuthorizedException($this);
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
		
		// check permissions
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
			
			$sM->assign('inventory', _l('transaction for').$inventory->get_name().' ('.$inventory->get_inventory_no().')');
			$sM->assign('date', _l('on').date('d.m.Y',strtotime($data[0]['date_time'])));
			$back = array(
					'href' => 'javascript:history.back(1)',
					'title' => _l('back'),
					'content' => _l('back to list')
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
					$field->readValue($data);
					$fields_out[] = $field->valueToHtml();
				}
				$sM->assign('data', $fields_out);
				$sM->assign('user', _l($movement['action'].' from').' '.$user->get_userinfo('name'));
			}
			
			// return
			return $sM->fetch('smarty.inventory.movement.tpl');
		} else {
			throw new NotAuthorizedException($this);
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
			
			// set value
			$field->value($value);
			
			// prepare sql-statement
			$sql = 'INSERT INTO value (id,table_name,table_id,field_id,value)
					VALUES (NULL,\'inventory_movement\','.$insert_id.','.$fieldid.',\''.$field->get_value().'\')';
			
			// execute
			$result = $db->query($sql);
			
			// get data
			if(!$result) {
			throw new MysqlErrorException($this, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
			}
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
					'title' => _l('show transactions'),
					'content' => date('d.m.Y',strtotime($date_time)),
					'name' => $name
				);
		}
		
		// return
		return $movements;
	}
}



?>
