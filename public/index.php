<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

/** @var \Illuminate\Foundation\Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

/** @var Kernel $kernel */
$kernel = $app->make(Kernel::class);

$request = Request::capture();

$response = $kernel->handle($request);

$response->send();

$kernel->terminate($request, $response);
