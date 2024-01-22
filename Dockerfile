# Use the official PHP image with FPM based on Alpine
FROM php:8.1-fpm-alpine

ENV APP_HOME /var/www/html/

# Set working directory
WORKDIR $APP_HOME

# Copy the application files to the container
COPY . $APP_HOME

# Install required extensions and dependencies
RUN apk add --no-cache libzip-dev libpng-dev unzip \
    && docker-php-ext-install pdo pdo_mysql zip gd

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Change ownership and permissions for the storage directory
RUN chown -R www-data:www-data /var/www/html/storage \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 777 /var/www/html/storage

# Run Composer install with the ignore-platform-req option
RUN composer install --ignore-platform-req=ext-zip

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
