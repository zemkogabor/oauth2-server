FROM composer:latest as composer_stage

WORKDIR /app

COPY composer.lock /app
COPY composer.json /app

RUN composer install --ignore-platform-reqs --prefer-dist --no-scripts --no-progress --no-interaction --no-dev

FROM php:8.2-fpm-bullseye

WORKDIR /app

COPY --from=composer_stage /app /app

# libpq-dev - Required for pdo_pgsql extenstion
# zip - Required for composer
# libzip-dev - Required for zip extenstion
RUN apt-get update
RUN apt-get install -y -q --no-install-recommends \
    libpq-dev \
    zip \
    libzip-dev

# pdo pdo_pgsql - PostgreSQL driver
# zip - Required for composer
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    zip

# Copy php config
COPY _docker/php/prod/base.ini /usr/local/etc/php/conf.d/base.ini

# Copy folders
COPY config /app/config
COPY db /app/db
COPY public /app/public
COPY src /app/src

# Copy files
COPY cli.php /app
COPY cli-migrations.php /app

# Create keys folder
RUN mkdir -p /app/var/keys
VOLUME /app/var/keys

ARG wwwdatauid=1000
RUN usermod -u $wwwdatauid www-data
RUN chown -R www-data:www-data /app

