FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git curl unzip libpq-dev libonig-dev libzip-dev zip nginx \
    && docker-php-ext-install pdo pdo_mysql mbstring zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN cp .env.example .env \
    && composer install --no-dev --optimize-autoloader \
    && php artisan key:generate --force

COPY docker/nginx-default.conf /etc/nginx/sites-available/default
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

CMD ["/start.sh"]
