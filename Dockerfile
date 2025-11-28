FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

# Dépendances système pour compiler l'extension MongoDB
RUN apk add --no-cache \
    autoconf make g++ \
    openssl-dev icu-dev krb5-dev \
    curl-dev libxml2-dev libzip-dev \
    linux-headers \
    php82-pear php82-dev \
    git unzip

# Extensions PHP standards
RUN docker-php-ext-install pdo_mysql zip

# Installer MongoDB via PECL
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb
