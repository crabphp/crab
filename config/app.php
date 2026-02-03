<?php

use Crab\Engine\Providers\ConfigServiceProvider;
use Crab\Engine\Providers\EventsServiceProvider;
use Crab\Engine\Providers\RoutingServiceProvider;
use Crab\Engine\Providers\HttpServiceProvider;
use App\Providers\AppServiceProvider;

return [
    'providers' => [
        ConfigServiceProvider::class,
        EventsServiceProvider::class,
        RoutingServiceProvider::class,
        HttpServiceProvider::class,
        AppServiceProvider::class,
    ],
];
