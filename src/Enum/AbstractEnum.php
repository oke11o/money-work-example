<?php

namespace App\Enum;

/**
 * Class AbstractEnum
 * @package App\Enum
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
abstract class AbstractEnum
{
    /**
     * @return array
     */
    public static function getValues(): array
    {
        return array_values(
            (new \ReflectionClass(static::class))->getConstants()
        );
    }

    /**
     * @return array
     */
    abstract public static function getLabels(): array;

    /**
     * @param $key
     * @return mixed|null
     */
    public static function getLabel($key)
    {
        return static::getLabels()[$key] ?? null;
    }
}
