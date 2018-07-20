<?php

namespace App\Controller\Errors;

use App\Controller\BaseController;
use App\Kernel\Http\Request;

/**
 * Class NotFoundController
 * @package App\Controller\Errors
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
class NotFoundController extends BaseController
{
    /**
     * @param Request $request
     * @return \App\Kernel\Http\Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function index(Request $request)
    {
        return $this->render('errors/404.html.twig');
    }
}