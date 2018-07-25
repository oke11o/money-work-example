<?php

namespace Tests\App\Factory;

use App\Enum\AvailableCurrencyEnum;
use App\Factory\MoneyFactory;
use PHPUnit\Framework\TestCase;

class MoneyFactoryTest extends TestCase
{

    /**
     * @var MoneyFactory
     */
    private $factory;

    public function setUp()
    {
        $this->factory = new MoneyFactory();
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Currency "asd" not available
     */
    public function invalidCurrency()
    {
        $this->factory->createFromFloat(1.2, 'asd');
    }

    /**
     * @test
     */
    public function createFromFloat()
    {
        $money = $this->factory->createFromFloat(1.2, AvailableCurrencyEnum::RUB);

        $this->assertEquals(120, $money->getAmount());
        $this->assertEquals(AvailableCurrencyEnum::RUB, $money->getCurrency()->getCode());
    }
}
