<?php

namespace App\Exception\DataMapper;

class MapperNotFoundException extends \RuntimeException
{
    public function __construct(string $class)
    {
        parent::__construct(sprintf('DataMapper for class "%s" not found', $class));
    }
}