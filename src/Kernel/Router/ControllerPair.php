<?php

namespace App\Kernel\Router;

/**
 * Class ControllerPair
 * @package App\Kernel\Router
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
class ControllerPair
{
    /**
     * @var string
     */
    private $controller;
    /**
     * @var string
     */
    private $action;

    public function __construct(string $controller, string $action)
    {
        $this->controller = $controller;
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }
}