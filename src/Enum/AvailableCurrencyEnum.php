<?php

namespace App\Enum;

/**
 * Class FiatCurrencyEnum
 * @package App\Enum
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
final class AvailableCurrencyEnum extends AbstractEnum
{
    public const RUB = 'RUB';

    /**
     * @return array
     */
    public static function getLabels(): array
    {
        return [
            self::RUB => 'Russian Ruble',
        ];
    }
}
