<?php

use Crab\Engine\Config\ConfigServiceProvider;
use Crab\Engine\Events\EventsServiceProvider;
use Crab\Engine\Routing\RoutingServiceProvider;
use Crab\Engine\Http\HttpServiceProvider;
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
