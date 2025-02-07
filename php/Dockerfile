FROM php:8.3-fpm

LABEL authors="vladimirbubnov"

RUN apt update \
    && apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

COPY ./php.ini /usr/local/etc/php/conf.d/php.ini

WORKDIR /var/www/app

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN touch /var/log/xdebug.log && \
    chmod 777 /var/log/xdebug.log

USER root
