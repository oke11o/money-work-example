<?php

namespace App\Exception\Security;

/**
 * Class InvalidPasswordException
 * @package App\Exception\Security
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
class InvalidPasswordException extends SecurityException
{
    public function __construct()
    {
        parent::__construct('Invalid password');
    }
}