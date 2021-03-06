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
	 * @param bool $show if true echoes the error, if false return it
	 * @return string HTML string of error message or void if dies on fatal error
	 */
	final public function errorMessage($outputType = HANDLE_EXCEPTION_VIEW, $show = true) {
		
		// prepare return
		$return = '';
		
		// check $outpuType
		if($outputType == HANDLE_EXCEPTION_JSON) {
			
			// get message
			$message = 
					'['.substr(get_class($this), 0, -9).': '.($this->getMessage() != '' ? '"'.$this->getMessage().'" | ' : '').$_SERVER['QUERY_STRING'].'"]
					'.(Object::staticDebugAll() === true ? $this->getTraceAsString() : '');
			
			// output jtable json
			$return = json_encode(array(
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
					<h3 class="red">'._l('error').'</h3>
					<p>'.$this->getInternalMessage().'</p>
					['.$type.': '.($this->getMessage() != '' ? '"'.$this->getMessage().'" | ' : '').$view.' | "'.$_SERVER['QUERY_STRING'].'"]
					'.(Object::staticDebugAll() === true ? $this->trace() : '').'
				</div>
			';
			
			// check if fatal
			if($this->getFatal() === true || is_null($view) === true || !$view instanceof PageView) {
				
				// prepare rest of HTML page
				$html = '
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
				<title>'._l('error').'</title>
				<link rel="stylesheet" type="text/css" href="css/page.css" />
			</head>
			<body>
				'.$message.'
			</body>
		</html>
				';
				
				// die
				if($show === true) {
					die($html);
				} else {
					$return = $html;
				}
				
			} else {
				
				// set "title" and "main" in view
				$view->getTpl()->assign('title', $view->title(_l('error')));
				$view->getTpl()->assign('main', $message);
				// show page
				if($show == true) {
					$view->showPage('smarty.main.tpl');
				} else {
					$return = $view->showPage('smarty.main.tpl', $show);
				}
			}
		}
		
		// check echo or return
		if($show == true) {
			echo $return;
		} else {
			return $return;
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
			$file = (isset($traceEntry['file']) ? substr($traceEntry['file'], strlen(JIPATH) + 1) : '');
			$trace .= '	<tr>
							<td class="center">'.($no+1).'</td>
							<td>'.(isset($traceEntry['class']) ? $traceEntry['class'] : '').(isset($traceEntry['type']) ? $traceEntry['type'] : '').$traceEntry['function'].'()</td>
							<td>'.$file.':'.(isset($traceEntry['line']) ? $traceEntry['line'] : '').'</td>
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
		$errorMessage = _l('Error: not authorized');
		
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
		
		return _l('Error: result not exists');
	}
}



/**
 * GetUnknownIdException is thrown, if the $_GET parameter id is not handled
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
		
		return _l('Error: link unknown param');
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
		
		return _l('Error: database error');
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
		
		return _l('Error: result import failed');
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
		
		return _l('Error: calendar entry not exists');
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
		
		return _l('Error: result not exists');
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
		
		return _l('Error: unknown task');
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
		
		return _l('Error: unknown action');
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
		
		return _l('Error: get request contains invalid characters');
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
		
		return _l('Error: post request contains invalid characters');
	}
}



/**
 * ResultForFutureCalendarException is thrown, if there are invalid characters in post request
 */
class ResultForFutureCalendarException extends CustomException {
	
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
		
		return _l('Error: result not possible for future calendar entries!');
	}
}



/**
 * ProtocolIdNotExistsExeption is thrown, if a protocol id not exists
 */
class ProtocolIdNotExistsExeption extends CustomException {
	
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
		
		return _l('Error: protocol entry not exists');
	}
}



/**
 * HolidayFunctionNotCallableExeption is thrown, if a given function name for holiday is
 * not callable
 */
class HolidayFunctionNotCallableExeption extends CustomException {
	
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
		
		return _l('Error: holiday function is not callable');
	}
}



/**
 * HolidayCalculationErrorExeption is thrown, if a holiday settings are not calculatable
 */
class HolidayCalculationErrorExeption extends CustomException {
	
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
		
		return _l('Error: holiday calculation error');
	}
}



/**
 * HolidayYearNotValidException is thrown, if a given year is not valid
 */
class HolidayYearNotValidException extends CustomException {
	
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
		
		return _l('Error: holiday year not valid');
	}
}



/**
 * TributeNotExistsException is thrown, if the tribute "tid" is not in database
 */
class TributeNotExistsException extends CustomException {
	
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
		
		return _l('Error: tribute not exists');
	}
}



/**
 * UidNotExistsException is thrown, if the user id is not in database
 */
class UidNotExistsException extends CustomException {
	
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
		
		return _l('Error: user not exists');
	}
}



/**
 * GidNotExistsException is thrown, if the group id is not in database
 */
class GidNotExistsException extends CustomException {
	
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
		
		return _l('Error: group not exists');
	}
}



/**
 * ObjectInUseException is thrown, if the user/group is linked to another object
 */
class ObjectInUseException extends CustomException {
	
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
		
		return _l('Error: object in use');
	}
}



/**
 * NidNotExistsException is thrown, if the navigation id is not in database
 */
class NidNotExistsException extends CustomException {
	
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
		
		return _l('Error: navi entry not exists');
	}
}



/**
 * WrongParamsException is thrown, if the given parameters are wrong
 */
class WrongParamsException extends CustomException {
	
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
		
		return _l('Error: wrong params');
	}
}



/**
 * MissingParamsException is thrown, if the required parameters are not given
 */
class MissingParamsException extends CustomException {
	
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
		
		return _l('Error: missing params');
	}
}



/**
 * AnnNotExistsException is thrown, if the announcement is not found in database
 */
class AnnNotExistsException extends CustomException {
	
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
		
		return _l('Error: no announcement for calendar entry');
	}
}



/**
 * CidNotExistsException is thrown, if the calendar id is not found in database
 */
class CidNotExistsException extends CustomException {
	
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
		
		return _l('Error: calendar entry not exists');
	}
}



/**
 * HeaderSentException is thrown, if any HTML header is already sent
 */
class HeaderSentException extends CustomException {
	
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
		
		return _l('Error: header already sent before download');
	}
}



/**
 * FileNotExistsException is thrown, if the file doesn't exists in database
 */
class FileNotExistsException extends CustomException {
	
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
		
		return _l('Error: file not exists');
	}
}



/**
 * ObjectNotExistsException is thrown, if the object doesn't exists in database
 */
class ObjectNotExistsException extends CustomException {
	
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
		
		return _l('Error: object not exists');
	}
}



/**
 * NotOwnedException is thrown, if the inventory object isn't owned by actual user
 */
class NotOwnedException extends CustomException {
	
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
		
		return _l('Error: not owner of object');
	}
}



/**
 * NotGivenToException is thrown, if the inventory object isn't given to another user
 */
class NotGivenToException extends CustomException {
	
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
		
		return _l('Error: object not given to');
	}
}



/**
 * NotGivenException is thrown, if the inventory object isn't prepared to be given
 */
class NotGivenException extends CustomException {
	
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
		
		return _l('Error: object not given');
	}
}



/**
 * DbActionUnknownException is thrown, if the action to be executed on database is unknown
 */
class DbActionUnknownException extends CustomException {
	
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
		
		return _l('Error: not saved in database');
	}
}



/**
 * YearNotValidException is thrown, if the the given year has no appointment
 */
class YearNotValidException extends CustomException {
	
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
		
		return _l('Error: no valid year given');
	}
}


?>
