FROM php:8.2-cli

# Prevent apt warnings
ENV DEBIAN_FRONTEND=noninteractive

# Force cache invalidation - change this value when you need to rebuild from scratch
ENV CACHE_BUST=2024-01-10-v1

# Install system dependencies including Ghostscript
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    ghostscript \
    libgs-dev \
    default-mysql-client \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Increase memory limit for Composer
RUN echo "memory_limit = 512M" > /usr/local/etc/php/conf.d/memory.ini

# Set working directory
WORKDIR /var/www

# Copy composer files first (for better caching)
COPY composer.json composer.lock ./

# Install dependencies with retries and prefer-dist
RUN composer install --no-dev --optimize-autoloader --no-scripts --prefer-dist || \
    composer install --no-dev --optimize-autoloader --no-scripts --prefer-dist || \
    composer install --no-dev --optimize-autoloader --no-scripts

# Copy rest of application
COPY . .

# Run post-install scripts
RUN composer dump-autoload --optimize

# Set permissions
RUN chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

# Expose port (Render will provide $PORT)
EXPOSE 8000

# Run artisan commands at startup (when .env is available), then start server
CMD php artisan config:clear && \
    php artisan cache:clear && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
