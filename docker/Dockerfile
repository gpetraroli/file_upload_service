FROM php:8.2-fpm-alpine AS php_upstream
FROM composer/composer:2-bin AS composer_upstream
FROM caddy:2.7.6-alpine AS caddy_upstream

# =====================================================================
# PHP IMAGE ========================================================

FROM php_upstream AS php

# Install php extensions using docker-php-extension-installer from github.com/mlocati
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions
RUN install-php-extensions \
    intl \
    opcache \
    zip \
    pdo \
    pdo_mysql

# install composer
COPY --from=composer_upstream --link /composer /usr/bin/composer

# (optional) install nano
RUN apk add nano

# install nodejs
RUN apk add --no-cache nodejs npm

# copy project
COPY . /var/www/app/

# install project dependencies
RUN cd /var/www/app/ && \
    composer install && \
#    if using npm
#    npm init -y && \
#    npm install --force \
#    if using importmap
    php bin/console importmap:install


WORKDIR /var/www/app

EXPOSE 9000

CMD ["./docker/entrypoint.sh"]

# =====================================================================
# CADDY IMAGE =========================================================

FROM caddy_upstream as caddy

# copy caddy config
COPY ./docker/Caddyfile /etc/caddy/Caddyfile

# copy project
COPY . /var/www/app

EXPOSE 80 443
