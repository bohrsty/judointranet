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
 * class CalendarListing implements the parent class for calendar listings
 */
class CalendarListing extends Listing implements ListingInterface {
	
	
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
	 * listingAsArray() returns the listing data as array of associative
	 * arrays (column name => value)
	 * 
	 * @return array array of associative arrays (column name => value) to use with template
	 */
	public function listingAsArray() {
		
		// return empty array
		return array();
	}
	
	
	/**
	 * listingAsHtml($templatefile, $assign) returns the listing data as HTML string
	 * generated from $templatefile; with the use of the required $assign fields
	 * 
	 * @param string $templatefile filename of the template to generate the listing
	 * @param array $assign array of fields required to be assigned in $templatefile
	 * @return string generated HTML string from $template
	 */
	public function listingAsHtml($templatefile, $assign) {
		
		// return empty string
		return '';
	}
	
	
	/**
	 * prepareResults($results) prepares the result array to use with jtable
	 * 
	 * @param array $results array containing results from database
	 * @return array prepared array to use with smarty template
	 */
	protected function prepareResults($results) {
		
		// walk through data
		$return = array();
				
		// prepare file attachement javascript
		$jsFileAttachement = '';
		
		foreach($results as $row) {
			
			// prepare smarty templates for links and images
			// name
			$smarty = new JudoIntranetSmarty();
			$nameLinkArray = array(
					'params' => 'id="c'.$row['id'].'" class="calendarDetails spanLink"',
					'title' => $row['event'],
					'content' => $row['event'],
				);
			$smarty->assign('span', $nameLinkArray);
			$nameLink = $smarty->fetch('smarty.span.tpl');
			
			// show icons for details and pdf
			$showArray = array();
			if(	($row['preset_id'] != 0
					&& $row['has_ann_value'] == 1)
				&& ($row['draftvalue'] == 0
					|| ($row['draftvalue'] == 1 && $this->getUser()->get_loggedin()))) {
				
				// set draft for filenames and translation
				$draftFilename = '';
				$draftTranslate = '';
				if($row['draftvalue'] == 1) {
					$draftFilename = '_draft_'.$this->getUser()->get_lang();
					$draftTranslate = ' draft';
				}
				
				$showArray[] = array(
						'href' => 'announcement.php?id=details&cid='.$row['id'].'&pid='.$row['preset_id'],
						'title' => _l('show announcement'.$draftTranslate),
						'name' => array(
								'src' => 'img/ann_details'.$draftFilename.'.png',
								'alt' => _l('show announcement'.$draftTranslate),
							),
					);
				$showArray[] = array(
						'href' => 'file.php?id=cached&table=calendar&tid='.$row['id'],
						'title' => _l('show announcement pdf'.$draftTranslate),
						'name' => array(
								'src' => 'img/ann_pdf'.$draftFilename.'.png',
								'alt' => _l('show announcement pdf'.$draftTranslate),
							),
					);
			} else {
				$showArray[] = '';
				$showArray[] = '';
			}
			
			// add attached file info
			if($row['files'] > 0) {
				
				$showArray[] = array(
						'span' => true,
						'params' => 'id="f'.$row['id'].'" class="calendarDetails spanLink"',
						'title' => _l('existing attachments'),
						'name' => array(
								'src' => 'img/attachment_info.png',
								'alt' => _l('existing attachments'),
							),
					);
			} else {
				$showArray[] = '';
			}
			
			// add attached result info
			if($row['results'] > 0) {
				
				$showArray[] = array(
						'href' => 'result.php?id=list&cid='.$row['id'],
						'title' => _l('result attached'),
						'name' => array(
								'src' => 'img/result_info.png',
								'alt' => _l('result attached'),
							),
					);
			} else {
				$showArray[] = '';
			}
			
			// add webservice result info
			if($row['webservice_results'] > 0) {
				
				$showArray[] = array(
						'span' => true,
						'params' => 'id="w'.$row['id'].'" class="calendarDetails spanLink"',
						'title' => _l('existing webservice results'),
						'name' => array(
								'src' => 'img/webservice_results.png',
								'alt' => _l('existing webservice results'),
							),
					);
			} else {
				$showArray[] = '';
			}
			
			$smarty->assign('data', $showArray);
			$smarty->assign('spacer', true);
			$show = $smarty->fetch('smarty.a.img.tpl');
			
			// external
			$isExternal = '';
			if($row['is_external'] == 1) {
				$imgArray = array(
						'params' => 'class="icon" title="'._l('is external').'"',
						'src' => 'img/external.png',
						'alt' => _l('is external'),
					);
				$smarty->assign('img', $imgArray);
				$isExternal = $smarty->fetch('smarty.img.tpl');
			}
				
			// add admin
			$adminArray = array();
			$admin = '';
			$annAdmin = '';
			$public = '';
			if($this->getUser()->get_loggedin() === true) {
				
				// edit
				$adminArray[] = array(
						'href' => 'calendar.php?id=edit&cid='.$row['id'],
						'title' => _l('edits entry'),
						'name' => array(
								'src' => 'img/edit.png',
								'alt' => _l('edit'),
							),
					);
				// delete
				$adminArray[] = array(
						'href' => 'calendar.php?id=delete&cid='.$row['id'],
						'title' => _l('deletes entry'),
						'name' => array(
								'src' => 'img/delete.png',
								'alt' => _l('delete'),
							),
					);
				// attachment
				$adminArray[] = array(
						'params' => 'id="attachDetachFiles_'.$row['id'].'" style="cursor: pointer"',
						'span' => true,
						'title' => _l('attach file(s)'),
						'js' => 'handleFileAttachement("#attachDetachFiles_'.$row['id'].'", "calendar", '.$row['id'].', function() {
										$("#calendarListallTable").jtable("reload");
									});',
						'name' => array(
								'src' => 'img/attachment.png',
								'alt' => ('attach file(s)'),
							),
					);
				$smarty->assign('data', $adminArray);
				$admin = $smarty->fetch('smarty.a.img.tpl');
				
				// add announcement admin if not external
				$annAdminArray = array();
				if($row['is_external'] == 0) {
					
					// check for preset form
					$presetForm = $this->checkPresetForm($row['preset_id'], $row['id']);
					if($presetForm['return'] === true) {
						
						// smarty
						$annAdmin = $presetForm['form'];
					} else {
						
						// get new or edit
						$action = '';
						if($row['has_ann_value'] == 1) {
							$action = 'edit';
						} else {
							$action = 'new';
						}
						
						// smarty
						// edit/new
						$annAdminArray[] = array(
								'href' => 'announcement.php?id='.$action.'&cid='.$row['id'].'&pid='.$row['preset_id'],
								'title' => _l('edits announcement'),
								'name' => array(
										'src' => 'img/ann_edit.png',
										'alt' => _l('edit announcement'),
									),
							);
						// delete
						$annAdminArray[] = array(
								'href' => 'announcement.php?id=delete&cid='.$row['id'].'&pid='.$row['preset_id'],
								'title' => _l('delete announcement'),
								'name' => array(
										'src' => 'img/ann_delete.png',
										'alt' => _l('deletes announcement'),
									),
							);
						// refresh pdf
						$annAdminArray[] = array(
								'href' => 'announcement.php?id=refreshpdf&cid='.$row['id'],
								'title' => _l('refresh announcement pdf file'),
								'name' => array(
										'src' => 'img/refresh_pdf.png',
										'alt' => _l('refresh announcement pdf file'),
									),
							);
						// add result if date <= today
						if(strtotime($row['date']) <= strtotime('today')) {
							$annAdminArray[] = array(
									'href' => 'result.php?id=new&cid='.$row['id'],
									'title' => _l('result new'),
									'name' => array(
											'src' => 'img/res_new.png',
											'alt' => _l('result new'),
										),
								);
						}
						
						$smarty->assign('data', $annAdminArray);
						$annAdmin = $smarty->fetch('smarty.a.img.tpl');
					}
				}
			
				// public indicator
				$publicUser = new User(false);
				if($publicUser->hasPermission('calendar', $row['id'], 'r')) {
					$imgArray = array(
							'params' => 'class="icon" title="'._l('public').'"',
							'src' => 'img/public.png',
							'alt' => _l('public'),
						);
					$smarty->assign('img', $imgArray);
					$public = $smarty->fetch('smarty.img.tpl');
				}
			}
			
			// add to return array
			$return[] = array(
					'event' => $nameLink,
					'date' => date('d.m.Y', strtotime($row['date'])),
					'endDate' => (!is_null($row['end_date']) ? date('d.m.Y', strtotime($row['end_date'])) : ''),
					'city' => $row['city'],
					'isExternal' => $isExternal,
					'public' => $public,
					'show' => $show,
					'admin' => $admin.$annAdmin
				);
		}
			
		// return
		return $return;
	}
	
	
	/**
	 * checkPresetForm($presetId, $calendarId) checks if $presetId == 0 and generates a zebra_form
	 * to choose the announcement-preset
	 * 
	 * @param int $presetId the actual preset id to check
	 * @param int $calendarId the actual calendar id
	 * @return array $array['return'] == true indicates, there is a form, false there isn't'
	 */
	private function checkPresetForm($presetId, $calendarId) {
		
		// check preset id
		if($presetId != 0) {
			return array(
					'return' => false,
					'form' => '',
				);
		}
		
		// get random id
		$randomId = Object::getRandomId();
		
		// collect data for signature
		$data = array(
				'apiClass' => 'PresetForm',
				'apiBase' => 'calendar.php',
				'randomId' => $randomId,
			);
		$_SESSION['api'][$randomId] = $data;
		$_SESSION['api'][$randomId]['time'] = time();
		$signedApi = base64_encode(hash_hmac('sha256', json_encode($data), $this->getGc()->get_config('global.apikey')));
		
		// get template
		$sForm = new JudoIntranetSmarty();
		// set variables
		$sForm->assign('id', $calendarId);
		$sForm->assign('url', 'api/internal.php?id='.$randomId.'&signedApi='.$signedApi);
		$sForm->assign('options', Preset::read_all_presets('calendar'));
		
		return array(
					'return' => true,
					'form' => $sForm->fetch('smarty.presetForm.tpl'),
				);
	}
	
	
	/**
	 * getListing($fields) returns an array containing all fields secified in $fields
	 * the user has permissions to
	 * 
	 * @param array $fields array with the required fields as values, id is allways added
	 * @return array array containing the result of the database query
	 */
	public static function getListing($fields) {
		
		// get permitted ids
		$ids = self::staticGetUser()->permittedItems('calendar', 'w') ;
		
		// check if empty result
		$mysqlData = implode(',', $ids);
		if(count($ids) == 0) {
			$mysqlData = 'SELECT FALSE';
		}
		
		// prepare fields
		$mysqlFields = '`id`';
		for($i=0; $i<count($fields); $i++) {
			$mysqlFields .= ',`#?`';
		}
		
		// get data
		$result = Db::ArrayValue('
			SELECT '.$mysqlFields.'
			FROM `calendar`
			WHERE `id` IN (#?)
				AND `valid`=TRUE
			ORDER BY `date` DESC
		',
				MYSQLI_ASSOC,
				array_merge($fields,array($mysqlData)));
		if($result === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// return
		return $result;
	}
	
}
?>
