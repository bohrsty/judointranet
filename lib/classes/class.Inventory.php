<?php


/**
 * class Inventory implements the representation of an inventory object
 */
class Inventory extends Object {
	
	/*
	 * class-variables
	 */
	private $id;
	private $inventory_no;
	private $name;
	private $serial_no;
	private $preset;
	private $valid;
	private $owned;
	
	/*
	 * getter/setter
	 */
	public function get_id(){
		return $this->id;
	}
	public function set_id($id) {
		$this->id = $id;
	}
	public function get_inventory_no(){
		return $this->inventory_no;
	}
	public function set_inventory_no($inventory_no) {
		$this->inventory_no = $inventory_no;
	}
	public function get_name(){
		return $this->name;
	}
	public function set_name($name) {
		$this->name = $name;
	}
	public function get_serial_no(){
		return $this->serial_no;
	}
	public function set_serial_no($serial_no) {
		$this->serial_no = $serial_no;
	}
	public function get_preset(){
		return $this->preset;
	}
	public function set_preset($preset) {
		$this->preset = $preset;
	}
	public function get_valid(){
		return $this->valid;
	}
	public function set_valid($valid) {
		$this->valid = $valid;
	}
	public function get_owned(){
		return $this->owned;
	}
	public function set_owned($owned) {
		$this->owned = $owned;
	}
	
	/*
	 * constructor/destructor
	 */
	public function __construct($id) {
		
		// parent constructor
		parent::__construct();
		
		// get field for given id
		$this->get_from_db($id);
	}
	
	/*
	 * methods
	 */
	/**
	 * get_from_db gets the inventory for the given inventoryid
	 * 
	 * @param int $id id of the fieldentry
	 * @return void
	 */
	private function get_from_db($id) {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql-statement
		$sql = "SELECT i.inventory_no,i.name,i.serial_no,i.preset_id,i.valid
				FROM inventory AS i
				WHERE i.id = $id";
		
		// execute
		$result = $db->query($sql);
		
		// fetch result
		list($inventory_no,$name,$serial_no,$preset_id,$valid) = $result->fetch_array(MYSQL_NUM);
		
		// set variables to object
		$this->set_id($id);
		$this->set_inventory_no($inventory_no);
		$this->set_name($name);
		$this->set_serial_no($serial_no);
		$this->set_preset(new Preset($preset_id,strtolower(get_class($this)),$id));
		$this->set_valid($valid);
		
		// get owned
		$owned_action = Inventory::movement_last_row($db,$id,'action');
		$owned_userid = Inventory::movement_last_row($db,$id,'user_id',2);
		if($owned_action === false) {
			$this->set_owned('');
		} elseif($owned_action[0] == 'given' && $owned_userid[1] == $_SESSION['user']->userid()) {
			$this->set_owned('givento');
		} else {
			$this->set_owned($owned_action[0]);
		}
		
		// close db
		$db->close();
	}
	
	
	
	
	
	
	/**
	 * return_inventory returns an array containing all inventories the
	 * user has rights to
	 * 
	 * @return array array containing the inventory_ids the user has rights to
	 */
	public static function return_inventories() {
		
		// get ids
		$return = Rights::get_authorized_entries('inventory');
		
		// return
		return $return;
	}
	
	
	
	
	
	
	/**
	 * return_my_inventory returns an array containing all inventories the
	 * user has rights to and movements are in progress
	 * 
	 * @return array array containing the inventory_ids the user has rights to and has movements on it
	 */
	public static function return_my_inventories() {
		
		// prepare return
		$return = array();
		
		// get ids
		$all = Rights::get_authorized_entries('inventory');
		
		// get db-object
		$db = Db::newDb();
		
		// check movements on each entry
		for($i=0;$i<count($all);$i++) {
			
			// get user_id and action
			$action = Inventory::movement_last_row($db,$all[$i],'action');
			$user_id = Inventory::movement_last_row($db,$all[$i],'user_id',3);
			
			// check action
			if($action[0] == 'taken') {
				
				// check user_id
				if($user_id[0] == $_SESSION['user']->userid() || ($user_id[1] == $_SESSION['user']->userid() && $user_id[0] != $user_id[2])) {
					$return[] = $all[$i];
				}
			} else {
				
				// check user_id
				if($user_id[0] == $_SESSION['user']->userid() || $user_id[1] == $_SESSION['user']->userid()) {
					$return[] = $all[$i];
				}
			}
		}
		
		// return
		return $return;
	}
	
	
	
	
	
	
	/**
	 * movement_last_row returns the value of requested $field from the
	 * inventory_movement-table
	 * 
	 * @param object $db database-object to query
	 * @param int $id inventory-id to query
	 * @param string $field database-field to be returned
	 * @return mixed the value of the requested $field or false if no result
	 */
	public static function movement_last_row($db,$id,$field,$rows = 1) {
				
		// prepare sql-statement
		$sql = "SELECT im.id,im.date_time,im.action,im.user_id
				FROM inventory_movement AS im
				WHERE im.inventory_id = $id
				ORDER BY im.date_time ASC";
		
		// execute
		$result = $db->query($sql);
		
		// return false if no result
		if($result->num_rows == 0) {
			return false;
		}
		
		// walk through rows
		$movement = array();
		for($i=0;$i<$rows;$i++) {
			
			// fetch result
			$element = $result->num_rows -1 -$i;
			$result->data_seek($element);
			
			$fetch = $result->fetch_array(MYSQL_ASSOC);
			$movement[] = $fetch[$field];
		}
		
		// clear result
		$result->close();
		
		// return
		return $movement;
	}
	
	
	
	
	
	
	/**
	 * movement_last_accessories tests if the $field has been given in last
	 * movement 
	 * 
	 * @param object $field field object to test
	 * @return bool true, if accessorie given, false otherwise
	 */
	public function movement_last_accessories($field) {
				
		// get db-object
		$db = Db::newDb();
		
		// get last movements
		$id = Inventory::movement_last_row($db,$this->get_id(),'id');
		
		// prepare sql-statement
		$sql = "SELECT v.value
				FROM value AS v
				WHERE table_name = 'inventory_movement'
				AND table_id = ".$id[0]."
				AND field_id = ".$field->get_id();
		
		// execute
		$result = $db->query($sql);
		
		// fetch result
		list($value) = $result->fetch_array(MYSQL_NUM);
		
		// return
		if($value == 1) {
			return true;
		} else {
			return false;
		}
	}
	
	
	
	
	
	
	/**
	 * movement_last_values returns an array containing the field values
	 * of the last movement 
	 * 
	 * @return array array contains tht field values of the last movement
	 */
	public function movement_last_values() {
				
		// get db-object
		$db = Db::newDb();
		
		// get last movements
		$id = Inventory::movement_last_row($db,$this->get_id(),'id',2);
		
		// prepare sql-statement
		$sql = "SELECT v.field_id,v.value
				FROM value AS v
				WHERE table_name = 'inventory_movement'
				AND table_id = ".$id[1];
		
		// execute
		$result = $db->query($sql);
		
		// fetch result
		$return = array();
		while(list($field_id,$value) = $result->fetch_array(MYSQL_NUM)) {
			$return['inventory-'.$field_id] = $value;
		}
		
		// return
		return $return;
	}
}



?>
