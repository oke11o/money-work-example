<?php

namespace App\Security;

use App\Entity\User;
use App\Exception\Security\InvalidPasswordException;
use App\Exception\Security\UserNotFoundException;
use App\Provider\UserProviderInterface;
use DI\Annotation\Injectable;

/**
 * Class Authenticator
 * @package App\Security
 *
 * @author  Sergey Koksharov <info@sharoff.org>
 *
 * @Injectable(lazy=true)
 */
class Authenticator
{
    /**
     * @var UserProviderInterface
     */
    private $userProvider;
    /**
     * @var PasswordEncoder
     */
    private $encoder;

    public function __construct(UserProviderInterface $userProvider, PasswordEncoder $encoder)
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