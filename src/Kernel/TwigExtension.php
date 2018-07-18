<?php

namespace App\Kernel;

use App\Kernel\Router\Router;
use Twig_Extension;
use Twig_SimpleFunction;

class TwigExtension extends Twig_Extension
{
    /**
     * @var Router
     */
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('path', [$this->router, 'generate']),
        );
    }

}