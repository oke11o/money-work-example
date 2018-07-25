<?php

namespace App\Kernel;

use App\Controller\BaseController;
use App\Controller\Errors\ServerErrorController;
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
    private $configName;
    /**
     * @var string
     */
    private $envConfigName;
    /**
     * @var string
     */
    private $localConfigName;
    /**
     * @var string
     */
    private $routesFilename ;
    /**
     * @var ContainerBuilder
     */
    private $containerBuilder;
    /**
     * @var string
     */
    private $environment;

    public function __construct($environment = 'prod', ContainerBuilder $containerBuilder)
    {
        $this->rootDir = dirname(dirname(__DIR__));
        $this->environment = $environment;

        $this->routesFilename = 'routes.php';

        $this->configDir = 'config';
        $this->configName = 'config.php';
        $this->envConfigName = "config_$environment.php";
        if ('test' === $environment) {
            $this->localConfigName = "config_local_$environment.php";
        } else {
            $this->localConfigName = 'config_local.php';
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
    public function getEnvConfigFilePath(): string
    {
        return $this->rootDir.DIRECTORY_SEPARATOR.$this->configDir.DIRECTORY_SEPARATOR.$this->envConfigName;
    }

    /**
     * @return string
     */
    public function getLocalConfigFilePath(): string
    {
        return $this->rootDir.DIRECTORY_SEPARATOR.$this->configDir.DIRECTORY_SEPARATOR.$this->localConfigName;
    }

    /**
     * @return string
     */
    public function getRoutesFilePath(): string
    {
        return $this->rootDir.DIRECTORY_SEPARATOR.$this->configDir.DIRECTORY_SEPARATOR.$this->routesFilename;
    }

    /**
     * @return string
     */
    public function getRootDir(): string
    {
        return $this->rootDir;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function run(Request $request): Response
    {
        $this->boot();

        /** @var Twig_Environment $twig */
        $twig = $this->container->get(Twig_Environment::class);
        /** @var Router $router */
        $router = $this->container->get(Router::class);

        $controllerPair = $router->match($request);

        try {
            $controllerName = $controllerPair->getController();
            if (!class_exists($controllerName)) {
                throw new KernelException('Invalid controller name');
            }
            /** @var BaseController $controller */
            $controller = new $controllerName($router, $twig, $this->container);
            $actionName = $controllerPair->getAction();
            if (!method_exists($controller, $actionName)) {
                throw new KernelException('Invalid controller action name');
            }

            return $controller->$actionName($request);
        } catch (\Exception|\Error $exception) {
            $controller = new ServerErrorController($router, $twig, $this->container);

            return $controller->index($request, $exception);
        }
    }

    /**
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\Container\ContainerExceptionInterface
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
        $this->container = $this->containerBuilder->create(
            $this->rootDir,
            $this->environment,
            $this->routes,
            $this->config->get('db')
        );
    }

    /**
     * @throws KernelException
     */
    private function parseConfig(): void
    {
        $configFilepath = $this->getConfigFilePath();
        $config = include $configFilepath;

        $config = $this->mergeEnvConfig($config);
        $config = $this->mergeLocalConfig($config);

        $this->validateConfig($config);

        $this->config = new ParameterBag($config);
    }

    /**
     * @param array $config
     * @return array
     */
    private function mergeEnvConfig(array $config): array
    {
        $localConfigFile = $this->getEnvConfigFilePath();
        if (\file_exists($localConfigFile)) {
            $localConfig = include $localConfigFile;
            $config = \array_merge($config, $localConfig);
        }

        return $config;
    }

    /**
     * @param array $config
     * @return array
     */
    private function mergeLocalConfig(array$config): array
    {
        $localConfigFile = $this->getLocalConfigFilePath();
        if (\file_exists($localConfigFile)) {
            $localConfig = include $localConfigFile;
            $config = \array_merge($config, $localConfig);
        }

        return $config;
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