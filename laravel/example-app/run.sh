#!/bin/sh

sleep 15 && npm install && npm run build && composer run dev &

sleep 25 && php artisan migrate:refresh --seed && php artisan install:api --without-migration-prompt &

sleep 25 && npm run dev &

wait