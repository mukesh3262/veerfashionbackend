# =========================
# Stage 1: Build PHP / Laravel
# =========================
FROM php:8.3-fpm AS laravel-builder

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    libonig-dev \
    npm \
    && docker-php-ext-install pdo_mysql zip mbstring

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Create non-root user
RUN useradd -m appuser
USER appuser

# Copy project files
COPY --chown=appuser:appuser . .

# Copy .env example and generate APP_KEY if missing
RUN cp .env.example .env || true
RUN php artisan key:generate

# Install PHP dependencies without running scripts yet
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress --no-scripts

# Manually run artisan commands
RUN php artisan package:discover
RUN php artisan config:cache
RUN php artisan route:cache

# =========================
# Stage 2: Build React frontend
# =========================
FROM node:20 AS react-builder

WORKDIR /var/www/html

# Copy package.json and yarn.lock/npm package-lock.json
COPY package*.json ./
COPY --from=laravel-builder /var/www/html /var/www/html

# Install Node dependencies
RUN npm install

# Build React assets
RUN npm run build

# =========================
# Stage 3: Final PHP runtime
# =========================
FROM php:8.3-fpm

WORKDIR /var/www/html

# Copy PHP/Laravel files from builder
COPY --from=laravel-builder /var/www/html /var/www/html

# Copy React build
COPY --from=react-builder /var/www/html/build /var/www/html/public/build

# Install PHP extensions
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libonig-dev \
    && docker-php-ext-install pdo_mysql zip mbstring

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
