#!/bin/bash
git pull origin master
php /usr/local/bin/composer dump-env dev
php /usr/local/bin/composer install
APP_ENV=dev APP_DEBUG=1 php bin/console cache:clear --env=dev
php bin/console doctrine:migrations:migrate --no-interaction --env=dev
npm install
./node_modules/.bin/encore dev