<?php

namespace App\Controller;

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
    private $router;
    /**
     * @var \Twig_Environment
     */
    private $twig;
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(Router $router, Twig_Environment $twig, ContainerInterface $container)
    {
        $this->router = $router;
        $this->twig = $twig;
        $this->container = $container;
    }

    public function render(string $templatePath, $params)
    {
        return new Response($this->twig->render($templatePath, $params));
    }

    public function redirect($url)
    {
        return new RedirectResponse($url);
    }
}