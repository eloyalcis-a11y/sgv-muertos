#!/bin/bash
set -e

PORT=${PORT:-80}

# Adaptar puerto para Render (inyecta automáticamente $PORT)
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Generar APP_KEY automáticamente si no se pasó en Render
if [ -z "$APP_KEY" ]; then
    echo "Configurando APP_KEY..."
    php artisan key:generate --force || true
fi

# Preparar base de datos SQLite y enlaces
touch database/database.sqlite
chown -R www-data:www-data database storage bootstrap/cache
chmod -R 775 database storage bootstrap/cache

php artisan storage:link || true
php artisan migrate --force || true

echo "Servidor listo en el puerto ${PORT}..."
exec apache2-foreground
