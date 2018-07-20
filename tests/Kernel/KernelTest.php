<?php

namespace App\Tests\Kernel;

use App\Kernel\ContainerBuilder;
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
     * @var ContainerBuilder|ObjectProphecy
     */
    private $containerBuilder;
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
        $this->containerBuilder = $this->prophesize(ContainerBuilder::class);
        $this->kernel = new Kernel('', 'test', $this->containerBuilder->reveal());

        $this->router = $this->prophesize(Router::class);
        $this->twig = $this->prophesize(Twig_Environment::class);
    }

    /**
     * @test
     */
    public function configFilePaths()
    {
        $root = dirname(dirname(__DIR__));
        $kernel = new Kernel('', 'test', $this->containerBuilder->reveal());

        $this->assertEquals($root.'/config/config_test.php', $kernel->getConfigFilePath());
        $this->assertEquals($root.'/config/config_local_test.php', $kernel->getLocalConfigFilePath());
        $this->assertEquals($root.'/config/routes.php', $kernel->getRoutesFilePath());
    }

    /**
     * @test
     */
    public function testRun()
    {
        $this->markTestIncomplete();

        $this->containerBuilder->create(Argument::type('string'), Argument::type('array'), Argument::type('array'))
            ->shouldBeCalled()->willReturn($this->container->reveal());
        $this->container->get(Router::class)->shouldBeCalled()->willReturn($this->router->reveal());
        $this->container->get(Twig_Environment::class)->shouldBeCalled()->willReturn($this->twig->reveal());

        $request = new Request([], [], [], [], []);
        $this->kernel->run($request);
    }
}
