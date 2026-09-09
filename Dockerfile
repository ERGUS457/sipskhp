FROM php:8.1-apache

# Install dependencies sistem untuk PostgreSQL, GD, dan ZIP
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_pgsql \
        pgsql \
        gd \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Aktifkan mod_rewrite Apache
RUN a2enmod rewrite

# Aktifkan AllowOverride All untuk /var/www/ agar .htaccess bekerja
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Salin Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Salin seluruh kode proyek ke container
COPY . /var/www/html/

# Install dependensi PHP (Dompdf, dll) via Composer
RUN if [ -f composer.json ]; then composer install --no-dev --optimize-autoloader --no-interaction; fi

# Berikan hak akses tulis ke folder cache, logs, dan uploads
RUN mkdir -p /var/www/html/application/cache /var/www/html/application/logs /var/www/html/uploads \
    && chown -R www-data:www-data /var/www/html/application/cache /var/www/html/application/logs /var/www/html/uploads \
    && chmod -R 775 /var/www/html/application/cache /var/www/html/application/logs /var/www/html/uploads

# Expose port 80
EXPOSE 80

# Entry command: dukung variabel $PORT dinamis dari Render (fallback ke 80) lalu jalankan apache
CMD sh -c "sed -i \"s/Listen 80/Listen \${PORT:-80}/\" /etc/apache2/ports.conf && sed -i \"s/<VirtualHost \*:80>/<VirtualHost \*:\${PORT:-80}>/\" /etc/apache2/sites-available/*.conf && apache2-foreground"
