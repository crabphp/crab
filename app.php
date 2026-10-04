<?php

declare(strict_types=1);

use App\Providers\AppProvider;
use Crab\App;
use Crab\Config\Config;
use Crab\Config\Env;

// Builds the application. `php cua` runs it and the tests load it.

Env::load(__DIR__ . '/.env');

$app = new App(Config::load(__DIR__ . '/config'));

$app->register(AppProvider::class);

$router = $app->router;
require __DIR__ . '/routes/web.php';

$console = $app->console;
require __DIR__ . '/routes/console.php';

return $app;
