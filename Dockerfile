# Use official PHP with Apache
FROM php:8.2-apache

RUN apt-get update && apt-get install -y zip unzip git libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# (optional debug)
RUN ls -la /var/www/html

ENV COMPOSER_MEMORY_LIMIT=-1
RUN composer install --no-dev --optimize-autoloader

RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf

EXPOSE 10000
CMD ["apache2-foreground"]
