<?php

/*
 * This file is part of the JudoIntranet project.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JudoIntranet\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * controller for the accounting page
 */
class AccountingController extends Controller {
    
    /**
     * @Route("/accounting.php", name="accounting")
     */
    public function accountingAction(Request $request) {
    	
    	// execute old code and catch error 
    	try {
    		
    		// create object
    		$accountingView = new \AccountingView();
    		
    		// get HTML from smarty template, put into response and return
    		return new Response($accountingView->toHtml(false));
    	}
    	catch(\Exception $e) {
    		return new Response(handleExceptions($e, HANDLE_EXCEPTION_VIEW, false));
    	}
    }
}
