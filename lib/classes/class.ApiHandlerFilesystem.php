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
 * class ApiHandlerFilesystem implements the data handling of files with the public api
 */
class ApiHandlerFilesystem extends ApiHandler {
	
	
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
	public function getResult() {
		
		// check "id" parameter of $request
		if($this->getRequest()['data']['id'] != '') {
			$apiMethod = 'handle'.ucfirst($this->getRequest()['data']['id']);
			if(is_callable(array($this, $apiMethod))) {
				
				// call method and return result
				return call_user_func(array($this, $apiMethod));
			} else {
				if(!is_null($request['data']['action'])) {
					return array(
							'result' => 'ERROR',
							'message' => 'API call failed [id not found \''.get_class($this).'::'.$apiMethod.'\']',
						);
				} else {
					header("HTTP/1.1 400 Bad Request");
					exit;
				}
			}
		} else {
			if(!is_null($request['data']['action'])) {
				return array(
						'result' => 'ERROR',
						'message' => 'API call failed [id not given]',
					);
			} else {
				header("HTTP/1.1 404 Not Found");
				exit;
			}
		}
	}
	
	
	/**
	 * handleTribute_file() returns the data for the tribute file entry "tid" in $request
	 * 
	 * @return mixed array if request action is given, void otherwise
	 */
	public function handleTribute_file() {
		
		// get request
		$request = $this->getRequest();
		
		// determine action
		$action = !is_null($request['data']['action']);
		
		// check tid given 404
		if(!isset($request['data']['tid']) || !is_numeric($request['data']['tid'])) {
			if($action) {
				return array(
						'result' => 'ERROR',
						'message' => 'API call failed [tid not given or not numeric]',
					);
			} else {
				header("HTTP/1.1 404 Not Found");
				exit;
			}
		}
		
		// check existance 404
		if(Page::exists('tribute_file', $request['data']['tid']) === false) {
			if($action) {
				return array(
						'result' => 'ERROR',
						'message' => 'API call failed [entry does not exists]',
					);
			} else {
				header("HTTP/1.1 404 Not Found");
				exit;
			}
		}
		
		// get object
		$tributeFile = new TributeFile($request['data']['tid']);
		
		// check permission 403
		if($this->getUser()->hasPermission('tribute', $tributeFile->getTributeId()) === false) {
			if($action) {
				return array(
						'result' => 'ERROR',
						'message' => 'API call failed [not permitted to access this entry]',
					);
			} else {
				header("HTTP/1.1 403 Forbidden");
				exit;
			}
		}
		
		// check valid 404
		if($tributeFile->getValid() == 0) {
			if($action) {
				return array(
						'result' => 'ERROR',
						'message' => 'API call failed [entry does not exists]',
					);
			} else {
				header("HTTP/1.1 404 Not Found");
				exit;
			}
		}
		
		// check action
		switch($request['data']['action']) {
			
			case 'delete':
				// check confirmation
				if($this->post('confirmed') !== false) {
					
					// delete file
					$deletion = $tributeFile->deleteEntry();
					// check deletion
					if($deletion === true) {
						return array(
								'result' => 'OK',
								'message' => 'Deletion successful',
							);
					} else {
						return array(
								'result' => 'ERROR',
								'message' => _l('Deletion failed give "TributeFile::#?id" to the #?systemadmin to check deletion.', array('id' => $tributeFile->getId(), 'systemadmin' => $this->getGc()->get_config('global.systemcontactName'))),
							);
					}
				} else {
					return array(
							'result' => 'ERROR',
							'message' => _l('Deletion not confirmed!'),
						);
				}
				break;
			
			default:
				// check thumbnail
				if(isset($request['options']['thumb']) && $request['options']['thumb'] == 1) {
					
					// get thumbnail
					$thumb = new Gmagick($tributeFile->getFilePath().'thumbs/'.$tributeFile->getFilename().'.png');
					// send header
					header('Cache-Control: public, must-revalidate, max-age=0');
					header('Pragma: public');
					header('Expires: Sat, 31 Dec 2011 05:00:00 GMT');
					header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
					header('Content-type: image/png');
					// output file content
					echo $thumb;
					exit;
				} else {
					
					// get file content
					$fh = fopen($tributeFile->getFilePath().'/'.$tributeFile->getFilename(), 'r');
					$file = fread($fh, filesize($tributeFile->getFilePath().'/'.$tributeFile->getFilename()));
					fclose($fh);
					// send header
					header('Cache-Control: public, must-revalidate, max-age=0');
					header('Pragma: public');
					header('Expires: Sat, 31 Dec 2011 05:00:00 GMT');
					header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
					header('Content-Type: application/force-download');
					header('Content-Type: application/octet-stream', false);
					header('Content-Type: application/download', false);
					header('Content-Type: application/pdf', false);
					header('Content-Disposition: attachment; filename="'.$tributeFile->getName().'";');
					header('Content-Transfer-Encoding: binary');
					header('Content-Length: '.strlen($file));
					// echo file content
					echo $file;
					exit;
				break;
			}
		}
	}
}

?>
