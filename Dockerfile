# 1. Verwende ein schlankes Basisimage mit FPM
FROM php:8.1-fpm-alpine

# 2. Setze Arbeitsverzeichnis
WORKDIR /var/www/html

# 3. Installiere benötigte Systemabhängigkeiten
RUN apk add --no-cache \
    git \
    curl \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip \
    && rm -rf /var/cache/apk/*

# 4. Composer installieren (von offiziellem Composer-Image)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 5. Kopiere nur die notwendigen Dateien zuerst für Caching
COPY composer.json composer.lock ./

# 6. Abhängigkeiten installieren (Production optimiert)
RUN composer install --no-dev --no-interaction --optimize-autoloader

# 7. Danach alle weiteren Projektdateien
COPY . .

# 8. Rechte ggf. anpassen (wenn Webserver wie www-data genutzt wird)
RUN chown -R www-data:www-data /var/www/html

# 9. Nutze PHP-FPM (kein eingebauter Dev-Server)
EXPOSE 9000
CMD ["php-fpm"]