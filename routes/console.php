<?php

declare(strict_types=1);

use Crab\Command;
use Crab\Contracts\Console\Input;
use Crab\Contracts\Console\Output;

Command::add(
    'hello:say',
    static function (Input $input, Output $output): int {
        $output->line('Hello, ' . $input->argument(0, 'world') . '!');

        return 0;
    },
    'Say hello: php cua hello:say [name]',
);
