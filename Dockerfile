# syntax=docker/dockerfile:1

ARG PHP_VERSION=7.4.33

# =========================================================
# Imagem base PHP + Apache
# =========================================================
FROM php:${PHP_VERSION}-apache AS php-base

RUN apt-get update \
    && apt-get install -y \
        curl \
        git \
        unzip \
        fonts-dejavu-core \
        libzip-dev \
        libonig-dev \
        libxml2-dev \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        bcmath \
        zip \
        gd \
        dom \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

COPY docker/apache/000-default.conf \
    /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

# =========================================================
# Imagem usada pelo Jenkins para testes e qualidade
# Inclui dependências de desenvolvimento
# =========================================================
FROM php-base AS ci

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

COPY . .

RUN --mount=type=cache,target=/tmp/composer-cache \
    set -eux; \
    export COMPOSER_CACHE_DIR=/tmp/composer-cache; \
    export COMPOSER_MAX_PARALLEL_HTTP=1; \
    success=0; \
    for attempt in 1 2 3; do \
        if composer install \
            --prefer-dist \
            --no-interaction \
            --no-progress; then \
            success=1; \
            break; \
        fi; \
        echo "Tentativa ${attempt} do Composer falhou. Tentando novamente..."; \
        sleep $((attempt * 10)); \
    done; \
    [ "$success" -eq 1 ]

# =========================================================
# Compilação do frontend com Laravel Mix
# =========================================================
FROM node:18-bullseye-slim AS assets

WORKDIR /app

ENV NODE_OPTIONS=--openssl-legacy-provider

COPY package.json package-lock.json ./

RUN npm ci

COPY . .

RUN npm run production

# =========================================================
# Imagem final de homologação e produção
# Não contém dependências de desenvolvimento
# =========================================================
FROM php-base AS production

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

COPY . .

RUN --mount=type=cache,target=/tmp/composer-cache \
    set -eux; \
    export COMPOSER_CACHE_DIR=/tmp/composer-cache; \
    export COMPOSER_MAX_PARALLEL_HTTP=1; \
    success=0; \
    for attempt in 1 2 3; do \
        if composer install \
            --no-dev \
            --prefer-dist \
            --no-interaction \
            --no-progress \
            --optimize-autoloader; then \
            success=1; \
            break; \
        fi; \
        echo "Tentativa ${attempt} do Composer falhou. Tentando novamente..."; \
        sleep $((attempt * 10)); \
    done; \
    [ "$success" -eq 1 ]

COPY --from=assets /app/public /var/www/html/public

RUN mkdir -p \
        storage/framework/cache \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
    && chown -R www-data:www-data \
        storage \
        bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]