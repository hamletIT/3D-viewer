#!/bin/bash
php artisan migrate:fresh --force
php artisan storage:link --force
php artisan db:seed --force
php artisan config:cache
php artisan serve --host=0.0.0.0 --port=$PORT
