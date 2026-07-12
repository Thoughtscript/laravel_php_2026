#!/bin/sh

redis-server /etc/redis.conf &

sleep 15 && npm install && npm run build && composer run dev &

sleep 25 && php artisan migrate:refresh --seed && php artisan cache:clear &&php artisan install:api --without-migration-prompt &

sleep 35 && npm run dev &

wait