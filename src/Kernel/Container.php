<?php

namespace App\Kernel;

use App\Exception\Container\NotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class Container
 * @package App\Kernel
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
class Container implements ContainerInterface
{
    /**
     * @var array
     */
    private $services = [];

    public function add(string $key, $service)
    {
        $this->services[$key] = $service;
    }
    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
     * @throws ContainerExceptionInterface Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($id)
    {
        if ($this->has($id)) {
            return $this->services[$id];
        }

        throw new NotFoundException($id);
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id)
    {
        return \array_key_exists($id, $this->services);
    }
}