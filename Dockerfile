# Use an official PHP runtime as a base image
FROM php:8.4-cli

# Basic update and install necessary packages
RUN apt-get update && apt-get install -y \
    cron \
    openssl \
    curl \
    libpq-dev \
    && apt-get clean

# Install PHP extensions
RUN curl -sSLf \
        -o /usr/local/bin/install-php-extensions \
        https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions && \
    chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions \
    gettext \
    iconv \
    intl \
    tidy \
    zip \
    sockets \
    pgsql \
    mysqli \
    pdo_mysql \
    pdo_pgsql \
    xdebug \
    && rm -rf /var/lib/apt/lists/*

# Install Composer (more straightforward)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set up working directory and copy app files
WORKDIR /var/php
COPY . /var/php

# Expose port 80 for the Slim application
EXPOSE 80

# Use PHP's built-in server to serve the application
CMD ["sh", "-c", "composer install && php -S 0.0.0.0:80 -t /var/php/public"]
