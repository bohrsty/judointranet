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

class TributeTest extends PHPUnit_Framework_TestCase {
	
	// setup
	public function setUp() {
		
	}
	
	
	public function tearDown() {
		
	}
	
	
	public function testSetterGetter() {
		
		// create object
		$tribute = new Tribute();
		
		// id
		$data = 1;
		
		$tribute->setId($data);
		$this->assertEquals($data, $tribute->getId());
		
		// name
		$data = 'Name';
		
		$tribute->setName($data);
		$this->assertEquals($data, $tribute->getName());
		
		// year
		$data = '2015';
		
		$tribute->setYear($data);
		$this->assertEquals($data, $tribute->getYear());
		
		// startDate
		$data = '2015-01-01';
		
		$tribute->setStartDate($data);
		$this->assertEquals($data, $tribute->getStartDate());

		// plannedDate
		$data = '2015-12-31';
		
		$tribute->setPlannedDate($data);
		$this->assertEquals($data, $tribute->getPlannedDate());

		// date
		$data = '2016-12-31';
		
		$tribute->setDate($data);
		$this->assertEquals($data, $tribute->getDate());
		
		// testimonialId
		$data = 1;
		
		$tribute->setTestimonialId($data);
		$this->assertEquals($data, $tribute->getTestimonialId());
		
		// description
		$data = 'Description';
		
		$tribute->setDescription($data);
		$this->assertEquals($data, $tribute->getDescription());
	}
	
	
	public function testConstruction() {
		
		$data = 'Tribute';
		$arg = array(
				'name' => 'Bob Builder',
				'startDate' => '1970-01-01',
				'plannedDate' => '1970-02-01',
				'date' => '1970-12-31',
				'testimonialId' => 1,
				'description' => 'Tribute for everything',
				'valid' => '0',
			);
		
		// from array
		// instance of
		$tribute = new $data($arg);
		$this->assertEquals($data, get_class($tribute));
		
		// values
		$this->assertEquals(0, $tribute->getId());
		$this->assertEquals($arg['name'], $tribute->getName());
		$this->assertEquals(date('Y', strtotime($arg['date'])), $tribute->getYear());
		$this->assertEquals($arg['startDate'], $tribute->getStartDate());
		$this->assertEquals($arg['plannedDate'], $tribute->getPlannedDate());
		$this->assertEquals($arg['date'], $tribute->getDate());
		$this->assertEquals($arg['testimonialId'], $tribute->getTestimonialId());
		$this->assertEquals($arg['description'], $tribute->getDescription());
		$this->assertEquals($arg['valid'], $tribute->getValid());
		
		// from database
		$data = 1;
		$tribute = new Tribute($data);
		
		// values
		$this->assertEquals($data, $tribute->getId());
		$this->assertEquals($arg['name'], $tribute->getName());
		$this->assertEquals(date('Y', strtotime($arg['date'])), $tribute->getYear());
		$this->assertEquals($arg['startDate'], $tribute->getStartDate());
		$this->assertEquals($arg['plannedDate'], $tribute->getPlannedDate());
		$this->assertEquals($arg['date'], $tribute->getDate());
		$this->assertEquals($arg['testimonialId'], $tribute->getTestimonialId());
		$this->assertEquals($arg['description'], $tribute->getDescription());
		$this->assertEquals($arg['valid'], $tribute->getValid());
		
		// test year
		unset($tribute);
		// start date
		$arg = array(
				'name' => 'Bob Builder',
				'startDate' => '1970-01-01',
				'testimonialId' => 1,
				'description' => 'Tribute for everything',
				'valid' => '0',
		);
		
		$tribute = new Tribute($arg);
		$this->assertEquals(date('Y', strtotime($tribute->getStartDate())), $tribute->getYear());
		
		// planned date
		unset($tribute);
		$arg['plannedDate'] = '1971-01-01';
		
		$tribute = new Tribute($arg);
		$this->assertEquals(date('Y', strtotime($tribute->getPlannedDate())), $tribute->getYear());
		
		// date
		unset($tribute);
		$arg['date'] = '1972-01-01';
		
		$tribute = new Tribute($arg);
		$this->assertEquals(date('Y', strtotime($tribute->getDate())), $tribute->getYear());
	}
	
	
	public function testUpdateWriteDb() {
		
		// create holiday
		$id = 1;
		$tribute = new Tribute($id);
		
		$orig = array(
				'name' => 'Bob Builder',
				'startDate' => '1970-01-01',
				'plannedDate' => '1970-02-01',
				'date' => '1970-12-31',
				'testimonialId' => 1,
				'description' => 'Tribute for everything',
				'valid' => '0',
			);
		$update = array(
				'name' => 'Paul Panzer',
				'startDate' => '1971-01-01',
				'plannedDate' => '1971-02-01',
				'date' => '1971-12-31',
				'testimonialId' => 2,
				'description' => 'Tribute for something else',
				'valid' => '1',
			);
		
		// update
		$tribute->update($update);
		$this->assertEquals($id, $tribute->getId());
		$this->assertEquals($update['name'], $tribute->getName());
		$this->assertEquals(date('Y', strtotime($update['date'])), $tribute->getYear());
		$this->assertEquals($update['startDate'], $tribute->getStartDate());
		$this->assertEquals($update['plannedDate'], $tribute->getPlannedDate());
		$this->assertEquals($update['date'], $tribute->getDate());
		$this->assertEquals($update['testimonialId'], $tribute->getTestimonialId());
		$this->assertEquals($update['description'], $tribute->getDescription());
		$this->assertEquals($update['valid'], $tribute->getValid());
		// write
		$tribute->writeDb();
		unset($tribute);
		
		// get data again
		$tribute = new Tribute($id);
		$this->assertEquals($id, $tribute->getId());
		$this->assertEquals($update['name'], $tribute->getName());
		$this->assertEquals(date('Y', strtotime($update['date'])), $tribute->getYear());
		$this->assertEquals($update['startDate'], $tribute->getStartDate());
		$this->assertEquals($update['plannedDate'], $tribute->getPlannedDate());
		$this->assertEquals($update['date'], $tribute->getDate());
		$this->assertEquals($update['testimonialId'], $tribute->getTestimonialId());
		$this->assertEquals($update['description'], $tribute->getDescription());
		$this->assertEquals($update['valid'], $tribute->getValid());
		
		// reset and cleanup
		$tribute->update($orig);
		$tribute->writeDb();
		
		// test year
		unset($tribute);
		// start date
		$tribute = new Tribute($id);
		$tribute->update($update);
		$this->assertEquals(date('Y', strtotime($tribute->getStartDate())), $tribute->getYear());
		
		// planned date
		unset($tribute);
		$arg['plannedDate'] = '1971-01-01';
		
		$tribute = new Tribute($id);
		$tribute->update($update);
		$tribute->update($arg);
		$this->assertEquals(date('Y', strtotime($tribute->getPlannedDate())), $tribute->getYear());
		
		// date
		unset($tribute);
		$arg['date'] = '1972-01-01';
		
		$tribute = new Tribute($id);
		$tribute->update($update);
		$tribute->update($arg);
		$this->assertEquals(date('Y', strtotime($tribute->getDate())), $tribute->getYear());
	}
	
	public function testDelete() {
		
		// save auto increment value
		$autoIncrement = TestDb::getAutoincrement('tribute');
		
		$new = array(
				'name' => 'Paul Panzer',
				'startDate' => '1971-01-01',
				'plannedDate' => '1971-02-01',
				'date' => '1971-12-31',
				'testimonialId' => 2,
				'description' => 'Tribute for something else',
				'valid' => '1',
			);
		
		$tribute = new Tribute($new);
		$newId = $tribute->writeDb();
		Tribute::delete($newId);
		
		$tribute = new Tribute($newId);
		$this->assertTrue(is_null($tribute->getName()));
		
		// reset auto increment value
		TestDb::resetAutoincrement('tribute', $autoIncrement);
	}
	
	
	public function testGetAllYearsTestimonials() {
		
		// years
		$this->assertTrue(is_array(Tribute::getAllYears()));
		
		// testimonials
		$this->assertTrue(is_array(Tribute::getAllTestimonials()));
	}
	
	
	public function testGetAllHistory() {
		
		// is array
		$this->assertTrue(is_array(Tribute::getAllHistory(1)));
		
		// all: first is test history entry
		$history = new TributeHistory(1);
		$allHistory = Tribute::getAllHistory(1);
		$this->assertEquals($history, $allHistory[0]);
		
		// valid only: first is not test history entry
		$allHistory = Tribute::getAllHistory(1, true);
		if(count($allHistory) == 0) {
			$allHistory[0] = null;
		}
		$this->assertNotEquals($history, $allHistory[0]);
	}
	
}

?>
