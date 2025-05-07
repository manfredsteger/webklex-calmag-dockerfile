FROM composer:2 AS vendor

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-interaction --optimize-autoloader

FROM php:8.1-fpm-alpine

WORKDIR /var/www/html

RUN apk add --no-cache git curl unzip libzip-dev \
    && docker-php-ext-install zip

COPY . .
COPY --from=vendor /app/vendor ./vendor

RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000
CMD ["php-fpm"]
