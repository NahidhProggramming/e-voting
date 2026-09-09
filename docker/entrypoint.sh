#!/bin/sh
set -e

echo "🚀 Starting Laravel E-Voting..."

# Use PORT from Railway or default to 8080
export PORT="${PORT:-8080}"
echo "📡 Server will listen on port: $PORT"

# Substitute PORT in Nginx config
envsubst '${PORT}' < /etc/nginx/http.d/default.conf > /etc/nginx/http.d/default.conf.tmp
mv /etc/nginx/http.d/default.conf.tmp /etc/nginx/http.d/default.conf

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link --force 2>/dev/null || true

# Run migrations
echo "📦 Running migrations..."
php artisan migrate --force --no-interaction

# Seed database if needed (only if no candidates exist)
echo "🌱 Checking if seeding is needed..."
php artisan db:seed --force --no-interaction 2>/dev/null || true

# Cache configuration for production
echo "⚡ Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "✅ Laravel is ready! Starting server..."

# Start supervisor (manages nginx + php-fpm)
exec /usr/bin/supervisord -c /etc/supervisord.conf
