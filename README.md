# Crab application

A starting point for an application on the [Crab framework](../framework).

## Start

~~~sh
composer install
cp .env.example .env
php cua serve
~~~

Then open http://127.0.0.1:8080. `php cua serve --single` serves in one process, without PHP's `pcntl` and `posix` extensions.

## Where things are

~~~text
app.php             describes the application: config, providers, routes
cua                 the command line: php cua <command>

routes/
  web.php           HTTP routes
  console.php       your own commands

app/
  Handlers/         route handlers as classes
  Providers/        where your services are bound

config/
  app.php           name, debug
  log.php           where and how much to log
  server.php        address and worker count

tests/              tests that call the application without a server
.env                settings for this machine, never committed
~~~

Everything starts in `app.php`, which describes the application one line at a time:

~~~php
return App::configure(__DIR__)
    ->withProviders([
        AppProvider::class,
    ])
    ->withRouting(
        routes: 'routes/web.php',
        commands: 'routes/console.php',
    )
    ->create();
~~~

`__DIR__` is the application's base path: `.env` and `config/` are loaded from it, and the other paths are relative to it. Add `->withMiddleware(Cors::class)` for middleware around every route, and further providers or route files to the same lists. The route files declare with `Route::` and `Command::`.

## Everyday tasks

| To do this | Do this |
| --- | --- |
| Add a page | Add a line to `routes/web.php` |
| Handle a route in a class | Add a class to `app/Handlers/` and name it in the route |
| Add a command | Add it in `routes/console.php`, then `php cua <name>` |
| Share a service | Bind it in `app/Providers/AppProvider.php`, then ask for it in a constructor |
| Change a setting | Edit `.env`; add new settings to a file in `config/` and read them with `config('file.key')` |
| See all routes | `php cua routes` |
| Describe the API | `php cua openapi`, or `--output=openapi.json` |
| See all commands | `php cua` |
| Run the tests | `composer test` |

## A route

~~~php
// routes/web.php
Route::get('/', static fn (): string => "Hello from Crab\n");
Route::get('/hello/{name}', Hello::class);
~~~

~~~php
// app/Handlers/Hello.php
final class Hello
{
    public function __invoke(string $name): array
    {
        return ['hello' => $name];
    }
}
~~~

A handler is a closure, a class with `__invoke()`, or a class and a method, `[UserController::class, 'show']`. The container makes the class, so its constructor can ask for services by type. `{name}` arrives as the parameter `$name`; declare it `int` and anything else is refused with `422`.

A handler returns data, which is sent as JSON, or a response when it needs to set the status or a header:

~~~php
Route::get('/users', static fn (int $page = 1, ?string $search = null): array => ...);      // fields from the query string

Route::post('/users', static function (#[Email] string $email, #[Length(min: 2)] string $name): Response {
    ...
    return Response::created($user, "/users/{$user->id}");
});
~~~

Parameters that are not in the pattern are fields of the request, read from the JSON body, or from the query string for `GET`. They are checked before the handler runs, and a request that does not fit is answered with `422` and the problems by field. When a route takes many fields, declare a class for them and take that instead.

`php cua openapi` writes the OpenAPI description of every route from these signatures.

## The framework for now

The framework is not published yet, so `composer.json` reads it from `../framework`. Keep the two folders side by side.
