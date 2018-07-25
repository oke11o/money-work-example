<?php

namespace App\Exception\Router;

/**
 * Class NotFoundRoute
 * @package App\Exception\Router
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
class NotFoundRouteException extends RouterException
{
    /**
     * NotFoundRouteException constructor.
     * @param string $routeName
     */
    public function __construct(string $routeName)
    {
        parent::__construct(sprintf('Cannot find controller="%s', $routeName));
    }
}