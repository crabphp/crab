<?php

declare(strict_types=1);

return [
    // The first connection is used when none is named. Add another and reach it with $database->connection('name').
    'connections' => [
        'main' => [
            // mysql or sqlite. SQLite, in storage/, needs no server; set DB_DRIVER=mysql and the rest in .env for MySQL.
            'driver' => env('DB_DRIVER', 'sqlite'),

            // MySQL's server, the database on it, and who connects. For SQLite, the database is the file.
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', 3306),
            'database' => env('DB_DATABASE', dirname(__DIR__) . '/storage/database.sqlite'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),

            // MySQL only: a worker serves other requests while its queries wait.
            // 'async' => true,
        ],
    ],
];
