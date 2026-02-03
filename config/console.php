<?php

use Crab\Engine\Console\Commands\ServeCommand;
use App\Console\Commands\HelloCommand;

return [
    'commands' => [
        ServeCommand::class,
        HelloCommand::class,
    ],
];
