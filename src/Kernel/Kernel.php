<?php

namespace App\Kernel;

use App\Controller\BaseController;
use App\DataMapper\MapperRepository;
use App\Entity\Transaction;
use App\Entity\User;
use App\Exception\Kernel\KernelException;
use App\Kernel\Http\Request;
use App\Kernel\Http\Response;
use App\Kernel\Router\Router;
use PDO;
use PDOException;
use Twig_Environment;
use Twig_Loader_Filesystem;

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
     * @var Router
     */
    private $router;
    /**
     * @var Twig_Environment
     */
    private $templateEngine;
    /**
     * @var MapperRepository
     */
    private $mapperRepository;

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

    public function __construct($rootDir = '', $environment = 'prod')
    {
        $this->rootDir = $rootDir;
        if (!$this->rootDir) {
            $this->rootDir = dirname(dirname(__DIR__));
        }
        $this->configDir = 'config';

        if ('prod' == $environment) {
            $this->configName = 'config.php';
            $this->configLocalName = 'config_local.php';
            $this->routesFilename = 'routes.php';
        } else {
            $this->configName = "config_$environment.php";
            $this->configLocalName = "config_local_$environment.php";
            $this->routesFilename = "routes_$environment.php";
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

        $controllerPair = $this->router->match($request);

        try {
            $controllerName = $controllerPair->getController();
            /** @var BaseController $controller */
            $controller = new $controllerName($this->router, $this->templateEngine, $this->container);
            $actionName = $controllerPair->getAction();

            return $controller->$actionName($request);
        } catch (\Exception|\Error $exception) {
            $controllerPair = $this->router->getServerError();

            $controllerName = $controllerPair->getController();
            /** @var BaseController $controller */
            $controller = new $controllerName($this->router, $this->templateEngine);
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
     * @throws \App\Exception\Kernel\KernelException
     */
    private function buildContainer(): void
    {
        //TODO: need DI
        $this->container = new Container();

        $this->initRouter();
        $this->initTwig();
        $this->templateEngine->addExtension(new TwigExtension($this->router));

        $this->initDataMapper();
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

    private function initRouter(): void
    {
        $this->router = new Router($this->routes);
        $this->container->add(Router::class, $this->router);
    }

    private function initTwig(): void
    {
        $loader = new Twig_Loader_Filesystem($this->rootDir.DIRECTORY_SEPARATOR.'templates');
        $twig = new Twig_Environment(
            $loader,
            [
                'cache' => $this->rootDir.DIRECTORY_SEPARATOR.'var'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'twig',
            ]

        );
        $this->templateEngine = $twig;

        $this->container->add(Twig_Environment::class, $this->templateEngine);
    }

    /**
     * @throws KernelException
     */
    private function initDataMapper(): void
    {
        $list = [
            User::class,
            Transaction::class,
        ];
        $pdo = $this->createPdo();
        $this->mapperRepository = new MapperRepository($pdo, $list);

        $this->container->add(MapperRepository::class, $this->mapperRepository);
    }

    /**
     * @return PDO
     * @throws KernelException
     */
    private function createPdo(): PDO
    {
        try {
            $config = $this->config->get('db');
            $pdo = new PDO(
                sprintf('mysql:host=%s;dbname=%s', $config['host'], $config['name']),
                $config['user'],
                $config['password']
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new KernelException('Connection failed: '.$e->getMessage(), 0, $e);
        }

        return $pdo;
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