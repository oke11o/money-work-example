<?php

namespace App\Controller;

use App\Kernel\Http\Request;
use App\Kernel\Http\Response;

class LoginController extends BaseController
{
    public function index(Request $request)
    {
        return $this->render('login/index.html.twig', ['tmp' => 'tmp']);
    }

}