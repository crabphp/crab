<?php

declare(strict_types=1);

use App\Plugins\AppPlugin;
use Crab\App;

// Describes the application. `php cua` runs it and the tests load it.
// The .env file, config folder and framework plugins are loaded by default.

return App::configure(__DIR__)
    ->withPlugins([
        AppPlugin::class,
    ])
    ->withRouting(routes: 'routes/web.php', commands: 'routes/console.php', health: '/up')
    ->create();
