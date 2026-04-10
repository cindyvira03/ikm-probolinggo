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
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project
COPY . .

# Install dependency TANPA menjalankan artisan dulu (INI FIX ERROR KAMU)
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Permission storage & cache
RUN chmod -R 777 storage bootstrap/cache

# Storage link (aman kalau tidak error)
RUN php artisan storage:link || true

# Cache config (jalankan setelah env ready nanti)
# jangan pakai config:cache saat build di Railway

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=10000