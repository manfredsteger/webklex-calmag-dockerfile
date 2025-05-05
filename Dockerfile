# Verwende das PHP CLI Image
FROM php:8.1-cli

# Arbeitsverzeichnis im Container
WORKDIR /app

# Systempakete installieren
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libzip-dev \
    zip \
    && docker-php-ext-install zip

# Composer installieren (ARM-kompatibel)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Projektdateien kopieren
COPY . .

# PHP-Abhängigkeiten installieren
RUN composer install --no-interaction --optimize-autoloader

# Exponiere Port 8000 für den eingebauten Server
EXPOSE 8000

# Starte den PHP Built-in Server im Container
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]