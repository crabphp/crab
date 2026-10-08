<?php

declare(strict_types=1);

use App\Providers\AppProvider;
use Crab\App;
use Crab\Database\DatabaseProvider;

// Describes the application. `php cua` runs it and the tests load it.
// The .env file and the config folder beside this file are loaded by default.

return App::configure(__DIR__)
    ->withProviders([
        DatabaseProvider::class,
        AppProvider::class,
    ])
    ->withRouting(
        routes: 'routes/web.php',
        commands: 'routes/console.php',
        health: '/up',
    )
    ->create();
