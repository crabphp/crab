<?php

declare(strict_types=1);

return [
    'address' => env('SERVER_ADDRESS', 'tcp://127.0.0.1:8080'),

    // Worker processes. More than one needs PHP's pcntl and posix extensions.
    'workers' => (int) env('SERVER_WORKERS', 1),

    // What `php cua serve` watches, to restart the server when you save. False to serve without watching.
    'watch' => ['app', 'config', 'routes', '.env'],

    // The largest request body taken, in bytes, and how many seconds a client has to send one. Raise both for uploads.
    'max_body' => 1024 * 1024,
    'body_timeout' => 30,

    // Where `php cua serve --daemon` writes its process id and everything it prints.
    'pid' => 'storage/crab.pid',
    'log' => 'storage/logs/server.log',
];
