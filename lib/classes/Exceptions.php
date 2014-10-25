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
 * class CustomException implements the parent class for exceptions for error handling
 */
class CustomException extends Exception {
	
	/*
	 * class-variables
	 */
	private $view;
	private $fatal;
	
	/*
	 * getter/setter
	 */
	public function getView(){
		return $this->view;
	}
	public function setView($view) {
		$this->view = $view;
	}
	public function getFatal(){
		return $this->fatal;
	}
	public function setFatal($fatal) {
		$this->fatal = $fatal;
	}
	
	/*
	 * constructor/destructor
	 */
	public function __construct(&$view = null, $message = null, $code = 0) {
		
		// call parent constructor
		parent::__construct($message, $code);
		
		// set view
		$this->setView($view);
		
		// set fatal
		$this->setFatal(false);
	}
	
	/*
	 * methods
	 */
	/**
	 * errorMessage($outputType) embeds the error information in HTML and returns it as string (not using
	 * smarty), dies on fatal error (defined in child classes) or uses this::view to display the
	 * error message
	 * 
	 * @param int $outputType type to determine the type of output (i.e. HTML or JSON)
	 * @return string HTML string of error message or void if dies on fatal error
	 */
	final public function errorMessage($outputType = HANDLE_EXCEPTION_VIEW) {
		
		// check $outpuType
		if($outputType == HANDLE_EXCEPTION_JSON) {
			
			// get message
			$message = 
					'['.substr(get_class($this), 0, -9).': '.($this->getMessage() != '' ? '"'.$this->getMessage().'" | ' : '').$_SERVER['QUERY_STRING'].'"]
					'.(file_exists(JIPATH.'/DEBUG_ALL') === true ? $this->getTraceAsString() : '');
			
			// output jtable json
			echo json_encode(array(
						'Result' => 'ERROR',
						'Message' => $message,
					));
		} else {
			
			// get view
			$view = $this->getView();
			
			// prepare type
			$type = substr(get_class($this), 0, -9);
			
			$message = '
				<div class="exception">
					<h3 class="red">'.Object::lang('error', true).'</h3>
					<p>'.$this->getInternalMessage().'</p>
					['.$type.': '.($this->getMessage() != '' ? '"'.$this->getMessage().'" | ' : '').$view.' | "'.$_SERVER['QUERY_STRING'].'"]
					'.(file_exists(JIPATH.'/DEBUG_ALL') === true ? $this->trace() : '').'
				</div>
			';
			
			// check if fatal
			if($this->getFatal() === true || is_null($view) === true || !$view instanceof PageView) {
				
				// prepare rest of HTML page
				$html = '
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
				<title>'.Object::lang('error', true).'</title>
				<link rel="stylesheet" type="text/css" href="css/page.css" />
			</head>
			<body>
				'.$message.'
			</body>
		</html>
				';
				
				// die
				die($html);
				
			} else {
				
				// set "title" and "main" in view
				$view->getTpl()->assign('title', $view->title(Object::lang('error', true)));
				$view->getTpl()->assign('main', $message);
				// show page
				$view->showPage('smarty.main.tpl');
			}
		}
	}
	
	
	/**
	 * trace() generates a HTML view of the call trace
	 * 
	 * @return string HTML string for trace
	 */
	protected function trace() {
		
		// get trace array
		$traceArray = $this->getTrace();
		
		// reverse array
		$traceArray = array_reverse($traceArray);		
		
		// walk through trace array and prepare HTML
		$trace = '	<table class="trace">
						<tr>
							<th>Pos.</th>
							<th>Function</th>
							<th>Location</th>
						</tr>';
		foreach($traceArray as $no => $traceEntry) {
			
			// get file
			$file = substr($traceEntry['file'], strlen($_SERVER['DOCUMENT_ROOT']));
			$trace .= '	<tr>
							<td class="center">'.($no+1).'</td>
							<td>'.$traceEntry['class'].$traceEntry['type'].$traceEntry['function'].'()</td>
							<td>'.$file.':'.$traceEntry['line'].'</td>
						</tr>';
		}		
		// prepare HTML
		$trace .= '</table>';
		
		return $trace;
	}
	
	
	/**
	 * getInternalMessage() return the translated error message
	 * 
	 * @return string translated error message
	 */
	protected function getInternalMessage() {
		
		return '';
	}
}



/**
 * class NotAuthorizedException is thrown, if the user is not authorized to access
 */
class NotAuthorizedException extends CustomException {
	
	/*
	 * constructor/destructor
	 */
	public function __construct(&$view = null, $message = null, $code = 0) {
		
		// call parent constructor
		parent::__construct($view, $message, $code);
		
		// set fatal
		$this->setFatal(false);
	}
	
	
	/**
	 * getInternalMessage() return the translated error message
	 * 
	 * @return string translated error message
	 */
	protected function getInternalMessage() {
		
		// translate message
		$errorMessage = Object::lang('Error: not authorized');
		
		// get template
		$smarty = new JudoIntranetSmarty();
		$smarty->assign('object', $this->getView());
		
		// return template-parsed message
		return $smarty->fetch('string:'.$errorMessage); 
	}
}



/**
 * ResultNotExistsException is thrown, if the result "rid" is not in database
 */
class ResultNotExistsException extends CustomException {
	
	/*
	 * constructor/destructor
	 */
	public function __construct(&$view = null, $message = null, $code = 0) {
		
		// call parent constructor
		parent::__construct($view, $message, $code);
		
		// set fatal
		$this->setFatal(false);
	}
	
	
	/**
	 * getInternalMessage() return the translated error message
	 * 
	 * @return string translated error message
	 */
	protected function getInternalMessage() {
		
		return Object::lang('Error: result not exists');
	}
}



/**
 * GetUnknownIdException is thrown, if the result "rid" is not in database
 */
class GetUnknownIdException extends CustomException {
	
	/*
	 * constructor/destructor
	 */
	public function __construct(&$view = null, $message = null, $code = 0) {
		
		// call parent constructor
		parent::__construct($view, $message, $code);
		
		// set fatal
		$this->setFatal(false);
	}
	
	
	/**
	 * getInternalMessage() return the translated error message
	 * 
	 * @return string translated error message
	 */
	protected function getInternalMessage() {
		
		return Object::lang('Error: link unknown param');
	}
}



/**
 * MysqlErrorException is thrown, if there is an error in database querys
 */
class MysqlErrorException extends CustomException {
	
	/*
	 * constructor/destructor
	 */
	public function __construct(&$view = null, $message = null, $code = 0) {
		
		// call parent constructor
		parent::__construct($view, $message, $code);
		
		// set fatal
		$this->setFatal(true);
	}
	
	
	/**
	 * getInternalMessage() return the translated error message
	 * 
	 * @return string translated error message
	 */
	protected function getInternalMessage() {
		
		return Object::lang('Error: database error');
	}
}



/**
 * ResultImportFailedException is thrown, if there is an error in result import process
 */
class ResultImportFailedException extends CustomException {
	
	/*
	 * constructor/destructor
	 */
	public function __construct(&$view = null, $message = null, $code = 0) {
		
		// call parent constructor
		parent::__construct($view, $message, $code);
		
		// set fatal
		$this->setFatal(false);
	}
	
	
	/**
	 * getInternalMessage() return the translated error message
	 * 
	 * @return string translated error message
	 */
	protected function getInternalMessage() {
		
		return Object::lang('Error: result import failed');
	}
}



/**
 * CalendarIdNotExistsExeption is thrown, if a calendar id not exists
 */
class CalendarIdNotExistsExeption extends CustomException {
	
	/*
	 * constructor/destructor
	 */
	public function __construct(&$view = null, $message = null, $code = 0) {
		
		// call parent constructor
		parent::__construct($view, $message, $code);
		
		// set fatal
		$this->setFatal(false);
	}
	
	
	/**
	 * getInternalMessage() return the translated error message
	 * 
	 * @return string translated error message
	 */
	protected function getInternalMessage() {
		
		return Object::lang('Error: calendar entry not exists');
	}
}



/**
 * ResultIdNotExistsExeption is thrown, if a result id not exists
 */
class ResultIdNotExistsExeption extends CustomException {
	
	/*
	 * constructor/destructor
	 */
	public function __construct(&$view = null, $message = null, $code = 0) {
		
		// call parent constructor
		parent::__construct($view, $message, $code);
		
		// set fatal
		$this->setFatal(false);
	}
	
	
	/**
	 * getInternalMessage() return the translated error message
	 * 
	 * @return string translated error message
	 */
	protected function getInternalMessage() {
		
		return Object::lang('Error: result not exists');
	}
}



/**
 * UnknownTaskException is thrown, if a task not exists
 */
class UnknownTaskException extends CustomException {
	
	/*
	 * constructor/destructor
	 */
	public function __construct(&$view = null, $message = null, $code = 0) {
		
		// call parent constructor
		parent::__construct($view, $message, $code);
		
		// set fatal
		$this->setFatal(false);
	}
	
	
	/**
	 * getInternalMessage() return the translated error message
	 * 
	 * @return string translated error message
	 */
	protected function getInternalMessage() {
		
		return Object::lang('Error: unknown task');
	}
}



/**
 * UnknownActionException is thrown, if an action not exists
 */
class UnknownActionException extends CustomException {
	
	/*
	 * constructor/destructor
	 */
	public function __construct(&$view = null, $message = null, $code = 0) {
		
		// call parent constructor
		parent::__construct($view, $message, $code);
		
		// set fatal
		$this->setFatal(false);
	}
	
	
	/**
	 * getInternalMessage() return the translated error message
	 * 
	 * @return string translated error message
	 */
	protected function getInternalMessage() {
		
		return Object::lang('Error: unknown action');
	}
}


/**
 * GetInvalidCharsException is thrown, if there are invalid characters in get request
 */
class GetInvalidCharsException extends CustomException {
	
	/*
	 * constructor/destructor
	 */
	public function __construct(&$view = null, $message = null, $code = 0) {
		
		// call parent constructor
		parent::__construct($view, $message, $code);
		
		// set fatal
		$this->setFatal(true);
	}
	
	
	/**
	 * getInternalMessage() return the translated error message
	 * 
	 * @return string translated error message
	 */
	protected function getInternalMessage() {
		
		return Object::lang('Error: get request contains invalid characters');
	}
}



/**
 * PostInvalidCharsException is thrown, if there are invalid characters in post request
 */
class PostInvalidCharsException extends CustomException {
	
	/*
	 * constructor/destructor
	 */
	public function __construct(&$view = null, $message = null, $code = 0) {
		
		// call parent constructor
		parent::__construct($view, $message, $code);
		
		// set fatal
		$this->setFatal(true);
	}
	
	
	/**
	 * getInternalMessage() return the translated error message
	 * 
	 * @return string translated error message
	 */
	protected function getInternalMessage() {
		
		return Object::lang('Error: post request contains invalid characters');
	}
}


?>
