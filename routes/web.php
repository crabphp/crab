<?php

use Crab\Routing\Router;
use Crab\Http\ResponseFactory;
use Psr\Http\Message\ServerRequestInterface;

return function (Router $router) {
    $router->get('/', function () {
        return ResponseFactory::text('CrabPHP OK');
    });

    $router->get('/hello', function () {
        return 'Hello CrabPHP';
    });

    $router->get('/echo', function (ServerRequestInterface $request) {
        $q = $request->getQueryParams()['q'] ?? '';
        return $q !== '' ? $q : 'echo';
    });
};
