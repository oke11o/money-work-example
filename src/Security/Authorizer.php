<?php

namespace App\Security;

use App\Entity\User;
use App\Provider\UserProvider;

/**
 * Class Authorizer
 * @package App\Security
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
class Authorizer
{
    const SESSION_USER_KEY = 'UserId';
    /**
     * @var UserProvider
     */
    private $userProvider;

    public function __construct(UserProvider $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    /**
     * @return User|null
     */
    public function getAuthUser(): ?User
    {
        session_start();
        $userId = $_SESSION[self::SESSION_USER_KEY] ?? null;
        session_write_close();
        if (!$userId) {
            return null;
        }

        return $this->userProvider->find($userId);
    }

    /**
     * @param User $user
     */
    public function saveUserToSession(User $user): void
    {
        session_start();
        $_SESSION[self::SESSION_USER_KEY] = $user->getId();
        session_write_close();
    }

    /**
     * @throws \Exception
     */
    public function logout()
    {
        session_start();
        unset($_SESSION[self::SESSION_USER_KEY]);
        $sidvalue = bin2hex(\random_bytes(13));
        setcookie('PHPSESSID', $sidvalue, 0);
        session_write_close();
    }
}