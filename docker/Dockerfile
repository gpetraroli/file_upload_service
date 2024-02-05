FROM php:8.2-fpm-alpine AS php_upstream
FROM composer/composer:2-bin AS composer_upstream
FROM caddy:2.7.6-alpine AS caddy_upstream

# =====================================================================
# PHP BASE IMAGE ======================================================

FROM php_upstream AS php_base

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

# copy project
COPY . /var/www/app/

# install nodejs if needed
# RUN apk add --no-cache nodejs npm

# TODO why composer install is not working here?
# is it because i have copied the project beforerunning composer and the caddy image doesn't recive artifix?

# install project dependencies
#RUN cd /var/www/app/ && \
#    composer install && \
#    if using npm
#    npm init -y && \
#    npm install --force \
#    if using importmap
#    php bin/console importmap:install

WORKDIR /var/www/app

EXPOSE 9000

# =====================================================================
# PHP DEV IMAGE =======================================================

FROM php_base AS php_dev

RUN chmod +x /var/www/app/docker/entrypoint.sh

ENTRYPOINT ["/var/www/app/docker/entrypoint.sh"]

# =====================================================================
# PHP PROD IMAGE ======================================================

FROM php_base AS php_prod

# =====================================================================
# CADDY IMAGE =========================================================

FROM caddy_upstream as caddy

# copy caddy config
COPY ./docker/Caddyfile /etc/caddy/Caddyfile

# copy project from php_base
COPY --from=php_base /var/www/app /var/www/app

EXPOSE 80 443
