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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * controller for login
 */
class SecurityController extends AbstractApiController {
    
    /**
     * @Method("POST")
     * @Route("/api/v2/login", name="login")
     *
     * @param Request $request the current request object
     * @return JsonResponse
     */
    public function loginAction(Request $request) {
        
        // set uri
        $this->setUri($request->getPathInfo());
        
        // check user
        $user = $this->getUser();
        if($user instanceof UserInterface) {
            return new JsonResponse($this->getApiResponse('API.login.alreadyLoggedIn'));
        }
        
        // error
        return new JsonResponse($this->getApiResponse('API.login.loginError', true));
    }
    
    /**
     * @Method("POST")
     * @Route("/api/v2/logout", name="logout")
     *
     * @param Request $request the current request object
     */
    public function logoutAction(Request $request) {
        
    }
    
    /**
     * @Method("GET")
     * @Route("/api/v2/user", name="getUser")
     *
     * @param Request $request the current request object
     * @return JsonResponse
     */
    public function getUserAction(Request $request) {
    
        // set uri
        $this->setUri($request->getPathInfo());
        
        // prepare user
        $response = array();
        
        // get current user
        $user = $this->getUser();
        
        // prepare user information for response
        if(is_null($user)) {
            $response['id'] = 0;
            $response['username'] = 'public';
            $response['name'] = 'Public';
            $response['loggedIn'] = false;
        } else {
            $response['id'] = $user->getId();
            $response['username'] = $user->getUsername();
            $response['name'] = $user->getName();
            $response['loggedIn'] = true;
        }
        
        // response
        return new JsonResponse($this->getApiResponse($response));
    }
}
