FROM node:18-alpine AS frontend_builder

WORKDIR /app

COPY package.json package-lock.json ./

RUN npm install

COPY . .

RUN npm run build

FROM php:8.2-fpm-alpine AS app

WORKDIR /var/www/html

RUN docker-php-ext-install pdo pdo_mysql bcmath exif gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./

RUN composer install --no-dev --optimize-autoloader

COPY . .

COPY --chown=www-data:www-data --from=frontend_builder /app/public ./public

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]