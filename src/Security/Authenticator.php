<?php

namespace App\Security;

use App\Entity\User;
use App\Exception\Security\InvalidPasswordException;
use App\Exception\Security\UserNotFoundException;
use App\Provider\UserProvider;

/**
 * Class Authenticator
 * @package App\Security
 *
 * @author  Sergey Koksharov <info@sharoff.org>
 */
class Authenticator
{
    /**
     * @var UserProvider
     */
    private $userProvider;
    /**
     * @var PasswordEncoder
     */
    private $encoder;

    public function __construct(UserProvider $userProvider, PasswordEncoder $encoder)
    {
        $this->userProvider = $userProvider;
        $this->encoder = $encoder;
    }

    /**
     * @param $username
     * @param $password
     * @return User
     * @throws \App\Exception\Security\SecurityException
     */
    public function authenticate($username, $password): User
    {
        $user = $this->userProvider->findByUsername($username);
        if (!$user) {
            throw new UserNotFoundException($username);
        }

        if (!$this->checkPassword($password, $user->getPassword())) {
            throw new InvalidPasswordException();
        }

        return $user;
    }

    /**
     * @param string $password
     * @param string $hash
     * @return bool
     */
    private function checkPassword(string $password, string $hash): bool
    {
        return $this->encoder->verifyPassword($password, $hash);
    }

}