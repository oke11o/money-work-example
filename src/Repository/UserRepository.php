<?php

namespace App\Repository;

class UserRepository
{
    private $environment;

    public function __construct(string $environment)
    {
        $this->environment = $environment;
    }
}