#!/bin/sh

composer dump-env prod

composer install --no-dev --optimize-autoloader
php ./bin/console importmap:install

# build assets (webpack encore or asset mapper)
php bin/console sass:build
php bin/console asset-map:compile

composer dump-env prod

php ./bin/console d:m:m --no-interaction
APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear

mkdir -p "./data"
mkdir -p "./var"

chown -R www-data:www-data ./data
chown -R www-data:www-data ./var

php-fpm
