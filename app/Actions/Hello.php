<?php

declare(strict_types=1);

namespace App\Actions;

/**
 * An action as a class: the code that answers a route. The container
 * makes it, so its constructor may ask for any service by type.
 * handle() takes the route's parameters by name, and what it returns
 * is sent as JSON.
 */
final class Hello
{
    /**
     * @return array{hello: string}
     */
    public function handle(string $name): array
    {
        return ['hello' => $name];
    }
}
