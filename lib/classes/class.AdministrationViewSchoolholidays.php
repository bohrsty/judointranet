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
 * class AdministrationViewSchoolholiday implements the control of the id "schoolholiday" administration page
 */
class AdministrationViewSchoolholidays extends AdministrationView {
	
	/*
	 * class-variables
	 */
	private $smarty;
	
	/*
	 * getter/setter
	 */
	
	/*
	 * constructor/destructor
	 */
	public function __construct() {
		
		// setup parent
		parent::__construct();
		
		// create smarty object
		$this->smarty = new JudoIntranetSmarty();
	}
	
	
	/**
	 * show() generates the output of the page
	 * 
	 * @return string output for the page to be added to the template
	 */
	public function show() {
		
		// check action
		if($this->get('year') !== false) {
			
			$year = $this->get('year');
			
			// check date
			if(!is_numeric($year) || strlen($year) > 4 || $year > (date('Y') + 2)) {
				throw new HolidayYearNotValidException($this);
			}
			
			// set caption
			$this->getTpl()->assign('caption', _l('Edit school holiday for year #?year', array('year' => $year)).'&nbsp;'.$this->helpButton(HELP_MSG_MANAGESCHOOLHOLIDAYSYEAR));
			
			// get all holidays for given year
			$holidays = Holiday::listAllSchoolHolidays($year);
			
			// prepare form
			$form = new JudoIntranet_Zebra_Form(
					'schoolHolidays',			// id/name
					'post',						// method
					'administration.php?id=schoolholidays&year='.$year		// action
			);
			
			// walk through holidays and create form fields
			$htmlNames = array();
			foreach($holidays as $name => $holiday) {
				
				// prepare htmlName
				$htmlName = str_replace('\\', '_', 
						str_replace('/', '_', 
								str_replace('-', '', 
										$this->replace_umlaute($name)
								)
						)
				);
				$htmlNames[$htmlName] = $holiday + array(
						'htmlName' => $htmlName.'_name',
						'htmlDate' => $htmlName.'_date',
						'htmlEndDate' => $htmlName.'_endDate',
					);
				
				// name
				$formIds[$htmlName.'_name'] = array('valueType' => 'string', 'type' => 'text',);
				$name = $form->add(
						$formIds[$htmlName.'_name']['type'],		// type
						$htmlName.'_name',		// id/name
						$name	// default
					);
				if($holiday['fix'] === false) {
					$nameRules['required'] = array('error', _l('required name'),);
				}
				// rules
				$nameRules['regexp'] = array(
						$this->getGc()->get_config('name.regexp.zebra'),	// regexp
						'error',	// error variable
						_l('allowed chars').' ['.$this->getGc()->get_config('name.desc').']',	// message
					);
				$name->set_rule($nameRules);
				unset($nameRules);
				
				
				// date
				$formIds[$htmlName.'_date'] = array('valueType' => 'string', 'type' => 'date',);
				$date = $form->add(
						$formIds[$htmlName.'_date']['type'],			// type
						$htmlName.'_date',			// id/name
						($holiday['date'] == '' ? '' : date('d.m.Y', strtotime($holiday['date'])))	// default
					);
				// format/position
				$date->format('d.m.Y');
				$date->inside(true);
				// rules
				if($holiday['fix'] === true) {
					$dateRules['required'] = array('error', _l('required date'),);
				}
				$dateRules['date'] = array('error', _l('check date'),);
				$date->set_rule($dateRules);
				unset($dateRules);
				
	
				// enddate
				$formIds[$htmlName.'_endDate'] = array('valueType' => 'string', 'type' => 'date',);
				$endDate = $form->add(
						$formIds[$htmlName.'_endDate']['type'],			// type
						$htmlName.'_endDate',			// id/name
						(is_null($holiday['endDate']) ? '' : date('d.m.Y', strtotime($holiday['endDate'])))	// default
					);
				// format/position
				$endDate->format('d.m.Y');
				$endDate->inside(true);
				// rules
				$endDateRules['date'] = array('error', _l('check date'),);
				$endDate->set_rule($endDateRules);
				unset($endDateRules);
			}
			
			// assign htmlnames
			$this->smarty->assign('holidays', $htmlNames);
			
			// submit-button
			$form->add(
					'submit',		// type
					'buttonSubmit',	// id/name
					_l('save')	// value
			);
			
			// validate
			if($form->validate()) {
			
				// get form data
				$data = $this->getFormValues($formIds);
				
				// prepare output
				$output = array();
				
				// delete existing entries
				Holiday::deleteAll($year);
				
				// walk through holidays
				foreach($htmlNames as $htmlName => $holiday) {
					
					// check end date
					if(strtotime($data[$holiday['htmlEndDate']]) <= strtotime($data[$holiday['htmlDate']])) {
						$data[$holiday['htmlEndDate']] = '';
					}
					// set end date null if empty
					if($data[$holiday['htmlEndDate']] == '') {
						$data[$holiday['htmlEndDate']] = null;
					}
					
					// check if fix
					if($holiday['fix'] === true) {
						
						// create object
						$holidayArray = array(
								'name' => $holiday['name'],
								'date' => $data[$holiday['htmlDate']],
								'endDate' => $data[$holiday['htmlEndDate']],
								'year' => $year,
								'valid' => '1',
							);
						$newHoliday = new Holiday($holidayArray);
						$newHoliday->writeDb();
						$output[] = array(
								'name' => $newHoliday->getName(),
								'date' => $newHoliday->getDate(),
								'endDate' => $newHoliday->getEndDate(),
							);
					} else {
						
						// check name changed and date set
						if($data[$holiday['htmlName']] != '' &&
								$data[$holiday['htmlName']] != $htmlName &&
								$data[$holiday['htmlDate']] != '') {
							
							// create object
							$holidayArray = array(
									'name' => $data[$holiday['htmlName']],
									'date' => $data[$holiday['htmlDate']],
									'endDate' => $data[$holiday['htmlEndDate']],
									'year' => $year,
									'valid' => '1',
							);
							$newHoliday = new Holiday($holidayArray);
							$newHoliday->writeDb();
							$output[] = array(
									'name' => $newHoliday->getName(),
									'date' => $newHoliday->getDate(),
									'endDate' => $newHoliday->getEndDate(),
								);
						}
					}
				}
				
				// assign output
				$this->smarty->assign('output', $output);
				
				// return
				return $this->smarty->fetch('smarty.holiday.edit.tpl');
			} else {
				return $form->render(__DIR__.'/../zebraTemplate.php', true, array($formIds, 'smarty.holiday.edit.tpl',array() ,$this->smarty));
			}
		} else {
			
			// list years (this + 2)
			$years[0] = date('Y');
			$years[1] = $years[0] + 1;
			$years[2] = $years[0] + 2;
			$this->smarty->assign('years', $years);
			
			// prepare caption
			$this->smarty->assign('caption', _l('Please choose a year to edit:'));
			
			// return
			return $this->smarty->fetch('smarty.holiday.list.tpl');
		}
	}

}

?>
