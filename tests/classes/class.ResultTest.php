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

class ResultTest extends PHPUnit_Framework_TestCase {
	
	private $testPresetId;
	
	// setup
	public function setUp() {
		
		// get preset id for result
		$result = TestDb::singleValue('SELECT `id` FROM `preset` WHERE `table`=\'result\' LIMIT 1');
		$this->testPresetId = (TestDb::$num_rows != 0 ? $result : 0);
	}
	
	
	public function tearDown() {
		
	}
	
	
	public function testSetterGetter() {
		
		// create object
		$result = new Result();
		
		$this->assertEquals(0, $result->getId());
		$this->assertNull($result->getCalendar());
		$this->assertEquals(0, $result->getPreset());
		
		// id
		$data = 1;
		
		$result->setId($data);
		$this->assertEquals($data, $result->getId());
		
		// calendar
		$data = -1;
		
		$result->setCalendar($data);
		$this->assertEquals($data, $result->getCalendar());
		
		// city
		$data = 'city';
		
		$result->setCity($data);
		$this->assertEquals($data, $result->getCity());
		
		// preset
		$data = 1;
		
		$result->setPreset($data);
		$this->assertEquals($data, $result->getPreset());
		
	}
	
	
	public function testAddingResultData() {
		
		// create object
		$result = new Result();
		
		// assert adding wrong $data
		$this->assertFalse($result->addStandings(''));
		$this->assertFalse($result->addStandings(array(1, 2, 3, 4,)));
		$this->assertFalse($result->addStandings(array(1, 2, 3, 4, 5, 6,)));
		$this->assertFalse($result->addStandings(array()));
		
		$data = array(
					'agegroup' => 'Jugend U10',
					'weightclass' => '-23,4',
					'place' => '1',
					'name' => 'Vorname Nachname',
					'club_id' =>'1',
				);
		// add standings
		$testAddStandings = $result->addStandings($data);
		$this->assertTrue($testAddStandings);
		
		// assert agegroups
		$agegroups = $result->getAgegroups();
		$this->assertArrayHasKey($data['agegroup'], $agegroups);
		
		// assert agegroup->weightclasses
		$weightclasses = $result->getWeightclasses($data['agegroup']);
		$this->assertArrayHasKey($data['weightclass'], $weightclasses);
		
		// assert abegroup->weightclass->place,name,club_id,...
		$standings = $result->getStandings($data['agegroup'], $data['weightclass']);
		$this->assertEquals($data['place'], $standings[0]['place']);
		$this->assertEquals($data['name'], $standings[0]['name']);
		$this->assertEquals($data['club_id'], $standings[0]['club_id']);
	}
	
	
	public function testConstructionWriteDbDelete() {
		
		// get autoincrement values for resetting
		$standingsAutoincrement = TestDb::getAutoincrement('standings');
		$resultAutoincrement = TestDb::getAutoincrement('result');
		
		/*
		 * construction
		 */
		// data
		$data = array(
					'agegroup' => 'Jugend U10',
					'weightclass' => '-23,4',
					'place' => '1',
					'name' => 'Vorname Nachname',
					'club_id' =>'1',
				);
		
		// get object
		$result = new Result(1);
		
		// assert variables
		$this->assertEquals(1, $result->getCalendar()->get_id());
		$this->assertNotEquals(0, $result->getPreset());
		
		// assert agegroups
		$agegroups = $result->getAgegroups();
		$this->assertArrayHasKey($data['agegroup'], $agegroups);
		
		// assert agegroup->weightclasses
		$weightclasses = $result->getWeightclasses($data['agegroup']);
		$this->assertArrayHasKey($data['weightclass'], $weightclasses);
		
		// assert abegroup->weightclass->place,name,club_id,...
		$standings = $result->getStandings($data['agegroup'], $data['weightclass']);
		$this->assertEquals($data['place'], $standings[0]['place']);
		$this->assertEquals($data['name'], $standings[0]['name']);
		$this->assertEquals($data['club_id'], $standings[0]['club_id']);
		
		/*
		 * write db
		 */
		 
		// get object
		$result = new Result(0, 1);
		
		// set is_team
		$result->setIsTeam(0);
		
		// test write without standings
		$this->assertFalse($result->writeDb());
		
		// data
		$data = array(
				array(
						'agegroup' => 'Jugend 1',
						'weightclass' => '-23,4',
						'place' => '1',
						'name' => 'Vorname1 Nachname1',
						'club_id' =>'1',
					),
				array(
						'agegroup' => 'Jugend 1',
						'weightclass' => '-24,6',
						'place' => '1',
						'name' => 'Vorname2 Nachname2',
						'club_id' =>'2',
					),
				array(
						'agegroup' => 'Jugend 1',
						'weightclass' => '-24,6',
						'place' => '2',
						'name' => 'Vorname3 Nachname3',
						'club_id' =>'1',
					),
				array(
						'agegroup' => 'Jugend 2',
						'weightclass' => '-34,5',
						'place' => '1',
						'name' => 'Vorname4 Nachname4',
						'club_id' =>'2',
					),
			);
		
		// add standings
		foreach($data as $standing) {
			$result->addStandings($standing);
		}
		
		// walk through agegroups
		foreach($result->getAgegroups() as $agegroup => $countAgegroups) {
			
			// walk through weightclasses
			foreach($result->getWeightclasses($agegroup) as $weightclass => $countWeightclasses) {
				
				// walk though standings
				foreach($result->getStandings($agegroup, $weightclass) as $standing) {
					
					$test = array(
							'agegroup' => $agegroup,
							'weightclass' => $weightclass,
							'place' => $standing['place'],
							'name' => $standing['name'],
							'club_id' => $standing['club_id'],
						);
					$this->assertContains($test, $data);
				}
			} 
		}
		
		// test write without preset
		$this->assertFalse($result->writeDb());
		
		// add preset
		$result->setPreset($this->testPresetId);
		
		// write db
		$resultId = $result->writeDb();
		
		// reload from database
		$result = new Result($resultId);
		$this->assertNotNull($resultId);
		
		// walk through agegroups
		foreach($result->getAgegroups() as $agegroup => $countAgegroups) {
			
			// walk through weightclasses
			foreach($result->getWeightclasses($agegroup) as $weightclass => $countWeightclasses) {
				
				// walk though standings
				foreach($result->getStandings($agegroup, $weightclass) as $standing) {
					
					$test = array(
							'agegroup' => $agegroup,
							'weightclass' => $weightclass,
							'place' => $standing['place'],
							'name' => $standing['name'],
							'club_id' => $standing['club_id'],
						);
					$this->assertContains($test, $data);
				}
			} 
		}
		
		/*
		 * delete from db
		 */
		Result::delete($resultId);
		$this->assertFalse(Result::exists('result', $resultId));
		
		/*
		 * reset autoincrement values
		 */
		TestDb::resetAutoincrement('result', $resultAutoincrement);
		TestDb::resetAutoincrement('standings', $standingsAutoincrement);
	}
	
}
?>
