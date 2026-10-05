<?php

declare(strict_types=1);

use App\Providers\AppProvider;
use Crab\App;

// Builds the application. `php cua` runs it and the tests load it.

return App::configure()
    ->withEnv(__DIR__ . '/.env')
    ->withConfig(__DIR__ . '/config')
    ->withProviders(AppProvider::class)
    ->withRoutes(__DIR__ . '/routes/web.php')
    ->withCommands(__DIR__ . '/routes/console.php')
    ->create();
