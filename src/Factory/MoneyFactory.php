<?php

namespace App\Factory;

use App\Dictionary\CurrencyUnitsDictionary;
use App\Enum\AvailableCurrencyEnum;
use DI\Annotation\Injectable;
use Money\Currency;
use Money\Money;

/**
 * Class MoneyFactory
 * @package App\Factory
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 *
 * @Injectable(lazy=true)
 */
class MoneyFactory
{
    /**
     * @param float $amount
     * @param string $currency
     * @return Money
     *
     * @throws \InvalidArgumentException
     */
    public function createFromFloat(float $amount, string $currency = AvailableCurrencyEnum::RUB): Money
    {
        if (!\in_array($currency, AvailableCurrencyEnum::getValues(), true)) {
            throw new \InvalidArgumentException(sprintf('Currency "%s" not available', $currency));
        }

        $mul = 10 ** CurrencyUnitsDictionary::UNITS[$currency];

        return new Money((int)($mul * $amount), new Currency($currency));
    }
}