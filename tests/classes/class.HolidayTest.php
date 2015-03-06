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

class HolidayTest extends PHPUnit_Framework_TestCase {
	
	// variables
	
	// setup
	public function setUp() {
		
	}
	
	
	public function tearDown() {
		
	}
	
	
	public function testConstruction() {
		
		$data = 'Holiday';
		$arg = array(
				'name' => 'Test Holiday',
				'date' => '1970-01-01',
				'endDate' => '1970-01-02',
				'year' => '1970',
				'valid' => '0',
			);
		
		// from array
		// instance of
		$holiday = new $data($arg);
		$this->assertEquals($data, get_class($holiday));
		
		// values
		$this->assertNull($holiday->getId());
		$this->assertEquals($arg['name'], $holiday->getName());
		$this->assertEquals($arg['date'], $holiday->getDate());
		$this->assertEquals($arg['endDate'], $holiday->getEndDate());
		$this->assertEquals($arg['year'], $holiday->getYear());
		$this->assertEquals($arg['valid'], $holiday->getValid());
		
		// from database
		$name = 'Test Holiday';
		$year = '1970';
		$holiday = new Holiday($name, $year);
		$this->assertEquals($arg['name'], $holiday->getName());
		$this->assertEquals($arg['date'], $holiday->getDate());
		$this->assertEquals($arg['endDate'], $holiday->getEndDate());
		$this->assertEquals($arg['year'], $holiday->getYear());
		$this->assertEquals($arg['valid'], $holiday->getValid());
	}
	
	
	public function testHolidaySetterGetter() {
		
		// create holiday
		$name = 'Test Holiday';
		$year = '1970';
		$holiday = new Holiday($name, $year);;
		
		// name
		$data = 'Different Holiday';
		$holiday->setName($data);
		$this->assertEquals($data, $holiday->getName());
		
		// date
		$data = '1970-12-30';
		$holiday->setDate($data);
		$this->assertEquals($data, $holiday->getDate());
		
		// endDate
		$data = '1970-12-31';
		$holiday->setEndDate($data);
		$this->assertEquals($data, $holiday->getEndDate());
		
		// year
		$data = '1971';
		$holiday->setYear($data);
		$this->assertEquals($data, $holiday->getYear());
		
		// valid
		$data = '1';
		$holiday->setValid($data);
		$this->assertEquals($data, $holiday->getValid());
	}
	
	
	public function testUpdateWriteDb() {
		
		// create holiday
		$name = 'Test Holiday';
		$year = '1970';
		$holiday = new Holiday($name, $year);
		
		$orig = array(
				'name' => 'Test Holiday',
				'date' => '1970-01-01',
				'endDate' => '1970-01-02',
				'year' => '1970',
				'valid' => '0',
			);
		$update = array(
				'name' => 'Different Holiday',
				'date' => '1970-12-30',
				'endDate' => '1970-12-31',
				'year' => '1971',
				'valid' => '1',
			);
		
		// update
		$holiday->update($update);
		$this->assertEquals($update['name'], $holiday->getName());
		$this->assertEquals($update['date'], $holiday->getDate());
		$this->assertEquals($update['endDate'], $holiday->getEndDate());
		$this->assertEquals($update['year'], $holiday->getYear());
		$this->assertEquals($update['valid'], $holiday->getValid());
		// write
		$holiday->writeDb();
		unset($holiday);
		
		// get data again
		$name = 'Different Holiday';
		$year = '1971';
		$holiday = new Holiday($name, $year);
		$this->assertEquals($update['name'], $holiday->getName());
		$this->assertEquals($update['date'], $holiday->getDate());
		$this->assertEquals($update['endDate'], $holiday->getEndDate());
		$this->assertEquals($update['year'], $holiday->getYear());
		$this->assertEquals($update['valid'], $holiday->getValid());
		
		// reset and cleanup
		$holiday->update($orig);
		$holiday->writeDb();
	}
	
	public function testGetHolidays() {
		
		// values in 2015
		$values = array(
				'Neujahr' => 1420066800,
				'Karfreitag' => 1428012000,
				'Ostersonntag' => 1428184800,
				'Ostermontag' => 1428271200,
				'Tag der Arbeit' => 1430431200,
				'Christi Himmelfahrt' => 1431554400,
				'Pfingstsonntag' => 1432418400,
				'Pfingstmontag' => 1432504800,
				'Tag der deutschen Einheit' => 1443823200,
				'1. Weihnachtstag' => 1450998000,
				'2. Weihnachtstag' => 1451084400,
			);
		
		// get holidays in 2015
		$holidays = Holiday::getHolidays('2015');
		$this->assertEquals($values, $holidays);
		
		// get school holidays
		$schoolHolidays = Holiday::getHolidays('2015', true);
		$this->assertTrue(is_array($schoolHolidays));
	}
	
	public function testDelete() {
		
		$new = array(
				'name' => 'Different Holiday',
				'date' => '1970-12-30',
				'endDate' => '1970-12-31',
				'year' => '1971',
				'valid' => '1',
			);
		
		$holiday = new Holiday($new);
		$holiday->writeDb();
		$holiday->deleteEntry();
		
		$holiday = new Holiday('Different Holiday', '1971');
		$this->assertTrue(is_null($holiday->getName()));
	}
	
	public function testDeleteAll() {
		
		$new = array(
				'name' => 'Different Holiday',
				'date' => '1970-12-30',
				'endDate' => '1970-12-31',
				'year' => '1971',
				'valid' => '1',
			);
		
		$holiday = new Holiday($new);
		$holiday->writeDb();
		Holiday::deleteAll($new['year']);
		
		$holiday = new Holiday('Different Holiday', '1971');
		$this->assertTrue(is_null($holiday->getName()));
	}

}

?>
