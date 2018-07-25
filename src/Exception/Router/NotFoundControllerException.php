<?php

namespace App\Exception\Router;

/**
 * Class NotFoundControllerException
 * @package App\Exception\Router
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
class NotFoundControllerException extends RouterException
{
    /**
     * RouterException constructor.
     */
    public function __construct()
    {
        parent::__construct('Cannot find default controller');
    }
}