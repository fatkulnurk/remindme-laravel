#!/bin/bash

npm install

if [ ! -f "vendor/autoload.php" ]; then
    composer install --no-progress --no-interaction
fi

composer update

if [ ! -f ".env" ]; then
    echo "Creating env file for env $APP_ENV"
    cp .env.example .env
else
    echo "env file exists."
fi

php artisan key:generate
php artisan migrate:fresh --seed
php artisan optimize:clear
php artisan view:clear
php artisan route:clear
php artisan test

php-fpm -D
nginx -g "daemon on;"

php artisan schedule:work | npm run dev
