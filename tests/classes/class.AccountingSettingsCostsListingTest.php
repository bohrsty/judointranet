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

class AccountingSettingsCostsListingTest extends PHPUnit_Framework_TestCase {
	
	// setup
	public function setUp() {
		
	}
	
	
	public function tearDown() {
		
	}
	
	
	public function testConstruction() {
		
		$data = 'AccountingSettingsCostsListing';
		
		// instance of
		$listing = new $data();
		$this->assertEquals($data, get_class($listing));
		
		// methods defined
		$listingMethods = get_class_methods($listing);
		$this->assertContains('listingAsArray', $listingMethods);
		$this->assertContains('listingAsHtml', $listingMethods);
		
		// method results
		$this->assertTrue(is_array($listing->listingAsArray()));
		$this->assertTrue(is_string($listing->listingAsHtml('')));
		$this->assertEquals('', $listing->listingAsHtml(''));
	}
	
	
	public function testGetAndUpdateData() {
		
		// get object
		$listing = new AccountingSettingsCostsListing();
		
		// total rows
		$this->assertGreaterThanOrEqual(3, $listing->totalRowCount());
		
		// get first row
		$data = 1;
		$firstRow = $listing->singleRow($data);
		$this->assertEquals($data, $firstRow['id']);
		$this->assertEquals('base', $firstRow['name']);
		$this->assertEquals('payback', $firstRow['type']);
		$this->assertTrue(is_numeric(str_replace(',', '.', str_replace('.', '', $firstRow['value']))));
		
		// update first value
		$value = '1,23';
		$listing->updateRow(array('id' => $data, 'value' => $value,));
		
		$firstRowUpdated = $listing->singleRow($data);
		$this->assertEquals($value, $firstRowUpdated['value']);
		
		// reset first row value
		$listing->updateRow(array('id' => $data, 'value' => $firstRow['value'],));
		$firstRowReset = $listing->singleRow($data);
		$this->assertEquals($firstRow['value'], $firstRowReset['value']);
	}
	
}
?>
