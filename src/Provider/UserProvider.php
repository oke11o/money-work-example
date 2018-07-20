<?php


namespace App\Provider;

use App\Entity\User;

class UserProvider
{
    /**
     * @param $username
     * @return User|null
     */
    public function findByUsername($username): ?User
    {
        if ('username' === $username) {
            return $this->createTmpUser();
        }
    }

    /**
     * @param int $id
     * @return User|null
     */
    public function find(int $id): ?User
    {
        if (1 === $id) {
            return $this->createTmpUser();
        }
    }

    /**
     * @return User
     */
    private function createTmpUser(): User
    {
        return (new User())
            ->setEmail('username')
            ->setId(1);
    }
}