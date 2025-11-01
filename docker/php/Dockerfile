FROM php:8.2-fpm-alpine

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
        bash git unzip icu-dev libzip-dev freetype-dev libjpeg-turbo-dev libpng-dev nodejs npm \
    && docker-php-ext-configure intl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql intl zip gd \
    && rm -rf /var/cache/apk/*

# Install Composer (copy from official image)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# PHP configuration via environment variables (optional defaults)
ENV PHP_MEMORY_LIMIT=512M \
    PHP_UPLOAD_LIMIT=32M

# Create a basic PHP INI override
RUN { \
  echo "memory_limit=${PHP_MEMORY_LIMIT}"; \
  echo "upload_max_filesize=${PHP_UPLOAD_LIMIT}"; \
  echo "post_max_size=${PHP_UPLOAD_LIMIT}"; \
} > /usr/local/etc/php/conf.d/99-overrides.ini

CMD ["php-fpm"]
