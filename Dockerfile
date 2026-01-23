FROM php:8.2-cli

# Prevent apt warnings
ENV DEBIAN_FRONTEND=noninteractive

# Force cache invalidation - change this value when you need to rebuild from scratch
ENV CACHE_BUST=2026-01-11-v1

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
    cron \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Allow Composer unlimited memory
ENV COMPOSER_MEMORY_LIMIT=-1

# Set working directory
WORKDIR /var/www

# Copy composer files first (for better caching)
COPY composer.json composer.lock ./

# ⬇️ FIX: disable scripts to prevent artisan from running during build
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --no-scripts

# Copy rest of application
COPY . .

# Safe autoload optimization (no artisan)
RUN composer dump-autoload --optimize

# Set permissions
RUN chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

# Expose port (Render will provide $PORT)
EXPOSE 8000

# Add cron job for Laravel scheduler
RUN echo "* * * * * cd /var/www && php artisan schedule:run >> /var/www/storage/logs/scheduler.log 2>&1" > /etc/cron.d/laravel-scheduler \
    && chmod 0644 /etc/cron.d/laravel-scheduler \
    && crontab /etc/cron.d/laravel-scheduler

# Start cron + Laravel server
CMD service cron start && \
    php artisan config:clear && \

    # Ensure cache table exists before clearing (needed when CACHE_STORE=database)
    php artisan cache:table --quiet || true && \

    # Run migrations first (so cache table is created) then clear cache
    if ! php artisan migrate:status > /dev/null 2>&1; then \
        echo "No tables found. Running migrations..." && \
        php artisan migrate --force; \
    else \
        echo "Database already initialized. Skipping migrations."; \
    fi && \

    php artisan cache:clear && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
