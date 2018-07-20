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
    public function getAuthUser():?User
    {
        session_start();
        $userId = $_SESSION[$this->getSessionUserKey()] ?? null;
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
        $_SESSION[$this->getSessionUserKey()] = $user->getId();
        session_write_close();
    }

    public function logout()
    {
        session_start();
        unset($_SESSION[$this->getSessionUserKey()]);
        session_write_close();
    }

    /**
     * @return string
     */
    private function getSessionUserKey(): string
    {
        return 'UserId';
    }

}