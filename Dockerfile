# Base PHP-FPM Alpine
FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

# Installer dépendances système nécessaires
RUN apk add --no-cache \
    autoconf g++ make pkgconfig \
    curl-dev openssl-dev libxml2-dev libzip-dev \
    mysql-client git unzip

# Installer extensions PHP : MySQL + MongoDB + ZIP
RUN docker-php-ext-install pdo_mysql zip
    # && pecl install mongodb-2.1.4 \
    # && docker-php-ext-enable mongodb

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Copier uniquement les fichiers Composer d'abord (pour le cache)
COPY composer.json composer.lock ./

# Installer les dépendances PHP
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Copier le reste du code
COPY . .

# Permissions correctes
RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000
CMD ["php-fpm"]
