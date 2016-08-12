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
 * class Tribute implements the representation of a tribute object
 */
class JudoIntranetFPDI extends FPDI {
	
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
	
		// parent constructor
		parent::__construct();
	}
	
	/*
	 * methods
	 */
	/**
	 * mergeFiles($files) merge the members of $file to a single pdf and returns it as string
	 * 
	 * @param array array containing the complete path to the pdf files that will be merged
	 * @return string the merged pdf as string
	 */
	public static function mergeFiles($files) {
		
		// get object
		$pdf = new JudoIntranetFPDI();
		
		// walk through $files
		foreach($files as $file) {
			
			$pagecount = $pdf->setSourceFile($file);
			for($i = 1; $i <= $pagecount; $i++) {
				$tplidx = $pdf->ImportPage($i);
				$size = $pdf->getTemplatesize($tplidx);
				
				// check orientation
				if($size['h'] > $size['w']) {
					$pdf->AddPage('P', array($size['w'], $size['h']));
				} else {
					$pdf->AddPage('L', array($size['w'], $size['h']));
				}
				$pdf->useTemplate($tplidx);
			}
		}
		
		// return string
		return $pdf->Output('merged.pdf', 'S');
	}
}
