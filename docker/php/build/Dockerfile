FROM php:7.1.2-fpm-alpine

RUN echo 'http://dl-3.alpinelinux.org/alpine/edge/testing' >> /etc/apk/repositories \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && set -ex \
    && apk --no-cache add \
    git \
    libpng-dev \
    icu-dev \
    libjpeg \
    php7.1-gd

RUN pecl install xdebug

RUN docker-php-ext-install gd

RUN apk del .build-deps

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

CMD ["php-fpm"]