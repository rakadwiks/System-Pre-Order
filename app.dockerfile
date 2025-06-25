FROM php:8.1.0-fpm

RUN apt-get update && \
    apt-get install -y default-mysql-client libmcrypt-dev libzip-dev unzip && \
    pecl install mcrypt && \
    docker-php-ext-enable mcrypt && \
    docker-php-ext-install pdo_mysql


WORKDIR /var/www