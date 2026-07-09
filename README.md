# laravel_php_2026

[![](https://img.shields.io/badge/php-8.5.8-purple.svg)](https://www.php.net/)
[![](https://img.shields.io/badge/Laravel-13.x-yellow.svg)](https://laravel.com/docs/13.x)
[![](https://img.shields.io/badge/Vue-3.5.13-green.svg)](https://vuejs.org/)

## Setup and Use

Rename [.env.bak](./laravel/example-app/.env.bak) to .env within the same directory.

Launch:
```bash
docker compose up
```

Verify DB [Migrations](./laravel/example-app/database/migrations/) and [Seeders](./laravel/example-app/database/seeders/DatabaseSeeder.php):
```bash
# From the interactive terminal
mysql --user=example --password=example example

SELECT * FROM example.users;
SELECT * FROM example.examples;
```

> It's critical that `artisan serve` be bound to the `host` and `port` explicitly. I've done so [here](./laravel/example-app/composer.json).

URLs:
1. [localhost:8000](localhost:8000) (once the above are completed)
1. [localhost:5137](localhost:5137) (this is for Vite debugging, Vue is rolled up and served on `8000`)

> Create a new User to get a valid decrypted Password.

API Endpoints:
1. http://localhost:8000/api/examples
1. http://localhost:8000/api/examples?page=2
1. http://localhost:8000/api/examples/5
1. `curl -X DELETE http://localhost:8000/api/examples/5`
1. `curl -X POST http://localhost:8000/api/examples -H "Accept: application/json" -H "Content-Type: application/json" -d '{"name": "A B", "note": "abcdefg"}'`
1. `curl -X PUT http://localhost:8000/api/examples/17 -H "Accept: application/json" -H "Content-Type: application/json" -d '{"name": "A B C", "note": "abcdefghi"}'`
1. http://localhost:8000/api/health
1. http://localhost:8000/api/examples/cached

### Notes

These are now automatically executed in [run.sh](./laravel/example-app/run.sh):

```bash
# Single Process
## Local PHP Web Server
php artisan serve

# Runs full environment
## `npm run dev` is also triggered
composer run dev
```

```bash
# For dangling Containers
docker system prune --volumes
```

Migrate and Seed database:
```bash
# From the interactive terminal
php artisan migrate:refresh --seed
# Install API routing and Sanctum
php artisan install:api
## These require an active connection to run
```

Additional commands to run from the **Interactive Terminal**:
```bash
# Run supplied tests
php artisan test
# Launch Vite dev server
npm run dev
# Test redis
redis-cli ping
```

Create Models, Controllers, etc.:
```bash
# Model
php artisan make:model Example
# HTTP Request validation
php artisan make:request ExampleRequest 
# JSON serialization
php artisan make:resource ExampleResource
php artisan make:resource ExampleScanResource
# REST Controller
php artisan make:controller Api/ExampleController --api
# Tests
php artisan make:test ExampleApiTest
# Factory
php artisan make:factory ExampleFactory
# Make migrations
php artisan make:migration create_examples_table --create=examples
```

Additional notes:

1. This doesn't use NGINX as a Proxy.
    * For a barebones example that does: https://github.com/Thoughtscript/pyng_2025/tree/main/nginx
1. SMTP has to be added and [configured](./laravel/example-app/.env.bak).
    * Registering a new User won't automatically fire off an email.
1. `artisan` is akin to `rake` or `pymanage`.
1. This attmpts to use a Mounted Volume for hot-reloading within the Container.
    * Align the [WORKDIR](./laravel/dockerfile) and [volumes](./docker-compose.yml).
1. Avoid N+1:
    * `$books = Book::with('author')->get();` not `$books = Book::all();`
1. Rate Limiting
    * Would be done through AWS WAF out in front (resources are allocated on the Controllers using Laravel).
1. Concurrency vs. Processes
    * Former to run concurrent Closures using background hidden PHP Processes.
    * Latter, to execute Shell and other Commands outside main Process. (Less flexible.)
    * Apparently, no maximum Worker cap on these.
    * For external API calls, one can batch: `Http::pool` with a Worker pool.
1. Caching through `predis`.
    * `redis` must be installed and is done so locally here (rather than in its own Container).
    * Apparently: `protected-mode no` must be set for no passwords.

## Resources and Links

1. https://www.php.net/docs.php
1. https://laravel.com/docs/13.x/configuration
1. https://laravel.com/docs/13.x/seeding
1. https://oneuptime.com/blog/post/2026-01-06-docker-hot-reloading/view
1. https://oneuptime.com/blog/post/2026-01-26-laravel-rest-api/view - this article lies!
1. https://laravel.com/docs/13.x/eloquent-factories
1. https://github.com/FakerPHP/Faker
1. https://laravel.com/docs/13.x/processes
1. https://laravel.com/docs/13.x/concurrency
1. https://oneuptime.com/blog/post/2026-01-21-redis-laravel-integration/view
1. https://laravel.com/docs/13.x/redis