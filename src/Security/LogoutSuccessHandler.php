<?php

/*
 * This file is part of the JudoIntranet project.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JudoIntranet\Security;

use JudoIntranet\Helper\ApiV2ResponseHelper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;


class LogoutSuccessHandler implements LogoutSuccessHandlerInterface {
    
    /**
     * constructor
     */
    public function __construct() {}
    
    
    /**
     * onLogoutSuccess(Request $request)
     * handles the logout response
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function onLogoutSuccess(Request $request) {
        
        // return response
        return new JsonResponse(ApiV2ResponseHelper::getApiResponse('API.success', '/logout'));
    }
}
