<?php

namespace App\Controller\Errors;

use App\Controller\BaseController;
use App\Kernel\Http\Request;

class ServerErrorController extends BaseController
{
    public function index(Request $request, \Exception $exception)
    {
        throw $exception;
    }
}