<?php

namespace App\Exception\Security;

/**
 * Class UserNotFoundException
 * @package App\Exception\Security
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
class UserNotFoundException extends SecurityException
{
    public function __construct($username)
    {
        parent::__construct(sprintf('User with username %s not found', $username));
    }
}