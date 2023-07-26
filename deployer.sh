#!/bin/bash
git pull origin master
composer install --no-dev --optimize-autoloader
php8.2 bin/console cache:clear --env=prod
php8.2 bin/console doctrine:migrations:migrate --no-interaction --env=prod
