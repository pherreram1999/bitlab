# Etapa 1: Construcción del Frontend (Vite/Node)
FROM node:20-alpine AS frontend_build
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# Etapa 2: Construcción del Backend y Servidor (FrankenPHP)
FROM dunglas/frankenphp:php8.3

# Argumentos y Variables de entorno
ARG PHP_MEMORY_LIMIT=512M
ENV SERVER_NAME=":8000"
ENV APP_ROOT=/app
ENV PHP_INI_MEMORY_LIMIT=${PHP_MEMORY_LIMIT}

# Instalar dependencias del sistema y extensiones
# pcntl es OBLIGATORIO para Laravel Octane
RUN install-php-extensions \
    pcntl \
    bcmath \
    gd \
    intl \
    zip \
    pdo_mysql \
    opcache \
    redis

# Configurar límite de memoria de PHP
RUN echo "memory_limit = ${PHP_INI_MEMORY_LIMIT}" > /usr/local/etc/php/conf.d/memory-limit.ini

# Copiar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Instalar dependencias de backend
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --prefer-dist

# Copiar código fuente y assets compilados
COPY . .
COPY --from=frontend_build /app/public/build public/build

# Copiar y configurar el entrypoint
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Permisos para carpetas de escritura
RUN chmod -R 777 storage bootstrap/cache

# Definir el entrypoint
ENTRYPOINT ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=8000", "--workers=auto", "--max-requests=1000"]