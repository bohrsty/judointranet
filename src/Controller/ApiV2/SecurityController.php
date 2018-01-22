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
     */
    public function loginAction(Request $request) {
        
        // set uri
        $this->setUri($request->getPathInfo());
        
        // check user
        $user = $this->getUser();
        if($user instanceof UserInterface) {
            return new JsonResponse($this->getApiResponse('Already logged in.'));
        }
    }
    
    /**
     * @Method("POST")
     * @Route("/api/v2/logout", name="logout")
     */
    public function logoutAction(Request $request) {
        
    }
}
