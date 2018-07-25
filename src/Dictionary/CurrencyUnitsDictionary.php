<?php

namespace App\Dictionary;

use App\Enum\AvailableCurrencyEnum;

/**
 * Class CurrenyUnitsDictionary
 * @package App\Dictionary
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
class CurrencyUnitsDictionary
{
    public const UNITS = [
        AvailableCurrencyEnum::RUB => 2,
    ];
}