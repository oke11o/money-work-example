<?php

namespace App\Exception\Kernel;

use Throwable;

/**
 * Class KernelException
 * @package App\Exception\Kernel
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
class KernelException extends \RuntimeException
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}