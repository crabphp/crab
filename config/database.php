<?php

declare(strict_types=1);

return [
    // The first connection is used when none is named. Add another and reach it with $database->connection('name').
    'connections' => [
        'main' => [
            // "sqlite:/path/to/file.sqlite", or "mysql:host=127.0.0.1;dbname=app".
            'dsn' => env('DB_DSN', 'sqlite:' . dirname(__DIR__) . '/storage/database.sqlite'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
        ],
    ],
];
