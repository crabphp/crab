<?php

declare(strict_types=1);

use App\Setup;
use Crab\App;

// Describes the application. `php cua` runs it and the tests load it.
// The .env file, config folder and core plugins are loaded by default; list optional ones, such as QueuePlugin, in withPlugins().

return App::configure(__DIR__)
    ->withSetup(Setup::class)
    ->withRouting(routes: 'routes/web.php', commands: 'routes/console.php', health: '/up')
    ->create();
