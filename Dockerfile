# Produktions-Setup: PHP-FPM + Nginx

FROM php:8.1-fpm

# Systempakete installieren
RUN apt-get update && apt-get install -y \
    nginx \
    unzip \
    git \
    curl \
    libzip-dev \
    zip \
    supervisor

# Composer installieren
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Arbeitsverzeichnis
WORKDIR /app

# Projektdateien kopieren
COPY . .

# PHP-Abhängigkeiten installieren
RUN composer install --no-interaction --optimize-autoloader

# Nginx-Konfiguration kopieren
COPY ./docker/nginx.conf /etc/nginx/nginx.conf

# Supervisor-Konfiguration kopieren
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 8000

CMD ["/usr/bin/supervisord"]