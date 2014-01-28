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
 * class File implements the representation of a file object
 */
class File extends Page {
	
	/*
	 * class-variables
	 */
	private $name;
	private $fileType;
	private $filename;
	private $content;
	private $cached;
	private $valid;
	
	
	/*
	 * getter/setter
	 */
	public function getName(){
		return $this->name;
	}
	public function setName($name) {
		$this->name = $name;
	}
	public function getFileType(){
		return $this->fileType;
	}
	public function setFileType($fileType) {
		$this->fileType = $fileType;
	}
	public function getFilename(){
		return $this->filename;
	}
	public function setFilename($filename) {
		$this->filename = $filename;
	}
	public function getContent(){
		return $this->content;
	}
	public function setContent($content) {
		$this->content = $content;
	}
	public function getCached($asString=true){
		
		// check if is null
		if(is_null($this->cached)) {
			return $this->cached;
		} else {
			
			// check if containing "|"
			if(strpos($this->cached, '|') !== false && $asString === false) {
				
				list($table, $tableId) = explode('|', $this->cached);
				return array(
						'table' => $table,
						'tableId' => $tableId,
					);
			} else {
				return $this->cached;
			}
		}	
	}
	public function setCached($cachedOrTable, $tableId=null) {
		
		// check if cached null
		if(is_null($cachedOrTable)) {
			$this->cached = null;
		} else {
			
			// check if combined info or two params given
			if(is_null($tableId)) {
				$this->cached = $cachedOrTable;
			} else {
				$this->cached = $cachedOrTable.'|'.$tableId;
			}
		}
	}
	public function getValid(){
		return $this->valid;
	}
	public function setValid($valid) {
		$this->valid = $valid;
	}
	
	
	/*
	 * constructor/destructor
	 */
	public function __construct($id = 0) {
		
		// parent constructor
		parent::__construct();
		
		// get data from db
		if($id != 0) {
			$this->getFromDb($id);
		}
	}
	
	
	/*
	 * methods
	 */
	/**
	 * isCached() returns true if file has been cached, false if not
	 * 
	 * @return bool true if file has been cached, false otherwise
	 */
	public function isCached() {
		 return !is_null($this->getCached());
	}
	
	
	/**
	 * factory($data) generates a new File object with the given $data
	 * 
	 * @param array $data associative array containing the required data for the new file
	 * @return object the resulting File object
	 */
	public static function factory($data) {
		
		// create empty object
		$file = new File();
		
		// set name
		$file->setName($data['name']);
		
		// set filename
		$file->setFilename($data['filename']);
		
		// set filetype
		$file->setFileType(self::mimetypeToId($data['mimetype']));
		
		// set content
		$file->setContent($data['content']);
		
		// set cached
		$file->setCached($data['cached']);
		
		// set valid
		$file->setValid($data['valid']);
		
		// return
		return $file;
	}
	
	
	/**
	 * mimetypeToId($mimetype) returns the corresponding filetype id from database
	 * 
	 * @param string $mimetype the text representation of the mimetype (returned by mime_content_type())
	 * @return int the id of the filetype entry in the database, or false
	 */
	public static function mimetypeToId($mimetype) {
		
		// get db object
		$db = Db::newDb();
		
		// prepare sql
		$sql = 'SELECT `id`
				FROM `file_type`
				WHERE `mimetype`=\''.$db->real_escape_string($mimetype).'\'';
		
		// execute statement
		$result = $db->query($sql);
		
		// get data
		$id = false;
		if($result) {
			list($id) = $result->fetch_array(MYSQL_NUM);
		} else {
			$errno = self::getError()->error_raised('MysqlError', $db->error, $sql);
			self::getError()->handle_error($errno);
		}
		
		return $id;
	}
	
	
	/**
	 * getFromDb($id) retrieves the informations for the given $id from database
	 * 
	 * @param int $id the id of the entry to be retrieved from db
	 * @return void
	 */
	private function getFromDb($id) {
		
		// get db object
		$db = Db::newDb();
		
		// prepare sql
		$sql = 'SELECT `name`,`file_type`,`filename`,`content`,`cached`,`valid`
				FROM `file`
				WHERE `id`='.$db->real_escape_string($id);
		
		// execute statement
		$result = $db->query($sql);
		
		// get data
		$name = $fileType = $filename = $content = $uploaded = $valid = null;
		if($result) {
			list($name, $fileType, $filename, $content, $cached, $valid) = $result->fetch_array(MYSQL_NUM);
		} else {
			$errno = $this->getError()->error_raised('MysqlError', $db->error, $sql);
			$this->getError()->handle_error($errno);
		}
		
		// set data
		$this->setId($id);
		$this->setName($name);
		$this->setFileType($fileType);
		$this->setFilename($filename);
		$this->setContent($content);
		if(strtolower($cached) == 'null') {
			$this->setCached(null);
		} else {
			$this->setCached($cached);
		}
		$this->setValid($valid);
	}
	
	
	/**
	 * writeDb() writes the actual object data into database, or updates them, if
	 * they exists
	 * 
	 * @return void
	 */
	public function writeDb() {
		
		// get db object
		$db = Db::newDb();
		
		// prepare sql
		$sql = 'INSERT INTO `file` (`id`,`name`,`file_type`,`filename`,`content`,`cached`,`valid`,`modified_by`)
				VALUES ('.($this->getId() == 0 ? 'NULL' : $db->real_escape_string($this->getId())).',
						\''.$db->real_escape_string($this->getName()).'\',
						'.$db->real_escape_string($this->getFileType()).',
						\''.$db->real_escape_string($this->getFilename()).'\',
						\''.$db->real_escape_string($this->getContent()).'\',
						'.(is_null($this->getCached()) ? 'NULL' : '\''.$db->real_escape_string($this->getCached()).'\'').',
						'.$db->real_escape_string($this->getValid()).',
						'.$db->real_escape_string($this->getUser()->get_id()).')
				ON DUPLICATE KEY UPDATE
						`name`=\''.$db->real_escape_string($this->getName()).'\',
						`file_type`='.$db->real_escape_string($this->getFileType()).',
						`filename`=\''.$db->real_escape_string($this->getFilename()).'\',
						`content`=\''.$db->real_escape_string($this->getContent()).'\',
						`cached`='.(is_null($this->getCached()) ? 'NULL' : '\''.$db->real_escape_string($this->getCached()).'\'').',
						`valid`='.$db->real_escape_string($this->getValid()).',
						`modified_by`='.$db->real_escape_string($this->getUser()->get_id());
		
		// execute statement
		$result = $db->query($sql);
		
		// get data
		if(!$result) {
			$errno = $this->getError()->error_raised('MysqlError', $db->error, $sql);
			$this->getError()->handle_error($errno);
		} else {
		
			// get insert_id
			if($this->getId() == 0) {
				$this->setId($db->insert_id);
			}
		}
	}
	
	
	/**
	 * exists($id) checks if a file with the given $id exists
	 * 
	 * @param int $id id to check if file exists
	 * @return bool true if file exists, false otherwise
	 */
	public static function exists($id) {
		
		// get db object
		$db = Db::newDb();
		
		// prepare sql
		$sql = 'SELECT *
				FROM `file`
				WHERE `id`='.$db->real_escape_string($id);
		
		// execute statement
		$result = $db->query($sql);
		
		// get data
		if($result) {
			return $result->num_rows == 1;
		} else {
			$errno = self::getError()->error_raised('MysqlError', $db->error, $sql);
			$this->getError()->handle_error($errno);
		}
	}
	
	
	/**
	 * update($data) updates $this with the given $data
	 * 
	 * @param array $data array containing the information to be updated
	 * @return void
	 */
	public function update($data) {
		
		// check if $data is array
		if(is_array($data)) {
			
			// set data
			if(isset($data['name']) && !is_null($data['name']))     {$this->setName($data['name']);}
			if(isset($data['mimetype']) && !is_null($data['mimetype'])) {$this->setFileType($this->mimetypeToId($data['mimetype']));}
			if(isset($data['filename']) && !is_null($data['filename'])) {$this->setFilename($data['filename']);}
			if(isset($data['content']) && !is_null($data['content']))  {$this->setContent($data['content']);}
			if(isset($data['cached']) && !is_null($data['cached'])) {$this->setCached($data['cached']);}
			if(isset($data['valid']) && !is_null($data['valid']))    {$this->setValid($data['valid']);}
		}
	}
	
	
	/**
	 * getFileTypeAs($field) returns the corresponding database $field of the filetype
	 * 
	 * @param string $field name of the database column to return filetype as
	 * @return $string the value of the filetype given by $field
	 */
	public function getFileTypeAs($field) {
		
		// get db object
		$db = Db::newDb();
		
		// prepare sql
		// check $field
		if($field == 'name') {
			
			$sql = 'SELECT `mimetype`
					FROM `file_type`
					WHERE `id`='.$db->real_escape_string($this->getFileType());
		} else {
			
			$sql = 'SELECT `'.$db->real_escape_string($field).'`
					FROM `file_type`
					WHERE `id`='.$db->real_escape_string($this->getFileType());
		}
		
		// execute statement
		$result = $db->query($sql);
		
		// get data
		$value = $mimetype = null;
		if($result) {
			
			// check $field
			if($field == 'name') {
				list($mimetype) = $result->fetch_array(MYSQL_NUM);
			} else {
				list($value) = $result->fetch_array(MYSQL_NUM);
			}
		} else {
			$errno = $this->getError()->error_raised('MysqlError', $db->error, $sql);
			$this->getError()->handle_error($errno);
		}
		
		// check translation
		if($field == 'name') {
			$value = parent::lang('class.File#getFileTypeAs#name#'.str_replace('/', '_', $mimetype));
		}
		
		// return
		return $value;
	}
	
	
	/**
	 * details returns the file entry details as array, divided in caption and value
	 * 
	 * @return array file entry details as array
	 */
	public function details() {
		
		// prepare data
		$data = array(
					'caption' => array(
							0 => parent::lang('class.File#details#data#name'),
							1 => parent::lang('class.File#details#data#filetype'),
							2 => parent::lang('class.File#details#data#filename'),
						),
					'value' => array(
							0 => $this->getName(),
							1 => $this->getFileTypeAs('name'),
							2 => $this->getFilename(),
						),
		);
		
		// return
		return $data;
	}
	
	
	/**
	 * allowedFileTypes() returns the list of allowed file types (extenstions) from config
	 * 
	 * @return string comma separated list of allowed file extensions
	 */
	public static function allowedFileTypes() {
		
		// get file type ids from config
		$fileTypeIds = self::getGc()->get_config('file.allowedFileTypes');
		
		// get db object
		$db = Db::newDb();
		
		// prepare sql
		$sql = 'SELECT `extension`
				FROM `file_type`
				WHERE `id` IN ('.$db->real_escape_string($fileTypeIds).')';
		
		// execute statement
		$result = $db->query($sql);
		
		// get data
		$extensions = '';
		if($result) {
			list($ext) = $result->fetch_array(MYSQL_NUM);
			$extensions .= $ext.',';
		} else {
			$errno = $this->getError()->error_raised('MysqlError', $db->error, $sql);
			$this->getError()->handle_error($errno);
		}
		// remove last comma
		$extensions = substr($extensions, 0, -1);
		
		// return
		return $extensions;
	}
	
	
	/**
	 * idFromCache($cache) returns the id of a file from database for the given $cache string
	 * 
	 * @param string $cache cache string "<table>|<tableId>"
	 * @return mixed the id of the file or false if not cached
	 */
	public static function idFromCache($cache) {
		
		// get db object
		$db = Db::newDb();
		
		// prepare sql
		$sql = 'SELECT `id`
				FROM `file`
				WHERE `cached`=\''.$db->real_escape_string($cache).'\'';
		
		// execute statement
		$result = $db->query($sql);
		
		// get data
		$id = false;
		if($result) {
			if($result->num_rows == 1) {
				list($id) = $result->fetch_array(MYSQL_NUM);
			}
		} else {
			$errno = self::getError()->error_raised('MysqlError', $db->error, $sql);
			self::getError()->handle_error($errno);
		}
		
		// return
		return $id;
	}
	
	
	/**
	 * cacheAge($table, $tableId) returns the age of a file = days since last modification
	 * 
	 * @param string $table table of the cached object
	 * @param int $tableId id of the file in $table
	 * @return mixed the age of the file in days or false if not cached
	 */
	public static function cacheAge($table, $tableId) {
		
		// get db object
		$db = Db::newDb();
		
		// prepare sql
		$sql = 'SELECT `last_modified`
				FROM `file`
				WHERE `cached`=\''.$db->real_escape_string($table.'|'.$tableId).'\'';
		
		// execute statement
		$result = $db->query($sql);
		
		// get data
		$lastModified = null;
		if($result) {
			if($result->num_rows == 1) {
				list($lastModified) = $result->fetch_array(MYSQL_NUM);
			}
		} else {
			$errno = self::getError()->error_raised('MysqlError', $db->error, $sql);
			self::getError()->handle_error($errno);
		}
		
		$now = time();
		$lastModifiedTimeStamp = (is_null($lastModified) ? 0 : strtotime($lastModified));
		$seconds = $now - $lastModifiedTimeStamp;
		$days = round($seconds / 60 / 60 / 24, 0);
				
		// return
		return ($days < 0 ? self::getGc()->get_config('file.maxCacheAge') + 1 : $days);
	}
	
	
	/**
	 * delete($fid) deletes the file with the given $fid from database
	 * 
	 * @param int $fid the file id of the file to be deleted from database
	 * @return void
	 */
	public static function delete($fid) {
		
		// check if file exists
		if(self::exists($fid)) {
			
			// get db object
			$db = Db::newDb();
			
			// prepare sql
			$sql = 'DELETE FROM `file`
					WHERE `id`='.$db->real_escape_string($fid);
			
			// execute statement
			$result = $db->query($sql);
			
			// get data
			if(!$result) {
				$errno = self::getError()->error_raised('MysqlError', $db->error, $sql);
				self::getError()->handle_error($errno);
			}
			
			// remove all attachments
			self::deleteAllAttachments($fid);
		}
	}
	
	
	/**
	 * attachedTo($table, $tableId) returns an array containing the ids of all files attached
	 * to $table->$tableId
	 * 
	 * @param string $table the table name of the entry the files are attached to
	 * @param int $tableId the id of the entry the files are attached to
	 * @return array array containing the ids of all files attached to $table->$tableId
	 */
	public static function attachedTo($table, $tableId) {
		
		// get db object
		$db = Db::newDb();
		
		// prepare sql
		$sql = 'SELECT file_id FROM `files_attached`
				WHERE `table_name`=\''.$db->real_escape_string($table).'\'
					AND `table_id`='.$db->real_escape_string($tableId);
		
		// execute statement
		$result = $db->query($sql);
		
		// get data
		$fileIds = array();
		if($result) {
			while(list($fileId) = $result->fetch_array(MYSQL_NUM)) {
				$fileIds[] = $fileId;
			}
		} else {
			$errno = self::getError()->error_raised('MysqlError', $db->error, $sql);
			self::getError()->handle_error($errno);
		}
		
		// return
		return $fileIds;
	}
	
	
	/**
	 * attachFiles($table, $tableId, $files) attaches the given $files to the
	 * given $table->$tableId
	 * 
	 * @param string $table the table name of the entry the file is to attach to
	 * @param int $tableId the id of the entry the file is to attach to
	 * @param array $files array of ids of the files to attach
	 * @return void
	 */
	public static function attachFiles($table, $tableId, $files) {
		
		// check if there is something to attach
		if(count($files) > 0) {
		
			// get db object
			$db = Db::newDb();
			
			// prepare sql
			$sql = 'INSERT INTO `files_attached` (table_name, table_id, file_id) VALUES';
			foreach($files as $id) {
				
				 $sql .= '(\''.$db->real_escape_string($table).'\',
					\''.$db->real_escape_string($tableId).'\',
					\''.$db->real_escape_string($id).'\'),';
			}
			$sql =  substr($sql, 0, -1);
			
			// execute statement
			$result = $db->query($sql);
			
			// get data
			if(!$result) {
				$errno = self::getError()->error_raised('MysqlError', $db->error, $sql);
				self::getError()->handle_error($errno);
			}
		}
	}
	
	
	/**
	 * deleteAttachedFiles($table, $tableId) deletes attached files for the given $table->$tableId
	 * 
	 * @param string $table the table name of the entry the files are deleted
	 * @param int $tableId the id of the entry the files are deleted
	 * @return void
	 */
	public static function deleteAttachedFiles($table, $tableId) {
		
		// get db object
		$db = Db::newDb();
		
		// prepare sql
		$sql = 'DELETE FROM `files_attached`
				WHERE table_name=\''.$db->real_escape_string($table).'\'
				AND table_id=\''.$db->real_escape_string($tableId).'\'';
		
		// execute statement
		$result = $db->query($sql);
		
		// get data
		if(!$result) {
			$errno = self::getError()->error_raised('MysqlError', $db->error, $sql);
			self::getError()->handle_error($errno);
		}
	}
	
	
	/**
	 * deleteAllAttachments($fileId) deletes all attachments for the given $fileId
	 * 
	 * @param int $fileId the id of the file that attachments to be deleted
	 * @return void
	 */
	public static function deleteAllAttachments($fileId) {
		
		// get db object
		$db = Db::newDb();
		
		// prepare sql
		$sql = 'DELETE FROM `files_attached`
				WHERE file_id=\''.$db->real_escape_string($fileId).'\'';
		
		// execute statement
		$result = $db->query($sql);
		
		// get data
		if(!$result) {
			$errno = self::getError()->error_raised('MysqlError', $db->error, $sql);
			self::getError()->handle_error($errno);
		}
	}
}

?>
