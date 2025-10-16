#!/bin/bash
set -e

echo "=========================================="
echo "Starting Student Project Repository Setup"
echo "=========================================="

# Wait for a moment to ensure filesystem is ready
sleep 2

# Verify vendor exists (should be installed during build)
if [ ! -d "vendor" ] || [ ! -f "vendor/autoload.php" ]; then
    echo "WARNING: Vendor directory missing, installing..."
    composer install --no-interaction --optimize-autoloader --no-dev
fi

echo "✅ Composer dependencies ready"

# Generate APP_KEY if not exists
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:CHANGEME" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
else
    echo "✅ Application key already set"
fi

# Setup database
echo "🗄️  Setting up database..."
if [ ! -f "database/database.sqlite" ]; then
    echo "Creating SQLite database file..."
    touch database/database.sqlite
fi

# Set database permissions BEFORE migrations
chmod 664 database/database.sqlite 2>/dev/null || true
chmod 775 database 2>/dev/null || true
chown -R www-data:www-data database 2>/dev/null || true

# Run migrations
echo "🔄 Running database migrations..."
php artisan migrate --force || {
    echo "⚠️  Migration failed, but continuing..."
}

# Seed database if needed (only on first run)
if [ ! -f "database/.seeded" ]; then
    echo "🌱 Seeding database..."
    php artisan db:seed --force || {
        echo "⚠️  Seeding failed, but continuing..."
    }
    touch database/.seeded
fi

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link --force 2>/dev/null || true

# Set permissions for storage and cache
echo "🔒 Setting permissions..."
chown -R www-data:www-data storage bootstrap/cache database 2>/dev/null || true
chmod -R 775 storage bootstrap/cache database 2>/dev/null || true

# Create necessary storage directories
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/logs
chmod -R 775 storage
chown -R www-data:www-data storage

# Clear any existing cache before caching (important!)
echo "🧹 Clearing old cache..."
php artisan cache:clear || true
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Only cache if not in development
if [ "$APP_ENV" != "local" ]; then
    echo "💾 Caching configuration..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
else
    echo "ℹ️  Skipping cache in development mode"
fi

echo "=========================================="
echo "✅ Setup complete! Starting Apache..."
echo "=========================================="

# Start Apache
exec apache2-foreground
