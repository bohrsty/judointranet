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

class FileTest extends PHPUnit_Framework_TestCase {
	
	// setup
	public function setUp() {
		
	}
	
	
	public function tearDown() {
		
	}
	
	
	public function testUserSetterGetter() {
		
		// get file object
		$file = new File();
		
		$this->assertEquals(0, $file->getId());
		
		// id
		$data = 1;
		
		$file->setId($data);
		$this->assertEquals($data, $file->getId());
		
		// name
		$data = 'Name of file';
		
		$file->setName($data);
		$this->assertEquals($data, $file->getName());
		
		// fileType
		$data = 1;
		
		$file->setFileType($data);
		$this->assertEquals($data, $file->getFileType());
		$fileTypeName = TestObject::lang($file->getFileTypeAs('mimetype'));
		
		$this->assertEquals('Textdokument', $file->getFileTypeAs('name'));
		$this->assertEquals('text/plain', $file->getFileTypeAs('mimetype'));
		$this->assertEquals('txt', $file->getFileTypeAs('extension'));
		
		// filename
		$data = 'very_comlicated_filename';
		
		$file->setFilename($data);
		$this->assertEquals($data, $file->getFilename());
		
		// content
		$rootPath = dirname(__FILE__).'/../../';
		$testFilename = $rootPath.'LICENSE';
		$fp = fopen($testFilename, 'rb');
		$data = fread($fp, filesize($testFilename));
		fclose($fp);
		
		$file->setContent($data);
		$this->assertEquals($data, $file->getContent());
		
		// cached
		$data1 = null;
		$table = 'protocol';
		$tableId = 1;
		
		// is not cached
		$file->setCached($data1);
		$this->assertEquals($data1, $file->getCached());
		$this->assertFalse($file->isCached());
		
		// is cached
		$file->setCached($table, $tableId);
		$this->assertEquals(array('table' => $table, 'tableId' => $tableId), $file->getCached(false));
		$this->assertEquals($table.'|'.$tableId, $file->getCached());
		$this->assertTrue($file->isCached());
		
		// valid
		$data = 1;
		
		$file->setValid($data);
		$this->assertEquals($data, $file->getValid());
		
		// id from cache
		$this->assertFalse(File::idFromCache('protocol|-1'));
	}
	
	
	public function testFactory() {
		
		// get file content
		$rootPath = dirname(__FILE__).'/../../';
		$testFilename = $rootPath.'LICENSE';
		$fp = fopen($testFilename, 'rb');
		$filecontent = fread($fp, filesize($testFilename));
		$mimetype = mime_content_type($fp);
		fclose($fp);
		
		// create File object for uploaded content
		$name = 'Name of file';
		$data = array(	'name' => $name, 
						'filename' => basename($testFilename),
						'mimetype' => $mimetype,
						'content' => $filecontent,
						'cached' => null,
						'valid' => 1,);
		$file = File::factory($data);
		
		$this->assertInstanceOf('File', $file);
		$this->assertEquals(0, $file->getId());
		$this->assertEquals($name, $file->getName());
		$this->assertEquals(basename($testFilename), $file->getFilename());
		$this->assertEquals(File::mimetypeToId($mimetype), $file->getFileType());
		$this->assertEquals($filecontent, $file->getContent());
		$this->assertEquals(1, $file->getValid());
	}
	
	
	public function testWriteDbAndConstruction() {
		
		// get file content
		$rootPath = dirname(__FILE__).'/../../';
		$testFilename = $rootPath.'MIT.txt';
		$fp = fopen($testFilename, 'rb');
		$filecontent = fread($fp, filesize($testFilename));
		$mimetype = mime_content_type($fp);
		fclose($fp);
		
		// create File object for uploaded content
		$name = 'MIT License';
		$data = array(	'name' => $name, 
						'filename' => basename($testFilename),
						'mimetype' => $mimetype,
						'content' => $filecontent,
						'cached' => null,
						'valid' => 0,);
		
		// check if file exists
		if(!Page::exists('file', 1)) {
			
			// create file object from factory
			$file = File::factory($data);
			
			// write to db
			$file->writeDb();
		} else {
			
			// get object from existing id
			$file = new File(1);
			
			// update
			$file->update($data);
			$file->writeDb();
		}
		
		$this->assertEquals(1, $file->getId());
		$this->assertEquals($name, $file->getName());
		$this->assertEquals(1, $file->getFileType());
		$this->assertEquals(basename($testFilename), $file->getFilename());
		$this->assertEquals($filecontent, $file->getContent());
		$this->assertFalse($file->isCached());
	}
	
	
	public function testDetails() {
		
		// get object
		$file = new File(1);
		$details = $file->details();
		$fileType = TestObject::lang(str_replace('/', '_', $file->getFileTypeAs('mimetype')));
		
		// assert
		$this->assertEquals('MIT License', $details['value'][0]);
		$this->assertEquals($fileType, $details['value'][1]);
		$this->assertEquals('MIT.txt', $details['value'][2]);
	}
	
	
	public function testExtensions() {
		
		// filetype
		$this->assertInternalType('int', strpos(File::allowedFileTypes(), 'txt'));
	}
	
	
	public function testDelete() {
		
		// get file content
		$rootPath = dirname(__FILE__).'/../../';
		$testFilename = $rootPath.'MIT.txt';
		$fp = fopen($testFilename, 'rb');
		$filecontent = fread($fp, filesize($testFilename));
		$mimetype = mime_content_type($fp);
		fclose($fp);
		
		// create File object for uploaded content
		$name = 'MIT License';
		$data = array(	'name' => $name, 
						'filename' => basename($testFilename),
						'mimetype' => $mimetype,
						'content' => $filecontent,
						'cached' => null,
						'valid' => 0,);
		
		// update file
		$file = new File(1);
		
		// update
		$file->update($data);
		$file->writeDb();
		
		// delete file
		File::delete(1);
		
		$this->assertFalse(Page::exists('file', 1));
		
		// recreate deleted file
		$file = new File(1);
		
		// update
		$file->update($data);
		$file->writeDb();
	}
	
	
	public function testAttachments() {
		
		// check if test entries still in database
		$this->assertEquals(0, count(File::attachedTo('testEntry', 1)));
		$this->assertEquals(0, count(File::attachedTo('testEntry', 2)));
		
		// attach some files
		$files1 = array(1,2,3,5);
		$files2 = array(1,3,4,6);
		File::attachFiles('testEntry', 1, $files1);
		File::attachFiles('testEntry', 2, $files2);
		// check entries
		$this->assertEquals(4, count(File::attachedTo('testEntry', 1)));
		$this->assertEquals(4, count(File::attachedTo('testEntry', 2)));
		
		// remove all for file 2
		File::deleteAllAttachments(1);
		// check entries
		$this->assertEquals(3, count(File::attachedTo('testEntry', 1)));
		$this->assertEquals(3, count(File::attachedTo('testEntry', 2)));
		
		// remove rest
		File::deleteAttachedFiles('testEntry', 1);
		File::deleteAttachedFiles('testEntry', 2);
		
		// check if no more entries
		$this->assertEquals(0, count(File::attachedTo('testEntry', 1)));
		$this->assertEquals(0, count(File::attachedTo('testEntry', 2)));
	}
	
}
?>
