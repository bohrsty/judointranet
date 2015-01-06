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
 * class ResultImporterSpreadsheet implements the representation of a result importer object
 * for Open XML (xslx) or Open Document (ods) spreadsheet 
 */
class ResultImporterSpreadsheet extends ResultImporter implements ResultImporterInterface {
	
	/*
	 * class-variables
	 */
	const filetypes = 'ods, ots, xls, xlt, xlsx, xltx';
	
	
	/*
	 * getter/setter
	 */
	public function setFileContent($fileContent){
		
		// set file content
		parent::setFileContent($fileContent);
	}
	
	
	/*
	 * constructor/destructor
	 */
	public function __construct() {
		
		// parent constructor
		parent::__construct();
		
		// set team or single
		$this->setIsTeam(null);
		
	}
	
	
	/*
	 * methods
	 */
	/**
	 * validate() checks if the given fileContent has the correct format according
	 * to module type and prepares the array to get the data
	 * 
	 * @return bool true if format is correct for type, false otherwise
	 */
	public function validate() {
		
		/*
		 * array format:
		 * 
		 * [1][A] = checksum (md5([2][A].[3][A].[4][A].[5][A]);)
		 * [2][A] = warning text
		 * [3][A] = info of text format in cells
		 * [4][A] = info for team result
		 * [5][A] = single " "
		 * [6][A][B][C][D] = column captions
		 * [7][A][B][C][D] = values
		 * .
		 * .
		 * .
		 */
		
		// get file content as array
		$phpExcel = PHPExcel_IOFactory::load($this->getFileContent());
		$data = $phpExcel->getActiveSheet()->toArray(null, true, true, true);
		
		// check "file" header
		// get checksum
		$checksum = md5($data[2]['A'].$data[3]['A'].$data[4]['A'].$data[5]['A']);
		if($data[1]['A'] != $checksum) {
			return false;	
		}
		
		// walk through rest of array
		$result = array();
		for($i=7; $i<=count($data); $i++) {
			
			// check values
			if(
				is_null($data[$i]['A']) ||	// place
				is_null($data[$i]['D']) ||	// agegroup
				is_null($data[$i]['E'])		// club
			) {
				return false;
			}
			
			// create result array
			$result[] = array(
					'agegroup' => $data[$i]['D'],
					'weightclass' => $data[$i]['C'],
					'name' => $data[$i]['B'],
					'place' => $data[$i]['A'],
					'club' => $data[$i]['E'],
			);
		}
		
		// set data
		$this->setResultStore($result);
		
		// everything done w/o errors
		return true;
	}
	
	
	/**
	 * getDataAsArray() returns the validated data as an array with the following structure:
	 * 'agegroup' => 'age group name',
	 * 'weightclass' => 'weightclass name' or null,
	 * 'name' => 'name of athlet'/'name of club group' or null,
	 * 'place' => place,
	 * 'club' => 'club name',
	 * 
	 * @return array array containing the validated data
	 */
	public function getDataAsArray() {
		
		// return data
		return $this->getResultStore();
	}
	
	
	/**
	 * returnFiletypes() returns the value of const filetypes
	 * 
	 * @return string allowed filetypes of this module
	 */
	public static function returnFiletypes() {
		
		// return data
		return self::filetypes;
	}
}