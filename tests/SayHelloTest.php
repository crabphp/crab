<?php

declare(strict_types=1);

namespace Tests;

use Crab\App;
use Crab\Http\Request;
use PHPUnit\Framework\TestCase;

final class SayHelloTest extends TestCase
{
    private App $app;

    protected function setUp(): void
    {
        $this->app = require dirname(__DIR__) . '/app.php';
    }

    public function testHome(): void
    {
        $response = $this->app->handle(new Request('GET', '/'));

        self::assertSame(200, $response->getStatusCode());
        self::assertSame("Hello from Crab\n", (string) $response->getBody());
    }

    public function testHelloSomeone(): void
    {
        $response = $this->app->handle(new Request('GET', '/hello/Crab'));

        self::assertSame('{"hello":"Crab"}', (string) $response->getBody());
    }

    public function testTheApplicationSaysItIsUp(): void
    {
        $response = $this->app->handle(new Request('GET', '/up'));

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('{"status":"up"}', (string) $response->getBody());
    }

    public function testUnknownPathIsNotFound(): void
    {
        self::assertSame(404, $this->app->handle(new Request('GET', '/missing'))->getStatusCode());
    }
}
