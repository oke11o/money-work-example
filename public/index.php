<?php

use App\Kernel\Kernel;
use App\Kernel\Http\Request;

require __DIR__.'/../vendor/autoload.php';
if ($_SERVER['REQUEST_URI'] === '/favicon.ico') {
    die();
}

$kernel = new Kernel('dev');
$request = new Request($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
$response = $kernel->run($request);
$response->send();
