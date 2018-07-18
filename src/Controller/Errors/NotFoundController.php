<?php


namespace App\Controller\Errors;

use App\Kernel\Http\Request;
use App\Kernel\Http\Response;

class NotFoundController
{

    public function index(Request $request)
    {
        return new Response('NotFoundController:index');
    }
}