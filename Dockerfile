FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
        libicu-dev \
        git \
    && docker-php-ext-install -j$(nproc) intl pdo pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
