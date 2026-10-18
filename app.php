<?php

declare(strict_types=1);

use App\Providers\AppProvider;
use Crab\App;

// Describes the application. `php cua` runs it and the tests load it.
// The .env file, config folder and framework providers are loaded by default.

return App::configure(__DIR__)
    ->withProviders([
        AppProvider::class,
    ])
    ->withRouting(routes: 'routes/web.php', commands: 'routes/console.php', health: '/up')
    ->create();
