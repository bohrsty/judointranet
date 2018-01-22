<?php

/*
 * This file is part of the JudoIntranet project.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JudoIntranet\Controller\ApiV2;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * controller for the v2 api calls (navi)
 */
class NaviController extends AbstractApiController {
	
	/**
	 * @Route("/api/v2/navi/", name="v2navihome")
	 */
	public function indexAction(Request $request) {
		
		// set uri
		$this->setUri($request->getPathInfo());
		
		// api navi entries
		$naviCollection = $this->get('navi_collection');
		$response = $naviCollection->loadNavi();

		return new JsonResponse($this->getApiResponse($response));
	}
}
