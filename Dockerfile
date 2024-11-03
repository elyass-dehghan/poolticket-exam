FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    curl nano && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql \
    && pecl install redis \
    && docker-php-ext-enable redis

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Install Composer
#COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Laravel dependencies
RUN composer install --no-interaction

RUN php artisan config:cache
RUN php artisan route:cache

# Expose port 9000
EXPOSE 9000

# Copy the init script
COPY init.sh /usr/local/bin/init.sh

# Run the init script
RUN chmod +x /usr/local/bin/init.sh && /usr/local/bin/init.sh

CMD ["php-fpm"]
