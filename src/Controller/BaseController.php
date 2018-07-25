<?php

namespace App\Controller;

use App\Entity\User;
use App\Kernel\Http\RedirectResponse;
use App\Kernel\Http\Response;
use App\Kernel\Router\Router;
use App\Manager\UserManager;
use App\RequestParser\DonateRequestParser;
use App\Security\Authenticator;
use App\Security\Authorizer;
use Psr\Container\ContainerInterface;
use Twig_Environment;

/**
 * Class BaseController
 * @package App\Controller
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
abstract class BaseController
{
    /**
     * @var Router
     */
    protected $router;
    /**
     * @var \Twig_Environment
     */
    protected $twig;
    /**
     * @var ContainerInterface
     */
    protected $container;
    /**
     * @var User
     */
    private $user = false;

    /**
     * BaseController constructor.
     * @param Router $router
     * @param Twig_Environment $twig
     * @param ContainerInterface $container
     */
    public function __construct(Router $router, Twig_Environment $twig, ContainerInterface $container)
    {
        $this->router = $router;
        $this->twig = $twig;
        $this->container = $container;
    }

    /**
     * @param string $templatePath
     * @param array $params
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    protected function render(string $templatePath, array $params = [])
    {
        $params = array_merge($this->getDefaultTemplateParams(), $params);

        return new Response($this->twig->render($templatePath, $params));
    }

    /**
     * @param $url
     * @return RedirectResponse
     */
    protected function redirect($url)
    {
        return new RedirectResponse($url);
    }

    /**
     * @param $route
     * @param array $params
     * @return RedirectResponse
     */
    protected function redirectToRoute($route, $params = [])
    {
        return new RedirectResponse($this->router->generate($route, $params));
    }

    /**
     * @return User|null
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function getUser(): ?User
    {
        if (false === $this->user) {
            $authorizer = $this->getAuthorizer();
            $this->user = $authorizer->getAuthUser();
        }

        return $this->user;
    }

    /**
     * @return array
     */
    private function getDefaultTemplateParams()
    {
        return [
            'user' => $this->getUser(),
        ];
    }

    /**
     * @return Authorizer
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function getAuthorizer(): Authorizer
    {
        return $this->container->get(Authorizer::class);
    }

    /**
     * @return Authenticator
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function getAuthenticator(): Authenticator
    {
        return $this->container->get(Authenticator::class);
    }

    /**
     * @return UserManager
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function getUserManager(): UserManager
    {
        return $this->container->get(UserManager::class);
    }

    /**
     * @return DonateRequestParser
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function getDonateRequestParser(): DonateRequestParser
    {
        return $this->container->get(DonateRequestParser::class);
    }
}