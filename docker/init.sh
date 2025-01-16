#!/bin/bash

# 启动 Nginx
nginx -g "daemon on; master_process on;"

# debug
echo "APP_ENV=local
APP_DEBUG=true
" > .env

# 查看有没有 .env 文件
cat .env

# 启动 PHP-FPM
php-fpm --fpm-config /usr/local/etc/php-fpm.d/www.conf
