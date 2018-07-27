<?php

namespace App\Security;

use App\Entity\User;
use App\Provider\UserProvider;
use DI\Annotation\Injectable;

/**
 * Class Authorizer
 * @package App\Security
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 *
 * @Injectable(lazy=true)
 */
class Authorizer
{
    const SESSION_USER_KEY = 'UserId';

    private $sessionStarted = false;
    private $sessionClosed = false;

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
        $this->sessionStart();
        $userId = $_SESSION[self::SESSION_USER_KEY] ?? null;
        $this->sessionClose();
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
        $this->sessionStart(true);
        $_SESSION[self::SESSION_USER_KEY] = $user->getId();
        $this->sessionClose(true);
    }

    /**
     * @throws \Exception
     */
    public function logout()
    {
        $this->sessionStart(true);
        unset($_SESSION[self::SESSION_USER_KEY]);
        $this->resetPhpsessid();
        $this->sessionClose(true);
    }

    protected function sessionStart($force = false): void
    {
        if (!$this->sessionStarted || $force) {
            session_start();
            $this->sessionStarted = true;
        }
    }

    protected function sessionClose($force = false): void
    {
        if (($this->sessionStarted && !$this->sessionClosed) || $force) {
            session_write_close();
            $this->sessionClosed = true;
        }
    }

    protected function resetPhpsessid(): void
    {
        $sidvalue = bin2hex(\random_bytes(13));
        setcookie('PHPSESSID', $sidvalue, 0);
    }
}