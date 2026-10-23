# Working on this application

A JSON API on the Crab framework. The server is long-running: one process serves many requests, so state that outlives a request is shared by every request after it.

## Start here

- `php cua describe` prints the whole application: every route with what it takes, the middleware, commands, scheduled tasks, listeners, servers, databases and settings. Read it before reading files.
- `php cua` lists the commands; `php cua <command> --help` explains one.
- The framework's guide is in `../framework/docs/`, one page per topic.

## Where things go

| What | Where |
| --- | --- |
| HTTP routes | `routes/web.php`, with `Route::get()` and the rest |
| Commands | `app/Console/`, written with `php cua make:command PruneUsers`, which registers it in `routes/console.php` |
| Code a route, job, listener or scheduled task runs | a class with `handle()` in `app/Actions/` |
| Middleware, input classes | `app/Http/`; `php cua make:middleware RequireAdmin` |
| Services, listeners, scheduled tasks | `register()` of `app/Providers/AppProvider.php` |
| Event objects | `app/Events/` |
| Settings | `config/`, the only place `env()` is called; read them with `config('file.key')` |
| Tables | `php cua make:migration create_posts_table` writes one in `database/migrations/` |
| Tests | `tests/` |

Make a folder when it has a file, not before. Nothing is found by convention: a provider is named in `app.php`, a command in `routes/console.php`.

## Rules

- An action's parameters are the request's data, checked against their types and attributes (`#[Email]`, `#[Length(min: 2)]`, `#[Range(min: 1)]`) before it runs; a request that does not fit gets `422`. Do not validate again inside the action.
- Services go in the action's constructor, never in `handle()`'s parameters.
- Never keep request data in a property or a static: the worker keeps the object for the next request.
- Return an array to answer with JSON, or a `Crab\Http\Response` to set the status or a header.
- Bind SQL values with `?` or `:name`; never put a value into the SQL text.
- A queued job may run more than once; write it so that running twice does no harm.

## Check your work

- `composer test` runs the tests. A test sends requests to the application in-process with `Crab\Testing\Api`, without a server; see `tests/ExampleTest.php`.
- After changing `config/` or `.env`, run `php cua describe app`: it lists every setting that does not fit, with what it takes. `start` refuses to run until they do.
- `php cua migrate:fresh` drops every table and runs the migrations again, for a clean development database.
- `php cua dev` serves on http://127.0.0.1:8080 and restarts when a file is saved. `php cua start` is for production and does not watch.
