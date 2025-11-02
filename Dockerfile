# Use official PHP with Apache
FROM php:8.2-apache

# Install required packages
RUN apt-get update && apt-get install -y zip unzip git libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy all app files
COPY . .

# Clean and reinstall dependencies
RUN rm -rf vendor \
    && composer clear-cache \
    && composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Configure Apache for Render
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf

EXPOSE 10000
CMD ["apache2-foreground"]
