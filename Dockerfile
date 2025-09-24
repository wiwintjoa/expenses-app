# Use PHP-FPM 8.2
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libpq-dev \
    gnupg2 ca-certificates sudo build-essential \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Install Node.js 20 (latest LTS)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --ignore-platform-reqs --no-interaction

# Install Node dependencies
RUN npm install

# Expose PHP-FPM and Vite ports
EXPOSE 9000 5173

# Start PHP-FPM
CMD ["php-fpm"]
