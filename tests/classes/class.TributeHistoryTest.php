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

class TributeHistoryTest extends PHPUnit_Framework_TestCase {
	
	// setup
	public function setUp() {
		
	}
	
	
	public function tearDown() {
		
	}
	
	
	public function testSetterGetter() {
		
		// create object
		$tributeHistory = new TributeHistory();
		
		// id
		$data = 1;
		
		$tributeHistory->setId($data);
		$this->assertEquals($data, $tributeHistory->getId());
		
		// tribute id
		$data = 1;
		
		$tributeHistory->setTributeId($data);
		$this->assertEquals($data, $tributeHistory->getTributeId());
		
		// history type id
		$data = 1;
		
		$tributeHistory->setType($data);
		$this->assertEquals($data, $tributeHistory->getType());
		
		// user id
		$data = 1;
		
		$tributeHistory->setHistoryUser($data);
		$this->assertEquals($data, $tributeHistory->getHistoryUser());

		// subject
		$data = 'Subject';
		
		$tributeHistory->setSubject($data);
		$this->assertEquals($data, $tributeHistory->getSubject());

		// content
		$data = 'Content';
		
		$tributeHistory->setContent($data);
		$this->assertEquals($data, $tributeHistory->getContent());
		
		// valid
		$data = 1;
		
		$tributeHistory->setValid($data);
		$this->assertEquals($data, $tributeHistory->getValid());
		
		// last modified
		$data = '2015-01-01 00:00';
		
		$tributeHistory->setLastModified($data);
		$this->assertEquals($data, $tributeHistory->getLastModified());
	}
	
	
	public function testConstruction() {
		
		$data = 'TributeHistory';
		$arg = array(
				'tributeId' => 1,
				'type' => 1,
				'userId' => 1,
				'subject' => 'Test entry',
				'content' => 'Test content',
				'valid' => '0',
			);
		
		// from array
		// instance of
		$tributeHistory = new $data($arg);
		$this->assertEquals($data, get_class($tributeHistory));
		
		// values
		$this->assertEquals(0, $tributeHistory->getId());
		$this->assertEquals($arg['tributeId'], $tributeHistory->getTributeId());
		$this->assertEquals(TributeHistory::getHistoryTypeById($arg['type']), $tributeHistory->getType());
		$this->assertEquals($arg['userId'], $tributeHistory->getHistoryUser()->get_id());
		$this->assertEquals($arg['subject'], $tributeHistory->getSubject());
		$this->assertEquals($arg['content'], $tributeHistory->getContent());
		$this->assertEquals($arg['valid'], $tributeHistory->getValid());
		
		// from database
		$data = 1;
		$tributeHistory = new TributeHistory($data);
		
		// values
		$this->assertEquals($data, $tributeHistory->getId());
		$this->assertEquals($arg['tributeId'], $tributeHistory->getTributeId());
		$this->assertEquals(TributeHistory::getHistoryTypeById($arg['type']), $tributeHistory->getType());
		$user = new User(false);
		$user->change_user($arg['userId'], false, 'id');
		$this->assertEquals($user, $tributeHistory->getHistoryUser());
		$this->assertEquals($arg['subject'], $tributeHistory->getSubject());
		$this->assertEquals($arg['content'], $tributeHistory->getContent());
		$this->assertEquals($arg['valid'], $tributeHistory->getValid());
	}
	
	
	public function testUpdateWriteDb() {
		
		// create holiday
		$id = 1;
		$tributeHistory = new TributeHistory($id);
		
		$orig = array(
				'tributeId' => 1,
				'type' => 1,
				'userId' => 1,
				'subject' => 'Test entry',
				'content' => 'Test content',
				'valid' => '0',
			);
		$update = array(
				'tributeId' => 2,
				'type' => 1,
				'userId' => 2,
				'subject' => 'Changed entry',
				'content' => 'Changed content',
				'valid' => '1',
			);
		
		// update
		$tributeHistory->update($update);
		$this->assertEquals($id, $tributeHistory->getId());
		$this->assertEquals($update['tributeId'], $tributeHistory->getTributeId());
		$this->assertEquals(TributeHistory::getHistoryTypeById($update['type']), $tributeHistory->getType());
		$user = new User(false);
		$user->change_user($update['userId'], false, 'id');
		$this->assertEquals($user, $tributeHistory->getHistoryUser());
		$this->assertEquals($update['subject'], $tributeHistory->getSubject());
		$this->assertEquals($update['content'], $tributeHistory->getContent());
		$this->assertEquals($update['valid'], $tributeHistory->getValid());
		// write
		$tributeHistory->writeDb();
		unset($tributeHistory);
		
		// get data again
		$tributeHistory = new TributeHistory($id);
		$this->assertEquals($id, $tributeHistory->getId());
		$this->assertEquals($update['tributeId'], $tributeHistory->getTributeId());
		$this->assertEquals(TributeHistory::getHistoryTypeById($update['type']), $tributeHistory->getType());
		$user = new User(false);
		$user->change_user($update['userId'], false, 'id');
		$this->assertEquals($user, $tributeHistory->getHistoryUser());
		$this->assertEquals($update['subject'], $tributeHistory->getSubject());
		$this->assertEquals($update['content'], $tributeHistory->getContent());
		$this->assertEquals($update['valid'], $tributeHistory->getValid());
		
		// reset and cleanup
		$tributeHistory->update($orig);
		$tributeHistory->writeDb();
	}
	
	public function testDelete() {
		
		// save auto increment value
		$autoIncrement = TestDb::getAutoincrement('tribute_history');
		
		$new = array(
				'tributeId' => 2,
				'type' => 1,
				'userId' => 1,
				'subject' => 'Another entry',
				'content' => 'Another content',
				'valid' => '1',
			);;
		
		$tributeHistory = new TributeHistory($new);
		$newId = $tributeHistory->writeDb();
		TributeHistory::delete($newId);
		
		$tributeHistory = new TributeHistory($newId);
		$this->assertTrue(is_null($tributeHistory->getTributeId()));
		
		// reset auto increment value
		TestDb::resetAutoincrement('tribute_history', $autoIncrement);
	}
	
	
	public function testGetTypeById() {
		
		// not existing should give id = 1; name = Default Type
		$this->assertEquals(array('id' => 1, 'name' => 'Default Type'), TributeHistory::getHistoryTypeById(-99999));
		
		// id 1
		$this->assertEquals(array('id' => 1, 'name' => 'Default Type'), TributeHistory::getHistoryTypeById(1));
		
		// id -1
		$this->assertEquals(array('id' => -1, 'name' => _l('System entry')), TributeHistory::getHistoryTypeById(-1));
		
	}
	
	
	public function testFactoryInsert() {
		
		// save auto increment value
		$autoIncrement = TestDb::getAutoincrement('tribute_history');
		
		// prepare data
		$data = array(
				'tributeId' => 1,
				'type' => 1,
				'userId' => 1,
				'subject' => 'Test entry',
				'content' => 'Test content',
				'valid' => '0',
			);
		$tributeHistory = new TributeHistory($data);
		
		// test
		$factoryHistory = TributeHistory::factoryInsert($data);
		$id = $factoryHistory['data']->getId();
		$tributeHistory->setId($id);
		$this->assertEquals(
			array(
					'result' => 'OK',
					'data' => $tributeHistory,
				),
			$factoryHistory
		);
		TributeHistory::delete($id);
		
		// change data and test
		$data['tributeId'] = false;
		$this->assertEquals(
			array(
					'result' => 'ERROR',
					'data' => _l('ERROR').': '._l('missing data'),
				),
			TributeHistory::factoryInsert($data)
		);
		
		
		// reset auto increment value
		TestDb::resetAutoincrement('tribute_history', $autoIncrement);
	}
	
}

?>
