#!/bin/bash
php artisan migrate --force
# comment after finish seeders
php artisan db:seed --force
php artisan config:cache
php artisan serve --host=0.0.0.0 --port=$PORT
