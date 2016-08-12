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
 * controller for the protocol page
 */
class ProtocolController extends Controller {
    
    /**
     * @Route("/protocol.php", name="protocol")
     */
    public function protocolAction(Request $request) {
    	
    	// execute old code and catch error 
    	try {
    		
    		// create object
    		$protocolView = new \ProtocolView();
    		
    		// get HTML from smarty template, put into response and return
    		return new Response($protocolView->toHtml(false));
    	}
    	catch(\Exception $e) {
    		return new Response(handleExceptions($e, HANDLE_EXCEPTION_VIEW, false));
    	}
    }
}
