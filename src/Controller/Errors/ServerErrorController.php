<?php

namespace App\Controller\Errors;

use App\Controller\BaseController;
use App\Kernel\Http\Request;

/**
 * Class NotFoundController
 * @package App\Controller\Errors
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
class ServerErrorController extends BaseController
{
    public const ACTION_NAME_500 = '500';
    /**
     * @param Request $request
     * @param \Throwable $exception
     * @return \App\Kernel\Http\Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function index(Request $request, \Throwable $exception)
    {
        return $this->render('errors/500.html.twig', [
            'message' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}