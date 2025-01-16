#!/bin/bash

# 启动 PHP-FPM
php-fpm --fpm-config /usr/local/etc/php-fpm.d/www.conf
echo "PHP-FPM started"

# 启动 Nginx
nginx -g "daemon off;"
if [ $? -ne 0 ]; then
    echo "Failed to start Nginx"
    exit 1
fi
