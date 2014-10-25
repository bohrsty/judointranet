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
 * class AccountingResultTask implements the data handling of the accounting result tasks
 */
class AccountingResultTask extends Task {
	
	
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
	 * getState($resultId) returns the actual state value from the database for the given $resultId
	 * 
	 * @param int $resultId result id to get the tasks state for
	 * @return int actual state from database
	 */
	public function getState($resultId) {
		
		// get value from database
		$result = Db::singleValue('
			SELECT `state`
			FROM `accounting_tasks`
			WHERE `table_name`=\'result\'
				AND `table_id`=#?
		',
		array($resultId,));
		if($result === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		} else {
			return $result;
		}
	}
	
	
	/**
	 * setState($resultId, $state) sets the given $state to database for the given $resultId
	 * 
	 * @param int $resultId result id to set the tasks state for
	 * @param int $state state value to be set in database
	 */
	public function setState($resultId, $state) {
		
		if(!Db::executeQuery('
			UPDATE `accounting_tasks`
			SET `state`=#?
			WHERE `table_name`=\'result\'
				AND `table_id`=#?
		',
		array($state, $resultId))) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
	}
	
	
	/**
	 * confirm($resultId) sets the state to 1
	 * 
	 * @param int $resultId result id to confirm the task for
	 */
	public function confirm($resultId) {
		
		// set state
		$this->setState($resultId, 1);
	}
	
	
	/**
	 * unconfirm($resultId) sets the state to 0
	 * 
	 * @param int $resultId result id to confirm the task for
	 */
	public function unconfirm($resultId) {
		
		// set state
		$this->setState($resultId, 0);
	}
}

?>
