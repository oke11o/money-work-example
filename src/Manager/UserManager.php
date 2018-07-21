<?php

namespace App\Manager;

use App\DataMapper\UserMapper;
use App\Entity\User;
use App\Exception\Manager\UserManagerException;
use Money\Money;

/**
 * Class UserManager
 * @package App\Manager
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
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
     */
    public function withdraw(User $user, Money $money)
    {
        if ($user->getAmount()->lessThan($money)) {
            throw new UserManagerException('Not enough money');
        }

        $this->userMapper->withdraw($user, $money);

        $user->getAmount()->subtract($money);

        return true;
    }
}