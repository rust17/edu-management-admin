#!/bin/bash

# 启动 Nginx
nginx -g "daemon on; master_process on;"

# 启动 PHP-FPM
php-fpm --fpm-config /usr/local/etc/php-fpm.d/www.conf
