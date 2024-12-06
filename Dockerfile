# Using PHP 8.2 ZTS (Zend Thread Safety) with Alpine Linux as the base image
FROM php:8.2-zts-alpine

# Installing the required dependencies
RUN apk add --no-cache oniguruma-dev libxml2-dev git nodejs npm && \
    docker-php-ext-install bcmath ctype fileinfo mbstring pdo_mysql xml

# Installing Composer
COPY --from=composer:2.8.2 /usr/bin/composer /usr/bin/composer

# Defining the environment variables
ENV WEB_DOCUMENT_ROOT /app/public
ENV APP_ENV local

# Setting the working directory
WORKDIR /antipa

# Copying the local files to the container
COPY . /antipa

# Copying the .env.example file as .env
RUN cp -n .env.example .env

# Configuring laravel & installing composer
RUN composer install && \
    php artisan migrate:fresh --seed && \
    php artisan key:generate && \
    php artisan optimize && \
    php artisan optimize:clear && \
    php artisan config:cache && \
    php artisan event:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Installing the required Node.js dependencies & running Node
RUN npm install && npm run dev

# Defining the command to start the application
CMD php artisan serve --host=0.0.0.0 --port=8000

# Exposing the port 8000
EXPOSE 8000