<?php

namespace App\Exception\Container;

use Psr\Container\NotFoundExceptionInterface;

class NotFoundException extends \RuntimeException implements NotFoundExceptionInterface
{
    public function __construct(string $key)
    {
        parent::__construct(sprintf('Service "%s" not found in container', $key));
    }
}