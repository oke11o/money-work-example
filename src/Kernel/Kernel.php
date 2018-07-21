<?php

namespace App\Kernel;

use App\Controller\BaseController;
use App\Exception\Kernel\KernelException;
use App\Kernel\Http\Request;
use App\Kernel\Http\Response;
use App\Kernel\Router\Router;
use Twig_Environment;

/**
 * Class Kernel
 * @package App\Kernel
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
class Kernel
{
    /**
     * @var ParameterBag
     */
    private $config;
    /**
     * @var array
     */
    private $routes = [];
    /**
     * @var Container
     */
    private $container;
    /**
     * @var string
     */
    private $rootDir;
    /**
     * @var string
     */
    private $configDir;

    /**
     * @var string
     */
    private $configName = '';

    /**
     * @var string
     */
    private $configLocalName = '';

    /**
     * @var string
     */
    private $routesFilename = '';
    /**
     * @var ContainerBuilder
     */
    private $containerBuilder;
    /**
     * @var string
     */
    private $environment;

    public function __construct($rootDir = '', $environment = 'prod', ContainerBuilder $containerBuilder)
    {
        $this->rootDir = $rootDir;
        if (!$this->rootDir) {
            $this->rootDir = dirname(dirname(__DIR__));
        }
        $this->configDir = 'config';
        $this->environment = $environment;

        $this->routesFilename = 'routes.php';
        if (\in_array($environment, ['prod', 'dev'])) {
            $this->configName = 'config.php';
            $this->configLocalName = 'config_local.php';
        } else {
            $this->configName = "config_$environment.php";
            $this->configLocalName = "config_local_$environment.php";
        }
        $this->containerBuilder = $containerBuilder;
    }

    /**
     * @return string
     */
    public function getConfigFilePath(): string
    {
        return $this->rootDir.DIRECTORY_SEPARATOR.$this->configDir.DIRECTORY_SEPARATOR.$this->configName;
    }

    /**
     * @return string
     */
    public function getLocalConfigFilePath(): string
    {
        return $this->rootDir.DIRECTORY_SEPARATOR.$this->configDir.DIRECTORY_SEPARATOR.$this->configLocalName;
    }

    /**
     * @return string
     */
    public function getRoutesFilePath(): string
    {
        return $this->rootDir.DIRECTORY_SEPARATOR.$this->configDir.DIRECTORY_SEPARATOR.$this->routesFilename;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function run(Request $request): Response
    {
        $this->boot();

        /** @var Router $router */
        $router = $this->container->get(Router::class);
        /** @var Twig_Environment $twig */
        $twig = $this->container->get(Twig_Environment::class);

        $controllerPair = $router->match($request);

        try {
            $controllerName = $controllerPair->getController();
            /** @var BaseController $controller */
            $controller = new $controllerName($router, $twig, $this->container);
            $actionName = $controllerPair->getAction();

            return $controller->$actionName($request);
        } catch (\Exception|\Error $exception) {
            $controllerPair = $router->getServerError();

            $controllerName = $controllerPair->getController();
            /** @var BaseController $controller */
            $controller = new $controllerName($router, $twig, $this->container);
            $actionName = $controllerPair->getAction();

            return $controller->$actionName($request, $exception);
        }
    }

    /**
     * @throws KernelException
     */
    private function boot(): void
    {
        $this->parseConfig();
        $this->parseRoutes();
        $this->buildContainer();
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws KernelException
     */
    private function buildContainer(): void
    {
        //TODO: need DI
        $this->container = $this->containerBuilder->create($this->rootDir, $this->environment, $this->routes, $this->config->get('db'));
    }

    /**
     * @throws KernelException
     */
    private function parseConfig(): void
    {
        $configFilepath = $this->getConfigFilePath();
        $config = include $configFilepath;
        $localConfigFile = $this->getLocalConfigFilePath();

        if (\file_exists($localConfigFile)) {
            $localConfig = include $localConfigFile;
            $config = \array_merge($config, $localConfig);
        }

        $this->validateConfig($config);

        $this->config = new ParameterBag($config);
    }

    /**
     * @throws KernelException
     */
    private function parseRoutes(): void
    {
        $routesFilepath = $this->getRoutesFilePath();
        $this->routes = include $routesFilepath;
        $this->validateRoutes($this->routes);
    }

    /**
     * @param array $config
     * @throws KernelException
     */
    private function validateConfig($config): void
    {
        //TODO: create validation
//        throw new ConfigValidationException();
    }

    /**
     * @param array $routes
     * @throws KernelException
     */
    private function validateRoutes($routes): void
    {
        //TODO: create validation
//        throw new RouteValidationException();
    }
}