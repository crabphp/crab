<?php

declare(strict_types=1);

return [
    // Show the details of a failure to the client. Never in production.
    'debug' => env('APP_DEBUG', false),

    // Give every request an id, returned in X-Request-Id and logged with failures.
    'request_id' => env('APP_REQUEST_ID', false),
];
