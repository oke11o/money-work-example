<?php

namespace App\Kernel;

/**
 * Class ParameterBag
 * @package App\Kernel
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
class ParameterBag implements \IteratorAggregate, \Countable
{
    /**
     * Parameter storage.
     */
    protected $parameters;

    /**
     * @param array $parameters An array of parameters
     */
    public function __construct(array $parameters = array())
    {
        $this->parameters = $parameters;
    }


    /**
     * Returns the parameters.
     *
     * @return array An array of parameters
     */
    public function all()
    {
        return $this->parameters;
    }

    /**
     * Returns the parameter keys.
     *
     * @return array An array of parameter keys
     */
    public function keys()
    {
        return array_keys($this->parameters);
    }

    /**
     * Replaces the current parameters by a new set.
     *
     * @param array $parameters An array of parameters
     */
    public function replace(array $parameters = array())
    {
        $this->parameters = $parameters;
    }

    /**
     * Adds parameters.
     *
     * @param array $parameters An array of parameters
     */
    public function add(array $parameters = array())
    {
        $this->parameters = array_replace($this->parameters, $parameters);
    }

    /**
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return array_key_exists($key, $this->parameters) ? $this->parameters[$key] : $default;
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function set($key, $value)
    {
        $this->parameters[$key] = $value;
    }

    /**
     * Returns true if the parameter is defined.
     *
     * @param string $key The key
     *
     * @return bool true if the parameter exists, false otherwise
     */
    public function has($key)
    {
        return array_key_exists($key, $this->parameters);
    }

    /**
     * Removes a parameter.
     *
     * @param string $key The key
     */
    public function remove($key)
    {
        unset($this->parameters[$key]);
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->parameters);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->parameters);
    }
}