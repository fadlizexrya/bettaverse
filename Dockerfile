FROM php:8.3-fpm-alpine

# Install ekstensi yang dibutuhkan Laravel
RUN docker-php-ext-install pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy kode project
COPY . .

# Beri izin akses folder storage dan cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]