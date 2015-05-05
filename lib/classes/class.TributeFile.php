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
 * class TributeFile implements the representation of a tribute file object
 */
class TributeFile extends Object {
	
	/*
	 * class-variables
	 */
	private $filePath;
	private $id;
	private $tributeId;
	private $type;
	private $filename;
	private $name;
	private $valid;
	private $lastModified;
	private $modifiedBy;
	private $error;
	
	/*
	 * getter/setter
	 */
	public function getFilePath(){
		return $this->filePath;
	}
	public function setFilePath($filePath) {
		$this->filePath = $filePath;
	}
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
	public function getFilename(){
		return $this->filename;
	}
	public function setFilename($filename) {
		$this->filename = $filename;
	}
	public function getName(){
		return $this->name;
	}
	public function setName($name) {
		$this->name = $name;
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
	public function getError(){
		return $this->error;
	}
	public function setError($error) {
		$this->error = $error;
	}
	
	/*
	 * constructor/destructor
	 */
	public function __construct($id = 0) {
		
		// parent constructor
		parent::__construct();
		
		// set file path
		$this->setFilePath(JIPATH.'/files/tribute/');
		
		// check $id
		if(is_array($id)) {
			
			// set values from array
			$this->setId(0);
			$this->setTributeId($id['tributeId']);
			$this->setType(self::getFiletypeById($id['type']));
			$this->setFilename($id['filename']);
			$this->setName($id['name']);
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
			SELECT `tribute_id`, `filetype_id`, `filename`, `name`, `valid`, `last_modified`
			FROM `tribute_file`
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
			$this->setType(self::getFiletypeById($result[0]['filetype_id']));
			$this->setFilename($result[0]['filename']);
			$this->setName($result[0]['name']);
			$this->setValid($result[0]['valid']);
			$this->setLastModified($result[0]['last_modified']);
		}
	}
	
	
	/**
	 * update sets the values from given array to the tribute file object
	 * 
	 * @param array $tributeFile array containing the new values
	 * @return void
	 */
	public function update($tributeFile) {
		
		// walk through array
		foreach($tributeFile as $name => $value) {
			
			// check $name
			if($name == 'tributeId') {
				$this->setTributeId($value);
			} elseif($name == 'type') {
				$this->setType(self::getFiletypeById($value));
			} elseif($name == 'filename') {
				$this->setFilename($value);
			} elseif($name == 'name') {
				$this->setName($value);
			} elseif($name == 'valid') {
				$this->setValid($value);
			}
		}
	}
	
	
	/**
	 * writeDb writes the tribute file data to db
	 * 
	 * @return int $this->id or id of new insert data
	 */
	public function writeDb() {
		
		// insert into database
		if(!Db::executeQuery('
			INSERT INTO `tribute_file` (`id`,`tribute_id`,`filetype_id`,`filename`,`name`,`valid`,`last_modified`)
			VALUES (#?, #?, #?, \'#?\', \'#?\', #?, CURRENT_TIMESTAMP)
			ON DUPLICATE KEY UPDATE
				`tribute_id`=#?,
				`filetype_id`=#?,
				`filename`=\'#?\',
				`name`=\'#?\',
				`valid`=#?,
				`last_modified`=CURRENT_TIMESTAMP
			',
			array(// insert
				($this->getId() == 0 ? 'NULL' : $this->getId()),
				$this->getTributeId(),
				$this->getType()['id'],
				$this->getFilename(),
				$this->getName(),
				$this->getValid(),
				// update
				$this->getTributeId(),
				$this->getType()['id'],
				$this->getFilename(),
				$this->getName(),
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
			DELETE FROM `tribute_file` WHERE `id`=#?
				',
		array($id,))) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}		
	}
	
	
	/**
	 * getFiletypeById($id) gets the tribute file type given by $id from database and returns
	 * it as array id => ; name => if $id exists, else returns the default type
	 * 
	 * @param int $id id of the history type to get
	 * @return array tribute history id and name as array
	 */
	public static function getFiletypeById($id) {
		
		// check system entry
		if($id == -1) {
			return array('id' => -1, 'name' => _l('File'));
		}
		
		// check if id exists
		if(Page::exists('tribute_file_type', $id)) {
			$sqlId = $id;
		} else {
			$sqlId = 1;
		}
		
		// execute query
		$result = Db::ArrayValue('
				SELECT `id`, `name`
				FROM `tribute_file_type`
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
	 * getAllHistoryTypes() gets all tribute file types from database and returns them
	 * as array id => ; name =>
	 * 
	 * @return array tribute history type ids and names as array
	 */
	public static function getAllFileTypes() {
		
		// execute query
		$result = Db::ArrayValue('
				SELECT `id`, `name`
				FROM `tribute_file_type`
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
	 * deleteAll($tributeId, $permanent) deletes all file entries for given $tributeId,
	 * $permanent=false only sets them invalid, true deletes them from db
	 * 
	 * @param int $tributeId id of the tribute, that file entries should be deleted
	 * @param bool $permanent indicates if the entries were deleted from database or only deactivated
	 */
	public static function deleteAll($tributeId, $permanent=false) {
		
		// check permanent
		if($permanent === false) {
			
			// prepare sql
			$sql = '
				UPDATE `tribute_file`
				SET `valid`=FALSE
				WHERE `tribute_id`=#?
			';
		} else {
			
			// prepare sql
			$sql = '
				DELETE FROM `tribute_file` WHERE `tribute_id`=#?
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
	 * factoryFile() creates an object from the given data in $_FILES and $_POST and inserts
	 * it in the database. It returns the object
	 * 
	 * @return object the TributeFile object
	 */
	public static function factoryFile() {
		
		// create object
		$tributeFile = new TributeFile(0);
		// prepare data
		$timestamp = time();
		$tempName = $_FILES['file']['tmp_name'];
		$origName = $tributeFile->replace_umlaute($_FILES['file']['name']);
		$md5file = md5_file($tempName);
		$filename = $tributeFile->getFilePath().$md5file.'_'.$timestamp;
		// check post data
		if(
			$tributeFile->post('tid') === false ||
			$tributeFile->post('fileType') === false ||
			$tributeFile->post('tid') == '' ||
			$tributeFile->post('fileType') == ''
		) {
			
			// set error
			$tributeFile->setError(
				array(
						'result' => 'ERROR',
						'message' => _l('ERROR').': '._l('missing data'),
					)
			);
			return $tributeFile;
		} else {
			
			$tid = $tributeFile->post('tid');
			$fileType = $tributeFile->post('fileType');
		}
		$data = array(
				'tributeId' => $tid,
				'filename' => $md5file.'_'.$timestamp.'.pdf',
				'name' => $origName,
				'type' => $fileType,
				'valid' => 1,
			);
		// update object
		$tributeFile->update($data);
		
		// check and move file
		$moved = false;
		if(is_uploaded_file($tempName) === true) {
			$moved = @move_uploaded_file($tempName, $filename.'.pdf');
		} else {
			
			// set error
			$tributeFile->setError(
				array(
						'result' => 'ERROR',
						'message' => _l('ERROR').': '._l('no uploaded file to process'),
					)
			);
			return $tributeFile;
		}
		// check moving uploaded file
		if($moved === false) {
			
			// set error
			$tributeFile->setError(
				array(
						'result' => 'ERROR',
						'message' => _l('ERROR').': '._l('file processing failed'),
					)
			);
			return $tributeFile;
		}
		
		// generate thumbnail using Gmagick
		$thumb = new Gmagick($filename.'.pdf[0]');
		$thumb->setImageColorspace(255);
		$thumb->setimageformat('png');
		$thumb->thumbnailimage(200, 200, true);
		$thumb->writeimage($tributeFile->getFilePath().'thumbs/'.$md5file.'_'.$timestamp.'.pdf.png');
		$thumb->clear();
		$thumb->destroy();
		
		// write object
		$newId = $tributeFile->writeDb();
		
		// set error
		$tributeFile->setError(
			array(
					'result' => 'OK',
					'message' => _l('File saved successfully'),
					'data' => array(
							'id' => $newId,
						),
				)
		);
		
		// return
		return $tributeFile;
	}
}