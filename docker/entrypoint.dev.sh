#!/bin/sh

composer install --prefer-dist --no-progress --no-interaction
php ./bin/console importmap:install

# if needs to build sass
./bin/console sass:build

php ./bin/console d:m:m --no-interaction
php ./bin/console cache:clear

mkdir -p "./data"
mkdir -p "./var"

chown -R www-data:www-data ./data
chown -R www-data:www-data ./var

php-fpm
