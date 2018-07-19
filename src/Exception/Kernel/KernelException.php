<?php

namespace App\Exception\Kernel;

use Throwable;

class KernelException extends \RuntimeException
{
    public function __construct($message = '', $code = 0, Throwable $previous)
    {
        parent::__construct($message, $code, $previous);
    }
}