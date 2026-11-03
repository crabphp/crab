<?php

declare(strict_types=1);

namespace App\Plugins;

use Crab\Contracts\Container\Container;
use Crab\Contracts\Plugin;

/**
 * Where the application's own services are bound.
 */
final class AppPlugin implements Plugin
{
    public function register(Container $container): void
    {
        // $container->singleton(Mailer::class, static fn() => new SmtpMailer(config('mail.host')));
    }
}
