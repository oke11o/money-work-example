<?php

if (PHP_SAPI == 'cli-server') {
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

use App\Kernel\Kernel;
use App\Kernel\Http\Request;


require __DIR__.'/../vendor/autoload.php';
if ($_SERVER['REQUEST_URI'] === '/favicon.ico') {
    die();
}

$kernel = new Kernel();
$request = new Request($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
$response = $kernel->run($request);
$response->send();
