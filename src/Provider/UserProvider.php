<?php


namespace App\Provider;

use App\DataMapper\UserMapper;
use App\Entity\User;
use DI\Annotation\Injectable;

/**
 * Class UserProvider
 * @package App\Provider
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 *
 * @Injectable(lazy=true)
 */
class UserProvider implements UserProviderInterface
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
     * @param $username
     * @return User|null
     */
    public function findByUsername($username): ?User
    {
        return $this->userMapper->findOneByEmail($username);
    }

    /**
     * @param int $id
     * @return User|null
     */
    public function find(int $id): ?User
    {
        return $this->userMapper->find($id);
    }
}