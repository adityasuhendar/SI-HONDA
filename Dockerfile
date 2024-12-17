# Gunakan PHP 8.3 dengan Apache
FROM php:8.2-apache

# Install ekstensi PHP yang diperlukan
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install mysqli gd

# Set direktori kerja Apache
WORKDIR /var/www/html

# Copy semua file proyek ke dalam container
COPY . .

# Berikan izin untuk Apache
RUN chown -R www-data:www-data /var/www/html

# Konfigurasi upload file
RUN echo "upload_max_filesize = 10M\npost_max_size = 12M" > /usr/local/etc/php/conf.d/custom.ini

# Expose port 80 untuk Apache
EXPOSE 80

# Jalankan Apache
ENV APACHE_RUN_PORT=${PORT}
CMD ["apache2-foreground"]
