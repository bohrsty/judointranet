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
 * class Help implements the control of the helpsystem
 */
class Help extends Object {
	
		
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

	
	/*
	 * methods
	 */
	/**
	 * handle() handles the generation and translation of the help messages
	 * 
	 * @param bool $show echoes directly if true, returns data if false
	 */
	public function handle($show = true) {
		
		// get message id
		$messageId = $this->get('hid');
		
		// add version
		$replacements['version'] = $this->getGc()->get_config('global.version');
		
		// translate
		$translateTitle = _l('HELP_TITLE_'.$messageId);
		$translateMessage = _l('HELP_MESSAGE_'.$messageId);
		
		// check $messageId
		if($translateMessage == 'HELP_MESSAGE_'.$messageId ||
			$translateTitle == 'HELP_TITLE_'.$messageId) {
			
			// set not found message
			$translateTitle = _l('HELP_TITLE_error');
			$translateMessage = _l('HELP_MESSAGE_error');
		}
		
		// get smarty template
		$replacementTemplate = new JudoIntranetSmarty();
		$replacementTemplate->assign('replace', $replacements);
		$replacementTemplate->assign('object', $this);
		$translateTitle = $replacementTemplate->fetch('string:'.$translateTitle);
		$translateMessage = $replacementTemplate->fetch('string:'.$translateMessage);
		
		// prepare output
		$output = array(
				'result' => 'OK',
				'title' => $translateTitle,
				'content' => $translateMessage,
			);
		if($show === true) {
			echo json_encode($output);
		} else {
			return $output;
		}
	}

}
?>
