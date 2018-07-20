<?php

namespace App\Controller;

use App\Entity\User;
use App\Kernel\Http\Request;
use App\Kernel\Http\Response;

class HomeController extends BaseController
{
    /**
     * @param Request $request
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function index(Request $request)
    {
        return $this->render('home/index.html.twig');
    }

}