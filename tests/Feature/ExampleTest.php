<?php

declare(strict_types=1);

namespace Tests\Feature;

use Crab\Testing\Api;
use PHPUnit\Framework\TestCase;

final class ExampleTest extends TestCase
{
    public function testTheApplicationAnswers(): void
    {
        $api = new Api(require dirname(__DIR__, 2) . '/app.php');

        $api->get('/')->assertStatus(200);
    }
}
