# ---- Stage 1: Base PHP Image ----
FROM php:8.3-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    git \
    bash \
    && docker-php-ext-install intl zip pdo pdo_mysql

# Copy Composer from Composer image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# COPY .env.production .env


# ✅ Create Laravel cache and storage directories BEFORE composer install
RUN mkdir -p bootstrap/cache storage/framework storage/logs \
    && chmod -R 777 bootstrap/cache storage

# Ensure storage directories exist and are writable    
RUN mkdir -p storage/framework/{cache,sessions,views} \
    && chmod -R 777 storage bootstrap/cache

RUN mkdir -p storage/framework/{cache,sessions,views} bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache  

RUN mkdir -p bootstrap/cache storage/framework/{cache,sessions,views} storage/logs \
    && chmod -R 777 bootstrap/cache storage        

RUN mkdir -p storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# Install PHP dependencies
RUN composer install --no-dev --no-interaction --optimize-autoloader

# Install PHP dependencies
RUN composer install --no-dev --no-interaction --no-progress --optimize-autoloader

# Expose port (Railway will inject $PORT at runtime)
EXPOSE 9000

# Copy entrypoint script
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Run the container using entrypoint
CMD ["/entrypoint.sh"]

# Run Laravel using the environment PORT
# CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-8080} -t public"]
# CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8000}