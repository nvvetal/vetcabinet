<?php

namespace VetCabinet\CoreBundle\Auth\Handler;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use Radiooo\AuthBundle\Entity;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class LoginAuthHandler implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface
{
    protected $router;
    protected $security;
    protected $entityManager;
    protected $container;

    function __construct(Router $router, SecurityContext $security, $entityManager, $container)
    {
        $this->router = $router;
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->container = $container;
    }

    /**
     * This is called when an interactive authentication attempt fails. This is
     * called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request                 $request
     * @param AuthenticationException $exception
     *
     * @return Response The response to return, never null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // TODO: Implement onAuthenticationFailure() method.
    }

    /**
     * This is called when an interactive authentication attempt succeeds. This
     * is called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request        $request
     * @param TokenInterface $token
     *
     * @return Response never null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $activeTheme = $this->container->get('liip_theme.active_theme');
        if ($activeTheme->getName() === 'phone') {
                return $this->onAuthenticationSuccessPhone($request, $token);
        }
        $res = new Response(json_encode(array('ok' => true)));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function onAuthenticationSuccessPhone(Request $request, TokenInterface $token)
    {
        $key = '_security.secured_area.target_path';
        $session = $request->getSession();
        if($session->has('login_popup') && $session->get('login_popup') === true) {
            $url = $this->router->generate('login_popup_success');
            $session->remove('login_popup');
        } elseif ($session->has($key)) {
            $url = $session->get($key);
            $session->remove($key);
        } else {
            $url = $this->router->generate('_welcome');
        }
        return new RedirectResponse($url);
    }


}