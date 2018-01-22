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
 * controller for the internal, external and help api calls
 */
class ApiController extends Controller {
    
    /**
     * @Route("/api/", name="apihome")
     * @Route("/api/{method}/{table}/", name="apipath2", requirements={"method"="^(?!v2).*"})
     * @Route("/api/{table}/{method}/{id}", name="apipath3", requirements={"table"="^(?!v2).*"})
     * @Route("/api/{table}/{id}/{tid}/{action}", name="apipath", requirements={"table"="^(?!v2).*"})
     * @Route("/api/{table}/{id}/{tid}/{action}/", name="apipath4", requirements={"table"="^(?!v2).*"})
     * @Route("/api/index.php", name="apiindex")
     */
    public function indexAction(Request $request) {
    	
    	// execute old code and catch error 
    	try {
    		
    		// create object
    		$api = new \Api();
    		$api->setContainer($this->container);
    		$api->setUser($this->getUser());
    		
    		// get data from the api
    		$result = $api->handle(false);
    		
    		// check result and create response accordingly
    		if($result['html'] === false) {
    			return new Response(json_encode($result['data']));
    		} else {
    			return new Response($result['data']);
    		}
    	}
    	catch(\Exception $e) {
    		return new Response(handleExceptions($e, HANDLE_EXCEPTION_JSON, false));
    	}
    }
    
    /**
     * @Route("/api/internal.php", name="apiinternal")
     */
    public function internalAction(Request $request) {
    	
    	// execute old code and catch error 
    	try {
    		
    		// create object
    		$api = new \InternalApi();
            $api->setContainer($this->container);
            $api->setUser($this->getUser());
    		
    		// set doctrine
    		$api->setDoctrine($this->getDoctrine());
    		
    		// get data from the api
    		return new Response(json_encode($api->handle(false)));
    	}
    	catch(\Exception $e) {
    		return new Response(handleExceptions($e, HANDLE_EXCEPTION_JSON, false));
    	}
    }
    
    /**
     * @Route("/api/help.php", name="apihelp")
     */
    public function helpAction(Request $request) {
    	
    	// execute old code and catch error 
    	try {
    		
    		// create object
    		$api = new \Help();
    		
    		// get data from the api
    		return new Response(json_encode($api->handle(false)));
    	}
    	catch(\Exception $e) {
    		return new Response(handleExceptions($e, HANDLE_EXCEPTION_JSON, false));
    	}
    }
}
