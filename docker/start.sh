#!/bin/sh
set -e

PORT="${PORT:-10000}"

sed -i "s/listen 8080;/listen ${PORT};/" /etc/nginx/sites-available/default
rm -f /etc/nginx/sites-enabled/default
ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

php-fpm -D
exec nginx -g 'daemon off;'
