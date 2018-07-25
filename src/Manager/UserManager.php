<?php

namespace App\Manager;

use App\DataMapper\UserMapper;
use App\Entity\User;
use App\Exception\Manager\UserManagerException;
use DI\Annotation\Injectable;
use Money\Money;

/**
 * Class UserManager
 * @package App\Manager
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 *
 * @Injectable(lazy=true)
 */
class UserManager
{
    /**
     * @var UserMapper
     */
    private $userMapper;

    public function __construct(UserMapper $userMapper)
    {
        $this->userMapper = $userMapper;
    }

    /**
     * @param User $user
     * @param Money $money
     * @throws UserManagerException
     */
    public function withdraw(User $user, Money $money): void
    {
        $error = $this->userMapper->withdraw($user, $money);
        if ($error) {
            throw new UserManagerException($error);
        }
    }
}