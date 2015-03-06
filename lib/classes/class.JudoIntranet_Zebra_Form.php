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
 * JudoIntranet_Zebra_Form configures Zebra_Form
 */
class JudoIntranet_Zebra_Form extends Zebra_Form {

	public function __construct() {

		// setup parent
		call_user_func_array(array('parent', '__construct'), func_get_args());
		
		// set docktype xhtml
		$this->doctype('xhtml');
		
		// prepare translation
		$lang = 'deutsch';
		$langs = array(
				'de_DE' => 'deutsch',
				'de_AT' => 'deutsch',
				'de_CH' => 'deutsch',
				'en_GB' => 'english',
				'en_US' => 'english',
				'fr_FR' => 'francais',
				'es_ES' => 'espanol',
				'it_IT' => 'italiano',
				'sq_AL' => 'albanian',
				'ca_ES' => 'catalan',
				'ro_RO' => 'romana',
			);
		if(array_key_exists(Object::staticGetUser()->get_lang(), $langs)) {
			$lang = $langs[Object::staticGetUser()->get_lang()];
		}
		
		// set language
		$this->language($lang);
		
		// check config for csfr
		if(Object::staticGetGc()->get_config('global.zebraFormCsrf') == '0') {
			$this->csrf(false);
		}
		
	}

}

?>
