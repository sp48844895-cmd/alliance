#!/bin/sh
set -e
PORT="${PORT:-10000}"
php artisan config:clear
php artisan route:clear
exec php artisan serve --host=0.0.0.0 --port="${PORT}"
