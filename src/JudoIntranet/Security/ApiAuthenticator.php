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

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use FOS\UserBundle\Security\UserProvider;
use JudoIntranet\Legacy\Encoder\Md5Bcrypt;
use JudoIntranet\Helper\ApiV2ResponseHelper;

class ApiAuthenticator extends AbstractGuardAuthenticator {
    
    /**
     * @var RouterInterface
     */
    private $router;
    
    /**
     * constructor
     */
    public function __construct(RouterInterface $router) {
        $this->router = $router;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getCredentials(Request $request) {
        
        // check if authenticator has to be used
        if($request->getPathInfo() != '/api/v2/login' || !$request->isMethod('POST')) {
            return;
        }
        
        // return the credentials
        return array(
            'username' => $request->query->get('_username'),
            'password' => $request->query->get('_password'),
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function getUser($credentials, UserProviderInterface $userProvider) {
        
        // check if the correct user provider is used
        if(!$userProvider instanceof UserProvider) {
            return;
        }
        
        // load user
        try {
            return $userProvider->loadUserByUsername($credentials['username']);
        }
        catch(UsernameNotFoundException $e) {
            throw new CustomUserMessageAuthenticationException('User not found');
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function checkCredentials($credentials, UserInterface $user) {
        
        // get encoder
        $encoder = new Md5Bcrypt();
        
        // check login
        if($encoder->isPasswordValid($user->getPassword(), $credentials['password'], null)) {
            return true;
        }
        throw new CustomUserMessageAuthenticationException('Wrong password');
    }
    
    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey) {
        
        // get redirection target
        $target = $this->router->generate('homepage');
        if(!empty($request->query->get('r'))) {
            $target = base64_decode($request->query->get('r'));
        }
        $data = array(
            'redirectTo' => $target,
        );
        
        // generate api response
        $response = ApiV2ResponseHelper::getApiResponse($data, $this->router->generate('login'));
        
        // redirect
        return new JsonResponse($response);
    }
    
    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception) {
        
        // set error
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
        
        // generate api response
        $response = ApiV2ResponseHelper::getApiResponse($exception->getMessage(), $this->router->generate('login'), true);
        
        // return response
        return new JsonResponse($response);
    }
    
    /**
     * {@inheritdoc}
     */
    public function start(Request $request, AuthenticationException $authException = null) {
        
        // generate api response
        $response = ApiV2ResponseHelper::getApiResponse('User not logged in, please login.', $this->router->generate('login'), true);
        
        // return response
        return new JsonResponse($response, Response::HTTP_FORBIDDEN);
    }
    
    /**
     * {@inheritdoc}
     */
    public function supportsRememberMe() {
        return false;
    }
}
