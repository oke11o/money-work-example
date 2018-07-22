<?php

namespace App\DataMapper;

/**
 * Interface MapperInterface
 * @package App\DataMapper
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
interface MapperInterface
{
    public function __construct(\PDO $pdo);
}