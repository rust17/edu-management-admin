#!/bin/bash

php artisan config:cache
php artisan route:cache

if [ $INIT_ADMIN_PASS ]; then
    echo "Initializing admin password..."
    php artisan admin:init --password=$INIT_ADMIN_PASS
fi

if [ $INIT_ADMIN_MENU ]; then
    echo "Initializing admin menu..."
    php artisan admin:init --menu=$INIT_ADMIN_MENU
fi

# 启动 PHP-FPM
php-fpm --fpm-config /usr/local/etc/php-fpm.d/www.conf
echo "PHP-FPM started"

# 启动 Nginx
nginx -g "daemon off;"
if [ $? -ne 0 ]; then
    echo "Failed to start Nginx"
    exit 1
fi
