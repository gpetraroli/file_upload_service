#!/bin/sh

composer install --prefer-dist --no-progress --no-interaction
php ./bin/console importmap:install

# build assets (webpack encore or asset mapper)
./bin/console sass:build

php ./bin/console d:m:m --no-interaction
php ./bin/console cache:clear

mkdir -p "./data"
mkdir -p "./var"

chown -R www-data:www-data ./data
chown -R www-data:www-data ./var

php-fpm
