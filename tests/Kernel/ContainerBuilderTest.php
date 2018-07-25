<?php

namespace App\Tests\Kernel;

use App\Kernel\ContainerBuilder;
use App\Kernel\Kernel;
use Psr\Container\ContainerInterface;

/**
 * Class ContainerBuilderTest
 * @package App\Tests\Kernel
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
class ContainerBuilderTest extends \PHPUnit\Framework\TestCase
{

    public function testCreate()
    {
        $builder = new ContainerBuilder();

        $kernel = new Kernel('test', $builder);

        $config = include $kernel->getConfigFilePath();
        if (\file_exists($kernel->getLocalConfigFilePath())) {
            $localConfig = include $kernel->getLocalConfigFilePath();
            $config = \array_merge($config, $localConfig);
        }
        $routes = include $kernel->getRoutesFilePath();

        $container = $builder->create($kernel->getRootDir(), 'dev', $routes, $config['db']);

        $this->assertInstanceOf(ContainerInterface::class, $container);
    }
}
