<?php

namespace App\Kernel;

use App\Controller\Errors\ServerErrorController;
use App\Exception\Kernel\KernelException;
use App\Kernel\Http\Request;
use App\Kernel\Http\Response;
use App\Kernel\Router\Router;
use Psr\Container\ContainerInterface;
use Twig_Environment;

/**
 * Class Kernel
 * @package App\Kernel
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
class Kernel
{
    public const ENV_PROD = 'prod';
    public const DI_CONFIG_KEY_ROUTES = 'di.config.routes';
    public const DI_CONFIG_KEY_DB = 'di.config.db';
    public const DI_CONFIG_KEY_TEMPLATE_PATH = 'di.config.templatePath';
    public const DI_CONFIG_KEY_TEMPLATE_CACHE_PATH = 'di.config.templateCachePath';
    public const DI_CONFIG_KEY_ENV = 'di.config.env';
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
     * @var string
     */
    private $environment;

    public function __construct($environment = 'prod')
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
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function run(Request $request): Response
    {
        $this->boot();

        try {
            $router = $this->getRouter();

            $controllerPair = $router->match($request);

            $controller = $this->createController($controllerPair->getController());
            $refMethod = $this->createRefMethod($controller, $controllerPair->getAction());
            $args = $this->createMethodArgs($request, $refMethod);

            return $refMethod->invokeArgs($controller, $args);
        } catch (\Exception|\Error $exception) {
            $controller = new ServerErrorController($router, $this->getTwig(), $this->container);

            return $controller->index($request, $exception);
        }
    }

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
     * @throws \Exception
     */
    private function buildContainer(): void
    {
        if (self::ENV_PROD === $this->environment) {
            $builder = new \DI\ContainerBuilder();
            $builder->enableCompilation($this->rootDir . '/var');
            $builder->writeProxiesToFile(true, $this->rootDir . '/var/proxies');
        } else {
            $builder = new \DI\ContainerBuilder();
        }

        $builder->useAnnotations(true);
        $builder->addDefinitions([
            self::DI_CONFIG_KEY_ROUTES => $this->routes,
            self::DI_CONFIG_KEY_DB => $this->config->get('db'),
            self::DI_CONFIG_KEY_TEMPLATE_PATH => $this->rootDir.'/templates',
            self::DI_CONFIG_KEY_TEMPLATE_CACHE_PATH => $this->rootDir.'/var/cache/twig',
            self::DI_CONFIG_KEY_ENV => $this->environment,
        ]);
        $builder->addDefinitions($this->rootDir.DIRECTORY_SEPARATOR.$this->configDir.DIRECTORY_SEPARATOR.'services.php');
        $container = $builder->build();

        $this->container = $container;
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

    /**
     * @param $controllerName
     * @return object
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    protected function createController($controllerName)
    {
        if (!class_exists($controllerName)) {
            throw new KernelException('Invalid controller name');
        }
        $refClass = new \ReflectionClass($controllerName);
        $refMethod = $refClass->getMethod('__construct');
        $reflectionParams = $refMethod->getParameters();
        $refMethod->getParameters();
        $constructorArgs = [];
        foreach ($reflectionParams as $param) {
            $type = $param->getType()->getName();
            if ($type === ContainerInterface::class) {
                $constructorArgs[] = $this->container;
            } else {
                $constructorArgs[] = $this->container->get($type);
            }
        }

        return $refClass->newInstanceArgs($constructorArgs);
    }

    /**
     * @param $controller
     * @param $actionName
     * @return \ReflectionMethod
     * @throws \ReflectionException
     */
    protected function createRefMethod($controller, $actionName): \ReflectionMethod
    {
        if (!method_exists($controller, $actionName)) {
            throw new KernelException('Invalid controller action name');
        }
        $refClass = new \ReflectionClass($controller);

        return $refClass->getMethod($actionName);
    }

    /**
     * @param Request $request
     * @param \ReflectionMethod $refMethod
     * @return array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function createMethodArgs(Request $request, \ReflectionMethod $refMethod): array
    {
        $args = [];
        $reflectionParams = $refMethod->getParameters();
        foreach ($reflectionParams as $param) {
            $type = $param->getType()->getName();
            if ($type === Request::class) {
                $args[] = $request;
            } else {
                $args[] = $this->container->get($type);
            }
        }

        return $args;
    }

    /**
     * @return Router
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function getRouter(): Router
    {
        return $this->container->get(Router::class);
    }

    /**
     * @return Twig_Environment
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function getTwig(): Twig_Environment
    {
        return $this->container->get(Twig_Environment::class);
    }
}