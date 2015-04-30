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

class TributeFileTest extends PHPUnit_Framework_TestCase {
	
	// setup
	public function setUp() {
		
	}
	
	
	public function tearDown() {
		
	}
	
	
	public function testSetterGetter() {
		
		// create object
		$tributeFile = new TributeFile();
		
		// id
		$data = 1;
		
		$tributeFile->setId($data);
		$this->assertEquals($data, $tributeFile->getId());
		
		// tribute id
		$data = 1;
		
		$tributeFile->setTributeId($data);
		$this->assertEquals($data, $tributeFile->getTributeId());
		
		// file type id
		$data = 1;
		
		$tributeFile->setType($data);
		$this->assertEquals($data, $tributeFile->getType());
		
		// filename
		$data = 'abcdefghijklmnopqrstuvwxyzabcdef_01234567890.pdf';
		
		$tributeFile->setFilename($data);
		$this->assertEquals($data, $tributeFile->getFilename());

		// name
		$data = 'Name';
		
		$tributeFile->setName($data);
		$this->assertEquals($data, $tributeFile->getName());

		// valid
		$data = 1;
		
		$tributeFile->setValid($data);
		$this->assertEquals($data, $tributeFile->getValid());
		
		// last modified
		$data = '2015-01-01 00:00';
		
		$tributeFile->setLastModified($data);
		$this->assertEquals($data, $tributeFile->getLastModified());
	}
	
	
	public function testConstruction() {
		
		$data = 'TributeFile';
		$arg = array(
				'tributeId' => 1,
				'type' => 1,
				'filename' => 'faa72be0554401021cfa6dd60bdd5dac_1430198346.pdf',
				'name' => 'Test file',
				'valid' => '0',
				'lastModified' => date('Y-m-d H:i:s'),
			);
		
		// from array
		// instance of
		$tributeFile = new $data($arg);
		$this->assertEquals($data, get_class($tributeFile));
		
		// values
		$this->assertEquals(0, $tributeFile->getId());
		$this->assertEquals($arg['tributeId'], $tributeFile->getTributeId());
		$this->assertEquals(TributeFile::getFiletypeById($arg['type']), $tributeFile->getType());
		$this->assertEquals($arg['filename'], $tributeFile->getFilename());
		$this->assertEquals($arg['name'], $tributeFile->getName());
		$this->assertEquals($arg['valid'], $tributeFile->getValid());
		
		// from database
		$data = 1;
		$tributeFile = new TributeFile($data);
				
		// values
		$this->assertEquals($data, $tributeFile->getId());
		$this->assertEquals($arg['tributeId'], $tributeFile->getTributeId());
		$this->assertEquals(TributeFile::getFiletypeById($arg['type']), $tributeFile->getType());
		$this->assertEquals($arg['filename'], $tributeFile->getFilename());
		$this->assertEquals($arg['name'], $tributeFile->getName());
		$this->assertEquals($arg['valid'], $tributeFile->getValid());
	}
	
	
	public function testUpdateWriteDb() {
		
		// create holiday
		$id = 1;
		$tributeFile = new TributeFile($id);
		
		$orig = array(
				'tributeId' => 1,
				'type' => 1,
				'filename' => 'faa72be0554401021cfa6dd60bdd5dac_1430198346.pdf',
				'name' => 'Test file',
				'valid' => '0',
			);
		$update = array(
				'tributeId' => 2,
				'type' => 1,
				'filename' => 'faa72bfa6dd60bdd5dace0554401021c_1430198346.pdf',
				'name' => 'Changed file',
				'valid' => '1',
			);
		
		// update
		$tributeFile->update($update);
		$this->assertEquals($id, $tributeFile->getId());
		$this->assertEquals($update['tributeId'], $tributeFile->getTributeId());
		$this->assertEquals(TributeFile::getFiletypeById($update['type']), $tributeFile->getType());
		$this->assertEquals($update['filename'], $tributeFile->getFilename());
		$this->assertEquals($update['name'], $tributeFile->getName());
		$this->assertEquals($update['valid'], $tributeFile->getValid());
		// write
		$tributeFile->writeDb();
		unset($tributeFile);
		
		// get data again
		$tributeFile = new TributeFile($id);
		$this->assertEquals($id, $tributeFile->getId());
		$this->assertEquals($update['tributeId'], $tributeFile->getTributeId());
		$this->assertEquals(TributeFile::getFiletypeById($update['type']), $tributeFile->getType());
		$this->assertEquals($update['filename'], $tributeFile->getFilename());
		$this->assertEquals($update['name'], $tributeFile->getName());
		$this->assertEquals($update['valid'], $tributeFile->getValid());
		
		// reset and cleanup
		$tributeFile->update($orig);
		$tributeFile->writeDb();
	}
	
	public function testDelete() {
		
		// save auto increment value
		$autoIncrement = TestDb::getAutoincrement('tribute_file');
		
		$new = array(
				'tributeId' => 2,
				'type' => 1,
				'filename' => 'faa72bfa6dd60bdd5dace0554401021c_1430198346.pdf',
				'name' => 'Another file',
				'valid' => '1',
			);;
		
		$tributeFile = new TributeFile($new);
		$newId = $tributeFile->writeDb();
		TributeFile::delete($newId);
		
		$tributeFile = new TributeFile($newId);
		$this->assertTrue(is_null($tributeFile->getTributeId()));
		
		// reset auto increment value
		TestDb::resetAutoincrement('tribute_file', $autoIncrement);
	}
	
	
	public function testGetTypeById() {
		
		// not existing should give id = 1; name = Default Type
		$this->assertEquals(array('id' => 1, 'name' => 'Default Type'), TributeFile::getFiletypeById(-99999));
		
		// id 1
		$this->assertEquals(array('id' => 1, 'name' => 'Default Type'), TributeFile::getFiletypeById(1));
		
		// id -1
		$this->assertEquals(array('id' => -1, 'name' => _l('File')), TributeFile::getFiletypeById(-1));
		
	}
	
}

?>
