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
 * class ResultImporterMm5export implements the representation of a result importer object
 * for MM5 txt export
 */
class ResultImporterMm5export extends ResultImporter implements ResultImporterInterface {
	
	/*
	 * class-variables
	 */
	const filetypes = 'txt';
	
	
	/*
	 * getter/setter
	 */
	public function setFileContent($fileContent){
		
		// set file content
		parent::setFileContent(file($fileContent, FILE_IGNORE_NEW_LINES));
	}
	
	
	/*
	 * constructor/destructor
	 */
	public function __construct() {
		
		// parent constructor
		parent::__construct();
		
		// set team or single
		$this->setIsTeam(false);
		
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
		 * file format:
		 * 
		 * <\n>
		 * Ergebnisse< >
		 * <\n>
		 * <\n>
		 * Ergebnisliste< ><eventname>< >am< ><date>< >in< ><city>
		 *  _
		 * |<agegroup>
		 * |  _
		 * | |<weightclass>< >kg<\t><num>< >Teilnehmer
		 * | |  _
		 * | | |<place>.< >Platz<\t><name><\t><year><\t><club><\t><...>
		 * | | |_
		 * | |_
		 * | Alle< >TN<\t><num>< >Teilnehmer
		 * |_
		 * <\n>
		 * <\n>
		 * 
		 * where:
		 * <\n> = linebreak
		 * < > = whitespace
		 * <\t> = tabulator
		 * <num> = number
		 * lines between single _ in row, connected with | could be repeated
		 */
		
		// get file content
		$file = $this->getFileContent();
		
		// check "file" header
		if(
			$file[0] != '' ||
			trim($file[1]) != 'Ergebnisse' ||
			$file[2] != '' ||
			$file[3] != '' ||
			$file[4] == ''
		) {
			return false;	
		}
		
		// walk through lines to get agegroup positions
		$agegroupPos = array(5,);
		for($i = 5; $i < count($file); $i++) {
			
			// recode UTF-8
			$file[$i] = utf8_encode($file[$i]);
			
			if(preg_match('/^Alle TN\t\d+ Teilnehmer$/', $file[$i]) > 0) {
				$agegroupPos[] = $i+1;
			}
		}
		
		// slice array into agegroup arrays
		$agegroupArrays = array();
		for($i = 0; $i < count($agegroupPos)-1; $i++) {
			
			// get length
			$length = $agegroupPos[$i+1]-$agegroupPos[$i]-1;
			
			// check if key exists
			if(isset($file[$agegroupPos[$i]])) {
				$agegroupArrays[] = array_slice($file, $agegroupPos[$i], $length, false);
			} else {
				return false;
			}
		}
		
		// walk through agegroups to extract weightclasses
		$result = array();
		foreach($agegroupArrays as $agegroup) {
			
			// get agegroup name
			$agegroupName = $agegroup[0];
			
			// get first weightclass
			preg_match('/^([\+|\-]\d{1,3}(,\d{1,2})?) kg\t.*/', $agegroup[1], $matches);
			// check weightclass
			if(!isset($matches[1]) || $matches[1] == '') {
				return false;
			} else {
				$weightclass = $matches[1];
			}
			
			// walk through rest and get result
			for($i = 2; $i < count($agegroup); $i++) {
				
				// explode on \t
				$line = preg_split('/\t/', $agegroup[$i]);
				
				// check for next weightclass
				if(preg_match('/^([\+|\-]\d{1,3}(,\d{1,2})?) kg/', $line[0], $matches) > 0) {
					
					// check weightclass
					if(!isset($matches[1]) || $matches[1] == '') {
						return false;
					} else {
						$weightclass = $matches[1];
					}
				} else {
					
					// get place
					if(preg_match('/[0-9]/', substr($line[0], 0, 1))) {
						$place = substr($line[0], 0, 1);
					} else {
						$place = substr($line[0], 0, 2);
					}
					
					// check values
					if(
							(!isset($agegroupName) || is_null($agegroupName) || $agegroupName == '') ||
							(!isset($weightclass) || is_null($weightclass) || $weightclass == '') ||
							(!isset($line[1]) || is_null($line[1]) || $line[1] == '') ||
							(!isset($place) || is_null($place) || $place == '') ||
							(!isset($line[3]) || is_null($line[3]) || $line[3] == '')
					) {
						return false;
					}
					
					// create result array
					$result[] = array(
							'agegroup' => $agegroupName,
							'weightclass' => $weightclass,
							'name' => $line[1],
							'place' => $place,
							'club' => $line[3],
						);
				}
			}
		}
		
		// set data
		$this->setResultStore($result);
		
		// everything done w/o errors
		return true;
	}
	
	
	/**
	 * getDataAsArray() returns the validated data as an array with the following structure:
	 * 'agegroup' => 'age group name',
	 * 'weightclass' => 'weightclass name',
	 * 'name' => 'name of athlet',
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