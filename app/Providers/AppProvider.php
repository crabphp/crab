<?php

declare(strict_types=1);

namespace App\Providers;

use Crab\Contracts\Container\Container;
use Crab\Contracts\Provider;

/**
 * Where the application's own services are bound.
 */
final class AppProvider implements Provider
{
    public function register(Container $container): void
    {
        // $container->singleton(Mailer::class, static fn() => new SmtpMailer(config('mail.host')));
    }
}
