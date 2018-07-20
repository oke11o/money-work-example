<?php

namespace App\Security;

use App\Entity\User;
use App\Kernel\ParameterBag;
use App\Provider\UserProvider;

/**
 * Class Authorizer
 * @package App\Security
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
class Authorizer
{
    /**
     * @var ParameterBag
     */
    private $session;
    /**
     * @var UserProvider
     */
    private $userProvider;

    public function __construct(ParameterBag $session, UserProvider $userProvider)
    {
        $this->session = $session;
        $this->userProvider = $userProvider;
    }

    /**
     * @return User|null
     */
    public function getAuthUser():?User
    {
        $userId = $this->session->get($this->getSessionUserKey());
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
        $this->session->set($this->getSessionUserKey(), $user->getId());
    }

    /**
     * @return string
     */
    private function getSessionUserKey(): string
    {
        return 'UserId';
    }

}