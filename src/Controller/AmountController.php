<?php

namespace App\Controller;

use App\Kernel\Http\Request;

/**
 * Class AmountController
 * @package App\Controller
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
class AmountController extends BaseController
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
        return $this->render('amount/index.html.twig');
    }
}