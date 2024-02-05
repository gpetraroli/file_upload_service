#!/bin/sh

composer install
php ./bin/console importmap:install

# if needs to build sass
php ./bin/console sass:build

php ./bin/console d:m:m --no-interaction
php ./bin/console cache:clear

php-fpm
