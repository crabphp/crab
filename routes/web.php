<?php

declare(strict_types=1);

use App\Actions\SayHello;
use Crab\Route;

Route::get('/', static fn(): string => "Hello from Crab\n");

Route::get('/hello/{name}', SayHello::class);
