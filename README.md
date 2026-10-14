# Crab application

A starting point for an application on the [Crab framework](../framework).

## Start

~~~sh
composer install
cp .env.example .env
php cua serve
~~~

Then open http://127.0.0.1:8080. The server restarts whenever you save a file under `app/`, `config/` or `routes/`, or `.env`; `--no-watch` serves without that. `php cua serve --single` serves in one process, without PHP's `pcntl` and `posix` extensions.

## In production

~~~sh
php cua serve --daemon       # start in the background; returns once it is up
php cua status               # is it running?
php cua reload               # new code, without refusing a connection
php cua stop                 # drain requests up to server.drain seconds, then stop
~~~

A deploy is: put the new code in place, `php cua migrate`, `php cua reload`. If the new code cannot start, the running server goes on serving and `reload` fails with the reason. The server's process id is in `storage/crab.pid` and what it prints in `storage/logs/server.log`. Under Docker, run `php cua serve --no-watch` in the foreground instead and let the container be replaced.

## Where things are

~~~text
app.php             describes the application: config, providers, routes
cua                 the command line: php cua <command>

routes/
  web.php           HTTP routes
  console.php       your own commands

app/
  Actions/         actions as classes
  Providers/        where your services are bound

config/
  app.php           name, debug
  database.php      which database, and where the migrations are
  log.php           where and how much to log
  server.php        address, worker count, what to watch, where the pid and log go

database/
  migrations/       changes to the tables, in order

storage/            what the application writes while it runs: logs, the SQLite file. Never committed.

tests/              tests that call the application without a server
.env                settings for this machine, never committed
~~~

Everything starts in `app.php`, which describes the application one line at a time:

~~~php
use App\Providers\AppProvider;
use Crab\App;

return App::configure(__DIR__)
    ->withProviders([
        AppProvider::class,
    ])
    ->withRouting(
        routes: 'routes/web.php',
        commands: 'routes/console.php',
        health: '/up',
    )
    ->create();
~~~

`__DIR__` is the application's base path: `.env` and `config/` are loaded from it, and the other paths are relative to it. `health: '/up'` makes `GET /up` answer `{"status":"up"}`, for whatever needs to know the server takes requests. The framework providers are registered by default; name only your own and your packages' providers. Add further providers or route files to the same lists; the route files declare with `Route::` and `Command::`.

## Everyday tasks

| To do this | Do this |
| --- | --- |
| Add a page | Add a line to `routes/web.php` |
| Handle a route in a class | Add a class to `app/Actions/` and name it in the route |
| Add a command | Add it in `routes/console.php`, then `php cua <name>` |
| Add middleware | `php cua make:middleware Name`, then `middleware: [Name::class]` on a route or group, or `->withMiddleware([Name::class])` in `app.php` |
| Share a service | Bind it in `app/Providers/AppProvider.php`, then ask for it in a constructor |
| Change a setting | Edit `.env`; add new settings to a file in `config/` and read them with `config('file.key')` |
| Change the tables | `php cua make:migration create_posts_table`, edit the file, then `php cua migrate` |
| Undo the last migration run | `php cua migrate:rollback` |
| Restart on save while developing | `php cua serve` does already |
| See what a command takes | `php cua serve --help` |
| See all routes | `php cua route:list` |
| Let a frontend on another origin call the API | `->withMiddleware(prepend: [new Cors(['https://app.example.com'])])` in `app.php` |
| Call another API | Ask for `Crab\Contracts\HttpClient\Client` in an action's constructor |
| Keep the result of slow work | Ask for `Crab\Contracts\Cache\Cache`, and `$cache->remember('key', 3600, fn () => ...)` |
| Do work outside the request | `php cua queue:install`, `php cua migrate`; push with `$queue->push(Job::class, id: 7)` and run `php cua queue:work`; a worker may start before the HTTP response is sent |
| Run something by the clock | Declare the task in `AppProvider`, and keep `php cua schedule:work` running |
| React to something that happened | `listen()` in `AppProvider`, and `dispatch()` an event object |
| Take large uploads | Raise `max_body` and `body_timeout` in `config/server.php` |
| Check the server is up | `GET /up` |
| See all commands | `php cua` |
| Run the tests | `composer test` |

## A route

~~~php
// routes/web.php
use App\Actions\Hello;
use Crab\Route;

Route::get('/', static fn (): string => "Hello from Crab\n");
Route::get('/hello/{name}', Hello::class);
~~~

~~~php
// app/Actions/Hello.php
namespace App\Actions;

final class Hello
{
    public function handle(string $name): array
    {
        return ['hello' => $name];
    }
}
~~~

An action is a closure, a class with `handle()`, or a class and a method, `[UserController::class, 'show']`. The container makes the class, so its constructor can ask for services by type. `{name}` arrives as the parameter `$name`; declare it `int` and anything else is refused with `422`.

An action returns data, which is sent as JSON, or a response when it needs to set the status or a header:

~~~php
Route::get('/users', static fn (int $page = 1, ?string $search = null): array => ['page' => $page, 'search' => $search]);

Route::post('/users', static function (#[Email] string $email, #[Length(min: 2)] string $name): Response {
    return Response::created(['email' => $email, 'name' => $name]);
});
~~~

Parameters that are not in the pattern are fields of the request, read from the JSON body, or from the query string for `GET`. They are checked before the action runs, and a request that does not fit is answered with `422` and the problems by field. When a route takes many fields, declare a class for them and take that instead.

Routes that share a path prefix or middleware go in a group:

~~~php
use Crab\Auth\TokenAuth;

Route::prefix('/admin')->middleware(RequireAdmin::class)->group(function () {
    Route::get('/users', [AdminController::class, 'users']);     // GET /admin/users
});

Route::get('/me', [UserController::class, 'me'])->middleware(TokenAuth::class);   // on one route
~~~

`php cua make:middleware RequireAdmin` writes a middleware class in `app/Http/`. To run one around every request, add `->withMiddleware([RequireAdmin::class])` to `app.php`.

## The database

It works out of the box with SQLite, in `storage/database.sqlite`; set `DB_DSN` in `.env` for MySQL. `php cua migrate` creates the tables, starting with the `users` table in `database/migrations/`.

~~~php
// A migration: database/migrations/2026_10_06_000000_create_users_table.php
$schema->create('users', function (Table $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamps();
});
~~~

An action class asks for `Crab\Contracts\Database\Database` in its constructor and queries with `$db->table('users')->where('email', $email)->first()`.

## The guide

Every topic has a page in the framework's `docs/` folder: routing, validation, the database, queues, scheduling, testing, deployment and the rest. Read them in a browser with `composer docs` in the framework's folder, which serves them at http://127.0.0.1:8900.

## The framework for now

The framework is not published yet, so `composer.json` reads it from `../framework`. Keep the two folders side by side.
