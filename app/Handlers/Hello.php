<?php

declare(strict_types=1);

namespace App\Handlers;

use Crab\Http\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * A route handler as a class. The container makes it, so its constructor
 * may ask for any service by type.
 */
final class Hello implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return Response::json(['hello' => $request->getAttribute('name')]);
    }
}
