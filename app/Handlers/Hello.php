<?php

declare(strict_types=1);

namespace App\Handlers;

/**
 * A route handler as a class. The container makes it, so its constructor
 * may ask for any service by type. __invoke() takes the route's
 * parameters by name, and what it returns is sent as JSON.
 */
final class Hello
{
    /**
     * @return array{hello: string}
     */
    public function __invoke(string $name): array
    {
        return ['hello' => $name];
    }
}
