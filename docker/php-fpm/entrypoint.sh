composer install
php artisan config:cache

chown www-data:www-data -R ./storage ./vendor
chmod -R 755 ./storage
chmod -R 755 ./vendor

cron && crontab -u www-data ./cron/main_crontab

docker-php-entrypoint php-fpm
