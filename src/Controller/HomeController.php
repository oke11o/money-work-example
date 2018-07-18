<?php

namespace App\Controller;

use App\Kernel\Http\Request;
use App\Kernel\Http\Response;

class HomeController extends BaseController
{
    public function index(Request $request)
    {
        return $this->render('home/index.html.twig', []);
    }

}