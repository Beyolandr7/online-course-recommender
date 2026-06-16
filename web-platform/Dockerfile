# ── Stage 1: Build assets (Node + Vite) ─────────────────────────────────────
FROM node:20-alpine AS node-builder

WORKDIR /app

# Install all npm deps (including devDependencies needed by Vite)
COPY package.json package-lock.json ./
RUN npm ci

# Copy only what Vite needs to compile — not the whole project
COPY resources/ ./resources/
COPY vite.config.js ./

# Run Vite production build → outputs to public/build/
RUN npm run build


# ── Stage 2: Install PHP dependencies (Composer) ────────────────────────────
FROM composer:2.7 AS composer-builder

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --ignore-platform-reqs

COPY . .
RUN composer dump-autoload --optimize --no-dev


# ── Stage 3: Production image ────────────────────────────────────────────────
FROM php:8.2-apache

# Install required PHP extensions + system deps in a single layer
RUN apt-get update && apt-get install -y --no-install-recommends \
        libzip-dev \
        zip \
        unzip \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        ca-certificates \
    && docker-php-ext-install \
        pdo_mysql \
        zip \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite (needed for Laravel's clean URLs)
RUN a2enmod rewrite

# Point Apache document root to Laravel's /public folder
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Change Apache to listen on port 7860 (required by Hugging Face Spaces)
RUN sed -i 's/80/7860/g' /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf

# Allow .htaccess overrides (required for Laravel routing)
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

WORKDIR /var/www/html

# Copy vendor from composer-builder stage
COPY --from=composer-builder /app/vendor ./vendor

# Copy application source FIRST (excludes anything in .dockerignore)
# public/build is excluded via .dockerignore so the next COPY always wins
COPY . .

# Copy built Vite assets from node-builder — AFTER COPY . . so it's never overwritten
# Laravel reads public/build/manifest.json to fingerprint asset URLs
COPY --from=node-builder /app/public/build ./public/build

# Fix storage and cache permissions (777 to allow any runtime user on Hugging Face Spaces)
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# Create .env from example if it doesn't exist
# Real values are injected by Render as environment variables
RUN cp -n .env.example .env || true

# Generate app key if not already set (safe — Render will override APP_KEY via env)
RUN php artisan key:generate --force

# Cache config/routes/views for faster cold starts
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Copy startup script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 7860
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]