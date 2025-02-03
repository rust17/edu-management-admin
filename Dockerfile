FROM php:7.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    nginx \
    libzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev

# Clean apt cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ && \
    docker-php-ext-install -j$(nproc) \
    pdo_pgsql \
    pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html

# Set directory permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Install project dependencies
RUN composer install --optimize-autoloader --no-dev

# Configure PHP
COPY docker/php/php.ini /usr/local/etc/php/php.ini

# Copy Nginx config file
COPY docker/nginx/default.conf /etc/nginx/sites-enabled/default

# Copy PHP-FPM config
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf

# Expose port
EXPOSE 80

# Copy startup script
COPY docker/init.sh /usr/local/bin/init.sh
RUN chmod +x /usr/local/bin/init.sh

# Start services
CMD ["/usr/local/bin/init.sh"]
