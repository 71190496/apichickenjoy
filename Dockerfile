# Stage 1: Build
FROM php:8.2-fpm AS build
WORKDIR /var/www/html
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpng-dev && \
    docker-php-ext-install pdo pdo_pgsql pgsql mbstring xml zip gd \
    && docker-php-ext-enable gd
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY . /var/www/html
RUN composer install

# # Stage 2: Runtime
# FROM php:8.2-fpm
# WORKDIR /var/www/html
# COPY --from=build /var/www/html /var/www/html
# EXPOSE 8000
# CMD php artisan serve --host=0.0.0.0 --port=8000