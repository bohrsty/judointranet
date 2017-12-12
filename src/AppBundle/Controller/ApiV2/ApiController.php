<?php

/*
 * This file is part of the JudoIntranet project.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller\ApiV2;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * controller for the v2 api calls
 */
class ApiController extends AbstractApiController {
	
	/**
	 * @Route("/api/v2/", name="v2home")
	 */
	public function indexAction(Request $request) {
		 
		// set uri
		$this->setUri($request->getPathInfo());
		
		// api info
		$response = array(
			'services' => $this->getApiServices(),
			'documentation' => 'https://',
		);
		
		return new JsonResponse($this->getApiResponse($response));
	}
}
