# ===========================
# Stage 1: Build do frontend
# ===========================
FROM node:18-alpine AS frontend_builder

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm install

COPY . .
RUN npm run build

# ===========================
# Stage 2: App PHP/Laravel
# ===========================
FROM php:8.2-fpm AS app

WORKDIR /var/www/html

# Dependências de sistema para extensões PHP (incluindo GD)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libwebp-dev \
    libonig-dev \
    libzip-dev \
    libicu-dev \
    libsodium-dev \
    zip unzip git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        bcmath \
        exif \
        gd \
        zip \
        mbstring \
        intl \
        sodium \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalar dependências PHP
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Copiar código da aplicação
COPY . .

# Copiar build do frontend para a pasta public
COPY --chown=www-data:www-data --from=frontend_builder /app/public ./public

# Permissões para Laravel
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
