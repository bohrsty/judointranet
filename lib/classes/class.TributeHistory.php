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
 * class Tribute implements the representation of a tribute object
 */
class TributeHistory extends Object {
	
	/*
	 * class-variables
	 */
	private $id;
	private $tributeId;
	private $type;
	private $historyUser;
	private $subject;
	private $content;
	private $valid;
	private $lastModified;
	private $modifiedBy;
	
	/*
	 * getter/setter
	 */
	public function getId(){
		return $this->id;
	}
	public function setId($id) {
		$this->id = $id;
	}
	public function getTributeId(){
		return $this->tributeId;
	}
	public function setTributeId($tributeId) {
		$this->tributeId = $tributeId;
	}
	public function getType(){
		return $this->type;
	}
	public function setType($type) {
		$this->type = $type;
	}
	public function gethistoryUser(){
		return $this->historyUser;
	}
	public function sethistoryUser($historyUser) {
		$this->historyUser = $historyUser;
	}
	public function getSubject(){
		return $this->subject;
	}
	public function setSubject($subject) {
		$this->subject = $subject;
	}
	public function getContent(){
		return $this->content;
	}
	public function setContent($content) {
		$this->content = $content;
	}
	public function getValid(){
		return $this->valid;
	}
	public function setValid($valid) {
		$this->valid = $valid;
	}
	public function getLastModified(){
		return $this->lastModified;
	}
	public function setLastModified($lastModified) {
		$this->lastModified = $lastModified;
	}
	
	/*
	 * constructor/destructor
	 */
	public function __construct($id = 0) {
		
		// parent constructor
		parent::__construct();
		
		// check $id
		if(is_array($id)) {
			
			// set values from array
			$this->setId(0);
			$this->setTributeId($id['tributeId']);
			$this->setType(self::getHistoryTypeById($id['type']));
			// check if user id given
			if(isset($id['userId'])) {
				$user = new User(false);
				$user->change_user($id['userId'], false, 'id');
			} else {
				$user = $this->getUser();
			}
			$this->setHistoryUser($user);
			$this->setSubject($id['subject']);
			$this->setContent($id['content']);
			$this->setValid($id['valid']);
			if(isset($id['lastModified'])) {
				$this->setLastModified(date('Y-m-d H:i:s', strtotime($id['lastModified'])));
			} else {
				$this->setLastModified(date('Y-m-d H:i:s'));
			}
		} else {
			
			// check if $id is 0
			if($id == 0) {
				$this->setId(0);
			} else {
				// get from database
				$this->getFromDb($id);
			}
		}
	}
	
	/*
	 * methods
	 */
	/**
	 * getFromDb($id) retrieves the informations for the given $id from database
	 * 
	 * @param int $id the id of the entry to be retrieved from db
	 * @return void
	 */
	private function getFromDb($id) {
		
		// get result values from db
		$result = Db::ArrayValue('
			SELECT `tribute_id`, `history_type_id`, `user_id`, `subject`, `content`, `valid`, `last_modified`
			FROM `tribute_history`
			WHERE `id`=#?
		',
		MYSQL_ASSOC,
		array($id,));
		if($result === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// set variables
		if(isset($result[0])) {

			$this->setId($id);
			$this->setTributeId($result[0]['tribute_id']);
			$this->setType(self::getHistoryTypeById($result[0]['history_type_id']));
			$user = new User(false);
			$user->change_user($result[0]['user_id'], false, 'id');
			$this->setHistoryUser($user);
			$this->setSubject($result[0]['subject']);
			$this->setContent($result[0]['content']);
			$this->setValid($result[0]['valid']);
			$this->setLastModified($result[0]['last_modified']);
		}
	}
	
	
	/**
	 * update sets the values from given array to the tribute object
	 * 
	 * @param array $tribute array containing the new values
	 * @return void
	 */
	public function update($tributeHistory) {
		
		// walk through array
		foreach($tributeHistory as $name => $value) {
			
			// check $name
			if($name == 'tributeId') {
				$this->setTributeId($value);
			} elseif($name == 'type') {
				$this->setType(self::getHistoryTypeById($value));
			} elseif($name == 'userId') {
				
				$user = new User(false);
				$user->change_user($value, false, 'id');
				$this->setHistoryUser($user);
			} elseif($name == 'subject') {
				$this->setSubject($value);
			} elseif($name == 'content') {
				$this->setContent($value);
			} elseif($name == 'valid') {
				$this->setValid($value);
			}
		}
	}
	
	
	/**
	 * writeDb writes the tribute history data to db
	 * 
	 * @return int $this->id or id of new insert data
	 */
	public function writeDb() {
		
		// insert into database
		if(!Db::executeQuery('
			INSERT INTO `tribute_history` (`id`,`tribute_id`,`history_type_id`,`user_id`,`subject`,`content`,`valid`,`last_modified`)
			VALUES (#?, #?, #?, #?, \'#?\', \'#?\', #?, \'#?\')
			ON DUPLICATE KEY UPDATE
				`tribute_id`=#?,
				`history_type_id`=#?,
				`user_id`=#?,
				`subject`=\'#?\',
				`content`=\'#?\',
				`valid`=#?,
				`last_modified`=CURRENT_TIMESTAMP
			',
			array(// insert
				($this->getId() == 0 ? 'NULL' : $this->getId()),
				$this->getTributeId(),
				$this->getType()['id'],
				$this->getHistoryUser()->get_id(),
				$this->getSubject(),
				$this->getContent(),
				$this->getValid(),
				$this->getLastModified(),
				// update
				$this->getTributeId(),
				$this->getType()['id'],
				$this->getHistoryUser()->get_id(),
				$this->getSubject(),
				$this->getContent(),
				$this->getValid(),))) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// set and return (new) id
		$newId = (isset(Db::$insertId) ? Db::$insertId : $this->getId());
		$this->setId($newId);
		return $newId;
	}
	
	
	/**
	 * deleteEntry() sets $this->valid to 0
	 */
	public function deleteEntry() {
		
		// set valid
		$this->setValid(0);
		$this->writeDb();
	}
	
	
	/**
	 * delete($id) deletes the tribute with the given $id from database
	 * 
	 * @param string $id id of the tribute entry
	 * @return void
	 */
	public static function delete($id) {
		
		// delete result
		if(!Db::executeQuery('
			DELETE FROM `tribute_history` WHERE `id`=#?
				',
		array($id,))) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}		
	}
	
	
	/**
	 * getHistoryTypeById($id) gets the tribute history given by $id from database and returns
	 * it as array id => ; name => if $id exists, else returns the default type
	 * 
	 * @param int $id id of the history type to get
	 * @return array tribute history id and name as array
	 */
	public static function getHistoryTypeById($id) {
		
		// check system entry
		if($id == -1) {
			return array('id' => -1, 'name' => _l('System entry'));
		}
		
		// check if id exists
		if(Page::exists('tribute_history_type', $id)) {
			$sqlId = $id;
		} else {
			$sqlId = 1;
		}
		
		// execute query
		$result = Db::ArrayValue('
				SELECT `id`, `name`
				FROM `tribute_history_type`
				WHERE `id`=#?
			',
				MYSQL_ASSOC,
				array($sqlId,));
		if($result === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		} else {
			return $result[0];
		}
		
	}
	
	
	/**
	 * getAllHistoryTypes() gets all tribute history types from database and returns them
	 * as array id => ; name =>
	 * 
	 * @return array tribute history type ids and names as array
	 */
	public static function getAllHistoryTypes() {
		
		// execute query
		$result = Db::ArrayValue('
				SELECT `id`, `name`
				FROM `tribute_history_type`
				WHERE `valid`=TRUE
			',
				MYSQL_ASSOC,
				array());
		if($result === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		} else {
			if(count($result) > 0) {
				return $result;
			} else {
				return array();
			}
		}
		
	}
	
	
	/**
	 * factoryInsert($data) creates an object from the given $data and inserts it in the database
	 * if any step failes it returns an error message, the object otherwise
	 * 
	 * @param array $data array used to construct the object
	 * @return array array containing a result key and a data key with error message or object
	 */
	public static function factoryInsert($data) {
		
		// check array
		if($data['tributeId'] === false ||
			$data['type'] === false ||
			$data['subject'] === false ||
			$data['content'] === false ||
			$data['tributeId'] == '' ||
			$data['type'] == '' ||
			$data['subject'] == '' ||
			$data['content'] == '') {
			
			return array(
					'result' => 'ERROR',
					'data' => _l('ERROR').': '._l('missing data'),
				);
		}
		
		// create object
		$tributeHistory = new TributeHistory($data);
		
		// write object
		$tributeHistory->writeDb();
		
		// return
		return array(
				'result' => 'OK',
				'data' => $tributeHistory,
			);
	}
	
	
	/**
	 * deleteAll($tributeId, $permanent) deletes all history entries for given $tributeId,
	 * $permanent=false only sets them invalid, true deletes them from db
	 * 
	 * @param int $tributeId id of the tribute, that history entries should be deleted
	 * @param bool $permanent indicates if the entries were deleted from database or only deactivated
	 */
	public static function deleteAll($tributeId, $permanent=false) {
		
		// check permanent
		if($permanent === false) {
			
			// prepare sql
			$sql = '
				UPDATE `tribute_history`
				SET `valid`=FALSE
				WHERE `tribute_id`=#?
			';
		} else {
			
			// prepare sql
			$sql = '
				DELETE FROM `tribute_history` WHERE `tribute_id`=#?
			';
		}
		
		if(!Db::executeQuery(
			$sql,
		array($tributeId,))) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
	}
	
	
	/**
	 * getFirstEntryFor($tributeId) returns the lastModified timestamp for the first entry
	 * of $tributeId
	 * 
	 * @param int $tributeId the id of the tribute to get first entry for
	 * @return string the timestamp of the first entry
	 */
	public static function getFirstEntryFor($tributeId) {
		
		$first = Db::singleValue('
				SELECT MIN(`last_modified`)
				FROM `tribute_history`
				WHERE `tribute_id`=#?
					AND `valid`=TRUE
			',
				array($tributeId,)
		);
		if($first === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		} else {
			return $first;
		}
	}
}