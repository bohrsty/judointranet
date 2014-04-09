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
 * class Error implements the error-handling and displays errors
 */
class Error extends Object {
	
	/*
	 * class-variables
	 */
	private $errors;
	
	/*
	 * getter/setter
	 */
	private function get_errors(){
		return $this->errors;
	}
	private function set_errors($errors) {
		$this->errors = $errors;
	}
	
	/*
	 * constructor/destructor
	 */
	public function __construct() {
		
		// initialize class-variables
		$this->set_errors(array());
	}
	
	/*
	 * methods
	 */
	/**
	 * error_raised takes the information about the error and stores it for handling
	 * 
	 * @param string $type type of the error
	 * @param string $message an optional message
	 * @param string $entry optional name of entry (i.e. post-fieldname)
	 * 
	 * @return int errornumber (array-key)
	 */
	public function error_raised($type,$message='',$entry='') {
		
		// add error to array and return key
		// get array
		$errors = $this->get_errors();
		
		// add error
		$errors[] = array(
						'type' => $type,
						'message' => $message,
						'entry' => $entry,
						'output' => ''
					);
		
		// set array
		$this->set_errors($errors);
		
		// return array-key
		return count($errors)-1;
	}
	
	
	
	
	
	
	/**
	 * handle_error handles the error given by errno
	 * 
	 * @param mixed $error takes an Exception or errno
	 * 
	 * @return void
	 */
	public function handle_error($error){
		
		// check if Exception or int
		$errno = null;
		if($error instanceof Exception) {
			
			// explode message by ":"
			$errno = $error->getCode();
		} else {
			
			$errno = $error;
		}
		
		// switch by error-type
		switch($this->return_type($errno)) {
			
			case 'GETInvalidChars':
								
				// fatal error, die()
				$message = '<div style="font-family: sans-serif; margin: 150px auto; width: 400px; height: 300px; border: 1px dashed red; border-radius: 3px; padding: 5px;">';
				$message .= '<h3 style="color: red;">'.parent::lang('class.Error#handle_error#GETInvalidChars#ERROR.caption').'</h3>';
				$message .= '<p>'.parent::lang('class.Error#handle_error#GETInvalidChars#ERROR.message').'</p>';
				$message .= '[GETInvalidChars in '.$this->return_entry($errno).']';
				$message .= '</div>';
				die($message);
			break;
			
			case 'POSTInvalidChars':
				
				// non-fatal error, message
				$message = '<p class="posterror">'.parent::lang('class.Error#handle_error#POSTInvalidChars#ERROR.message').'</p>';
				
				// set output
				$errors = $this->get_errors();
				$errors[$errno]['output'] = $message;
				$this->set_errors($errors);
			break;
			
			case 'ReadTemplateFile':
				
				// fatal error, die()
				$message = '<div style="font-family: sans-serif; margin: 150px auto; width: 400px; height: 300px; border: 1px dashed red; border-radius: 3px; padding: 5px;">';
				$message .= '<h3 style="color: red;">'.parent::lang('class.Error#handle_error#ReadTemplateFile#ERROR.caption').'</h3>';
				$message .= '<p>'.parent::lang('class.Error#handle_error#ReadTemplateFile#ERROR.message').'</p>';
				$message .= '[ReadTemplateFile: "'.$this->return_message($errno).'"]';
				$message .= '</div>';
				die($message);
			break;
			
			case 'GETUnkownId':
				
				// non-fatal error, message
				$message = '<div style="font-family: sans-serif; margin: 150px auto; width: 400px; height: 300px; border: 1px dashed red; border-radius: 3px; padding: 5px;">';
				$message .= '<h3 style="color: red;">'.parent::lang('class.Error#handle_error#GETUnknownId#ERROR.caption').'</h3>';
				$message .= '<p>'.parent::lang('class.Error#handle_error#GETUnknownId#ERROR.message').'</p>';
				$message .= '[GETUnknownId: "'.$this->return_entry($errno).'"]';
				$message .= '</div>';
				
				// set output
				$errors = $this->get_errors();
				$errors[$errno]['output'] = $message;
				$this->set_errors($errors);
			break;
			
			case 'CannotGetNavi':
				
				// fatal error, die()
				$message = '<div style="font-family: sans-serif; margin: 150px auto; width: 400px; height: 300px; border: 1px dashed red; border-radius: 3px; padding: 5px;">';
				$message .= '<h3 style="color: red;">'.parent::lang('class.Error#handle_error#CannotGetNavi#ERROR.caption').'</h3>';
				$message .= '<p>'.parent::lang('class.Error#handle_error#CannotGetNavi#ERROR.message').'</p>';
				$message .= '[CannotGetNavi from '.$this->return_entry($errno).']';
				$message .= '</div>';
				die($message);
			break;
			
			case 'NotAuthorized':
				
				// non-fatal error, message
				$message = '<div style="font-family: sans-serif; margin: 150px auto; width: 400px; height: 300px; border: 1px dashed red; border-radius: 3px; padding: 5px;">';
				$message .= '<h3 style="color: red;">'.parent::lang('class.Error#handle_error#NotAuthorized#ERROR.caption').'</h3>';
				$message .= '<p>'.parent::lang('class.Error#handle_error#NotAuthorized#ERROR.message').'</p>';
				$message .= '[NotAuthorized: "'.$this->return_entry($errno).'"]';
				$message .= '</div>';
				
				// set output
				$errors = $this->get_errors();
				$errors[$errno]['output'] = $message;
				$this->set_errors($errors);
			break;
			
			case 'NotAuthorizedDemo':
				
				// non-fatal error, message
				$message = '<div style="font-family: sans-serif; margin: 150px auto; width: 400px; height: 300px; border: 1px dashed red; border-radius: 3px; padding: 5px;">';
				$message .= '<h3 style="color: red;">'.parent::lang('class.Error#handle_error#NotAuthorizedDemo#ERROR.caption').'</h3>';
				$message .= '<p>'.parent::lang('class.Error#handle_error#NotAuthorizedDemo#ERROR.message').'</p>';
				$message .= '[NotAuthorizedDemo: "'.$this->return_entry($errno).'"]';
				$message .= '</div>';
				
				// set output
				$errors = $this->get_errors();
				$errors[$errno]['output'] = $message;
				$this->set_errors($errors);
			break;
			
			case 'DbActionUnknown':
				
				// non-fatal error, message
				$message = '<div style="font-family: sans-serif; margin: 150px auto; width: 400px; height: 300px; border: 1px dashed red; border-radius: 3px; padding: 5px;">';
				$message .= '<h3 style="color: red;">'.parent::lang('class.Error#handle_error#DbActionUnknown#ERROR.caption').'</h3>';
				$message .= '<p>'.parent::lang('class.Error#handle_error#DbActionUnknown#ERROR.message').'</p>';
				$message .= '[DbActionUnknown: "'.$this->return_entry($errno).'"]';
				$message .= '</div>';
				
				// set output
				$errors = $this->get_errors();
				$errors[$errno]['output'] = $message;
				$this->set_errors($errors);
			break;
			
			case 'CidNotExists':
				
				// non-fatal error, message
				$message = '<div style="font-family: sans-serif; margin: 150px auto; width: 400px; height: 300px; border: 1px dashed red; border-radius: 3px; padding: 5px;">';
				$message .= '<h3 style="color: red;">'.parent::lang('class.Error#handle_error#CidNotExists#ERROR.caption').'</h3>';
				$message .= '<p>'.parent::lang('class.Error#handle_error#CidNotExists#ERROR.message').'</p>';
				$message .= '[CidNotExits: "'.$this->return_entry($errno).'" in '.$this->return_message($errno).']';
				$message .= '</div>';
				
				// set output
				$errors = $this->get_errors();
				$errors[$errno]['output'] = $message;
				$this->set_errors($errors);
			break;
			
			case 'NotOwned':
				
				// non-fatal error, message
				$message = '<div style="font-family: sans-serif; margin: 150px auto; width: 400px; height: 300px; border: 1px dashed red; border-radius: 3px; padding: 5px;">';
				$message .= '<h3 style="color: red;">'.parent::lang('class.Error#handle_error#NotOwned#ERROR.caption').'</h3>';
				$message .= '<p>'.parent::lang('class.Error#handle_error#NotOwned#ERROR.message').'</p>';
				$message .= '[NotOwned: "'.$this->return_entry($errno).'" in '.$this->return_message($errno).']';
				$message .= '</div>';
				
				// set output
				$errors = $this->get_errors();
				$errors[$errno]['output'] = $message;
				$this->set_errors($errors);
			break;
			
			case 'NotGiven':
				
				// non-fatal error, message
				$message = '<div style="font-family: sans-serif; margin: 150px auto; width: 400px; height: 300px; border: 1px dashed red; border-radius: 3px; padding: 5px;">';
				$message .= '<h3 style="color: red;">'.parent::lang('class.Error#handle_error#NotGiven#ERROR.caption').'</h3>';
				$message .= '<p>'.parent::lang('class.Error#handle_error#NotGiven#ERROR.message').'</p>';
				$message .= '[NotGiven: "'.$this->return_entry($errno).'" in '.$this->return_message($errno).']';
				$message .= '</div>';
				
				// set output
				$errors = $this->get_errors();
				$errors[$errno]['output'] = $message;
				$this->set_errors($errors);
			break;
			
			case 'NotGivenTo':
				
				// non-fatal error, message
				$message = '<div style="font-family: sans-serif; margin: 150px auto; width: 400px; height: 300px; border: 1px dashed red; border-radius: 3px; padding: 5px;">';
				$message .= '<h3 style="color: red;">'.parent::lang('class.Error#handle_error#NotGivenTo#ERROR.caption').'</h3>';
				$message .= '<p>'.parent::lang('class.Error#handle_error#NotGivenTo#ERROR.message').'</p>';
				$message .= '[NotGivenTo: "'.$this->return_entry($errno).'" in '.$this->return_message($errno).']';
				$message .= '</div>';
				
				// set output
				$errors = $this->get_errors();
				$errors[$errno]['output'] = $message;
				$this->set_errors($errors);
			break;
			
			case 'MissingParams':
				
				// non-fatal error, message
				$message = '<div style="font-family: sans-serif; margin: 150px auto; width: 400px; height: 300px; border: 1px dashed red; border-radius: 3px; padding: 5px;">';
				$message .= '<h3 style="color: red;">'.parent::lang('class.Error#handle_error#MissingParams#ERROR.caption').'</h3>';
				$message .= '<p>'.parent::lang('class.Error#handle_error#MissingParams#ERROR.message').'</p>';
				$message .= '[MissingParams: "'.$this->return_entry($errno).']';
				$message .= '</div>';
				
				// set output
				$errors = $this->get_errors();
				$errors[$errno]['output'] = $message;
				$this->set_errors($errors);
			break;
			
			case 'WrongParams':
				
				// non-fatal error, message
				$message = '<div style="font-family: sans-serif; margin: 150px auto; width: 400px; height: 300px; border: 1px dashed red; border-radius: 3px; padding: 5px;">';
				$message .= '<h3 style="color: red;">'.parent::lang('class.Error#handle_error#WrongParams#ERROR.caption').'</h3>';
				$message .= '<p>'.parent::lang('class.Error#handle_error#WrongParams#ERROR.message').'</p>';
				$message .= '[WrongParams: "'.$this->return_entry($errno).']';
				$message .= '</div>';
				
				// set output
				$errors = $this->get_errors();
				$errors[$errno]['output'] = $message;
				$this->set_errors($errors);
			break;
			
			case 'AnnNotExists':
				
				// non-fatal error, message
				$message = '<div style="font-family: sans-serif; margin: 150px auto; width: 400px; height: 300px; border: 1px dashed red; border-radius: 3px; padding: 5px;">';
				$message .= '<h3 style="color: red;">'.parent::lang('class.Error#handle_error#AnnNotExists#ERROR.caption').'</h3>';
				$message .= '<p>'.parent::lang('class.Error#handle_error#AnnNotExists#ERROR.message').'</p>';
				$message .= '[AnnNotExists: "'.$this->return_entry($errno).'"]';
				$message .= '</div>';
				
				// set output
				$errors = $this->get_errors();
				$errors[$errno]['output'] = $message;
				$this->set_errors($errors);
			break;
			
			case 'UsertableNotExists':
				
				// non-fatal error, message
				$message = '<div style="font-family: sans-serif; margin: 150px auto; width: 400px; height: 300px; border: 1px dashed red; border-radius: 3px; padding: 5px;">';
				$message .= '<h3 style="color: red;">'.parent::lang('class.Error#handle_error#UsertableNotExists#ERROR.caption').'</h3>';
				$message .= '<p>'.parent::lang('class.Error#handle_error#UsertableNotExists#ERROR.message').'</p>';
				$message .= '[UsertableNotExists: "'.$this->return_message($errno).'"]';
				$message .= '</div>';
				
				// set output
				$errors = $this->get_errors();
				$errors[$errno]['output'] = $message;
				$this->set_errors($errors);
			break;
			
			case 'RowNotExists':
				
				// non-fatal error, message
				$message = '<div style="font-family: sans-serif; margin: 150px auto; width: 400px; height: 300px; border: 1px dashed red; border-radius: 3px; padding: 5px;">';
				$message .= '<h3 style="color: red;">'.parent::lang('class.Error#handle_error#RowNotExists#ERROR.caption').'</h3>';
				$message .= '<p>'.parent::lang('class.Error#handle_error#RowNotExists#ERROR.message').'</p>';
				$message .= '[RowNotExists: "'.$this->return_message($errno).'"]';
				$message .= '</div>';
				
				// set output
				$errors = $this->get_errors();
				$errors[$errno]['output'] = $message;
				$this->set_errors($errors);
			break;
			
			case 'MysqlError':
								
				// fatal error, die()
				$message = '<div style="font-family: sans-serif; margin: 150px auto; width: 400px; height: 300px; border: 1px dashed red; border-radius: 3px; padding: 5px;">';
				$message .= '<h3 style="color: red;">'.parent::lang('class.Error#handle_error#MysqlError#ERROR.caption').'</h3>';
				$message .= '<p>'.parent::lang('class.Error#handle_error#MysqlError#ERROR.message').'</p>';
				$message .= '[MysqlError: "'.$this->return_message($errno).'"][Statement: '.$this->return_entry($errno).']';
				$message .= '</div>';
				die($message);
			break;
			
			case 'HeaderSent':
				
				// non-fatal error, message
				$message = '<div style="font-family: sans-serif; margin: 150px auto; width: 400px; height: 300px; border: 1px dashed red; border-radius: 3px; padding: 5px;">';
				$message .= '<h3 style="color: red;">'.parent::lang('class.Error#handle_error#HeaderSent#ERROR.caption').'</h3>';
				$message .= '<p>'.parent::lang('class.Error#handle_error#HeaderSent#ERROR.message').'</p>';
				$message .= '[HeaderSent: "'.$this->return_entry($errno).'"]';
				$message .= '</div>';
				
				// set output
				$errors = $this->get_errors();
				$errors[$errno]['output'] = $message;
				$this->set_errors($errors);
			break;
			
			case 'ObjectNotExists':
				
				// non-fatal error, message
				$message = '<div style="font-family: sans-serif; margin: 150px auto; width: 400px; height: 300px; border: 1px dashed red; border-radius: 3px; padding: 5px;">';
				$message .= '<h3 style="color: red;">'.parent::lang('class.Error#handle_error#ObjectNotExists#ERROR.caption').'</h3>';
				$message .= '<p>'.parent::lang('class.Error#handle_error#ObjectNotExists#ERROR.message').'</p>';
				$message .= '[ObjectNotExists: "'.$this->return_entry($errno).'"]';
				$message .= '</div>';
				
				// set output
				$errors = $this->get_errors();
				$errors[$errno]['output'] = $message;
				$this->set_errors($errors);
			break;
			
			case 'GETUnknownAction':
				
				// non-fatal error, message
				$message = '<div style="font-family: sans-serif; margin: 150px auto; width: 400px; height: 300px; border: 1px dashed red; border-radius: 3px; padding: 5px;">';
				$message .= '<h3 style="color: red;">'.parent::lang('class.Error#handle_error#GETUnknownAction#ERROR.caption').'</h3>';
				$message .= '<p>'.parent::lang('class.Error#handle_error#GETUnknownAction#ERROR.message').'</p>';
				$message .= '[GETUnknownAction: "'.$this->return_message($errno).'"]';
				$message .= '</div>';
				
				// set output
				$errors = $this->get_errors();
				$errors[$errno]['output'] = $message;
				$this->set_errors($errors);
			break;
			
			case 'NidNotExists':
				
				// non-fatal error, message
				$message = '<div style="font-family: sans-serif; margin: 150px auto; width: 400px; height: 300px; border: 1px dashed red; border-radius: 3px; padding: 5px;">';
				$message .= '<h3 style="color: red;">'.parent::lang('class.Error#handle_error#NidNotExists#ERROR.caption').'</h3>';
				$message .= '<p>'.parent::lang('class.Error#handle_error#NidNotExists#ERROR.message').'</p>';
				$message .= '[NidNotExits: "'.$this->return_entry($errno).'" in '.$this->return_message($errno).']';
				$message .= '</div>';
				
				// set output
				$errors = $this->get_errors();
				$errors[$errno]['output'] = $message;
				$this->set_errors($errors);
			break;
			
			case 'GidNotExists':
				
				// non-fatal error, message
				$message = '<div style="font-family: sans-serif; margin: 150px auto; width: 400px; height: 300px; border: 1px dashed red; border-radius: 3px; padding: 5px;">';
				$message .= '<h3 style="color: red;">'.parent::lang('class.Error#handle_error#GidNotExists#ERROR.caption').'</h3>';
				$message .= '<p>'.parent::lang('class.Error#handle_error#GidNotExists#ERROR.message').'</p>';
				$message .= '[GidNotExits: "'.$this->return_entry($errno).'" in '.$this->return_message($errno).']';
				$message .= '</div>';
				
				// set output
				$errors = $this->get_errors();
				$errors[$errno]['output'] = $message;
				$this->set_errors($errors);
			break;
			
			case 'UidNotExists':
				
				// non-fatal error, message
				$message = '<div style="font-family: sans-serif; margin: 150px auto; width: 400px; height: 300px; border: 1px dashed red; border-radius: 3px; padding: 5px;">';
				$message .= '<h3 style="color: red;">'.parent::lang('class.Error#handle_error#UidNotExists#ERROR.caption').'</h3>';
				$message .= '<p>'.parent::lang('class.Error#handle_error#UidNotExists#ERROR.message').'</p>';
				$message .= '[UidNotExits: "'.$this->return_entry($errno).'" in '.$this->return_message($errno).']';
				$message .= '</div>';
				
				// set output
				$errors = $this->get_errors();
				$errors[$errno]['output'] = $message;
				$this->set_errors($errors);
			break;
			
			case 'ObjectInUse':
				
				// non-fatal error, message
				$message = '<div style="font-family: sans-serif; margin: 150px auto; width: 400px; height: 300px; border: 1px dashed red; border-radius: 3px; padding: 5px;">';
				$message .= '<h3 style="color: red;">'.parent::lang('class.Error#handle_error#ObjectInUse#ERROR.caption').'</h3>';
				$message .= '<p>'.parent::lang('class.Error#handle_error#ObjectInUse#ERROR.message').'</p>';
				$message .= '[ObjectInUse: "'.$this->return_entry($errno).'" in '.$this->return_message($errno).']';
				$message .= '</div>';
				
				// set output
				$errors = $this->get_errors();
				$errors[$errno]['output'] = $message;
				$this->set_errors($errors);
			break;
			
			default:
				
			break;
		}
	}
	
	
	
	
	
	
	/**
	 * return_type returns the type of the error
	 * 
	 * @param int $errno number of error
	 * 
	 * @return string the type of the error
	 */
	private function return_type($errno) {
		
		// get errors
		$errors = $this->get_errors();
		
		// return type
		return $errors[$errno]['type'];
	}
	
	
	
	
	
	
	/**
	 * return_message returns the message of the error
	 * 
	 * @param int $errno number of error
	 * 
	 * @return string the error-message
	 */
	private function return_message($errno) {
		
		// get errors
		$errors = $this->get_errors();
		
		// return type
		return $errors[$errno]['message'];
	}
	
	
	
	
	
	
	/**
	 * return_entry returns the entry (i.e. name of post-field) of the error
	 * 
	 * @param int $errno number of error
	 * 
	 * @return string the error-entry
	 */
	private function return_entry($errno) {
		
		// get errors
		$errors = $this->get_errors();
		
		// return type
		return $errors[$errno]['entry'];
	}
	
	
	
	
	
	
	/**
	 * to_html returns the error as html
	 * 
	 * @param int $errno error number of asked error
	 * @return string html-code of the error
	 */
	public function to_html($errno) {
		
		// get errors
		$errors = $this->get_errors();
		
		// return type
		return $errors[$errno]['output'];
	}
	
	
	
	
	
	
	/**
	 * errno_by_entry returns the errornumber of the given entry
	 * 
	 * @param string $entry name of the entry (i.e. post-fieldname)
	 * @return mixed false if entry not found, errno if found
	 */
	public function errno_by_entry($entry) {
		
		// get errors
		$errors = $this->get_errors();
		
		// walk through $errors
		foreach($errors as $errno => $error) {
			
			if($error['entry'] == $entry) {
				return $errno;
			} else {
				return false;
			}
		}
	}
}



?>
