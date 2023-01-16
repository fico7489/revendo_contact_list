FROM php:8.1.14-fpm-alpine3.17

COPY --from=composer:2.4.4 /usr/bin/composer /usr/local/bin/composer

RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql
