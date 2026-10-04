<?php

declare(strict_types=1);

return [
    // A file path, or a stream such as php://stderr.
    'path' => env('LOG_PATH', 'php://stderr'),

    // The least severe level written: debug, info, notice, warning, error, critical, alert or emergency.
    'level' => env('LOG_LEVEL', 'debug'),
];
