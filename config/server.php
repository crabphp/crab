<?php

declare(strict_types=1);

return [
    'address' => env('SERVER_ADDRESS', 'tcp://127.0.0.1:8080'),

    // Worker processes. More than one needs PHP's pcntl and posix extensions.
    'workers' => env('SERVER_WORKERS', 1),

    // Run the queue and scheduler inside `start`. On in production.
    'with' => [
        'queue' => env('SERVER_QUEUE', false),
        'schedule' => env('SERVER_SCHEDULE', false),
    ],

    // Every other setting has a default. `php cua config:publish server` writes them all, to change one.
];
