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
	protected function get_id(){
		return $this->id;
	}
	protected function set_id($id) {
		$this->id = $id;
	}
	protected function get_rights(){
		return $this->rights;
	}
	protected function set_rights($rights) {
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
	/**
	 * return_rights returns the value of $rights
	 * 
	 * @return object value of $rights
	 */
	public function return_rights() {
		return $this->get_rights();
	}
 	
 	
 }

?>
