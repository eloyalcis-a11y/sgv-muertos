#!/bin/bash
set -e

PORT=${PORT:-80}

# Adaptar puerto para Render (inyecta automáticamente $PORT)
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Asegurar que exista .env
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Generar APP_KEY si aún no tiene una
if [ -z "$APP_KEY" ] && ! grep -q "^APP_KEY=base64:" .env; then
    echo "Generando APP_KEY..."
    php artisan key:generate --force || true
fi

# Asegurar archivo y permisos de SQLite
touch database/database.sqlite
chown -R www-data:www-data database storage bootstrap/cache .env
chmod -R 775 database storage bootstrap/cache
chmod 664 .env

# Crear enlace simbólico de almacenamiento
php artisan storage:link || true

# Ejecutar migraciones
php artisan migrate --force || true

# Limpiar cachés para que tome la configuración fresca
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

echo "Servidor listo en el puerto ${PORT}..."
exec apache2-foreground
