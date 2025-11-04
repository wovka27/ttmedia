FROM php:8.2-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    libjpeg-dev libpng-dev libfreetype6-dev libzip-dev zip unzip git curl \
    libicu-dev libxml2-dev libxslt-dev libldap2-dev libonig-dev \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install pdo_mysql gd intl zip soap xsl opcache mysqli \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY php.ini /usr/local/etc/php/php.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN usermod -u 1000 www-data && chown -R www-data:www-data /var/www/html

USER www-data
