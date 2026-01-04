#!/bin/sh
set -e

# Opcional: Esperar unos segundos si la BD externa tarda en responder
# sleep 5

echo "🚀 Iniciando despliegue..."

echo "📂 Ejecutando migraciones..."
php artisan migrate --force

echo "🌱 Ejecutando seeders..."
php artisan db:seed --force

echo "🔥 Iniciando servidor Octane (FrankenPHP)..."
exec php artisan octane:start --server=frankenphp --host=0.0.0.0 --port=8000 --workers=auto --max-requests=1000
