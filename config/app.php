<?php

declare(strict_types=1);

return [
    'name' => env('APP_NAME', 'Crab'),

    // The secret what the application signs is signed with, such as cookie sessions.
    // php cua key:generate writes APP_KEY into .env; give each machine its own and never commit it.
    'key' => env('APP_KEY'),

    // Show the details of a failure to the client. Never in production.
    'debug' => env('APP_DEBUG', false),

    // Where the application is reached; the start of every URL Urls writes for a named route.
    'url' => env('APP_URL', 'http://127.0.0.1:8080'),

    // The time zone every date is made, stored and read in. Keep it in UTC; show a person their own time.
    'timezone' => env('APP_TIMEZONE', 'UTC'),
];
