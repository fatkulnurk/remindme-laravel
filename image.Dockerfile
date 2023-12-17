# see: https://hub.docker.com/layers/library/php/8.2-fpm/images/sha256-37ff79d092685f251be709e87c902c20b3aba4f28a93371332d9c85013e39fd3?context=explore.
# image base on debian:12-slim
FROM php:8.2-fpm as php

# Arguments
ARG UID
ARG GID

# Set environment variables
ENV UID=${UID}
ENV GID=${GID}

# Set environment variables for NodeJS
ENV NODE_MAJOR=20

# Set environment variables for PHP OPCache
ENV PHP_OPCACHE_ENABLE=1
ENV PHP_OPCACHE_ENABLE_CLI=0
ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS=0
ENV PHP_OPCACHE_REVALIDATE_FREQ=0

# Install dependencies.
RUN apt-get update && apt-get install -y unzip libpq-dev libcurl4-gnutls-dev nginx libonig-dev

# Install PHP extensions.
RUN docker-php-ext-install mysqli pdo pdo_mysql bcmath curl opcache mbstring

# Install Redis extension, see: https://stackoverflow.com/questions/31369867/how-to-install-php-redis-extension-using-the-official-php-docker-image-approach
RUN pecl install redis && docker-php-ext-enable redis

# Copy composer executable.
COPY --from=composer:2.3.5 /usr/bin/composer /usr/bin/composer

# Install Nodejs & NPM
RUN apt-get update
RUN apt-get install -y ca-certificates curl gnupg
RUN mkdir -p /etc/apt/keyrings
RUN curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg
RUN echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_$NODE_MAJOR.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list
RUN apt-get update \
    && apt-get install nodejs -y

# Copy configuration files.
COPY ./docker/php/php.ini /usr/local/etc/php/php.ini
COPY ./docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf

# Set working directory to /var/www.
WORKDIR /var/www

# Copy files from current folder to container current folder (set in workdir).
COPY --chown=www-data:www-data ./src .
#COPY ./docker/entrypoint.sh /var/www/docker/entrypoint.sh

# Create laravel caching folders.
RUN mkdir -p /var/www/storage/framework /var/www/storage/framework/cache \
    /var/www/storage/framework/testing /var/www/storage/framework/sessions \
    /var/www/storage/framework/views

RUN mkdir -p /var/www/storage /var/www/storage/logs /var/www/storage/framework \
    /var/www/storage/framework/sessions /var/www/bootstrap /var/www/node_modules /var/www/vendor

# Fix files ownership.
RUN chown -R www-data /var/www/storage
RUN chown -R www-data /var/www/storage/framework
RUN chown -R www-data /var/www/storage/framework/sessions

# Set correct permission.
RUN chmod -R 755 /var/www/storage
RUN chmod -R 755 /var/www/storage/logs
RUN chmod -R 755 /var/www/storage/framework
RUN chmod -R 755 /var/www/storage/framework/sessions
RUN chmod -R 755 /var/www/bootstrap

# Log
RUN touch /var/www/storage/logs/laravel.log
RUN chmod 775 /var/www/storage/logs/laravel.log

RUN chown -R www-data:www-data /var/www

# Adjust user permission & group
RUN usermod --uid ${UID} www-data
RUN groupmod --gid ${GID} www-data
