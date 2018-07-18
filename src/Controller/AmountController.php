<?php

namespace App\Controller;

use App\Kernel\Http\Request;
use App\Kernel\Http\Response;
use App\Kernel\Router\Router;

class AmountController extends BaseController
{
    public function index(Request $request)
    {
        return new Response('AmountController:index');
    }
}