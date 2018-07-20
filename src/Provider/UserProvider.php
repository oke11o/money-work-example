<?php


namespace App\Provider;

use App\Entity\User;

/**
 * Interface UserProviderInterface
 * @package App\Provider
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
class UserProvider implements UserProviderInterface
{
    /**
     * @param $username
     * @return User|null
     */
    public function findByUsername($username): ?User
    {
        if ('admin@admin.ru' === $username) {
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
            ->setEmail('admin@admin.ru')
            ->setId(1)
            ->setPassword('$2y$10$99ry9IrnRrF2kyyZxEo4WOj9iQItbYIpqeuaalosYxTr.l10ueeva')
            ;
    }
}