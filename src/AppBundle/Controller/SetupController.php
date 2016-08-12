<?php

/*
 * This file is part of the JudoIntranet project.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * controller for the setup page
 */
class SetupController extends Controller {
    
    /**
     * @Route("/setup.php", name="setup")
     */
    public function setupAction(Request $request) {
    	
    	// require setup functions
    	require_once JIPATH.'/lib/setup.inc.php';
    	
    	// get default config
		$defaultConfig = parse_ini_file(JIPATH.'/cnf/default.ini', true);
		
		// create template
		$tpl = new \JudoIntranetSmarty();
		// set global variables in template
		$tpl->assign('pagename', lang('setup#page#init#name'));
		$tpl->assign('setupDisabledNavi', true);
		$tpl->assign('systemLogo', '../img/logo.png');
		
		// check versions
		if(isset($_SESSION['setup']['version'])) {
			$checkVersion = $_SESSION['setup']['version'];
		} else {
			$checkVersion = checkDbVersion();
		}
		
		// check access
		$access = checkAccess();
		
		if($access === true) {
			
			// smarty
			$tpl->assign('title', lang('setup#init#title#setup'));
			$tpl->assign('main', runSetup($checkVersion));
			$tpl->assign('jquery', true);
			$tpl->assign('zebraform', true);
			$tpl->assign('tinymce', false);
			
		} else {
			
			// smarty
			$tpl->assign('title', lang('setup#init#Error#NotAuthorized'));
			$tpl->assign('main', $access);
			$tpl->assign('jquery', true);
			$tpl->assign('zebraform', false);
			$tpl->assign('tinymce', false);
		}
		
		
		// global smarty
		return new Response($tpl->fetch('smarty.main.tpl'));
    }
}
