<?php

declare(strict_types=1);

use Crab\Contracts\Console\Console;
use Crab\Contracts\Console\Input;
use Crab\Contracts\Console\Output;

return static function (Console $console): void {
    $console->add('hello', static function (Input $input, Output $output): int {
        $output->line('Hello, ' . $input->argument(0, 'world') . '!');

        return 0;
    }, 'Say hello: php cua hello [name]');
};
