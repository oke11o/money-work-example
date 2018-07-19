<?php

namespace App\DataMapper;

interface MapperInterface
{
    public function __construct(\PDO $pdo);
}