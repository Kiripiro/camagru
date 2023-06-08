FROM php:8.1-fpm-alpine

RUN apk add --no-cache libpng-dev libjpeg-turbo-dev \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install pdo_mysql gd

RUN echo "date.timezone = Europe/Paris" > /usr/local/etc/php/conf.d/timezone.ini

RUN docker-php-ext-enable gd