<?php

namespace App\Kernel;

use App\Controller\BaseController;
use App\Kernel\Http\Request;
use App\Kernel\Http\Response;
use App\Kernel\Router\Router;
use Psr\Container\ContainerInterface;
use Twig_Environment;
use Twig_Function;
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
     * @var ContainerInterface
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
     * @var Router
     */
    private $templateEngine;

    public function __construct($rootDir = '')
    {
        $this->rootDir = $rootDir;
        if (!$this->rootDir) {
            $this->rootDir = dirname(dirname(__DIR__));
        }
        $this->configDir = 'config';
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
            $controller = new $controllerName($this->router, $this->templateEngine);
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

    private function boot(): void
    {
        $this->parseConfig();
        $this->parseRoutes();
        $this->buildContainer();
    }

    private function buildContainer(): void
    {
        //TODO: need container
        $this->router = new Router($this->routes);
        $loader = new Twig_Loader_Filesystem($this->rootDir.DIRECTORY_SEPARATOR.'templates');
        $twig = new Twig_Environment(
            $loader, array(
            'cache' => $this->rootDir.DIRECTORY_SEPARATOR.'var'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'twig',
        )
        );
        $this->templateEngine = $twig;
        $this->templateEngine->addExtension(new TwigExtension($this->router));
    }

    private function parseConfig(): void
    {
        $config = include $this->rootDir.DIRECTORY_SEPARATOR.$this->configDir.DIRECTORY_SEPARATOR.'config.php';
        $localConfigFile = $this->rootDir.DIRECTORY_SEPARATOR.$this->configDir.DIRECTORY_SEPARATOR.'config_local.php';
        if (\file_exists($localConfigFile)) {
            $localConfig = include $localConfigFile;
            $config = \array_merge($config, $localConfig);
        }

        $this->config = new ParameterBag($config);
    }

    private function parseRoutes(): void
    {
        $this->routes = include $this->rootDir.DIRECTORY_SEPARATOR.$this->configDir.DIRECTORY_SEPARATOR.'routes.php';
    }

}