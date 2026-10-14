FROM php:8.4-apache

# Dependencias del sistema y extensiones de PHP necesarias para Laravel
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libsqlite3-dev \
    && docker-php-ext-install pdo_sqlite mbstring zip gd pcntl bcmath \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Configuración de Apache para Laravel y múltiples puertos (80, 10000, 8080)
RUN a2enmod rewrite

RUN echo "Listen 80\nListen 10000\nListen 8080" > /etc/apache2/ports.conf

RUN echo '<VirtualHost *:*>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        Options -Indexes +FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Copiar dependencias de Composer primero (optimiza caché)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-scripts --prefer-dist

# Copiar el resto del código
COPY . .

# Crear .env base a partir de .env.example
RUN cp .env.example .env && php artisan key:generate --force

# Finalizar autoload optimizado
RUN composer dump-autoload --optimize

# Permisos
RUN touch database/database.sqlite \
    && chmod -R 777 storage bootstrap/cache database .env \
    && chown -R www-data:www-data storage bootstrap/cache database .env

# Script de arranque
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80 10000 8080

ENTRYPOINT ["docker-entrypoint.sh"]
