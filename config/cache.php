<?php

declare(strict_types=1);

return [
    // Where entries are kept: file, in storage/cache; database, after `php cua cache:install`; array, in
    // memory, for tests; or redis with the crabphp/redis package. `php cua config:publish cache` has the rest.
    'store' => env('CACHE_STORE', 'file'),
];
