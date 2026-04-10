# Base image PHP
FROM php:8.2-fpm

# Install dependencies sistem
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev

# Install ekstensi PHP yang dibutuhkan Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project ke container
COPY . .

# Install dependency Laravel
RUN composer install --no-dev --optimize-autoloader

# Permission storage & cache
RUN chmod -R 775 storage bootstrap/cache

# Cache config (optional tapi bagus untuk production)
RUN php artisan config:cache
RUN php artisan route:cache || true

# Expose port Render
EXPOSE 10000

# Jalankan Laravel server
CMD php artisan storage:link && php artisan serve --host=0.0.0.0 --port=10000