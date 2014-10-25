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

class ResultImporterTest extends PHPUnit_Framework_TestCase {
	
	// variables
	private $fileName;
	private $fileContent;
	
	// setup
	public function setUp() {
		
		// set variables
		$this->fileName = '../../tmp/tempfile';
		$this->fileContent = 'some;very;important;result;data
some;very;important;result;data
some;very;important;result;data';
		
		// write to file
		$fp = fopen($this->fileName, 'w');
		fwrite($fp, $this->fileContent);
		fclose($fp);
	}
	
	
	public function tearDown() {
		
		// remove file
		unlink($this->fileName);
	}
	
	
	public function testUserSetterGetter() {
		
		// get file object
		$resultImporter = new ResultImporter();
		
		// fileContent
		$data = array();
		
		$resultImporter->setFileContent($data);
		$this->assertEquals($data, $resultImporter->getFileContent());
		
		// fileContent
		$data = null;
		
		$resultImporter->setResultStore($data);
		$this->assertEquals($data, $resultImporter->getResultStore());
		
		
	}
	
	
	public function testFactory() {
		
		// prepare data
		$type = '';
		$fileContent = file($this->fileName, FILE_IGNORE_NEW_LINES);
		
		// call factory empty type
		$importer = ResultImporter::factory($this->fileName, $type);
		
		$this->assertInstanceOf('ResultImporter', $importer);
		$this->assertEquals($fileContent, $importer->getFileContent());
		$this->assertTrue(is_array($importer->getResultStore()));
		
		// call factory with non-existing type (object should be of class ResultImporter)
		$type = 'Testtype';
		
		$importer = ResultImporter::factory($this->fileName, $type);
		
		$this->assertInstanceOf('ResultImporter', $importer);
		$this->assertEquals($fileContent, $importer->getFileContent());
		$this->assertTrue(is_array($importer->getResultStore()));
		
	}
	
}
?>
