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
			
			case 'create':
				return $this->actionCreate();
			break;
			
			case 'delete':
				return $this->actionDelete();
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
	 * checkReturn($return) checks the value of $return and generates the according message
	 * 
	 * @param mixed $return return value of the action to be checked
	 * @return mixed array containing the message in case of error, true if successful
	 */
	private function checkReturn($return) {
		
		// success
		if($return === true) {
			return true;
		}
		
		// switch return values and generate messages
		switch($return) {
			
			case JTABLE_NOT_AUTORIZED:
				return array(
						'Result' => 'ERROR',
						'Message' => _l('not authorized to perform this action'),
					);
			break;
			
			case JTABLE_ROW_NOT_EXISTS:
				return array(
						'Result' => 'ERROR',
						'Message' => _l('provided row not exists'),
					);
			break;
			
			default:
				return array(
						'Result' => 'ERROR',
						'Message' => _l('unknown error'),
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
		
		// prepare error message
		$errorNotAuthorized = array(
				'Result' => 'ERROR',
				'Message' => _l('API call failed [not authorized]'),
			);
		
		// switch $_GET['provider']
		switch($this->get('provider')) {
			
			case 'AccountingSettingsCosts':
				return $this->actionListAccountSettingsCost();
			break;
			
			case 'ResultListall':
				return $this->actionListResultListall();
			break;
			
			case 'ResultList':
				return $this->actionListResultList();
			break;
			
			case 'AccountingResultTask':
				return $this->actionListAccountingResultTask();
			break;
			
			case 'CalendarListall':
				return $this->actionListCalendarListall();
			break;
			
			case 'UsertableField':
				
				// check permission
				if(($this->get('table') == 'file_type' && !$this->getUser()->isAdmin())
				|| ($this->get('table') == 'club' && $this->getUser()->isMemberOf(2) === false)
				|| ($this->getUser()->hasPermission('navi', 35, 'r') === false)) {
					return $errorNotAuthorized;
				} else {
					return $this->actionListUsertableField();
				}
			break;
			
			case 'ProtocolListall':
				return $this->actionListProtocolListall();
			break;
			
			case 'ProtocolDecisions':
				return $this->actionListProtocolDecisions();
			break;
			
			case 'FileListall':
				return $this->actionListFileListall();
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
	 * actionUpdate() handles the update action for jTable, gets and returns its data
	 * 
	 * @return array data for jTable
	 */
	private function actionUpdate() {
		
		// prepare error message
		$errorNotAuthorized = array(
				'Result' => 'ERROR',
				'Message' => _l('API call failed [not authorized]'),
			);
		
		// switch $_GET['provider']
		switch($this->get('provider')) {
			
			case 'AccountingSettingsCosts':
				return $this->actionUpdateAccountSettingsCost();
			break;
			
			case 'UsertableField':
				
				// check permission
				if($this->getUser()->hasPermission('navi', 35, 'w')) {
					return $this->actionUpdateUsertableField();
				} else {
					return $errorNotAuthorized;
				}
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
	 * actionDelete() handles the delete action for jTable, gets and returns its data
	 * 
	 * @return array data for jTable
	 */
	private function actionDelete() {
		
		// prepare error message
		$errorNotAuthorized = array(
				'Result' => 'ERROR',
				'Message' => _l('API call failed [not authorized]'),
			);
		
		// switch $_GET['provider']
		switch($this->get('provider')) {
			
			case 'UsertableField':
				
				// check permission
				if($this->getUser()->hasPermission('navi', 35, 'w')) {
					return $this->actionDeleteUsertableField();
				} else {
					return $errorNotAuthorized;
				}
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
	 * actionCreate() handles the create action for jTable, gets and returns its data
	 * 
	 * @return array data for jTable
	 */
	private function actionCreate() {
		
		// prepare error message
		$errorNotAuthorized = array(
				'Result' => 'ERROR',
				'Message' => _l('API call failed [not authorized]'),
			);
		
		// switch $_GET['provider']
		switch($this->get('provider')) {
			
			case 'UsertableField':
				
				// check permission
				if($this->getUser()->hasPermission('navi', 35, 'w')) {
					return $this->actionCreateUsertableField();
				} else {
					return $errorNotAuthorized;
				}
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
	 * actionUpdateAccountSettingsCost() handles the update action for jTable, gets and returns the
	 * data from AccountSettingsCostListing class
	 * 
	 * @return array data for jTable
	 */
	private function actionUpdateAccountSettingsCost() {
		
		// prepare postData
		$postData = array();
		$postDataArray = array(
				'id',
				'value',
			);
		// check data
		foreach($postDataArray as $field) {
			if($this->post($field) === false) {
				return array(
						'Result' => 'ERROR',
						'Message' => _l('API call failed [missing post data]'),
					);
			}
			$postData[$field] = $this->post($field);
		}
		
		// get object
		$accountingSettingsCost = new AccountingSettingsCostsListing();
		// add row
		$accountingSettingsCost->updateRow($postData);
		// get result
		$postData = $accountingSettingsCost->singleRow($this->post('id'));
		
		// prepare return
		$postData['name'] = _l('costs '.$postData['name']);
		return array(
				'Result' => 'OK',
				'Records' => $postData,
			);
	}
	
	
	/**
	 * actionListResultListall() handles the list action for jTable, gets and returns the
	 * data from ResultListallListing class
	 * 
	 * @return array data for jTable
	 */
	private function actionListResultListall() {
		
		// prepare getData (jtStartIndex, jtPageSize)
		$getData = array(
				'limit' => ($this->get('jtStartIndex') !== false && $this->get('jtPageSize') !== false ? 'LIMIT '.$this->get('jtStartIndex').', '.$this->get('jtPageSize') : ''),
				'orderBy' => ($this->get('jtSorting') !== false ? 'ORDER BY '.$this->get('jtSorting') : ''),
			);
		
		// get object
		$resultListallListing = new ResultListallListing();
		
		// prepare return
		return array(
				'Result' => 'OK',
				'Records' => $resultListallListing->listingAsArray($getData),
				'TotalRecordCount' => $resultListallListing->totalRowCount(),
			);
	}
	
	
	/**
	 * actionListResultList() handles the list action for jTable, gets and returns the
	 * data from ResultListListing class
	 * 
	 * @return array data for jTable
	 */
	private function actionListResultList() {
		
		// prepare getData (jtStartIndex, jtPageSize)
		$getData = array(
				'limit' => ($this->get('jtStartIndex') !== false && $this->get('jtPageSize') !== false ? 'LIMIT '.$this->get('jtStartIndex').', '.$this->get('jtPageSize') : ''),
				'orderBy' => ($this->get('jtSorting') !== false ? 'ORDER BY '.$this->get('jtSorting') : ''),
			);
		
		// get object
		$resultListListing = new ResultListListing();
		
		// prepare return
		return array(
				'Result' => 'OK',
				'Records' => $resultListListing->listingAsArray($getData),
				'TotalRecordCount' => $resultListListing->totalRowCount(),
			);
	}
	
	
	/**
	 * actionListAccountingResultTask() handles the list action for jTable, gets and returns the
	 * data from AccountingResultTaskListing class
	 * 
	 * @return array data for jTable
	 */
	private function actionListAccountingResultTask() {
		
		// prepare getData (jtStartIndex, jtPageSize)
		$getData = array(
				'limit' => ($this->get('jtStartIndex') !== false && $this->get('jtPageSize') !== false ? 'LIMIT '.$this->get('jtStartIndex').', '.$this->get('jtPageSize') : ''),
				'orderBy' => ($this->get('jtSorting') !== false ? 'ORDER BY '.$this->get('jtSorting') : ''),
			);
		
		// get object
		$accountingResultTastListing = new AccountingResultTaskListing();
		
		// prepare return
		return array(
				'Result' => 'OK',
				'Records' => $accountingResultTastListing->listingAsArray($getData),
				'TotalRecordCount' => $accountingResultTastListing->totalRowCount(),
			);
	}
	
	
	/**
	 * actionListCalendarListall() handles the list action for jTable, gets and returns the
	 * data from CalendarListallListing class
	 * 
	 * @return array data for jTable
	 */
	private function actionListCalendarListall() {
		
		// prepare getData (jtStartIndex, jtPageSize)
		$getData = array(
				'limit' => ($this->get('jtStartIndex') !== false && $this->get('jtPageSize') !== false ? 'LIMIT '.$this->get('jtStartIndex').', '.$this->get('jtPageSize') : ''),
				'orderBy' => ($this->get('jtSorting') !== false ? 'ORDER BY '.$this->get('jtSorting') : ''),
			);
		
		// get object
		$calendarListallListing = new CalendarListallListing();
		
		// prepare return
		return array(
				'Result' => 'OK',
				'Records' => $calendarListallListing->listingAsArray($getData),
				'TotalRecordCount' => $calendarListallListing->totalRowCount(),
			);
	}
	
	
	/**
	 * actionListUsertableField() handles the list action for jTable, gets and returns the
	 * data from UsertableFieldListing class
	 * 
	 * @return array data for jTable
	 */
	private function actionListUsertableField() {
		
		// prepare getData (jtStartIndex, jtPageSize)
		$getData = array(
				'limit' => ($this->get('jtStartIndex') !== false && $this->get('jtPageSize') !== false ? 'LIMIT '.$this->get('jtStartIndex').', '.$this->get('jtPageSize') : ''),
				'orderBy' => ($this->get('jtSorting') !== false ? 'ORDER BY '.$this->get('jtSorting') : ''),
			);
		
		// get object
		$usertableFieldListing = new AdministrationUsertableFieldListing();
		
		// prepare return
		return array(
				'Result' => 'OK',
				'Records' => $usertableFieldListing->listingAsArray($getData),
				'TotalRecordCount' => $usertableFieldListing->totalRowCount(),
			);
	}
	
	
	/**
	 * actionUpdateUsertableField() handles the update action for jTable, gets and returns the
	 * data from AdministrationUsertableFieldListing class
	 * 
	 * @return array data for jTable
	 */
	private function actionUpdateUsertableField() {
		
		// get table config
		$tableConfig = $this->getTableConfig($this->get('table'));
		// get column names as array
		$postDataArray = array_merge(array('id'), explode(',', $tableConfig['cols']));
		
		// prepare postData
		$postData = array();
		// check data
		foreach($postDataArray as $field) {
			
			// check valid
			if($field == 'valid' && $this->post($field) === false) {
				$postData[$field] = 'false';
			} else {
				
				if($this->post($field) === false) {
					return array(
							'Result' => 'ERROR',
							'Message' => _l('API call failed [missing post data]'),
						);
				}
				$postData[$field] = $this->post($field);
			}
		}
		
		// get object
		$usertableField = new AdministrationUsertableFieldListing();
		// add row
		$returnValue = $usertableField->updateRow($postData);
		// get result
		$postData = $usertableField->singleRow($this->post('id'));
		
		// return
		$return = $this->checkReturn($returnValue);
		if($return !== true) {
			return $return;
		}
		return array(
				'Result' => 'OK',
				'Records' => $postData,
			);
	}
	
	
	/**
	 * actionDeleteUsertableField() handles the delete action for jTable, gets and returns the
	 * data from AdministrationUsertableFieldListing class
	 * 
	 * @return array data for jTable
	 */
	private function actionDeleteUsertableField() {
		
		// check if id given
		if($this->post('id') === false) {
			return array(
					'Result' => 'ERROR',
					'Message' => _l('API call failed [missing post data]'),
				);
		}
		
		// get object
		$usertableField = new AdministrationUsertableFieldListing();
		// add row
		$returnValue = $usertableField->deleteRow($this->post('id'));
		$return = $this->checkReturn($returnValue);
		// return
		if($return !== true) {
			return $return;
		}
		return array(
				'Result' => 'OK',
			);
	}
	
	
	/**
	 * actionCreateUsertableField() handles the create action for jTable, gets and returns the
	 * data from AdministrationUsertableFieldListing class
	 * 
	 * @return array data for jTable
	 */
	private function actionCreateUsertableField() {
		
		// get table config
		$tableConfig = $this->getTableConfig($this->get('table'));
		// get column names as array
		$postDataArray = explode(',', $tableConfig['cols']);
		
		// prepare postData
		$postData = array();
		// check data
		foreach($postDataArray as $field) {
			
			// check valid
			if($field == 'valid') {
				$postData[$field] = 'true';
			} else {
				
				if($this->post($field) === false) {
					return array(
							'Result' => 'ERROR',
							'Message' => _l('API call failed [missing post data]'),
						);
				}
				$postData[$field] = $this->post($field);
			}
		}
		
		// get object
		$usertableField = new AdministrationUsertableFieldListing();
		// add row
		$returnValue = $usertableField->createRow($postData);
		if($returnValue['return'] === true) {
			// get result
			$postData = $usertableField->singleRow($returnValue['newId']);
		}
		
		// return
		$return = $this->checkReturn($returnValue['return']);
		if($return !== true) {
			return $return;
		}
		return array(
				'Result' => 'OK',
				'Record' => $postData,
			);
	}
	
	
	/**
	 * actionListProtocolListall() handles the list action for jTable, gets and returns the
	 * data from ProtocolListallListing class
	 * 
	 * @return array data for jTable
	 */
	private function actionListProtocolListall() {
		
		// prepare getData (jtStartIndex, jtPageSize)
		$getData = array(
				'limit' => ($this->get('jtStartIndex') !== false && $this->get('jtPageSize') !== false ? 'LIMIT '.$this->get('jtStartIndex').', '.$this->get('jtPageSize') : ''),
				'orderBy' => ($this->get('jtSorting') !== false ? 'ORDER BY '.$this->get('jtSorting') : ''),
			);
		
		// get object
		$protocolListallListing = new ProtocolListallListing();
		
		// prepare return
		return array(
				'Result' => 'OK',
				'Records' => $protocolListallListing->listingAsArray($getData),
				'TotalRecordCount' => $protocolListallListing->totalRowCount(),
			);
	}
	
	
	/**
	 * actionListProtocolDecisions() handles the list action for jTable, gets and returns the
	 * data from ProtocolDecisionsListing class
	 * 
	 * @return array data for jTable
	 */
	private function actionListProtocolDecisions() {
		
		// prepare getData (jtStartIndex, jtPageSize)
		$getData = array(
				'limit' => ($this->get('jtStartIndex') !== false && $this->get('jtPageSize') !== false ? 'LIMIT '.$this->get('jtStartIndex').', '.$this->get('jtPageSize') : ''),
				'orderBy' => ($this->get('jtSorting') !== false ? 'ORDER BY '.$this->get('jtSorting') : ''),
			);
		
		// get object
		$protocolDecisionsListing = new ProtocolDecisionsListing();
		
		// prepare return
		return array(
				'Result' => 'OK',
				'Records' => $protocolDecisionsListing->listingAsArray($getData),
				'TotalRecordCount' => $protocolDecisionsListing->totalRowCount(),
			);
	}
	
	
	/**
	 * actionListFileListall() handles the list action for jTable, gets and returns the
	 * data from FileListallListing class
	 * 
	 * @return array data for jTable
	 */
	private function actionListFileListall() {
		
		// prepare getData (jtStartIndex, jtPageSize)
		$getData = array(
				'limit' => ($this->get('jtStartIndex') !== false && $this->get('jtPageSize') !== false ? 'LIMIT '.$this->get('jtStartIndex').', '.$this->get('jtPageSize') : ''),
				'orderBy' => ($this->get('jtSorting') !== false ? 'ORDER BY '.$this->get('jtSorting') : ''),
			);
		
		// get object
		$fileListallListing = new FileListallListing();
		
		// prepare return
		return array(
				'Result' => 'OK',
				'Records' => $fileListallListing->listingAsArray($getData),
				'TotalRecordCount' => $fileListallListing->totalRowCount(),
			);
	}
}

?>
