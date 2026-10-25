# Crab application

A starting point for an application on the [Crab framework](../framework).

## Start

~~~sh
composer install
cp .env.example .env
php cua dev
~~~

Open http://127.0.0.1:8080. The server restarts when you save a file. `php cua` lists every command, `php cua describe` prints the whole application for coding agents, and `composer test` runs the tests.

## Where things are

~~~text
app.php                      describes the application: providers, routes, middleware
routes/web.php               HTTP routes
routes/console.php           your commands
app/Actions/                 route actions as classes
app/Providers/AppProvider.php  your services
config/                      settings, read from .env; config('file.key') reads one
storage/                     logs and the SQLite file; never committed
tests/                       tests that send the application requests, without a server
AGENTS.md                    the conventions, for coding agents
~~~

## Making things

| To add | Run |
| --- | --- |
| A command | `php cua make:command PruneUsers`, which writes it and registers it as `user:prune` |
| Middleware | `php cua make:middleware RequireAdmin` |
| A table | `php cua make:migration create_posts_table`, then `php cua migrate`; `php cua migrate:fresh` starts the database over |
| A setting the framework has | `php cua config:publish server` writes every server setting, at its default |

The database is SQLite in `storage/database.sqlite` until you set `DB_DRIVER=mysql` and `DB_DATABASE` (with `DB_HOST`, `DB_USERNAME` and `DB_PASSWORD`) in `.env`.

## In production

~~~text
APP_DEBUG=false
APP_URL=https://api.example.com
SERVER_WORKERS=4
~~~

Then `php cua start --daemon`, with `php cua reload` after each deploy and `php cua stop` to stop. See [Deployment](../framework/docs/deployment.md).

## The guide

Every topic has a page in the framework's `docs/` folder. `composer docs` in the framework's folder serves them at http://127.0.0.1:8900.

The framework is not published yet, so `composer.json` reads it from `../framework`. Keep the two folders side by side.
