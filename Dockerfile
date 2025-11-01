# Use official PHP with Apache
FROM php:8.2-apache

# Install PHP extensions for PostgreSQL and tools
RUN apt-get update && apt-get install -y \
    zip unzip git \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Set working directory
WORKDIR /var/www/html

# Copy app files
COPY . .

# Enable Apache modules
RUN a2enmod rewrite

# Let Apache use Render's dynamic port
ENV APACHE_RUN_PORT=$PORT

# Expose default port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
