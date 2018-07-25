<?php

namespace App\Kernel\Router;

use App\Exception\Kernel\KernelException;
use App\Exception\Router\NotFoundControllerException;
use App\Exception\Router\NotFoundRouteException;
use App\Kernel\Http\Request;
use DI\Annotation\Injectable;

/**
 * Class Router
 * @package App\Kernel\Router
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 *
 * @Injectable(lazy=true)
 */
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
        $requestMethod = $this->parseRequestMethod($request);
        $requestUri = $this->parseRequestUri($request);

        return $this->doMatch($requestMethod, $requestUri);
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
            throw new NotFoundRouteException($routeName);
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
     * @throws \App\Exception\Kernel\KernelException
     */
    protected function setServerErrorController(): ?Route
    {
        $route = $this->routes->getNamedRoute('500');
        if (!$route) {
            throw new KernelException('500 route not difined');
        }
        $this->serverError = new ControllerPair($route->getController(), $route->getAction());

        return $route;
    }

    /**
     * @param $requestMethod
     * @param $requestUri
     * @return ControllerPair
     * @throws \RuntimeException
     */
    private function doMatch($requestMethod, $requestUri): ControllerPair
    {
        /** @var Route $route */
        foreach ($this->routes as $route) {
            if ($route->getMethods() && !\in_array($requestMethod, $route->getMethods(), true)) {
                continue;
            }

            if ($requestUri === $route->getUrl()) {
                return new ControllerPair($route->getController(), $route->getAction());
            }
        }
        $notFoundRoute = $this->routes->getNamedRoute('400');

        if (!$notFoundRoute) {
            throw new NotFoundControllerException();
        }

        return new ControllerPair($notFoundRoute->getController(), $notFoundRoute->getAction());
    }

    /**
     * @param Request $request
     * @return string
     */
    private function parseRequestMethod(Request $request): string
    {
        return $request->getServer()->get('REQUEST_METHOD');
    }

    /**
     * @param Request $request
     * @return string
     */
    private function parseRequestUri(Request $request): string
    {
        $requestUri = $request->getServer()->get('REQUEST_URI');
        if ($needle = strpos($requestUri, '?')) {
            $requestUri = substr($requestUri, 0, $needle);
        }
        if (!$requestUri) {
            $requestUri = '/';
        }

        return $requestUri;
    }
}