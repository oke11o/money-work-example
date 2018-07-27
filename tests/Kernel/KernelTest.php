<?php

namespace App\Tests\Kernel;

use App\Kernel\Http\Request;
use App\Kernel\Kernel;
use App\Kernel\Router\Router;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;
use Twig_Environment;

class KernelTest extends TestCase
{
    /**
     * @var Kernel
     */
    private $kernel;
    /**
     * @var ContainerInterface|ObjectProphecy
     */
    private $container;
    /**
     * @var Router|ObjectProphecy
     */
    private $router;
    /**
     * @var Twig_Environment|ObjectProphecy
     */
    private $twig;

    public function setUp()
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->kernel = new Kernel(Kernel::ENV_TEST);

        $this->router = $this->prophesize(Router::class);
        $this->twig = $this->prophesize(Twig_Environment::class);
    }

    /**
     * @test
     */
    public function configFilePaths()
    {
        $root = dirname(dirname(__DIR__));
        $kernel = new Kernel(Kernel::ENV_TEST);

        $this->assertEquals($root.'/config/config.php', $kernel->getConfigFilePath());
        $this->assertEquals($root.'/config/config_test.php', $kernel->getEnvConfigFilePath());
        $this->assertEquals($root.'/config/config_local_test.php', $kernel->getLocalConfigFilePath());
        $this->assertEquals($root.'/config/routes.php', $kernel->getRoutesFilePath());
    }

    /**
     * @test
     */
    public function testRun()
    {
        $this->markTestIncomplete();

        $this->container->get(Router::class)->shouldBeCalled()->willReturn($this->router->reveal());
        $this->container->get(Twig_Environment::class)->shouldBeCalled()->willReturn($this->twig->reveal());

        $request = new Request([], [], [], [], []);
        $this->kernel->run($request);
    }
}
