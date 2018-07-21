<?php

namespace App\Provider;

use App\Entity\User;

/**
 * Interface UserProviderInterface
 * @package App\Provider
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
interface UserProviderInterface
{
    /**
     * @param $username
     * @return User|null
     */
    public function findByUsername($username): ?User;

    /**
     * @param int $id
     * @return User|null
     */
    public function find(int $id): ?User;
}