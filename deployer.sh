#!/bin/bash

git pull origin master
php8.1 /usr/local/bin/composer install --no-dev --optimize-autoloader
php8.1 bin/console cache:clear --env=prod
php8.1 bin/console doctrine:migrations:migrate --no-interaction
