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
			$GLOBALS['Error']->handle_error($e);
		}
		
		// set classvariables
		$this->setPageView($pageView);
	}

	
	/*
	 * methods
	 */
	/**
	 * getMessage() get the HTML code of the helpmessate for the given
	 * $messageId
	 * 
	 * @param int $messageId id of the message
	 * @return string HTML code helpmessage
	 */
	public function getMessage($messageId, $replacements=null) {
		
		// get infos from db
		if($this->helpmessageExists($messageId)) {
			$message = $this->getMessageFromDB($messageId);
		} else {
			
			$message = array(
					'title'   => 'class.Help#global#title#errorIdNotExists',
					'message' => 'class.Help#global#message#errorIdNotExists',
				); 
		}
		
		// translate and replace placeholders
		foreach($message as $key => $value) {
			$message[$key] = parent::lang($value);
			if(!is_null($replacements) && is_array($replacements)) {
				
				// get smarty template
				$replacementTemplate = new JudoIntranetSmarty();
				$replacementTemplate->assign('replace', $replacements);
				$message[$key] = $replacementTemplate->fetch('string:'.$message[$key]);
			}
		}
		
		// prepare random-id
		$randomId = base_convert(mt_rand(10000000, 99999999), 10, 36);
		
		// prepare template values
		$templateValues = array(
				'buttonClass' => $_SESSION['GC']->get_config('help.buttonClass'),
				'dialogClass' => $_SESSION['GC']->get_config('help.dialogClass'),
				'imgTitle' => parent::lang('class.Help#getMessage#templateValues#imgTitle'),
				'title' => $message['title'],
				'message' => $message['message'],
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
	
	
	/**
	 * getMessageFromDb() gets the infos of the given $messageId from db
	 * 
	 * @param int $messageId id of the message to get
	 * @return array array containing the message infos
	 */
	private function getMessageFromDb($messageId) {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql-statement
		$sql = "SELECT title,message
				FROM helpmessages
				WHERE id = $messageId";
		
		// execute
		$result = $db->query($sql);
		
		// return
		return $result->fetch_array(MYSQL_ASSOC);
	}
	
	
	/**
	 * helpmessageExists() checks if the given $messageId exists in db
	 * 
	 * @param int $messageId id of the message to check
	 * @return boolean true if exists, false otherwise
	 */
	private function helpmessageExists($messageId) {
		
		// get db-object
		$db = Db::newDb();
		
		// prepare sql-statement
		$sql = "SELECT COUNT(*)
				FROM helpmessages
				WHERE id = $messageId";
		
		// execute
		$result = $db->query($sql);
		
		// get value
		list($count) = $result->fetch_array(MYSQL_NUM);
		
		// return
		return $count == 1;
	}

}
?>
