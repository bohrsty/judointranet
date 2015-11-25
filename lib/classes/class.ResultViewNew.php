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
 * class ResultViewNew implements the control of the id "new" result page
 */
class ResultViewNew extends ResultView {
	
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
		
		// check date of calendar entry
		$calendar = new Calendar($this->get('cid'));
		if($calendar->get_date('U') > strtotime('today')) {
			throw new ResultForFutureCalendarException($this, 'Result not possible for future calendar entries.');
		} else {
		
			// check step
			if(isset($_SESSION['import']) && is_array($_SESSION['import'])) {
				// check form
				if($this->post('name_resultUpload1') === false && $this->post('name_resultUpload2') === false) {
					
					unset($_SESSION['import']);
					throw new ResultImportFailedException($this, 'Second step not possible; Session removed, please try again.');
				} else {
					return $this->secondStep();
				}
			}
			
			return $this->firstStep();
		}
	}
	
	
	/**
	 * firstStep() handles the first step of the import
	 * 
	 * @return string output of the first step to be added to the template
	 */
	private function firstStep() {
		
		// form to import result file
		// prepare form
		$form = new Zebra_Form(
				'resultUpload1',		// id/name
				'post',					// method
				'result.php?id=new&cid='.$this->get('cid')	// action
			);
		// set language
		$form->language('deutsch');
		// set docktype xhtml
		$form->doctype('xhtml');
		
		// elements
		// importer
		$options = ResultImporter::returnModules();
		$formIds['importer'] = array('valueType' => 'int', 'type' => 'select',);
		$form->add(
				'label',		// type
				'labelImporter',	// id/name
				'importer',			// for
				_l('import format')	// label text
			);
		$type = $form->add(
				$formIds['importer']['type'],	// type
				'importer',		// id/name
				'',			// default
				array(		// attributes
					)
			);
		$type->add_options($options);
		$type->set_rule(
				array(
						'required' => array(
								'error',
								_l('error select')
							),
					)
			);
		$form->add(
				'note',		// type
				'noteImporter',	// id/name
				'importer',		// for
				_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_RESULTIMPORTER)	// note text
			);
		
		// description
		$formIds['desc'] = array('valueType' => 'string', 'type' => 'text',);
		$form->add(
				'label',		// type
				'labelDesc',	// id/name
				'desc',			// for
				_l('result description')	// label text
			);
		$desc = $form->add(
						$formIds['desc']['type'],		// type
						'desc'		// id/name
			);
		$form->add(
				'note',			// type
				'noteDesc',	// id/name
				'desc',		// for
				_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_RESULTDESC)	// note text
			);
		
		// add rules
		$desc->set_rule(
				array(
						'regexp' => array(
								$this->getGc()->get_config('textarea.regexp.zebra'),
								'error',
								_l('error allowedChars').' ['.$this->getGc()->get_config('textarea.desc').']',
							),
						'required' => array(
								'error',
								_l('error text required'),
							),
					)
			);
		
		
		// file
		$formIds['formContent'] = array('valueType' => 'file', 'type' => 'file',);
		$form->add(
				'label',		// type
				'labelContent',	// id/name
				'formContent',			// for
				_l('resultfile').':'	// label text
			);
		$formContent = $form->add(
						$formIds['formContent']['type'],		// type
						'formContent'		// id/name
			);
		$form->add(
				'note',			// type
				'noteFormContent',	// id/name
				'formContent',		// for
				_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDFILE)	// note text
			);
		
		// add rules
		$allowedFileTypes = ResultImporter::returnModuleFiletypes();
		$formContent->set_rule(
				array(
						'upload' => array(
								$this->getGc()->get_config('global.temp'),
								ZEBRA_FORM_UPLOAD_RANDOM_NAMES,
								'error',
								_l('error upload'),
							),
						'filetype' => array(
								$allowedFileTypes,
								'error',
								_l('only the following file extensions are allowed!').' ['.$allowedFileTypes.']',
						),
						'required' => array(
								'error',
								_l('error required'),
							),
					)
			);
		
		// submit-button
		$form->add(
				'submit',		// type
				'buttonSubmit',	// id/name
				_l('next')	// value
			);
		
		// validate
		if($form->validate()) {
			
			// get form data
			$data = $this->getFormValues($formIds, $form->file_upload, false);
			
			// create importer
			$importer = ResultImporter::factory($data['formContent']['tempFilename'], $data['importer']);

			// get data from importer
			if($importer->validate() === false) {
				
				// unlink temp file
				unlink($data['formContent']['tempFilename']);
								
				throw new ResultImportFailedException($this, 'No valid data for import module "'.$data['importer'].'".');
			}
			$_SESSION['import']['data'] = $importer->getDataAsArray();
			$_SESSION['import']['isTeam'] = $importer->getIsTeam();
			$_SESSION['import']['filename'] = $data['formContent']['filename'];
			$_SESSION['import']['desc'] = $data['desc'];
			
			// unlink temp file
			unlink($data['formContent']['tempFilename']);
			
			// second step
			return $this->secondStep();
		} else {
		
			// pagecaption
			$this->getTpl()->assign('pagecaption', _l('result import'));
			
			return $form->render('', true);
		}
	}
	
	
	/**
	 * secondStep() handles the second step of the import
	 * 
	 * @return string output of the second step to be added to the template
	 */
	private function secondStep() {
		
		// prepare template
		$sCorrectClubs = new JudoIntranetSmarty();
		
		// form to correct clubs
		// prepare form
		$form = new Zebra_Form(
				'resultUpload2',		// id/name
				'post',					// method
				'result.php?id=new&cid='.$this->get('cid')	// action
			);
		// set language
		$form->language('deutsch');
		// set docktype xhtml
		$form->doctype('xhtml');
		
		// preset
		$options = Preset::read_all_presets('result');
		$formIds['preset'] = array('valueType' => 'int', 'type' => 'select',);
		$form->add(
				'label',		// type
				'labelPreset',	// id/name
				'preset',			// for
				_l('preset')	// label text
			);
		$preset = $form->add(
				$formIds['preset']['type'],	// type
				'preset',		// id/name
				'',			// default
				array(		// attributes
					)
			);
		$preset->add_options($options);
		$preset->set_rule(
				array(
						'required' => array(
								'error',
								_l('error select')
							),
					)
			);
		$form->add(
				'note',		// type
				'notePreset',	// id/name
				'preset',		// for
				_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_FIELDTYPE)	// note text
			);
		
		// isTeam
		$sessionIsTeam = $_SESSION['import']['isTeam'];
		$options = array(
				'' => _l('- choose -'),
				0 => _l('single'),
			);
		if($sessionIsTeam !== false) {
			$options[1] = _l('team');
		}
		$formIds['isTeam'] = array('valueType' => 'int', 'type' => 'select',);
		$form->add(
				'label',		// type
				'labelIsTeam',	// id/name
				'isTeam',			// for
				_l('single/team')	// label text
			);
		$isTeam = $form->add(
				$formIds['isTeam']['type'],	// type
				'isTeam',		// id/name
				(is_null($sessionIsTeam) ? '' : ($sessionIsTeam === false ? 0 : 1))		// default
			);
		$isTeam->add_options($options);
		$isTeam->set_rule(
				array(
						'required' => array(
								'error',
								_l('error select')
							),
					)
			);
		$form->add(
				'note',		// type
				'noteIsTeam',	// id/name
				'isTeam',		// for
				_l('help').'&nbsp;'.$this->helpButton(HELP_MSG_ISTEAM)	// note text
			);
		
		// add club check
		$this->addClubCheck($form, $formIds);
		
		// submit-button
		$form->add(
				'submit',		// type
				'buttonSubmit',	// id/name
				_l('save')	// value
			);
		
		// get data from session
		$data = $_SESSION['import']['data'];
		
		// assign data
		$sCorrectClubs->assign('data', $data);
		
		// validate
		if($form->validate()) {
			
			// get form data
			$formData = $this->getFormValues($formIds);
			if(!is_null($sessionIsTeam)) {
				$formData['isTeam'] = ($sessionIsTeam === false ? 0 : 1);
			}
			
			// create result
			$result = new Result(0, $this->get('cid'));
			$result->setIsTeam($formData['isTeam']);
			
			// walk through session data
			for($i = 0; $i < count($data); $i++) {
				
				// set club id and remove club
				$data[$i]['club_id'] = $formData['club_'.$i];
				unset($data[$i]['club']);
				
				// add standings
				$result->addStandings($data[$i]);
			}
			
			// assign to template
			$this->smarty->assign('result', $result);
			$this->smarty->assign('filename', $_SESSION['import']['filename']);
			
			// add preset, desc, isTeam and save result in database
			$result->setPreset($formData['preset']);
			$result->setDesc($_SESSION['import']['desc']);
			$result->setIsTeam($formData['isTeam']);
			$result->writeDb();
			
			// remove session
			unset($_SESSION['import']);
			
			// set js redirection
			$this->jsRedirectTimeout('result.php?id=listall');
					
			// smarty
			return $this->smarty->fetch('smarty.result.new.tpl');
		} else {
			
			// pagecaption
			$this->getTpl()->assign('pagecaption', _l('result import check club'));
			
			return $form->render('lib/zebraTemplate.php', true, array($formIds, 'smarty.resultimporter.tpl', null, $sCorrectClubs));
		}
		
	}
	
	
	/**
	 * addClubCheck(&$form, &$formIds) checks the levenshtein distance of the club entries and adds
	 * form elements for correction to $form
	 * 
	 * @param object $form zebra_form object to add elements to
	 * @param array $formIds ids for zebra_form
	 * @return void
	 */
	private function addClubCheck(&$form, &$formIds) {
		
		// get data array from session
		$data = $_SESSION['import']['data'];
		
		// get club array
		$clubs = Page::readClubs();
		
		// walk through data
		for($i = 0; $i < count($data); $i++) {
			
			// calculate levenshtein
			$clubLevenshtein = array();
			foreach($clubs as $no => $clubArray) {
				
				// set levenshtein distance for each club
				$clubLevenshtein[$no] = levenshtein($clubArray['name'], $data[$i]['club']);
			}
			
			// sort levenshteins and get $no with lowest levenshtein distance
			asort($clubLevenshtein, SORT_NUMERIC);
			$keysClubLevenshtein = array_keys($clubLevenshtein);
			
			// create options array for select
			$options = array();
			foreach($clubs as $no => $temp) {
				$options[$no] = $clubs[$no]['name'];
			}
			
			// add to form
			$formIds['club_'.$i] = array('valueType' => 'int', 'type' => 'select',);
			$club = $form->add(
					$formIds['club_'.$i]['type'],	// type
					'club_'.$i,		// id/name
					$keysClubLevenshtein[0],			// default
					array(		// attributes
						)
				);
			$club->add_options($options, true);
			$club->set_rule(
					array(
							'required' => array(
									'error',
									_l('error select')
								),
						)
				);
		}
	}
}

?>
