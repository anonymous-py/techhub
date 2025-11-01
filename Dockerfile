# Use official PHP with Apache
FROM php:8.2-apache

# Install PHP extensions for PostgreSQL and tools
RUN apt-get update && apt-get install -y zip unzip git \
    && docker-php-ext-install pdo pdo_pgsql

# Set working directory
WORKDIR /var/www/html

# Copy app files
COPY . .

# Enable Apache modules
RUN a2enmod rewrite

# Apache uses Render's dynamic port at runtime
ENV APACHE_RUN_PORT=$PORT

# Expose default port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
