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
 * class ResultListing implements the parent class for result listings
 */
class ResultListing extends Listing implements ListingInterface {
	
	
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
		
		// smarty
		$sList = array();
		foreach($results as $row) {
			
			// prepare smarty templates for links and images
			// name
			$smarty = new JudoIntranetSmarty();
			$nameLinkArray = array(
				array(
						'href' => 'calendar.php?id=details&cid='.$row['calendar_id'],
						'title' => _l('calendar details'),
						'name' => $row['name'],
					)
				);
			$smarty->assign('data', $nameLinkArray);
			$nameLink = $smarty->fetch('smarty.a.img.tpl');
			
			// show
			$showArray = array(
				array(
						'href' => 'result.php?id=details&rid='.$row['id'],
						'title' => _l('result details'),
						'name' => array(
								'src' => 'img/res_details.png',
								'alt' => _l('result details'),
							),
					),
				array(
						'href' => 'file.php?id=cached&table=result&tid='.$row['id'],
						'title' => _l('result pdf'),
						'name' => array(
								'src' => 'img/res_pdf.png',
								'alt' => _l('result pdf'),
							),
					),
				);
			$smarty->assign('data', $showArray);
			$show = $smarty->fetch('smarty.a.img.tpl');
				
			// if user is loggedin add admin-links
			$admin = '';
			if($this->getUser()->get_loggedin() === true) {
				
				// delete
				$adminArray = array(
					array(
							'href' => 'result.php?id=delete&rid='.$row['id'],
							'title' => _l('result delete'),
							'name' => array(
									'src' => 'img/res_delete.png',
									'alt' => _l('result delete'),
								),
						),
					);
				$smarty->assign('data', $adminArray);
				$admin = $smarty->fetch('smarty.a.img.tpl');
			}
			
			$sList[] = array(
					'desc' => $row['desc'],
					'name' => $nameLink,
					'date' => date('d.m.Y', strtotime($row['date'])),
					'city' => $row['city'],
					'show' => $show,
					'admin' => $admin,
				);
		}
			
		// return
		return $sList;
	}
	
}
?>
