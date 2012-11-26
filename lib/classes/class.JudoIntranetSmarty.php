<?php


/**
 * JudoIntranetSmarty configures Smarty
 */
class JudoIntranetSmarty extends Smarty {

	public function __construct() {

		// setup parent
		parent::__construct();
		
		// set directories
		$this->setTemplateDir('templates/');
		$this->setCompileDir('templates/smarty/compile/');
		$this->setConfigDir('templates/smarty/config/');
		$this->setCacheDir('templates/smarty/cache/');
	}

}

?>
