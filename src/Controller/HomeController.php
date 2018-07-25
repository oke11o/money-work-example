<?php

namespace App\Controller;

use App\Kernel\Http\Request;
use App\Kernel\Http\Response;

/**
 * Class HomeController
 * @package App\Controller
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
class HomeController extends BaseController
{
    public const ACTION_NAME_INDEX = 'home';

    /**
     * @param Request $request
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function index(Request $request)
    {
        return $this->render('home/index.html.twig');
    }

}