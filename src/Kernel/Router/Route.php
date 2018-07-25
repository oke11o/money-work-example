<?php

namespace App\Kernel\Router;

/**
 * Class Route
 * @package App\Kernel\Router
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
class Route
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string|null
     */
    private $url;
    /**
     * @var string
     */
    private $controller;
    /**
     * @var string
     */
    private $action;
    /**
     * @var array
     */
    private $methods;

    /**
     * @param string $name
     * @param string|null $uri
     * @param string $controller
     * @param string $action
     * @param array $methods
     */
    public function __construct(string $name, $uri, string $controller, string $action, array $methods = [])
    {
        $this->name = $name;
        $this->url = $uri;
        $this->controller = $controller;
        $this->action = $action;
        $this->methods = $methods;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @return array
     */
    public function getMethods(): array
    {
        return $this->methods;
    }
}