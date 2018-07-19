<?php

namespace App\Tests\Kernel\Router;

use App\Controller\Errors\ServerErrorController;
use App\Kernel\Router\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    /**
     * @var Router
     */
    private $router;

    public function setUp()
    {
        $root = dirname(dirname(dirname(__DIR__)));
        $routes = include $root.'/config/routes.php';
        $this->router = new Router($routes);
    }

    /**
     * @test
     */
    public function getServerError()
    {
        $controllerPair = $this->router->getServerError();

        $this->assertEquals(ServerErrorController::class, $controllerPair->getController());
        $this->assertEquals('index', $controllerPair->getAction());
    }

    /**
     * @test
     * @dataProvider allRoutesExamples
     */
    public function allRoutes($url)
    {
        $this->markTestIncomplete('Later');
    }

    public function allRoutesExamples()
    {
        return [
            [
                '/'
            ],
        ];
    }
}
