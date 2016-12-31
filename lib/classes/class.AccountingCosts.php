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
 * class AccountingCosts implements the data handling of the accounting costs (incoming and outgoing)
 */
class AccountingCosts extends Object {
	
	
	/*
	 * class-variables
	 */
	
	
	/*
	 * getter/setter
	 */
	
	/*
	 * constructor/destructor
	 */
	
	/*
	 * methods
	 */
	/**
	 * getAmountsAsArray() returns all costs as array
	 * 
	 * @return array array containing all costs
	 */
	static public function getAmountsAsArray() {
		
		
		// get data
		$result = Db::ArrayValue('
			SELECT `name`, `type`, `value`
			FROM `accounting_costs`
			WHERE `valid`=TRUE
		',
		MYSQLI_ASSOC,
		array());
		if($result === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// walk through result
		$costs = array();
		foreach($result as $row) {
			$costs[$row['type']][$row['name']] = $row['value'];
		}
		
		// return
		return $costs;
	}
	
	
	/**
	 * toCalc($value) replaces all "," in $value(from database) to "." to be able to calculate
	 * with the value
	 * 
	 * @param mixed $value value to calculate with
	 */
	public static function toCalc($value) {
		return str_replace(',', '.', $value);
	}
	
	
	/**
	 * toDisplay($value) replaces all "." in $value(from calculation) to "," to correctly display
	 * the value
	 * 
	 * @param mixed $value value to display
	 */
	public static function toDisplay($value) {
		return str_replace('.', ',', $value);
	}
	
}

?>
