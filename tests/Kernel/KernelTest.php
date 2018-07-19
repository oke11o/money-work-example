<?php

namespace App\Tests\Kernel;

use App\Kernel\Kernel;
use PHPUnit\Framework\TestCase;

class KernelTest extends TestCase
{
    /**
     * @var Kernel
     */
    private $kernel;

    public function setUp()
    {
        $this->kernel = new Kernel('', 'test');
    }

    public function testConfigFilePaths()
    {
        $kernel = new Kernel('', 'test');

        $root = dirname(dirname(__DIR__));
        $this->assertEquals($root.'/config/config_test.php', $kernel->getConfigFilePath());
        $this->assertEquals($root.'/config/config_local_test.php', $kernel->getLocalConfigFilePath());
        $this->assertEquals($root.'/config/routes_test.php', $kernel->getRoutesFilePath());
    }
}
