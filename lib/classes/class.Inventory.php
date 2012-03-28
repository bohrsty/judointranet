<?php


/**
 * class Preset implements a preset (combination of fields)
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
	private function get_id(){
		return $this->id;
	}
	private function set_id($id) {
		$this->id = $id;
	}
	private function get_inventory_no(){
		return $this->inventory_no;
	}
	private function set_inventory_no($inventory_no) {
		$this->inventory_no = $inventory_no;
	}
	private function get_name(){
		return $this->name;
	}
	private function set_name($name) {
		$this->name = $name;
	}
	private function get_serial_no(){
		return $this->serial_no;
	}
	private function set_serial_no($serial_no) {
		$this->serial_no = $serial_no;
	}
	private function get_preset(){
		return $this->preset;
	}
	private function set_preset($preset) {
		$this->preset = $preset;
	}
	private function get_valid(){
		return $this->valid;
	}
	private function set_valid($valid) {
		$this->valid = $valid;
	}
	private function get_owned(){
		return $this->owned;
	}
	private function set_owned($owned) {
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
		if($owned_action !== false) {
			$this->set_owned($owned_action);
		} else {
			$this->set_owned('');
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
	 * return_id returns the value of $id
	 * 
	 * @return int the id of this object
	 */
	public function return_id() {
		
		// return
		return $this->get_id();
	}
	
	
	
	
	
	
	/**
	 * return_preset returns the value of $preset
	 * 
	 * @return object the preset-object attached to this inventory
	 */
	public function return_preset() {
		
		// return
		return $this->get_preset();
	}
	
	
	
	
	
	
	/**
	 * return_name returns the value of $name
	 * 
	 * @return string the name of this entry
	 */
	public function return_name() {
		
		// return
		return $this->get_name();
	}
	
	
	
	
	
	
	/**
	 * return_valid returns the value of $valid
	 * 
	 * @return int the info, if this inventory is activ
	 */
	public function return_valid() {
		
		// return
		return $this->get_valid();
	}
	
	
	
	
	
	
	/**
	 * return_inventory_no returns the value of $inventory_no
	 * 
	 * @return string the inventory-no of this entry
	 */
	public function return_inventory_no() {
		
		// return
		return $this->get_inventory_no();
	}
	
	
	
	
	
	
	/**
	 * return_owned returns the value of $owned
	 * 
	 * @return int the id of the user this inventory is owned by
	 */
	public function return_owned() {
		
		// return
		return $this->get_owned();
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
			
			// get user_id
			$user_id = Inventory::movement_last_row($db,$all[$i],'user_id');
				
			// check user_id
			if($user_id == $_SESSION['user']->userid()) {
				$return[] = $all[$i];
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
	public static function movement_last_row($db,$id,$field) {
				
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
		
		// fetch result
		$last_element = $result->num_rows -1;
		$result->data_seek($last_element);
		
		$movement = $result->fetch_array(MYSQL_ASSOC);
		
		// clear result
		$result->close();
		
		// return
		return $movement[$field];
	}
}



?>