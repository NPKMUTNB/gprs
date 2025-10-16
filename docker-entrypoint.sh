#!/bin/bash
set -e

echo "=========================================="
echo "Starting Student Project Repository Setup"
echo "=========================================="

# Wait for a moment to ensure filesystem is ready
sleep 2

# Check if vendor exists, if not install
if [ ! -d "vendor" ] || [ ! -f "vendor/autoload.php" ]; then
    echo "📦 Installing Composer dependencies..."
    composer install --no-interaction --optimize-autoloader --no-dev || {
        echo "⚠️  Composer install failed, trying without --no-dev..."
        composer install --no-interaction --optimize-autoloader
    }
else
    echo "✅ Composer dependencies already installed"
fi

# Check if node_modules exists, if not install
if [ ! -d "node_modules" ]; then
    echo "📦 Installing NPM dependencies..."
    npm ci --production || npm install --production || {
        echo "⚠️  NPM install failed, trying regular install..."
        npm install
    }
else
    echo "✅ NPM dependencies already installed"
fi

# Build assets if needed
if [ ! -d "public/build" ] || [ -z "$(ls -A public/build 2>/dev/null)" ]; then
    echo "🎨 Building frontend assets..."
    npm run build || {
        echo "⚠️  Asset build failed, continuing anyway..."
    }
else
    echo "✅ Assets already built"
fi

# Setup database
echo "🗄️  Setting up database..."
if [ ! -f "database/database.sqlite" ]; then
    touch database/database.sqlite
fi
chmod 664 database/database.sqlite 2>/dev/null || true
chown www-data:www-data database/database.sqlite 2>/dev/null || true

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link 2>/dev/null || true

# Only cache if not in development
if [ "$APP_ENV" != "local" ]; then
    echo "🧹 Caching configuration..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
else
    echo "ℹ️  Skipping cache in development mode"
fi

# Set permissions
echo "🔒 Setting permissions..."
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

echo "=========================================="
echo "✅ Setup complete! Starting Apache..."
echo "=========================================="

# Start Apache
exec apache2-foreground
