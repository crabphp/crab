# Crab application

A starting point for an application on the [Crab framework](https://github.com/crabphp/framework).

## Start

~~~sh
composer create-project crabphp/crab shop
cd shop
php cua dev
~~~

`create-project` copies `.env.example` to `.env`. From a clone of this repository instead, run `composer install` and `cp .env.example .env` before `php cua dev`.

Open http://127.0.0.1:8080. The server restarts when you save a file. `php cua` lists every command, `php cua describe` prints the whole application for coding agents, and `composer test` runs the tests.

It needs PHP 8.4 with `mbstring`, `pdo_sqlite`, `pcntl` and `posix`. Development also needs `dom` and `xmlwriter`, for PHPUnit, which runs the tests; `create-project` checks for them even with `--no-dev`. A production install, `composer install --no-dev`, does not.

## On Windows

Develop inside WSL 2, not in PowerShell or the Command Prompt: the server uses PHP's `pcntl` and `posix` extensions, which PHP for Windows does not have. In PowerShell opened as administrator, run `wsl --install` and restart Windows. Then, in the Ubuntu terminal it installs:

~~~sh
sudo apt update
sudo apt install -y software-properties-common
sudo add-apt-repository -y ppa:ondrej/php
sudo apt update
sudo apt install -y php8.4-cli php8.4-mbstring php8.4-xml php8.4-sqlite3 unzip
~~~

Install Composer from [getcomposer.org/download](https://getcomposer.org/download/) and move it into place with `sudo mv composer.phar /usr/local/bin/composer`. Then run the commands under [Start](#start) from your Linux home folder (`cd ~`), not from `/mnt/c`, and open http://localhost:8080 in your browser on Windows.

[Developing on Windows](https://github.com/crabphp/framework/blob/main/docs/installation.md#developing-on-windows) in the framework's guide explains each step.

## Where things are

~~~text
app.php                      describes the application: plugins, routes, middleware
routes/web.php               HTTP routes
routes/console.php           your commands
app/Actions/                 route actions as classes
app/Setup.php                your services
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

Then `php cua start --daemon`, with `php cua reload` after each deploy and `php cua stop` to stop. See [Deployment](https://github.com/crabphp/framework/blob/main/docs/deployment.md).

## The guide

Every topic has a page in [the framework's guide](https://github.com/crabphp/framework/blob/main/docs/documentation.md), which comes with it too, in `vendor/crabphp/framework/docs/`.
