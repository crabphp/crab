<?php

declare(strict_types=1);

use App\Handlers\Hello;

/** @var Crab\Contracts\Routing\Router $router */

$router->get('/', static fn() => "Hello from Crab\n");

$router->get('/hello/{name}', Hello::class);
