<?php

use Crab\Routing\Router;
use Crab\Http\Request;
use Crab\Http\Response;

return function (Router $router) {
    $router->get('/', function () {
        return Response::text('CrabPHP OK');
    });

    $router->get('/hello', function () {
        return 'Hello CrabPHP';
    });

    $router->get('/echo', function (Request $request) {
        $q = $request->query('q', '');
        return $q !== '' ? $q : 'echo';
    });
};
