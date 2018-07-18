<?php

namespace App\Controller;

use App\Kernel\Http\Response;
use App\Kernel\Router\Router;

class BaseController
{
    /**
     * @var Router
     */
    private $router;
    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(Router $router, $twig)
    {
        $this->router = $router;
        $this->twig = $twig;
    }

    public function render(string $templatePath, $params)
    {
        return new Response($this->twig->render($templatePath, $params));
    }
}