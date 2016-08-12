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
 * class File implements the control of the files page
 */
class FileView extends PageView {
	
	/*
	 * class-variables
	 */
	
	/*
	 * getter/setter
	 */
	
	/*
	 * constructor/destructor
	 */
	public function __construct() {
		
		// setup parent
		parent::__construct();
	}
	
	/**
	 * init chooses the functionality by using $_GET['id']
	 * 
	 * @param bool $show uses smarty display method to show, if true, smarty fetch method if false
	 * @return void
	 */
	public function init($show = true) {
		
		// set pagename
		$this->getTpl()->assign('pagename',_l('files'));
		
		// init helpmessages
		$this->initHelp();
		
		// switch $_GET['id'] if set
		if($this->get('id') !== false) {
			
			// check permissions
			$naviId = Navi::idFromFileParam(self::requestedFilename(), $this->get('id'));
			if($this->getUser()->hasPermission('navi', $naviId)) {
				
				switch($this->get('id')) {
					
					case 'listall':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('files: listall')));
						$fileViewListall = new FileViewListall();
						$this->getTpl()->assign('main', $fileViewListall->show());
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', false);
						$this->getTpl()->assign('tinymce', false);
					break;
					
					case 'upload':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('files: upload')));
						$this->getTpl()->assign('main', $this->upload());
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
						$this->getTpl()->assign('tinymce', false);
					break;
					
					case 'details':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('files: details')));
						$this->getTpl()->assign('main', $this->details());
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', false);
						$this->getTpl()->assign('tinymce', false);
					break;
					
					case 'edit':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('files: edit')));
						$this->getTpl()->assign('main', $this->edit());
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
						$this->getTpl()->assign('tinymce', false);
					break;
					
					case 'download':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('files: download')));
						$this->getTpl()->assign('main', $this->download());
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', false);
						$this->getTpl()->assign('tinymce', false);
					break;
					
					case 'delete':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('files: delete')));
						$this->getTpl()->assign('main', $this->delete(null));
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
						$this->getTpl()->assign('tinymce', false);
					break;
					
					case 'cached':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('files: download')));
						$this->getTpl()->assign('main', $this->cached());
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', false);
						$this->getTpl()->assign('tinymce', false);
					break;
					
					case 'attach':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('files: attach')));
						$this->getTpl()->assign('main', $this->attach());
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
						$this->getTpl()->assign('tinymce', false);
					break;
					
					default:
						throw new GetUnknownIdException($this, $this->get('id'));
					break;
				}
			} else {
				
				// error not authorized
				throw new NotAuthorizedException($this);
			}
		} else {
			
			// id not set
			// smarty-title
			$this->getTpl()->assign('title', $this->title(_l('files'))); 
			// smarty-main
			$this->getTpl()->assign('main', $this->defaultContent());
			// smarty-jquery
			$this->getTpl()->assign('jquery', true);
			// smarty-hierselect
			$this->getTpl()->assign('zebraform', false);
			// smarty-tiny_mce
			$this->getTpl()->assign('tinymce', false);
		}
		
		// global smarty
		if($show === true) {
			$this->showPage('smarty.main.tpl', $show);
		} else {
			return $this->showPage('smarty.main.tpl', $show);
		}
	}
	
	
	
	
	
	
	
	/**
	 * defaultContent() returns the html content to be displayed on page without
	 * parameters or functions
	 * 
	 * @return string html content as default content
	 */
	protected function defaultContent() {
		
		// smarty-template
		$sD = new JudoIntranetSmarty();
		
		// smarty
		$sD->assign('caption', _l('files'));
		$text[] = array(
				'caption' => '',
				'text' => ''
			);
		$sD->assign('text', $text);
		
		// return
		return $sD->fetch('smarty.default.content.tpl');
	}
	
	
	
	
	
	
	
	/**
	 * readAllEntries() get all file entries from db for that the actual
	 * user has sufficient rights. returns an array with file objects
	 * 
	 * @return array all entries as file objects
	 */
	private function readAllEntries() {
		
		// return file objects
		return File::readAllowedEntries();
	}
	
	
	
	
	
	
	
	/**
	 * details() returns the details of a file entry as html-string
	 * 
	 * @return string html-string with the details of the file entry
	 */
	private function details() {
		
		// get $fid
		$fid = $this->get('fid');
		
		// pagecaption
		$this->getTpl()->assign('pagecaption',_l('details'));
		
		// get file-object
		$file = new File($fid);
		
		$cached = $file->getCached(false);
		
		// check rights
		if($this->getUser()->hasPermission('file', $fid)
			|| (!is_null($cached)
				&& $this->getUser()->hasPermission($cached['table'], $cached['tableId']))) {
			
			// smarty-template
			$sFD = new JudoIntranetSmarty();
			
			// smarty
			$sFD->assign('data', $file->details());
			
			// prepare links
			// back to listall
			$links[] = array(
					'href' => 'file.php?id=listall',
					'title' => _l('back'),
					'name' => _l('back')
				);
			// download
			$links[] = array(
					'href' => 'file.php?id=download&fid='.$file->getId(),
					'title' => _l('download'),
					'name' => _l('download')
				);
			$sFD->assign('links',$links);
			
			return $sFD->fetch('smarty.file.details.tpl');
		} else {
			throw new NotAuthorizedException($this);
		}
	}
	
	
	/**
	 * download() handles the data to download a file
	 * 
	 * @return string error message in case of error
	 */
	private function download($fid=null) {
		
		// get $fid
		if(is_null($fid)) {
			$fid = $this->get('fid');
		}
		
		// check permissions
		if($this->getUser()->hasPermission('file', $fid) || $this->get('id') == 'cached') {
		
			// get file object
			$file = new File($fid);
			
			// get content
			$fileContent = $file->getContent();
			
			// check if header allready sent
			if(!headers_sent()) {
				
				// prepare header
				header('Cache-Control: public, must-revalidate, max-age=0');
				header('Pragma: public');
				header('Expires: Sat, 31 Dec 2011 05:00:00 GMT');
				header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
				header('Content-Type: application/force-download');
				header('Content-Type: application/octet-stream', false);
				header('Content-Type: application/download', false);
				header('Content-Type: '.$file->getFileTypeAs('mimetype'), false);
				header('Content-Disposition: attachment; filename="'.$file->getFilename().'";');
				header('Content-Transfer-Encoding: binary');
				header('Content-Length: '.strlen($fileContent));
				
				// send data
				echo $fileContent;
				
				// exit script
				exit;
			} else {
				throw new HeaderSentException($this);
			}
		} else {
			throw new NotAuthorizedException($this);
		}
	}
	
	
	/**
	 * delete() handles the deletion of the file
	 * 
	 * @param array $config config for the deletion page (translation names, links, etc.) (compatible to parent delaration)
	 * @return string html of the deletion page
	 */
	protected function delete($config) {
		
		// get $fid
		$fid = $this->get('fid');
		
		// pagecaption
		$this->getTpl()->assign('pagecaption',_l('delete file'));
		
		// check permissions
		if($this->getUser()->hasPermission('file', $fid, 'w')) {
			
			// smarty-templates
			$sConfirmation = new JudoIntranetSmarty();
			
			// form
			$form = new Zebra_Form(
				'formConfirm',			// id/name
				'post',				// method
				'file.php?id=delete&fid='.$fid		// action
			);
			// set language
			$form->language('deutsch');
			// set docktype xhtml
			$form->doctype('xhtml');
			
			// add button
			$form->add(
				'submit',		// type
				'buttonSubmit',	// id/name
				_l('yes'),	// value
				array('title' => _l('delete file'))
			);
			
			// smarty-link
			$link = array(
							'params' => 'class="submit"',
							'href' => 'file.php?id=listall',
							'title' => _l('cancels deletion'),
							'content' => _l('cancel')
						);
			$sConfirmation->assign('link', $link);
			$sConfirmation->assign('spanparams', 'id="cancel"');
			$sConfirmation->assign('message', _l('you really want to delete this file?').'&nbsp;'.$this->helpButton(HELP_MSG_DELETE));
			$sConfirmation->assign('form', $form->render('', true));
			
			// validate
			if($form->validate()) {
			
				// get file object
				$file = new File($fid);
				
				// disable entry
				$file->update(array('valid' => 0));
				
				// smarty
				$sConfirmation->assign('message', _l('successful deleted file.'));
				$sConfirmation->assign('form', '');
				
				// write entry
				$file->writeDb();
				
				// set js redirection
				$this->jsRedirectTimeout('file.php?id=listall');
			}
			
			// smarty return
			return $sConfirmation->fetch('smarty.confirmation.tpl');
		} else {
			throw new NotAuthorizedException($this);
		}
	}
	
	
	/**
	 * upload() creates the "upload"-form and handle its response
	 * 
	 * @return string html-string with the "upload"-form
	 */
	private function upload() {
		
		// pagecaption
		$this->getTpl()->assign('pagecaption',_l('upload file').'&nbsp;'.$this->helpButton(HELP_MSG_FILEUPLOAD));
		
		// smarty-templates
		$sD = new JudoIntranetSmarty();
		
		// prepare return
		$return = '';
		
		// prepare form
		$form = new Zebra_Form(
				'fileUpload',		// id/name
				'post',					// method
				'file.php?id=upload'	// action
			);
		// set language
		$form->language('deutsch');
		// set docktype xhtml
		$form->doctype('xhtml');
		
		// elements
		// name
		$formIds['name'] = array('valueType' => 'string', 'type' => 'text',);
		$form->add(
				'label',		// type
				'labelName',	// id/name
				'name',			// for
				_l('name'),	// label text
				array('inside' => true,)	// label inside
			);
		$name = $form->add(
						$formIds['name']['type'],		// type
						'name'		// id/name
			);
		$form->add(
				'note',			// type
				'noteName',	// id/name
				'name',		// for
				_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDNAME)	// note text
			);
		
		// add rules
		$name->set_rule(
				array(
						'regexp' => array(
								$this->getGc()->get_config('textarea.regexp.zebra'),
								'error',
								_l('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
							),
						'required' => array(
								'error',
								_l('required name'),
							),
					)
			);
		
		// file
		$formIds['formContent'] = array('valueType' => 'file', 'type' => 'file',);
		$form->add(
				'label',		// type
				'labelContent',	// id/name
				'formContent',			// for
				_l('select file').':'	// label text
			);
		$formContent = $form->add(
						$formIds['formContent']['type'],		// type
						'formContent'		// id/name
			);
		$form->add(
				'note',			// type
				'noteFormContent',	// id/name
				'formContent',		// for
				_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDFILE)	// note text
			);
		
		// add rules
		$allowedFileTypes = File::allowedFileTypes();
		$formContent->set_rule(
				array(
						'filetype' => array(
								$allowedFileTypes,
								'error',
								_l('only the following file extensions are allowed!').' ['.$allowedFileTypes.']',
							),
						'upload' => array(
								JIPATH.'/'.$this->getGc()->get_config('global.temp'),
								ZEBRA_FORM_UPLOAD_RANDOM_NAMES,
								'error',
								_l('could not upload file!'),
							),
					)
			);
		// set paths to mimes.json and process.php
		$form->assets_path(JIPATH.'/vendor/stefangabos/zebra_form/', '/');
		
		// checkbox public
		$formIds['public'] = array('valueType' => 'int', 'type' => 'checkbox', 'default' => 1);
		$form->add(
				'label',		// type
				'labelPublic',	// id/name
				'public',		// for
				_l('public access')	// label text
			);
		$public = $form->add(
				$formIds['public']['type'],		// type
				'public',						// id/name
				'1',							// value
				null							// default
			);
		$form->add(
				'note',			// type
				'notePublic',	// id/name
				'public',		// for
				_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDISPUBLIC)	// note text
			);
		
		// permissions
		$result = $this->zebraAddPermissions($form, 'file');
		$form = $result['form'];
		$permissionConfig['ids'] = $result['formIds'];
		$permissionConfig['iconRead'] = $result['iconRead'];
		$permissionConfig['iconEdit'] = $result['iconEdit'];
		
		// submit-button
		$form->add(
				'submit',		// type
				'buttonSubmit',	// id/name
				_l('upload')	// value
			);
		
		// validate
		if($form->validate()) {
			
			// get form data
			$data = $this->getFormValues($formIds, $form->file_upload);
			// get form permissions
			$permissions = $this->getFormPermissions($permissionConfig['ids']);
			
			// add public access
			if($data['public'] == 1) {
				$permissions[0]['group'] = Group::fakePublic();
				$permissions[0]['value'] = 'r';
			}
			
			// collect file data
			$fileData = array(
					'name' => $data['name'],
					'filename' => $data['formContent']['filename'],
					'mimetype' => $data['formContent']['mimetype'],
					'content' => $data['formContent']['fileContent'],
					'cached' => null,
					'valid' => true,
				);
						
			$file = File::factory($fileData);
				
			// write to db
			$file->writeDb();
			
			// write permissions
			$file->dbDeletePermission();
			$file->dbWritePermission($permissions);
			
			// set js redirection
			$this->jsRedirectTimeout('file.php?id=listall');
			
			// smarty
			$sCD = new JudoIntranetSmarty();
			$sCD->assign('data', $file->details());
			return $sCD->fetch('smarty.file.details.tpl');
		} else {
			return $form->render(__DIR__.'/../zebraTemplate.php', true, array($formIds, 'smarty.zebra.permissions.tpl', $permissionConfig,));
		}
	}
	
	
	/**
	 * edit() creates the "edit"-form and handle its response
	 * 
	 * @return string html-string with the "edit"-form
	 */
	private function edit() {
		
		// get id and file object
		$fid = $this->get('fid');
		$file = new File($fid);
		
		// pagecaption
		$this->getTpl()->assign('pagecaption',_l('edit file'));
		
		// check permissions
		if($this->getUser()->hasPermission('file', $fid, 'w')) {
		
			// smarty-templates
			$sD = new JudoIntranetSmarty();
			
			// prepare return
			$return = '';
			
			// prepare form
			$form = new Zebra_Form(
					'fileEdit',		// id/name
					'post',					// method
					'file.php?id=edit&fid='.$fid	// action
				);
			// set language
			$form->language('deutsch');
			// set docktype xhtml
			$form->doctype('xhtml');
			
			// elements
			// location
			$formIds['name'] = array('valueType' => 'string', 'type' => 'text',);
			$form->add(
					'label',		// type
					'labelName',	// id/name
					'name',			// for
					_l('name').':'
				);
			$name = $form->add(
							$formIds['name']['type'],	// type
							'name',		// id/name
							$file->getName()	// default
				);
			$form->add(
					'note',			// type
					'noteName',	// id/name
					'name',		// for
					_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDNAME)	// note text
				);
			
			// add rules
			$name->set_rule(
					array(
							'regexp' => array(
									$this->getGc()->get_config('textarea.regexp.zebra'),
									'error',
									_l('allowed chars').' ['.$this->getGc()->get_config('textarea.desc').']',
								),
							'required' => array(
									'error',
									_l('required name'),
								),
						)
				);
			
			// file
			$formIds['formContent'] = array('valueType' => 'file', 'type' => 'file',);
			$form->add(
					'label',		// type
					'labelContent',	// id/name
					'formContent',			// for
					_l('content').':'	// label text
				);
			$formContent = $form->add(
							$formIds['formContent']['type'],		// type
							'formContent'		// id/name
				);
			$form->add(
					'note',			// type
					'noteFormContent',	// id/name
					'formContent',		// for
					_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDFILE)	// note text
				);
			
			// add rules
			$allowedFileTypes = File::allowedFileTypes();
			$formContent->set_rule(
					array(
							'filetype' => array(
									$allowedFileTypes,
									'error',
									_l('only the following file extensions are allowed!').' ['.$allowedFileTypes.']',
								),
							'upload' => array(
									JIPATH.'/'.$this->getGc()->get_config('global.temp'),
									ZEBRA_FORM_UPLOAD_RANDOM_NAMES,
									'error',
									_l('could not upload file!'),
								),
						)
				);
			// set paths to mimes.json and process.php
			$form->assets_path(JIPATH.'/vendor/stefangabos/zebra_form/', '/');
			
			// checkbox public
			$formIds['public'] = array('valueType' => 'int', 'type' => 'checkbox', 'default' => 1);
			$form->add(
					'label',		// type
					'labelPublic',	// id/name
					'public',		// for
					_l('public access')	// label text
				);
			$public = $form->add(
					$formIds['public']['type'],		// type
					'public',						// id/name
					'1',							// value
					($file->isPermittedFor(0) ? array('checked' => 'checked') : null)							// default
				);
			$form->add(
					'note',			// type
					'notePublic',	// id/name
					'public',		// for
					_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDISPUBLIC)	// note text
				);
			
			// permissions
			$result = $this->zebraAddPermissions($form, 'file');
			$form = $result['form'];
			$permissionConfig['ids'] = $result['formIds'];
			$permissionConfig['iconRead'] = $result['iconRead'];
			$permissionConfig['iconEdit'] = $result['iconEdit'];
			
			// submit-button
			$form->add(
					'submit',		// type
					'buttonSubmit',	// id/name
					_l('save')	// value
				);
			
			// validate
			if($form->validate()) {
				
				// get form data
				$data = $this->getFormValues($formIds, $form->file_upload);
				// get form permissions
				$permissions = $this->getFormPermissions($permissionConfig['ids']);
				
				// add public access
				if($data['public'] == 1) {
					$permissions[0]['group'] = Group::fakePublic();
					$permissions[0]['value'] = 'r';
				}
				
				// collect file data
				$fileUpdate = array(
						'name' => $data['name'],
						'filename' => (isset($data['formContent']) ? $data['formContent']['filename'] : null),
						'content' => (isset($data['formContent']) ? $data['formContent']['fileContent'] : null),
					);
							
				$file->update($fileUpdate);
					
				// write to db
				$file->writeDb();
				
				// write permissions
				$file->dbDeletePermission();
				$file->dbWritePermission($permissions);
				
				// set js redirection
				$this->jsRedirectTimeout('file.php?id=listall');
				
				// smarty
				$sCD = new JudoIntranetSmarty();
				$sCD->assign('data', $file->details());
				return $sCD->fetch('smarty.file.details.tpl');
			} else {
				return $form->render(__DIR__.'/../zebraTemplate.php', true, array($formIds, 'smarty.zebra.permissions.tpl', $permissionConfig,));
			}
		} else {
			throw new NotAuthorizedException($this);
		}
	}
	
	
	/**
	 * cached() handles the cached file entries for all Page subclasses that produces .pdf files
	 * 
	 * @return string HTML code of the page
	 */
	private function cached() {
		
		// get table, table id and file id
		$table = $this->get('table');
		$tid = $this->get('tid');
		
		// create object
		$className = ucfirst($table);
		$object = new $className($tid);
		// get additional checks
		$additionalChecks = $object->additionalChecksPassed();
		
		// check draft field (if calendar, ...)
		$draftValue = 0;
		if($table == 'calendar') {
			$draftValue = Calendar::getDraftValue($object->get_preset_id(), $tid);
		}
		
		// check additional checks
		for($i=0; $i<count($additionalChecks)-2; $i++) {
			if($additionalChecks[$i]['result'] === false) {
				// error
				if(class_exists($additionalChecks[$i]['error'].'Exception', true)) {
					$exception = $additionalChecks[$i]['error'].'Exception';
					throw new $exception($this, $additionalChecks[$i]['errorEntry']);
				} else {
					throw new CustomException($this, $additionalChecks[$i]['error'].': '.$additionalChecks[$i]['errorEntry']);
				}
			}
		}
		
		// check permissions
		$permissionTable = $table;
		$permissionTid = $tid;
		// if result check permissions against calendar table
		if($table == 'result') {
			$permissionTable = 'calendar';
			$permissionTid = $object->getCalendar()->get_id();
		}
		
		if($this->getUser()->hasPermission($permissionTable, $permissionTid) && $additionalChecks['permissions'] || $this->getUser()->isAdmin()) {
			
			// check draft field
			if($draftValue == 0 || ($draftValue == 1 && $this->getUser()->get_loggedin())) {
				
				// get file id
				$fid = File::idFromCache($table.'|'.$tid);
				
				// check cache age
				if(File::cacheAge($table, $tid) > $this->getGc()->get_config('file.maxCacheAge')) {
						
					// (re)create cached file
					$fid = $object->createCachedFile($fid);
				}
				
				// return download
				return $this->download($fid);
			} else {
				throw new FileNotExistsException($this, $tid);
			}
		} else {
			throw new NotAuthorizedException($this);
		}
	}
	
	
	/**
	 * prepareFiles($files) prepares the file objects as array to use with smarty template
	 * 
	 * @param array $files array containing file objects to prepare as list for smarty
	 * @return array prepared array to use with smarty template
	 */
	private function prepareFiles($files) {
		
		$counter = 0;
		// smarty
		$sList = array();
		foreach($files as $entry) {
			
			// check if valid
			if($entry->getValid() == 1) {
				
				// smarty
				$shortFilename = $entry->getFilename();
				if(strlen($entry->getFilename()) > 35) {
					$shortFilename = substr($entry->getFilename(), 0, 6).'[...]'.substr($entry->getFilename(), -19);
				}
				$sList[$counter] = array(
						'name' => array(
								'href' => 'file.php?id=details&fid='.$entry->getId(),
								'title' => _l('details'),
								'name' => $entry->getName(),
							),
						'filetype' => $entry->getFileTypeAs('name'),
						'filename' => array(
								'name' => $shortFilename,
								'title' => $entry->getFilename(),
							),
					);
				// show
				$sList[$counter]['show'][] = array(
							'href' => 'file.php?id=download&fid='.$entry->getId(),
							'title' => $entry->getName()._l(' download'),
							'src' => 'img/file_download.png',
							'alt' => '\''.$entry->getName().'\''._l('filename'),
						);
					
				// add admin
				
				// if user is loggedin add admin-links
				if($this->getUser()->get_loggedin() === true) {
					
					// edit
					$sList[$counter]['admin'][] = array(
							'href' => 'file.php?id=edit&fid='.$entry->getId(),
							'title' => _l('edit file'),
							'src' => 'img/file_edit.png',
							'alt' => _l('edit file')
						);
					// delete
					$sList[$counter]['admin'][] = array(
							'href' => 'file.php?id=delete&fid='.$entry->getId(),
							'title' => _l('delete file'),
							'src' => 'img/file_delete.png',
							'alt' => _l('delete file')
						);
				} else {
					
					// smarty
					$sList[$counter]['admin'][] = array(
							'href' => '',
							'title' => '',
							'src' => '',
							'alt' => ''
						);
				}
				
				// increment counter
				$counter++;

			} else {
				
				// deleted items
			}
		}
			
		// return
		return $sList;
	}
	
	
	/**
	 * prepareCached($files) prepares the cached file objects as array to use with smarty template
	 * no need for edit and delete links
	 * 
	 * @param array $files array containing file objects to prepare as list for smarty
	 * @return array prepared array to use with smarty template
	 */
	private function prepareCached($files) {
		
		// smarty
		$sList = array();
		$descList = array();
		foreach($files as $entry) {
			
			// get table
			$table = $entry->getCached(false)['table'];
			
			// prepare desc info
			if($table == 'result') {
				$descList[] = $table;
			}
			
			// check if valid
			if($entry->getValid() == 1) {
			
				// prepare counter
				if(!isset($counter[$table])) {
					$counter[$table] = 0;
				}
				
				// smarty
				// translate table
				$shortFilename = $entry->getFilename();
				if(strlen($entry->getFilename()) > 35) {
					$shortFilename = substr($entry->getFilename(), 0, 6).'[...]'.substr($entry->getFilename(), -19);
				}
				$sList[$table]['name'] = _l('table name '.$table);
				$sList[$table][$counter[$table]] = array(
						'name' => array(
								'href' => 'file.php?id=details&fid='.$entry->getId(),
								'title' => _l('name'),
								'name' => $entry->getName(),
							),
						'filetype' => $entry->getFileTypeAs('name'),
						'filename' => array(
								'name' => $shortFilename,
								'title' => $entry->getFilename(),
							),
					);
				// desc
				if($table == 'result') {
					$result = new Result($entry->getCached(false)['tableId']);
					$sList[$table][$counter[$table]]['desc'] = $result->getDesc();
				}
				// show
				$sList[$table][$counter[$table]]['show'][] = array(
						'href' => 'file.php?id=cached&table='.$table.'&tid='.$entry->getCached(false)['tableId'],
						'title' => $entry->getName()._l('filename'),
						'src' => 'img/file_download.png',
						'alt' => '\''.$entry->getFilename().'\''._l('filename'),
					);
				
				// increment counter
				$counter[$table]++;

			} else {
				
				// deleted items
			}
		}
				
		// return
		return array($descList, $sList);
	}
	
	
	/**
	 * attach() handles the attachment functionality for Page objects
	 * 
	 * @return string HTML code of the page
	 */
	private function attach() {
		
		// get table and tableId
		$table = $this->get('table');
		$tableId = $this->get('tid');
		
		// pagecaption
		$this->getTpl()->assign('pagecaption', _l('attach file'));
		
		// check if $tableId exists in $table
		if(Page::exists($table, $tableId)) {
			
			// check permission
			if($this->getUser()->hasPermission($table, $tableId, 'w')) {
				
				// prepare template
				$sAttach = new JudoIntranetSmarty();
				
				// get object
				$objectClass = ucfirst($table);
				$object = new $objectClass($tableId);
				
				// assign title
				$sAttach->assign('title', _l('attach file to:').' "'.$object->getName().'"');
				
				// read all permitted entries
				$entries = $this->readAllEntries();
				
				// actual entries
				$setEntries = File::attachedTo($table, $tableId);
				
				// walk through entries (split into cached and not cached)
				$files = array();
				$cachedSection = array();
				foreach($entries as $entry) {
					
					// check valid
					if($entry->getValid() == 1) {
						
						// check $entry->cached
						if($entry->isCached()) {
							
							// get table
							list($thisTable, $thisTableId) = explode('|', $entry->getCached());
							$cachedSection[$thisTable][$entry->getId()] = $entry->getFilename().' ('.$entry->getFileTypeAs('name').')';
						} else {
							$files[$entry->getId()] = $entry->getName().' - '.$entry->getFilename().' ('.$entry->getFileTypeAs('name').')';
						}
					}
				}
				
				// form
				$form = new Zebra_Form(
						'fileAttach',		// id/name
						'post',			// method
						'file.php?id=attach&table='.$table.'&tid='.$tableId	// action
					);
				// set language
				$form->language('deutsch');
				// set docktype xhtml
				$form->doctype('xhtml');
				
				// add jquery for dialogs
				$this->add_jquery('
							$(function() {
								var element = $("#labelFiles").parent();
								var p = $(\'<p>'._l('Click to choose from').' <span id="labelFilesLink" class="spanLink">'._l('uploaded').'</span></p>\');
								element.before(p);
								element.hide();
								$("#labelFilesLink").click(function() {
									element.slideToggle();
								});
							});
						');
				
				// prepare formid
				// files
				$formIds['files'] = array('valueType' => 'array', 'type' => 'checkboxes', 'default' => 1);
				// add radio list
				$form->add(
						'label',		// type
						'labelFiles',	// id/name
						'files',		// for
						_l('uploaded')	// label text
					);
				$form->add(
						$formIds['files']['type'],	// type
						'files[]',			// id/name
						$files,		// values
						$setEntries	// default
					);
				
				// cached
				foreach($cachedSection as $tableName => $cachedFiles) {
					
					// translate tableName
					$transTableName = _l('table name '.$tableName);
					// label name
					$labelName = 'label'.$tableName.'Files';
					// add jquery for dialogs
					$this->add_jquery('
							$(function() {
								var element = $("#'.$labelName.'").parent(); 
								var p = $(\'<p>'._l('Click to choose from').' <span id="'.$labelName.'Link" class="spanLink">'.$transTableName.'</span></p>\');
								element.before(p);
								element.hide();
								$("#'.$labelName.'Link").click(function() {
									element.slideToggle();
								});
							});
						');
					
					// cached files
					$formIds[$tableName.'Files'] = array('valueType' => 'array', 'type' => 'checkboxes', 'default' => 1);
					// add radio list
					$form->add(
							'label',		// type
							$labelName,	// id/name
							$tableName.'Files',		// for
							$transTableName	// label text
						);
					$element = $form->add(
							$formIds[$tableName.'Files']['type'],	// type
							$tableName.'Files[]',			// id/name
							$cachedFiles,		// values
							$setEntries	// default
						);
				}
				
				// submit-button
				$form->add(
						'submit',		// type
						'buttonSubmit',	// id/name
						_l('save')	// value
					);
				
				// assign form
				$sAttach->assign('form', $form->render('', true));
				
				// validate
				if($form->validate()) {
					
					// get form data
					$data = $this->getFormValues($formIds);
					
					// combine file ids
					$fileIds = array();
					foreach($data as $section) {
						$fileIds = array_merge($fileIds, $section);
					}
					
					// delete all attachments
					File::deleteAttachedFiles($table, $tableId);
					// add attachments from form
					File::attachFiles($table, $tableId, $fileIds);
					
					// get file objects
					$fileObjects = array();
					foreach($fileIds as $id) {
						$fileObjects[] = new File($id);
					}
					
					// assign to template
					$sAttach->assign('files', $fileObjects);
					$sAttach->assign('attached', _l('attached files:'));
					$sAttach->assign('none', _l('- none -'));
					$sAttach->assign('fileHref', 'file.php?id=download&fid=');
					$sAttach->assign('form', '');
				}
				
				// return
				return $sAttach->fetch('smarty.file.attach.tpl');
			} else {
				throw new NotAuthorizedException($this);
			}
		} else {
			throw new ObjectNotExistsException($this, $table.' -> '.$tableId);
		}
	}
}

?>
