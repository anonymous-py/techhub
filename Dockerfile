# Use official PHP with Apache
FROM php:8.2-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y zip unzip git libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy app files
COPY . .

# Install dependencies (this will create a complete vendor folder)
RUN composer install --no-dev --optimize-autoloader

# Configure Apache for Render
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf

# Expose the port
EXPOSE 10000

# Start Apache
CMD ["apache2-foreground"]
