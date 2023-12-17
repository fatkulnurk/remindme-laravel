#!/bin/bash

npm install
npm run build

if [ ! -f "vendor/autoload.php" ]; then
    composer install --optimize-autoloader --no-dev --no-progress --no-interaction
fi

composer update

if [ ! -f ".env" ]; then
    echo "Creating env file for env $APP_ENV"
    cp .env.example .env
else
    echo "env file exists."
fi

php artisan key:generate
php artisan migrate
php artisan optimize:clear
php artisan optimize

php-fpm -D
nginx -g "daemon on;"
php artisan schedule:work
