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
 * class ApiHandlerFile implements the data handling of file objectss with the public api
 */
class ApiHandlerFile extends ApiHandler {
	
	
	/*
	 * class-variables
	 */
	
	
	/*
	 * getter/setter
	 */
	
	/*
	 * constructor/destructor
	 */
	public function __construct($request) {
		
		// setup parent
		parent::__construct($request);
		
		// set request
		$this->setRequest($request);
	}
	
	/*
	 * methods
	 */
	/**
	 * getResult() handles the api requests for filesystem
	 * 
	 * @return array array containing the result 
	 */
	// inherit from parent
	
	
	/**
	 * handleAttach() returns the data for the the attached or attachable file objects for
	 * entry "tid" in $request
	 * 
	 * @return mixed array if request action is given, void otherwise
	 */
	public function handleAttach() {
		
		// get request
		$request = $this->getRequest();
		
		// check table and tid
		if(strpos($request['data']['tid'], '|') === false) {
			return array(
					'result' => 'ERROR',
					'message' => 'API call failed [table|tid not correct formatted]',
			);
		}
		
		// get table and tid
		list($table, $tid) = explode('|', $request['data']['tid']);
		
		// check tid given
		if(!isset($tid) || !is_numeric($tid)) {
			return array(
					'result' => 'ERROR',
					'message' => 'API call failed [tid not given or not numeric]',
				);
		}
		
		// check existance
		if(Page::exists($table, $tid) === false) {
			return array(
					'result' => 'ERROR',
					'message' => 'API call failed [entry does not exists]',
				);
		}
		
		// check permission
		if($this->getUser()->hasPermission($table, $tid, 'w') === false) {
			return array(
					'result' => 'ERROR',
					'message' => 'API call failed [not permitted to access this entry]',
				);
		}
		
		// get object
		if(class_exists(ucfirst($table), false) || @class_exists(ucfirst($table))) {
			
			$object = new $table($tid);
			
			// check valid
			if($object->getValid() == 0) {
				return array(
						'result' => 'ERROR',
						'message' => 'API call failed [entry does not exists]',
					);
			}
		} else {
			return array(
					'result' => 'ERROR',
					'message' => 'API call failed [table does not exists]',
				);
		}
				
		// get attached files
		$fileIds = File::attachedTo($table, $tid);
		
		// check action
		switch($request['data']['action']) {
			
			case 'listAttachable':
				
				// get attachable files
				$attachableFiles = File::readAllowedEntries();
				
				// prepare files
				$files = array();
				$section = array();
				foreach($attachableFiles as $attachableFile) {
					
					// get file information
					$cached = $attachableFile->getCached(false);
					$files[] = array(
							'id' => $attachableFile->getId(),
							'name' => $attachableFile->getName(),
							'filename' => $attachableFile->getFilename(),
							'filetype' => $attachableFile->getFileTypeAs('name'),
							'cached' => (is_null($cached) ? false : true),
							'table' => (!is_null($cached) ? (is_array($cached) ? $cached['table'] : 'uploaded') : 'uploaded'),
							'attached' => (in_array($attachableFile->getId(), $fileIds) ? true : false),
					);
					
					// get section
					$cachedSection = (!is_null($cached) ? (is_array($cached) ? $cached['table'] : 'uploaded') : 'uploaded');
					if(!in_array(array('name' => $cachedSection, 'translateName' => _l('table name '. $cachedSection),), $section)) {
						$section[] = array(
								'name' => $cachedSection,
								'translateName' => _l('table name '. $cachedSection),
							);
					}
				}
				
				// return data for form
				return array(
						'result' => 'OK',
						'data' => array(
								'message' => html_entity_decode(_l('Attach or detach files')),
								'values' => $files,
								'sections' => $section,
							),
					);
				break;
			
			case 'attach':
				
				// get table and tableId
				list($table, $tableId) = explode('|', $request['data']['tid']);
				
				// check attach or detach
				if($request['options']['attach'] == 'true') {
					
					// attach file
					$result = File::attachFile($table, $tableId, $request['options']['file']);
					if($result === true) {
						return array(
								'result' => 'OK',
								'message' => html_entity_decode(_l('Successfully attached file')),
							);
					}
				} elseif($request['options']['attach'] == 'false') {

					// detach file
					$result = File::detachFile($table, $tableId, $request['options']['file']);
					if($result === true) {
						return array(
								'result' => 'OK',
								'message' => html_entity_decode(_l('Successfully detached file')),
						);
					}
				}
				
				break;
			
			default:
				
				// only list names and filenames of attached files
				// get file objects
				$files = array();
				foreach($fileIds as $fileId) {
					$file = new File($fileId);
					$files[] = array(
							'id' => $file->getId(),
							'name' => $file->getName(),
							'filename' => $file->getFilename(),
						);
				}
				
				// return list
				return array(
						'result' => 'OK',
						'data' => array(
								'message' => _l('Attached files'),
								'values' => $files,
							),
					);
				break;
		}
	}
}

?>
