FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git curl unzip libonig-dev libzip-dev zip \
    && docker-php-ext-install pdo pdo_mysql mbstring zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN cp .env.example .env \
    && composer install --no-dev --optimize-autoloader \
    && php artisan key:generate --force

RUN chmod -R 775 storage bootstrap/cache

EXPOSE 10000

CMD ["/bin/sh", "-c", "php artisan config:clear && php artisan route:clear && exec php artisan serve --host=0.0.0.0 --port=${PORT:-10000}"]
