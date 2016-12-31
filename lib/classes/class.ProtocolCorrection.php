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
 * class ProtocolCorrection implements the representation of a protocol correction object
 */
class ProtocolCorrection extends Object {

	/*
	 * class-variables
	 */
	private $protocol;
	private $modified;
	private $pid;
	private $uid;
	private $finished;
	
	/*
	 * getter/setter
	 */
	public function get_protocol(){
		return $this->protocol;
	}
	public function set_protocol($protocol) {
		$this->protocol = $protocol;
	}
	public function get_modified(){
		return $this->modified;
	}
	public function set_modified($modified) {
		$this->modified = $modified;
	}
	public function get_pid(){
		return $this->pid;
	}
	public function set_pid($pid) {
		$this->pid = $pid;
	}
	public function get_uid(){
		return $this->uid;
	}
	public function set_uid($uid) {
		$this->uid = $uid;
	}
	public function get_finished(){
		return $this->finished;
	}
	public function set_finished($finished) {
		$this->finished = $finished;
	}
	
	/*
	 * constructor/destructor
	 */
	public function __construct($arg,$uid=null) {
		
		// parent constructor
		parent::__construct();
		
		// check uid
		if(is_null($uid)) {
			$uid = $this->getUser()->get_id();
		}
		$this->set_uid($uid);
		
		// check if user has allready corrected
		if(ProtocolCorrection::hasCorrected($arg->get_id(),$uid) === true) {
			$this->getFromDb($arg->get_id());
		} else {
			$this->set_protocol($arg->get_protocol());
			$this->set_finished(0);
		}
		
	}
	
	/*
	 * methods
	 */
	/**
	 * getFromDb gets the protocol correction text for the given protocolid
	 * 
	 * @param int $id id of the protocolentry
	 * @return void
	 */
	private function getFromDb($id) {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql-statement
		$sql = "SELECT p.protocol,p.modified,p.finished
				FROM protocol_correction AS p
				WHERE p.pid = $id
				AND p.uid=".$this->get_uid();
		
		// execute
		$result = $db->query($sql);
		
		// fetch result
		list($protocol,$modified,$finished) = $result->fetch_array(MYSQLI_NUM);
		
		// set variables to object
		$this->set_pid($id);
		$this->set_protocol($protocol);
		$this->set_modified($modified);
		$this->set_finished($finished);
		
		// close db
		$db->close();
	}
	
	
	
	
	
	
	
	
	/**
	 * hasCorrected checks if the actual user has already corrected this protocol
	 * 
	 * @param int $id id of the protocol to be checked
	 * @param int $uid uid of the user to be checked
	 * @return bool true if user has checked, false otherwise
	 */
	public static function hasCorrected($id,$uid=null) {
		
		// get db-object
		$db = Db::newDb();
		
		// check uid
		if(is_null($uid)) {
			$uid = self::staticGetUser()->get_id();
		}
		
		// prepare sql-statement
		$sql = 'SELECT *
				FROM `protocol_correction`
				WHERE `pid` = #?
				AND `uid`=#?';
		
		// execute
		if(!Db::executeQuery(
			$sql,
			array($id, $uid,)
		)) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		} else {
			return Db::$num_rows == 1;
		}
	}
	
	
	
	
	
	
	
	
	/**
	 * update updates the fields of $this with the given values
	 * 
	 * @param array $correction array containig the changed values
	 * @return void
	 */
	public function update($correction) {
		
		// walk through array
		foreach($correction as $name => $value) {
			
			// check $name
			if($name == 'protocol') {
				$this->set_protocol($value);
			} elseif($name == 'modified') {
				$this->set_modified($value);
			} elseif($name == 'pid') {
				$this->set_pid($value);
			} elseif($name == 'finished') {
				$this->set_finished($value);
			}
		}
	}
	
	
	
	
	
	
	
	
	/**
	 * writeDb writes the actual values of $this to db
	 * 
	 * @param string $action indicates new correction or update existing
	 * @return void
	 */
	public function writeDb($action='new') {
		
		// get db-object
		$db = Db::newDb();
		
		// check action
		if($action == 'new') {
		
			// insert
			// prepare sql-statement
			$sql = "INSERT INTO protocol_correction
						(uid,
						pid,
						protocol,
						modified,
						finished,
						valid)
					VALUES (".$db->real_escape_string($this->get_uid()).","
						.$db->real_escape_string($this->get_pid()).",'"
						.$db->real_escape_string($this->get_protocol())."','"
						.$db->real_escape_string(date('Y-m-d H:i:s'))."',"
						.$db->real_escape_string($this->get_finished()).","
						.$db->real_escape_string(1).")";
			
			// execute;
			$db->query($sql);
		} elseif($action == 'update') {
			
			// update
			// prepare sql-statement
			$sql = "UPDATE protocol_correction
					SET
						protocol='".$db->real_escape_string($this->get_protocol())."',
						modified='".$db->real_escape_string(date('Y-m-d H:i:s'))."',
						finished=".$db->real_escape_string($this->get_finished()).",
						valid=".$db->real_escape_string(1)."
					WHERE uid = ".$db->real_escape_string($this->get_uid())."
					AND pid = ".$this->get_pid();
			
			// execute
			$db->query($sql);
		} else {
			throw new DbActionUnknownException($this, 'write_protocol: '.$action);
		}
		
		// close db
		$db->close();
	}
	
	
	
	
	
	
	
	
	/**
	 * listCorrections returns an array of all corrections of this protocol
	 * 
	 * @param int $id id of the protocol to be checked
	 * @return array list of all corrections of the given protocol id
	 */
	public static function listCorrections($pid) {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql-statement
		$sql = "SELECT *
				FROM protocol_correction
				WHERE pid = ".$pid;
		
		// execute
		$result = $db->query($sql);
		
		// get result
		$corrections = array();
		while($correction = $result->fetch_array(MYSQLI_ASSOC)) {
			$corrections[] = $correction;
		} 
		
		// close db
		$db->close();
		
		// return
		return $corrections;
	}

	
}