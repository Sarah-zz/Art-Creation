# Base PHP-FPM Alpine
FROM php:8.2-fpm-alpine

# Dossier de travail
WORKDIR /var/www/html

# Installer dépendances système et extensions
RUN apk add --no-cache \
    autoconf g++ make pkgconfig \
    curl-dev openssl-dev libxml2-dev libzip-dev \
    mysql-client git unzip nginx bash \
    && docker-php-ext-install pdo_mysql zip \
    && pecl install mongodb-1.17.0 \
    && docker-php-ext-enable mongodb

# Installer Composer globalement
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Copier fichiers Composer pour profiter du cache Docker
COPY composer.json composer.lock ./

# Installer dépendances PHP
RUN composer install --no-scripts --no-autoloader

# Copier le reste du code source
COPY . .

# Générer autoloader optimisé
RUN composer dump-autoload --optimize

# Permissions
RUN chown -R www-data:www-data /var/www/html

# Copier configuration Nginx pour Render
COPY nginx/render.conf /etc/nginx/conf.d/default.conf

# Exposer le port HTTP standard
EXPOSE 80

# Lancer PHP-FPM + Nginx
CMD ["sh", "-c", "php-fpm & nginx -g 'daemon off;'"]
# Base PHP-FPM Alpine
FROM php:8.2-fpm-alpine

# Dossier de travail
WORKDIR /var/www/html

# Installer dépendances système nécessaires
RUN apk add --no-cache \
    autoconf g++ make pkgconfig \
    curl-dev openssl-dev libxml2-dev libzip-dev \
    mysql-client git unzip

# Installer extensions PHP : MySQL + MongoDB + ZIP
RUN docker-php-ext-install pdo_mysql zip \
    && pecl install mongodb-1.17.0 \
    && docker-php-ext-enable mongodb

# Installer Composer globalement
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Copier fichiers Composer pour profiter du cache Docker
COPY composer.json composer.lock ./

# Installer dépendances PHP
RUN composer install --no-scripts --no-autoloader

# Copier le reste du code source
COPY . .

# Générer autoloader optimisé
RUN composer dump-autoload --optimize

# Permissions pour PHP-FPM
RUN chown -R www-data:www-data /var/www/html

# Exposer le port PHP-FPM
EXPOSE 9000

# Lancer PHP-FPM
CMD ["php-fpm"]
