<?php

namespace App\Controller\Errors;

use App\Controller\BaseController;
use App\Kernel\Http\Request;

class ServerErrorController extends BaseController
{
    public function index(Request $request, \Exception $exception)
    {
        return $this->render('errors/500.html.twig', [
            'message' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}