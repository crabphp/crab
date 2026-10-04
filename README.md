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
app.php             builds the application: config, providers, routes
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

Everything starts in `app.php`; it is ten lines and reads top to bottom.

## Everyday tasks

| To do this | Do this |
| --- | --- |
| Add a page | Add a line to `routes/web.php` |
| Handle a route in a class | Add a class to `app/Handlers/` and name it in the route |
| Add a command | Add it in `routes/console.php`, then `php cua <name>` |
| Share a service | Bind it in `app/Providers/AppProvider.php`, then ask for it in a constructor |
| Change a setting | Edit `.env`; add new settings to a file in `config/` and read them with `config('file.key')` |
| See all routes | `php cua routes` |
| See all commands | `php cua` |
| Run the tests | `composer test` |

## A route

~~~php
// routes/web.php
$router->get('/', static fn() => "Hello from Crab\n");
$router->get('/hello/{name}', Hello::class);
~~~

A closure returns a string or a response. `{name}` arrives as `$request->getAttribute('name')`. A class handler implements PSR-15's `RequestHandlerInterface` and is made by the container, so its constructor can ask for services by type.

## The framework for now

The framework is not published yet, so `composer.json` reads it from `../framework`. Keep the two folders side by side.
