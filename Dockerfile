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
# Stage 2: App PHP/Laravel com Nginx
# ===========================
FROM php:8.2-fpm

WORKDIR /var/www/html

# Variáveis para o Composer
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_MEMORY_LIMIT=-1

# Dependências de sistema + Nginx + Redis + Supervisor
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libwebp-dev \
    libonig-dev \
    libzip-dev \
    libicu-dev \
    libsodium-dev \
    librdkafka-dev \
    nginx \
    supervisor \
    zip unzip git curl \
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
        pcntl \
        sockets \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configuração do Nginx
RUN rm -f /etc/nginx/sites-enabled/default
COPY <<'EOF' /etc/nginx/sites-available/laravel
server {
    listen 80;
    server_name _;
    root /var/www/html/public;
    index index.php index.html;

    client_max_body_size 100M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_read_timeout 300;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

RUN ln -s /etc/nginx/sites-available/laravel /etc/nginx/sites-enabled/laravel

# Configuração do Supervisor para rodar PHP-FPM, Nginx e Queue Worker
COPY <<'EOF' /etc/supervisor/conf.d/laravel.conf
[supervisord]
nodaemon=true
user=root
logfile=/var/log/supervisor/supervisord.log
pidfile=/var/run/supervisord.pid

[program:php-fpm]
command=/usr/local/sbin/php-fpm --nodaemonize
autostart=true
autorestart=true
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0

[program:nginx]
command=/usr/sbin/nginx -g "daemon off;"
autostart=true
autorestart=true
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0

[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/html/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
numprocs=2
user=www-data
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0
EOF

# 1. Instalar dependências PHP (sem scripts e sem autoloader)
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --ignore-platform-reqs \
    --no-autoloader \
    --no-scripts

# 2. Copiar código da aplicação
COPY . .

# 3. Copiar arquivo .env para dentro da imagem
COPY .env .env

# 4. Remover arquivo hot do Vite (se existir) para forçar modo produção
RUN rm -f public/hot

# 5. Copiar build do frontend (DEPOIS de copiar o código para não sobrescrever)
COPY --from=frontend_builder /app/public/build ./public/build

# 6. Verificar se o manifest do Vite existe (essencial para produção)
RUN test -f public/build/manifest.json || (echo "ERRO: manifest.json do Vite não encontrado!" && exit 1)

# 7. Gerar o autoloader e otimizar para produção
RUN composer dump-autoload --optimize --no-dev

# 8. Cache das configurações para produção (após ter o .env)
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Permissões para Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Criar Service Provider customizado para forçar HTTPS durante o BUILD
RUN mkdir -p /var/www/html/app/Providers
COPY <<'PHP_EOF' /var/www/html/app/Providers/ForceHttpsServiceProvider.php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class ForceHttpsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Detecta quando está atrás de um proxy HTTPS (Traefik)
        if (
            (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
            (isset($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on') ||
            !empty($_ENV['APP_FORCE_HTTPS'])
        ) {
            $_SERVER['HTTPS'] = 'on';
            $_SERVER['SERVER_PORT'] = 443;
            URL::forceScheme('https');
        }
    }
}
PHP_EOF

# Registrar o Service Provider
RUN if [ -f /var/www/html/bootstrap/providers.php ]; then \
        if ! grep -q "ForceHttpsServiceProvider" /var/www/html/bootstrap/providers.php; then \
            sed -i "s/return \[/return [\n    App\\\\Providers\\\\ForceHttpsServiceProvider::class,/" /var/www/html/bootstrap/providers.php; \
        fi; \
    fi && \
    if [ -f /var/www/html/config/app.php ] && ! grep -q "ForceHttpsServiceProvider" /var/www/html/config/app.php; then \
        sed -i "/App\\\\Providers\\\\AppServiceProvider::class,/a \        App\\\\Providers\\\\ForceHttpsServiceProvider::class," /var/www/html/config/app.php; \
    fi

# Criar script de entrypoint para configurar HTTPS no RUNTIME
COPY <<'ENTRYPOINT_EOF' /entrypoint.sh
#!/bin/bash
set -e

echo "=== Configurando Laravel para HTTPS ==="

# Forçar URLs HTTPS (sobrescreve qualquer configuração do .env)
export APP_URL="${APP_URL:-https://boxfarma.otmiz.tech}"
export ASSET_URL="${ASSET_URL:-https://boxfarma.otmiz.tech}"
export APP_FORCE_HTTPS=true

# Atualizar .env com URLs HTTPS e configurações de proxy
sed -i 's|^APP_URL=.*|APP_URL='"$APP_URL"'|g' /var/www/html/.env
sed -i 's|^ASSET_URL=.*|ASSET_URL='"$ASSET_URL"'|g' /var/www/html/.env || echo "ASSET_URL=$ASSET_URL" >> /var/www/html/.env

# Configurar Laravel para confiar em proxies (Traefik)
grep -q "^TRUSTED_PROXIES=" /var/www/html/.env && sed -i 's|^TRUSTED_PROXIES=.*|TRUSTED_PROXIES=*|g' /var/www/html/.env || echo "TRUSTED_PROXIES=*" >> /var/www/html/.env

# Limpar e recriar caches com as novas configurações
echo "=== Limpando caches ==="
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo "=== Recriando caches ==="
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=== Iniciando aplicação ==="
exec /usr/bin/supervisord -c /etc/supervisor/supervisord.conf
ENTRYPOINT_EOF

RUN chmod +x /entrypoint.sh

# Expor porta 80 para Traefik
EXPOSE 80

# Usar o entrypoint script
ENTRYPOINT ["/entrypoint.sh"]
