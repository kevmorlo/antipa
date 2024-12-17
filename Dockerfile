# Multi-stage Dockerfile pour Laravel avec PHP 8.2 et Node.js
FROM node:18-alpine as node-build
WORKDIR /antipa
COPY package.json package-lock.json ./
RUN npm install
COPY . .
RUN npm run build

FROM php:8.2-zts-alpine
RUN apk add --no-cache oniguruma-dev libxml2-dev git && \
    docker-php-ext-install bcmath ctype fileinfo mbstring pdo_mysql xml && \
    rm -rf /var/cache/apk/*
COPY --from=composer:2.8.2 /usr/bin/composer /usr/bin/composer

WORKDIR /antipa
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader
COPY . .
COPY --from=node-build /antipa/public/js /antipa/public/js
COPY --from=node-build /antipa/public/css /antipa/public/css

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
EXPOSE 8000