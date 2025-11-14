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

# Variáveis para o Composer
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_MEMORY_LIMIT=-1

# Dependências de sistema...
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

# 1. Instalar dependências PHP (sem scripts e sem autoloader)
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --ignore-platform-reqs \
    --no-autoloader \
    --no-scripts

# 2. Copiar código da aplicação (agora o 'artisan' existe)
COPY . .

# 3. Copiar build do frontend
COPY --chown=www-data:www-data --from=frontend_builder /app/public ./public

# 4. AGORA sim, gerar o autoloader e rodar os scripts
# O dump-autoload vai disparar o 'package:discover' automaticamente
RUN **composer dump-autoload --optimize --no-dev**

# Permissões para Laravel (corrigi o caminho, são 2 diretórios)
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
