<?php

namespace VetCabinet\CoreBundle\Auth\Handler;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use Radiooo\AuthBundle\Entity;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class LogoutAuthHandler implements LogoutSuccessHandlerInterface
{
    protected $security;
    protected $container;
    protected $router;

    function __construct(Router $router, SecurityContext $security, $container)
    {
        $this->router = $router;
        $this->security = $security;
        $this->container = $container;
    }

    /**
     * Creates a Response object to send upon a successful logout.
     *
     * @param Request $request
     *
     * @return Response never null
     */
    public function onLogoutSuccess(Request $request)
    {
        $url        = $this->router->generate('_welcome');
        $response   = new RedirectResponse($url);
        return $response;
    }
}