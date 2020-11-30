composer install

chown www-data:www-data -R ./storage ./vendor
chmod -R 755 ./storage
chmod -R 755 ./vendor

php artisan config:clear
# @TODO: This is just to make the app working. Dont generate a new key every time if we have data that is encrypted (e.g. passwords)
php artisan key:generate
php artisan config:cache
php artisan migrate

npm install && npm run dev

rm -f laravel-echo-server.lock

/etc/init.d/supervisor start

cron && crontab -u www-data ./cron/main_crontab

docker-php-entrypoint php-fpm
