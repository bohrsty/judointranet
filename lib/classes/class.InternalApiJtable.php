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

// secure against direct execution
if(!defined("JUDOINTRANET")) {die("Cannot be executed directly! Please use index.php.");}

/**
 * class InternalApiJtable implements the data handling of the internal ajax requests for jTable
 */
class InternalApiJtable extends InternalApi {
	
	
	/*
	 * class-variables
	 */
	
	
	/*
	 * getter/setter
	 */
	
	/*
	 * constructor/destructor
	 */
	public function __construct() {
		
		// setup parent
		parent::__construct();
	}
	
	/*
	 * methods
	 */
	/**
	 * result() handles the parameters, gets the data and returns the result array
	 * 
	 * @return array array containing the result data
	 */
	protected function result() {
		
		// switch $_GET['action']
		switch($this->get('action')) {
			
			case 'list':
				return $this->actionList();
			break;
			
			case 'update':
				return $this->actionUpdate();
			break;
			
			default:
				return array(
					'Result' => 'OK',
					'Records' => array(),
				);
			break;	
		}
	}
	
	
	/**
	 * actionList() handles the list action for jTable, gets and returns its data
	 * 
	 * @return array data for jTable
	 */
	private function actionList() {
		
		// switch $_GET['provider']
		switch($this->get('provider')) {
			
			case 'AccountingSettingsCosts':
				return $this->actionListAccountSettingsCost();
			break;
			
			default:
				return array(
					'Result' => 'ERROR',
					'Message' => _l('API call failed [unknown provider]'),
				);
			break;
		}
	}
	
	
	/**
	 * actionListAccountSettingsCost() handles the list action for jTable, gets and returns the
	 * data from AccountSettingsCostListing class
	 * 
	 * @return array data for jTable
	 */
	private function actionListAccountSettingsCost() {
		
		// prepare postData (jtStartIndex, jtPageSize)
		$postData = array(
				'limit' => ($this->get('jtStartIndex') !== false && $this->get('jtPageSize') !== false ? 'LIMIT '.$this->get('jtStartIndex').', '.$this->get('jtPageSize') : ''),
				'orderBy' => ($this->get('jtSorting') !== false ? 'ORDER BY '.$this->get('jtSorting') : ''),
			);
		
		// get object
		$accountingSettingsCost = new AccountingSettingsCostsListing();
		
		// prepare return
		return array(
				'Result' => 'OK',
				'Records' => $accountingSettingsCost->listingAsArray($postData),
				'TotalRecordCount' => $accountingSettingsCost->totalRowCount(),
			);
	}
	
	
	/**
	 * actionUpdate() handles the update action for jTable, gets and returns its data
	 * 
	 * @return array data for jTable
	 */
	private function actionUpdate() {
		
		// switch $_GET['provider']
		switch($this->get('provider')) {
			
			case 'AccountingSettingsCosts':
				return $this->actionUpdateAccountSettingsCost();
			break;
			
			default:
				return array(
					'Result' => 'ERROR',
					'Message' => _l('API call failed [unknown provider]'),
				);
			break;
		}
	}
	
	
	/**
	 * actionUpdateAccountSettingsCost() handles the update action for jTable, gets and returns the
	 * data from AccountSettingsCostListing class
	 * 
	 * @return array data for jTable
	 */
	private function actionUpdateAccountSettingsCost() {
		
		// prepare postData
		// check data
		if($this->post('id') === false) {
			return array(
					'Result' => 'ERROR',
					'Message' => _l('API call failed [missing post data]'),
				);
		}
		if($this->post('value') === false) {
			return array(
					'Result' => 'ERROR',
					'Message' => _l('API call failed [missing post data]'),
				);
		}
		$postData = array(
				'id' => $this->post('id'),
				'value' => $this->post('value'),
			);
		
		// get object
		$accountingSettingsCost = new AccountingSettingsCostsListing();
		// add row
		$accountingSettingsCost->updateRow($postData);
		// get result
		$postData = $accountingSettingsCost->singleRow($this->post('id'));
		
		// prepare return
		$postData['name'] = parent::lang('costs '.$postData['name']);
		return array(
				'Result' => 'OK',
				'Records' => $postData,
			);
	}
}

?>
