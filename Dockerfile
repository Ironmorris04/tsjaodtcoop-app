FROM php:8.2-cli

ENV DEBIAN_FRONTEND=noninteractive
ENV CACHE_BUST=2026-01-11-v1

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
    libzip-dev \
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    libmagickwand-dev \
    default-mysql-client \
    cron \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    intl

# Install imagick (required by spatie/pdf-to-image in some setups)
RUN pecl install imagick \
    && docker-php-ext-enable imagick

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV COMPOSER_MEMORY_LIMIT=-1

WORKDIR /var/www

COPY composer.json composer.lock ./

RUN composer install --no-dev --optimize-autoloader --prefer-dist --no-interaction

COPY . .

RUN composer dump-autoload --optimize

RUN chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

EXPOSE 8000

RUN echo "* * * * * cd /var/www && php artisan schedule:run >> /var/www/storage/logs/scheduler.log 2>&1" > /etc/cron.d/laravel-scheduler \
    && chmod 0644 /etc/cron.d/laravel-scheduler \
    && crontab /etc/cron.d/laravel-scheduler

CMD service cron start && \
    php artisan config:clear && \
    php artisan cache:clear && \
    if ! php artisan migrate:status > /dev/null 2>&1; then \
        php artisan migrate --force; \
    fi && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
