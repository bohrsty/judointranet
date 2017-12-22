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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JudoIntranet\Helper\ApiV2ResponseHelper;

/**
 * global abstract for the v2 api calls
 */
class AbstractApiController extends Controller {
	
	/**
	 * the current URI
	 */
	private $uri;
	
	
	
	/**
	 * getUri()
	 * 
	 * @return string the current value of $uri
	 */
	public function getUri() {
		return $this->uri;
	}
	
	/**
	 * setUri($uri)
	 * 
	 * @param string $uri the URI to set
	 * @return \Symfony\Bundle\FrameworkBundle\Controller\Controller
	 */
	public function setUri($uri) {
		$this->uri = $uri;
		
		return $this;
	}
	
	
	
	/**
	 * constructor
	 */
	public function __construct() {
		
		// set URI
		$this->setUri('/api/v2/');
	}
	
	
	
	/**
	 * getApiResponse($data, $isError)
	 * embeds the api data into api response
	 * 
	 * @param mixed $data the data to embed (array or string)
	 * @param bool $isError if true error response, result otherwise
	 * @return array the response for JSON response
	 */
	protected function getApiResponse($data, $isError = false) {
		
		// prepare values
		$uri = $this->getUri();
		
		// return
		return ApiV2ResponseHelper::getApiResponse($data, $uri, $isError);
	}
	
	
	/**
	 * getApiServices()
	 * collects the information about the available services and returns them as array
	 * 
	 * @return array the information about the available services
	 */
	protected function getApiServices() {
		return array();
	}
}
