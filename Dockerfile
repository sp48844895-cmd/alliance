FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git curl unzip libonig-dev libzip-dev zip \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring zip \
    && rm -rf /var/lib/apt/lists/*

RUN printf "upload_max_filesize=10M\npost_max_size=12M\nmemory_limit=256M\n" > /usr/local/etc/php/conf.d/uploads.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

COPY . .

RUN cp .env.example .env \
    && composer dump-autoload --optimize --no-interaction

RUN mkdir -p public/uploads/banners storage/story \
    && chmod -R 775 storage bootstrap/cache public/uploads

EXPOSE 10000

CMD ["/bin/sh", "-c", "touch database/database.sqlite && php artisan config:clear && php artisan route:clear && php artisan migrate --force && mkdir -p public/uploads/banners && chmod -R 775 storage bootstrap/cache public/uploads && exec php -d upload_max_filesize=10M -d post_max_size=12M artisan serve --host=0.0.0.0 --port=${PORT:-10000}"]
