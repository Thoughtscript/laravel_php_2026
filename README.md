# laravel_php_2026

[![](https://img.shields.io/badge/php-8.5.8-purple.svg)](https://www.php.net/)
[![](https://img.shields.io/badge/Laravel-13.x-yellow.svg)](https://laravel.com/docs/13.x)
[![](https://img.shields.io/badge/Vue-3.5.13-green.svg)](https://vuejs.org/)

## Setup and Use

Launch:
```bash
docker compose up
```

```bash
# For dangling Containers
docker system prune --volumes
```

Migrate and Seed database:
```bash
# From the interactive terminal
php artisan migrate:refresh --seed
# Run supplied tests
php artisan test
npm run dev
```

Verify DB [Migrations](./laravel/example-app/database/migrations/) and [Seeders](./laravel/example-app/database/seeders/DatabaseSeeder.php):
```bash
# From the interactive terminal
mysql --user=example --password=example example

SELECT * FROM example.users;
```

> It's critical that `artisan serve` be bound to the `host` and `port` explicitly. I've done so [here](./laravel/example-app/composer.json).

URLs:
1. localhost:8000 (once the above are completed)
1. localhost:5137 (this is for Vite debugging, Vue is rolled up and served on `8000`)

> Create a new User to get a valid decrypted Password.

### Notes

```bash
# Single Process
## Local PHP Web Server
php artisan serve

# Runs full environment
## `npm run dev` is also triggered
composer run dev
```

1. This doesn't use NGINX as a Proxy.
    * For a barebones example see: https://github.com/Thoughtscript/pyng_2025/tree/main/nginx
1. SMTP has to be added and [configured](./laravel/example-app/.env).
    * Registering a new User won't automatically fire off an email.
1. `artisan` is akin to `rake` or `pymanage`.
1. This attmpts to use a Mounted Volume for hot-reloading within the Container.
    * Align the [WORKDIR](./laravel/dockerfile) and [volumess](./docker-compose.yml).

## Resources and Links

1. https://www.php.net/docs.php
1. https://laravel.com/docs/13.x/configuration
1. https://laravel.com/docs/13.x/seeding
1. https://oneuptime.com/blog/post/2026-01-06-docker-hot-reloading/view