<?php


namespace App\Provider;

use App\Repository\UserRepository;

class UserProvider
{
    /**
     * @var UserRepository
     */
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }
}