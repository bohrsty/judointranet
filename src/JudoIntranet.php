<?php

/*
 * This file is part of the JudoIntranet package.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JudoIntranet;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * class to get various information about the software
 */
class JudoIntranet extends Bundle {
	
	
	/**
	 * getVersion()
	 * returns the current version from composer.json
	 * 
	 * @return string
	 */
	public static function getVersion() {
		
		// read composer.json
		$json = file_get_contents(__DIR__.'/../composer.json');
		
		// get json as array
		$data = json_decode($json, true);
		
		// return
		return $data['version'];
	}
}