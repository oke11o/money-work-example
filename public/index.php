<?php

use App\Kernel\ContainerBuilder;
use App\Kernel\Kernel;
use App\Kernel\Http\Request;

if (PHP_SAPI === 'cli-server') {
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__.'/../vendor/autoload.php';
if ($_SERVER['REQUEST_URI'] === '/favicon.ico') {
    die();
}

$kernel = new Kernel('', 'dev', new ContainerBuilder());
$request = new Request($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
$response = $kernel->run($request);
$response->send();
