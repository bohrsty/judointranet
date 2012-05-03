<?php


/**
 * Page is the parent-class for the page-objects
 */
 class Page extends Object {
 	
 	/*
	 * class-variables
	 */
	private $id;
	private $rights;
	
	/*
	 * getter/setter
	 */
	public function get_id(){
		return $this->id;
	}
	public function set_id($id) {
		$this->id = $id;
	}
	public function get_rights(){
		return $this->rights;
	}
	public function set_rights($rights) {
		$this->rights = $rights;
	}
	
	
	/*
	 * constructor/destructor
	 */
	public function __construct() {
		
		// parent constructor
		parent::__construct();
	}
	
	/*
	 * methods
	 */
 	
 	
 }

?>
