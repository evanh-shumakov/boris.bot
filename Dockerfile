FROM php:8.2-alpine

# Install PHP extensions
RUN apk add --no-cache zip libzip-dev \
    && docker-php-ext-install pdo_mysql zip

COPY ./ /app/

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Install xdebug
#RUN apk add --no-cache $PHPIZE_DEPS \
#    && pecl install xdebug-3.1.6 \
#    && docker-php-ext-enable xdebug \
#    && echo 'xdebug.mode=debug' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

VOLUME ["/app"]

WORKDIR /app
