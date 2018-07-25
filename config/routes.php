<?php


return [
    '_default' => 'home',
    \App\Controller\HomeController::ACTION_NAME_INDEX => [
        'uri' => '/',
        'controller' => \App\Controller\HomeController::class,
        'action' => 'index',
        'methods' => ['GET'],
    ],
    \App\Controller\LoginController::ACTION_NAME_LOGIN => [
        'uri' => '/login',
        'controller' => \App\Controller\LoginController::class,
        'action' => 'index',
        'methods' => ['GET', 'POST'],
    ],
    \App\Controller\LoginController::ACTION_NAME_LOGOUT => [
        'uri' => '/logout',
        'controller' => \App\Controller\LoginController::class,
        'action' => 'logout',
        'methods' => ['GET'],
    ],
    \App\Controller\AmountController::ACTION_NAME_AMOUNT => [
        'uri' => '/amount',
        'controller' => \App\Controller\AmountController::class,
        'action' => 'index',
        'methods' => ['GET'],
    ],
    \App\Controller\AmountController::ACTION_NAME_DONATE => [
        'uri' => '/donate',
        'controller' => \App\Controller\AmountController::class,
        'action' => 'donate',
        'methods' => ['POST'],
    ],
    \App\Controller\Errors\NotFoundController::ACTION_NAME_404 => [
        'controller' => \App\Controller\Errors\NotFoundController::class,
        'action' => 'index',
    ],
    \App\Controller\Errors\ServerErrorController::ACTION_NAME_500 => [
        'controller' => \App\Controller\Errors\ServerErrorController::class,
        'action' => 'index',
    ],
];