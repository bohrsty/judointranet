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
	private $pageView;
	
	/*
	 * getter/setter
	 */
	private function getPageView() {
		return $this->pageView;
	}
	private function setPageView($pageView) {
		$this->pageView = $pageView;
	}
	
	
	/*
	 * constructor/destructor
	 */
	public function __construct($pageView) {
		
		// setup parent
		try {
			parent::__construct();
		} catch(Exception $e) {
			
			// handle error
			$this->getError()->handle_error($e);
		}
		
		// set classvariables
		$this->setPageView($pageView);
	}

	
	/*
	 * methods
	 */
	/**
	 * getMessage() get the HTML code of the helpmessage for the given
	 * $messageId
	 * 
	 * @param int $messageId id of the message
	 * @return string HTML code helpmessage
	 */
	public function getMessage($messageId, $replacements=array()) {
		
		// add version
		$replacements['version'] = $this->getGc()->get_config('global.version');
		
		// translate
		$translateTitle = parent::lang('HELP_TITLE_'.$messageId);
		$translateMessage = parent::lang('HELP_MESSAGE_'.$messageId);
		
		// check $messageId
		if($translateMessage == 'HELP_MESSAGE_'.$messageId ||
			$translateTitle == 'HELP_TITLE_'.$messageId) {
			
			// set not found message
			$translateTitle = parent::lang('HELP_TITLE_error');
			$translateMessage = parent::lang('HELP_MESSAGE_error');
		}
		
		// get smarty template
		$replacementTemplate = new JudoIntranetSmarty();
		$replacementTemplate->assign('replace', $replacements);
		$replacementTemplate->assign('object', $this);
		$translateTitle = $replacementTemplate->fetch('string:'.$translateTitle);
		$translateMessage = $replacementTemplate->fetch('string:'.$translateMessage);
		
		// prepare random-id
		$randomId = base_convert(mt_rand(10000000, 99999999), 10, 36);
		
		// prepare template values
		$templateValues = array(
				'buttonClass' => $this->getGc()->get_config('help.buttonClass'),
				'dialogClass' => $this->getGc()->get_config('help.dialogClass'),
				'imgTitle' => parent::lang('help'),
				'title' => $translateTitle,
				'message' => $translateMessage,
				'messageId' => $randomId,
			);
		
		// smarty template
		$helpTemplate = new JudoIntranetSmarty();
		$helpTemplate->assign('help', $templateValues);
		
		// add dialog
		$this->getPageView()->addHelpmessages($randomId, $helpTemplate->fetch('smarty.help.dialog.tpl'));
		
		// return button
		return $helpTemplate->fetch('smarty.help.button.tpl');	
	}

}
?>
