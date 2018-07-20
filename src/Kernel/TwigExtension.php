<?php

namespace App\Kernel;

use App\Kernel\Router\Router;
use Twig_Extension;
use Twig_SimpleFunction;

/**
 * Class TwigExtension
 * @package App\Kernel
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
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