<?php

return [
    'home' => [
        'uri' => '/',
        'controller' => \App\Controller\HomeController::class,
        'action' => 'index',
        'methods' => ['GET'],
    ],
    'login' => [
        'uri' => '/login',
        'controller' => \App\Controller\LoginController::class,
        'action' => 'index',
        'methods' => ['GET', 'POST'],
    ],
    'logout' => [
        'uri' => '/logout',
        'controller' => \App\Controller\LoginController::class,
        'action' => 'logout',
        'methods' => ['GET'],
    ],
    'amount' => [
        'uri' => '/amount',
        'controller' => \App\Controller\AmountController::class,
        'action' => 'index',
        'methods' => ['GET'],
    ],
    '_default' => 'home',
    '400' => [
        'controller' => \App\Controller\Errors\NotFoundController::class,
        'action' => 'index',
    ],
    '500' => [
        'controller' => \App\Controller\Errors\ServerErrorController::class,
        'action' => 'index',
    ],
];