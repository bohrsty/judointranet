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

class ResultImporterMm5exportTest extends PHPUnit_Framework_TestCase {
	
	// variables
	private $fileName;
	private $fileContent;
	
	// setup
	public function setUp() {
		
		// set variables
		$this->fileName = '../../tmp/tempfile';
		$this->fileContent = '
Ergebnisse


Ergebnisliste <eventname> am <date> in <city>
<agegroup1>
-12 kg	4 Teilnehmer
1. Platz	<name1>	<year>	<club1>	<...>
2. Platz	<name2>	<year>	<club2>	<...>
3. Platz	<name3>	<year>	<club3>	<...>
3. Platz	<name4>	<year>	<club4>	<...>
-23 kg	4 Teilnehmer
1. Platz	<name1>	<year>	<club1>	<...>
2. Platz	<name2>	<year>	<club2>	<...>
3. Platz	<name3>	<year>	<club3>	<...>
3. Platz	<name4>	<year>	<club4>	<...>
Alle TN	0 Teilnehmer
<agegroup2>
-12 kg	4 Teilnehmer
1. Platz	<name1>	<year>	<club1>	<...>
2. Platz	<name2>	<year>	<club2>	<...>
3. Platz	<name3>	<year>	<club3>	<...>
3. Platz	<name4>	<year>	<club4>	<...>
-23 kg	4 Teilnehmer
1. Platz	<name1>	<year>	<club1>	<...>
2. Platz	<name2>	<year>	<club2>	<...>
3. Platz	<name3>	<year>	<club3>	<...>
3. Platz	<name4>	<year>	<club4>	<...>
Alle TN	0 Teilnehmer

';
		
		// write to file
		$fp = fopen($this->fileName, 'w');
		fwrite($fp, $this->fileContent);
		fclose($fp);
	}
	
	
	public function tearDown() {
		
		// remove file
		unlink($this->fileName);
	}
	
	
	public function testFactory() {
		
		// prepare data
		$type = 'mm5export';
		$fileContent = file($this->fileName, FILE_IGNORE_NEW_LINES);
		
		// call factory type given
		$importer = ResultImporter::factory($this->fileName, $type);
		
		$type = ucfirst(strtolower($type));
		$this->assertInstanceOf('ResultImporter'.$type, $importer);
		$this->assertEquals($fileContent, $importer->getFileContent());
		$this->assertTrue(is_array($importer->getResultStore()));
		
	}
	
	
	public function testAnalyzeFormat() {
		
		// prepare data
		$type = 'mm5export';
		$fileContent = file($this->fileName, FILE_IGNORE_NEW_LINES);
		
		// call factory type given
		$importer = ResultImporter::factory($this->fileName, $type);
		
		$type = ucfirst(strtolower($type));
		$this->assertInstanceOf('ResultImporter'.$type, $importer);
		$this->assertEquals($fileContent, $importer->getFileContent());
		$this->assertTrue(is_array($importer->getResultStore()));
		
		// validate file format
		$this->assertTrue($importer->validate());
		
		// test data from resultstore
		$data = $importer->getDataAsArray();
		$this->assertEquals(16, count($data));		
		$this->assertEquals($fileContent[7],
				$data[0]['place'].". Platz\t".$data[0]['name']."\t<year>\t".$data[0]['club']."\t<...>"
			);
		$this->assertEquals($fileContent[13],
				$data[5]['place'].". Platz\t".$data[5]['name']."\t<year>\t".$data[5]['club']."\t<...>"
			);
		for($i = 12; $i <= 15; $i++) {
			$this->assertEquals($fileContent[17], $data[$i]['agegroup']);
		}
		for($i = 12; $i <= 15; $i++) {
			$this->assertEquals(substr($fileContent[23], 0, 3), $data[$i]['weightclass']);
		}
	}
	
}
?>
