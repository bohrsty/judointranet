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
 * class TributeView implements the control of the tribute page
 */
class TributeView extends PageView implements ViewInterface {
	
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
	 * @return void
	 */
	public function init() {
		
		// set pagename
		$this->getTpl()->assign('pagename', _l('Tributes'));
		
		// init helpmessages
		$this->initHelp();
		
		// switch $_GET['id'] if set
		if($this->get('id') !== false) {
			
			// check permissions
			$naviId = Navi::idFromFileParam(basename($_SERVER['SCRIPT_FILENAME']), $this->get('id'));
			if($this->getUser()->hasPermission('navi', $naviId)) {
				
				switch($this->get('id')) {
					
					case 'listall':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('Tributes: listall')));
						$listall = new TributeViewListall();
						$this->getTpl()->assign('main', $listall->show());
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', false);
						$this->getTpl()->assign('tinymce', false);
					break;
					
					case 'new':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('Tributes: new')));
						$new = new TributeViewNew();
						$this->getTpl()->assign('main', $new->show());
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
						$this->getTpl()->assign('tinymce', false);
					break;
					
					case 'edit':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('Tributes: edit')));
						// if tid exists
						if(Page::exists('tribute', $this->get('tid'))) {
							$edit = new TributeViewEdit();
							$this->getTpl()->assign('main', $edit->show());
						} else {
							throw new TributeNotExistsException($this, $this->get('tid'));
						}
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
						$this->getTpl()->assign('tinymce', false);
					break;
					
					case 'delete':
						
						// smarty
						$this->getTpl()->assign('title', $this->title(_l('Tributes: delete')));
						$delete = new TributeViewDelete();
						$this->getTpl()->assign('main', $delete->show());
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', true);
						$this->getTpl()->assign('tinymce', false);
					break;
					
					default:
						
						// id set, but no functionality
						// smarty
						$this->getTpl()->assign('title', '');
						$this->getTpl()->assign('main', '');
						$this->getTpl()->assign('jquery', true);
						$this->getTpl()->assign('zebraform', false);
						$this->getTpl()->assign('tinymce', false);
						
						// throw exception
						throw new GetUnknownIdException($this);
					break;
				}
			} else {
				
				// smarty
				$this->getTpl()->assign('title', '');
				$this->getTpl()->assign('main', '');
				$this->getTpl()->assign('jquery', true);
				$this->getTpl()->assign('zebraform', false);
				$this->getTpl()->assign('tinymce', false);
				
				// throw exception
				throw new NotAuthorizedException($this);
			}
		} else {
			
			// id not set
			// smarty-title
			$this->getTpl()->assign('title', $this->title(_l('Tributes', true))); 
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
		$this->showPage('smarty.main.tpl');
	}
}

?>
