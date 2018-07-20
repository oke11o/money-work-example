<?php

namespace App\Controller;

use App\Entity\User;
use App\Kernel\Http\RedirectResponse;
use App\Kernel\Http\Response;
use App\Kernel\Router\Router;
use Psr\Container\ContainerInterface;
use Twig_Environment;

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

    protected function redirect($url)
    {
        return new RedirectResponse($url);
    }

    protected function redirectToRoute($route, $params = [])
    {
        return new RedirectResponse($this->router->generate($route, $params));
    }

    protected function getUser(): ?User
    {
        if ($this->container->has(User::class)) {
            return $this->container->get(User::class);
        }
    }

    private function getDefaultTemplateParams()
    {
        return [
            'user' => $this->getUser(),
        ];
    }
}