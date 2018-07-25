<?php

namespace App\Kernel\Router;

/**
 * Class RouteCollection
 * @package App\Kernel\Router
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
class RouteCollection implements \Iterator
{
    /**
     * @var int
     */
    private $position = 0;
    /**
     * @var Route[]
     */
    private $routes = [];
    /**
     * @var Route[]
     */
    private $cache = [];

    /**
     * @param Route $route
     */
    public function addRoute(Route $route)
    {
        $this->routes[] = $route;
        $this->cache[$route->getName()] = $route;
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return $this->routes[$this->position];
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return isset($this->routes[$this->position]);
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * @param $name
     * @return Route|null
     */
    public function getNamedRoute($name)
    {
        return $this->cache[$name] ?? null;
    }
}