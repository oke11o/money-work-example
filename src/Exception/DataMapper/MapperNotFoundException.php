<?php

namespace App\Exception\DataMapper;

/**
 * Class DataMapperException
 * @package App\Exception\DataMapper
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
class MapperNotFoundException extends DataMapperException
{
    public function __construct(string $class)
    {
        parent::__construct(sprintf('DataMapper for class "%s" not found', $class));
    }
}