#!/bin/bash
git pull origin master
php8.2 /usr/local/bin/composer dump-env dev
php8.2 /usr/local/bin/composer install
APP_ENV=dev APP_DEBUG=1 php8.2 bin/console cache:clear --env=dev
php8.2 bin/console doctrine:migrations:migrate --no-interaction --env=dev
npm install
./node_modules/.bin/encore dev