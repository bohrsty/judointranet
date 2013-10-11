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

class FilterTest extends PHPUnit_Framework_TestCase {
	
	// variables
	
	// setup
	public function setUp() {
		
	}
	
	
	public function tearDown() {
		
	}
	
	
	public function testFilterSetterGetter() {
		
		// get object
		$filter = new Filter(1);
		$this->assertEquals(1, $filter->getId());
		$this->assertEquals('Alle', $filter->getName());
		
		// id
		$data = 2;
		
		$filter->setId($data);
		$this->assertEquals($data, $filter->getId());
		$this->assertEquals($data, $filter->propertyByString('id'));
		
		// name
		$data = 'FilterName';
		
		$filter->setName($data);
		$this->assertEquals($data, $filter->getName());
		$this->assertEquals($data, $filter->propertyByString('name'));
	}
	
	
	public function testFilterGetOwnAndAllFilterInfos() {
		
		// all existing filter
		$allFilter = Filter::allExistingFilter();
		$this->assertArrayHasKey(1, $allFilter);
		$this->assertEquals(1, $allFilter[1]->getId());
		$this->assertEquals('Alle', $allFilter[1]->getName());
		
		// all existing filter as array with defined value
		$definedFilter = Filter::allExistingFilter('name');
		$this->assertEquals('Alle', $definedFilter[1]);
		
		// all filter of an item
		$ownFilter = Filter::allFilterOf('calendar', 1);
		$this->assertArrayHasKey(1, $ownFilter);
		$this->assertEquals(1, $ownFilter[1]->getId());
		$this->assertEquals('Alle', $ownFilter[1]->getName());
		
	}
	
	
	public function testFilterGettingFilteredItems() {
		
		// create global user
		$_SESSION['user'] = new User();
		
		// all items of filter
		$items = Filter::filterItems(1, 'calendar');
		$this->assertArrayHasKey(1, $items);
		$this->assertInstanceOf('Calendar', $items[1]);
		// all items of date
		$items = Filter::filterItems(false, 'calendar', date('Y-m-d', 0), date('Y-m-d'));
		$this->assertArrayHasKey(1, $items);
		$this->assertInstanceOf('Calendar', $items[1]);
		// all items of filter and date
		$items = Filter::filterItems(1, 'calendar', date('Y-m-d', 0), date('Y-m-d'));
		$this->assertArrayHasKey(1, $items);
		$this->assertInstanceOf('Calendar', $items[1]);
	}
	
	
	public function testFilterRemoveAndWriteToDb() {
		
		// remove all entrys for given item
		Filter::dbRemove('calendar', 1);
		$ownFilter = Filter::allFilterOf('calendar', 1);
		$this->assertEmpty($ownFilter);
		
		// write filter to db
		$filter = new Filter(1);
		$filter->dbWrite('calendar', 1);
		$ownFilter = Filter::allFilterOf('calendar', 1);
		$this->assertArrayHasKey(1, $ownFilter);
		$this->assertEquals(1, $ownFilter[1]->getId());
		$this->assertEquals('Alle', $ownFilter[1]->getName());
	}
}
?>
