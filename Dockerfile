# Gunakan PHP 8.1 dengan Apache
FROM php:8.1-apache

# Install ekstensi PHP yang diperlukan
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring gd

# Set working directory
WORKDIR /var/www/html

# Copy semua file proyek ke dalam container
COPY . .

# Install Composer dependencies (jika ada)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader || true

# Optional: Konfigurasi upload file
RUN echo "upload_max_filesize = 10M\npost_max_size = 12M" > /usr/local/etc/php/conf.d/custom.ini

# Expose port Apache
EXPOSE 80

# Jalankan Apache
CMD ["apache2-foreground"]
