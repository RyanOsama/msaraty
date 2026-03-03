FROM node:20-alpine AS node-builder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

FROM php:8.2-apache

# ✅ استخدام أداة تثبيت الإضافات السريعة
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN apt-get update && apt-get install -y zip unzip git curl \
    && install-php-extensions pdo_mysql mbstring zip exif pcntl bcmath gd

RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader --prefer-dist

COPY . .
COPY --from=node-builder /app/public/build ./public/build

RUN composer dump-autoload --optimize

ENV APACHE_DOCUMENT_ROOT /var/www/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache storage/logs bootstrap/cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 777 /var/www/storage /var/www/bootstrap/cache

RUN sed -i 's/Listen 80/Listen 9876/' /etc/apache2/ports.conf
RUN sed -i 's/:80/:9876/g' /etc/apache2/sites-available/000-default.conf

EXPOSE 9876

CMD ["apache2-foreground"]
