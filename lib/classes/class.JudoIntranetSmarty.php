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
 * JudoIntranetSmarty configures Smarty
 */
class JudoIntranetSmarty extends Smarty {

	public function __construct() {

		// setup parent
		parent::__construct();
		
		// set directories
		$this->setTemplateDir(JIPATH.'/templates/');
		$this->setCompileDir(JIPATH.'/templates/smarty/compile/');
		$this->setConfigDir(JIPATH.'/templates/smarty/config/');
		$this->setCacheDir(JIPATH.'/templates/smarty/cache/');
		
		// add plugin
		$this->registerPlugin('block', 'lang', array($this, 'smarty_block_lang'));
	}
	
	
	/**
	 * {lang}...{/lang}
	 * 
	 * @param array $params parameters
	 * @param string $content contents of the block
	 * @param Smarty_Internal_Template $template template object
	 * @param boolean &$repeat repeat flag
	 * @return string content re-formatted
	 */
	function smarty_block_lang($params, $content, $template, &$repeat) {
		
		// return if no content given
		if(is_null($content)) {
			return;
		}
		
		// check parameters
		if(count($params) > 0) {
			
			// translate with replacement
			return _l($content, $params);
		} else {
			
			// just translate and return
			return _l($content);
		}
	}

}

?>
