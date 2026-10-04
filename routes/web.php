<?php

declare(strict_types=1);

use App\Handlers\Hello;
use Crab\Contracts\Routing\Router;

return static function (Router $router): void {
    $router->get('/', static fn() => "Hello from Crab\n");

    $router->get('/hello/{name}', Hello::class);
};
