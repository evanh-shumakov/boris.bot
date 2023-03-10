FROM php:8.0
RUN apt-get update && apt-get install -y zip unzip
COPY ./ /app/
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
VOLUME ["/app"]
WORKDIR /app
CMD bash -c "composer install && php ./start_bot.php"
