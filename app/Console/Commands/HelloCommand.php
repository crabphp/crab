<?php

namespace App\Console\Commands;

use Crab\Engine\Console\CommandInterface;

class HelloCommand implements CommandInterface
{
    public function name(): string
    {
        return 'hello';
    }

    public function description(): string
    {
        return 'Print Hello World';
    }

    public function handle(array $argv): int
    {
        echo "Hello World\n";
        return 0;
    }
}
