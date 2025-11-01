# Use official PHP with Apache
FROM php:8.2-apache

# Install PHP extensions if needed
RUN apt-get update && apt-get install -y zip unzip git \
    && docker-php-ext-install pdo pdo_mysql mysqli

# Set working directory inside container
WORKDIR /var/www/html

# Copy your app into the container
COPY . .

# Update Apache to listen on Render’s dynamic port
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf

# Expose that port (Render expects it)
EXPOSE 10000

# Start Apache
CMD ["apache2-foreground"]
