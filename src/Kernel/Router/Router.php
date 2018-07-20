<?php

namespace App\Kernel\Router;

use App\Kernel\Http\Request;

class Router
{
    /**
     * @var RouteCollection
     */
    private $routes;

    /**
     * @var string
     */
    private $basePath = '';
    /**
     * @var ControllerPair|null
     */
    private $serverError;

    /**
     * @param array $routesConfig
     * @param null $basePath
     */
    public function __construct(array $routesConfig, $basePath = null)
    {

        $this->routes = $this->parseCollection($routesConfig);

        $this->setBasePath($basePath);
        $this->setServerErrorController();
    }

    /**
     * @param $basePath
     */
    public function setBasePath($basePath)
    {
        $this->basePath = rtrim($basePath, '/');
    }

    /**
     * @param Request $request
     * @return ControllerPair
     * @throws \RuntimeException
     */
    public function match(Request $request): ControllerPair
    {
        $requestMethod = $request->getServer()->get('REQUEST_METHOD');
        $requestUri = $request->getServer()->get('REQUEST_URI');
        if ($needle = strpos($requestUri, '?')) {
            $requestUri = substr($requestUri, 0, $needle);
        }
        if (!$requestUri) {
            $requestUri = '/';
        }

        /** @var Route $route */
        foreach ($this->routes as $route) {
            if ($route->getMethods() && !\in_array($requestMethod, $route->getMethods(), true)) {
                continue;
            }

            if ($requestUri == $route->getUrl()) {
                return new ControllerPair($route->getController(), $route->getAction());
            }

            if ('400' === $route->getName()) {
                $notFound = new ControllerPair($route->getController(), $route->getAction());
            }

        }

        if (!$notFound) {
            throw new \RuntimeException('Undefined default controller');
        }

        return $notFound;
    }

    /**
     * @param $routeName
     * @param array $params
     * @return string
     */
    public function generate($routeName = '', array $params = [])
    {
        $route = $this->routes->getNamedRoute($routeName);
        if (!$route) {
            throw new \RuntimeException(sprintf('Cannot find route "%s"', $routeName));
        }

        $url = $route->getUrl();
        if ($params) {
            $url .= '?'.http_build_query($params);
        }

        return $url;
    }

    /**
     * @param array $routesConfig
     * @return RouteCollection
     */
    protected function parseCollection(array $routesConfig): RouteCollection
    {
        $collection = new RouteCollection();
        $aliases = [];
        foreach ($routesConfig as $name => $route) {
            if (\is_array($route)) {
                $collection->addRoute(
                    new Route(
                        $name,
                        $route['uri'] ?? null,
                        $route['controller'],
                        $route['action'],
                        $route['methods'] ?? []
                    )
                );
            } else {
                $aliases[$route] = $name;
            }
        }
        foreach ($aliases as $key => $name) {
            if ($route = $collection->getNamedRoute($key)) {
                $alias = clone $route;
                $alias->setName($name);
                $collection->addRoute($alias);
            }
        }


        return $collection;
    }

    /**
     * @return ControllerPair|null
     */
    public function getServerError(): ?ControllerPair
    {
        return $this->serverError;
    }

    /**
     * @return Route|null
     */
    protected function setServerErrorController(): ?Route
    {
        $route = $this->routes->getNamedRoute('500');
        $this->serverError = new ControllerPair($route->getController(), $route->getAction());

        return $route;
    }
}