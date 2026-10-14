#!/bin/bash

# Asegurar archivo .env
if [ ! -f /var/www/html/.env ]; then
    cp /var/www/html/.env.example /var/www/html/.env
fi

# Asegurar APP_KEY si no existe
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force || true
fi

# Permisos para SQLite y almacenamiento
touch /var/www/html/database/database.sqlite
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database /var/www/html/.env || true
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database /var/www/html/.env || true

# Enlace de almacenamiento y migraciones
php artisan storage:link || true
php artisan migrate --force || true

# Limpieza de caché
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Configurar puerto dinámico de Render si viene especificado
if [ -n "$PORT" ]; then
    echo "Agregando escucha en puerto $PORT..."
    echo "Listen $PORT" >> /etc/apache2/ports.conf
fi

echo "Iniciando Apache en primer plano..."
exec apache2-foreground
