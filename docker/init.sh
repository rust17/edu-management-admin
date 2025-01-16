#!/bin/bash

# 启动 Nginx
nginx -g "daemon on; master_process on;"

# 查看有没有 .env 文件
ls /var/www/html
cat /var/www/html/.env

# 启动 PHP-FPM
php-fpm --fpm-config /usr/local/etc/php-fpm.d/www.conf
