#!/bin/bash

# Production Deployment Script
# Server IP: 139.59.244.163
# Port: 80

echo "🚀 Student Project Repository - Production Deployment"
echo "======================================================"
echo "Server: 139.59.244.163"
echo "Port: 80"
echo ""

# Check if running as root or with sudo
if [ "$EUID" -ne 0 ]; then 
    echo "⚠️  This script needs to be run with sudo for port 80"
    echo "   Please run: sudo ./deploy-production.sh"
    exit 1
fi

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "❌ Docker is not installed. Installing Docker..."
    curl -fsSL https://get.docker.com -o get-docker.sh
    sh get-docker.sh
    rm get-docker.sh
    echo "✅ Docker installed"
fi

# Check if Docker Compose is installed
if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose is not installed. Installing Docker Compose..."
    curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
    chmod +x /usr/local/bin/docker-compose
    echo "✅ Docker Compose installed"
fi

echo ""
echo "✅ Docker and Docker Compose are ready"
echo ""

# Stop any existing containers
echo "🛑 Stopping existing containers..."
docker-compose down

# Copy production environment file
if [ ! -f .env ]; then
    echo "📝 Creating .env file from .env.production..."
    cp .env.production .env
    echo "✅ .env file created"
else
    echo "⚠️  .env file already exists"
    read -p "Do you want to overwrite with .env.production? (y/n) " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        cp .env.production .env
        echo "✅ .env file updated"
    fi
fi

# Generate APP_KEY if not set
if ! grep -q "APP_KEY=base64:" .env; then
    echo "🔑 Generating application key..."
    APP_KEY=$(openssl rand -base64 32)
    sed -i.bak "s|APP_KEY=|APP_KEY=base64:${APP_KEY}|" .env
    rm -f .env.bak
    echo "✅ Application key generated"
else
    echo "ℹ️  Application key already set"
fi

# Create necessary directories
echo ""
echo "📁 Creating necessary directories..."
mkdir -p storage/app/public
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache
mkdir -p database

# Set permissions
echo "🔒 Setting permissions..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

echo ""
echo "🏗️  Building Docker containers..."
docker-compose build --no-cache

echo ""
echo "🚀 Starting Docker containers..."
docker-compose up -d

echo ""
echo "⏳ Waiting for containers to be ready..."
sleep 10

echo ""
echo "📦 Installing Composer dependencies..."
docker-compose exec -T app composer install --no-interaction --optimize-autoloader --no-dev

echo ""
echo "🎨 Building frontend assets..."
docker-compose exec -T app npm ci --production
docker-compose exec -T app npm run build

echo ""
echo "🗄️  Setting up database..."
# Create database file if it doesn't exist
docker-compose exec -T app touch database/database.sqlite
docker-compose exec -T app chmod 664 database/database.sqlite
docker-compose exec -T app chown www-data:www-data database/database.sqlite

# Run migrations
echo "Running migrations..."
docker-compose exec -T app php artisan migrate --force

echo ""
read -p "Do you want to seed the database with initial data? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    docker-compose exec -T app php artisan db:seed --force
    echo "✅ Database seeded"
    echo ""
    echo "📋 Default credentials:"
    echo "   Admin: admin@example.com / password"
    echo "   Advisor: advisor@example.com / password"
    echo "   Committee: committee@example.com / password"
    echo "   Student: student@example.com / password"
    echo ""
    echo "⚠️  IMPORTANT: Change these passwords immediately!"
fi

echo ""
echo "🔗 Creating storage link..."
docker-compose exec -T app php artisan storage:link

echo ""
echo "🧹 Optimizing application..."
docker-compose exec -T app php artisan config:cache
docker-compose exec -T app php artisan route:cache
docker-compose exec -T app php artisan view:cache
docker-compose exec -T app php artisan optimize

echo ""
echo "🔒 Setting final permissions..."
docker-compose exec -T app chown -R www-data:www-data /var/www/html/storage
docker-compose exec -T app chown -R www-data:www-data /var/www/html/bootstrap/cache
docker-compose exec -T app chmod -R 775 /var/www/html/storage
docker-compose exec -T app chmod -R 775 /var/www/html/bootstrap/cache

echo ""
echo "✅ Deployment complete!"
echo ""
echo "🌐 Application is now running at:"
echo "   http://139.59.244.163"
echo ""
echo "📋 Useful commands:"
echo "   - View logs:        docker-compose logs -f app"
echo "   - Stop:             docker-compose stop"
echo "   - Start:            docker-compose start"
echo "   - Restart:          docker-compose restart"
echo "   - Shell access:     docker-compose exec app bash"
echo "   - View status:      docker-compose ps"
echo ""
echo "🔒 Security Checklist:"
echo "   [ ] Change default passwords"
echo "   [ ] Set up firewall (ufw)"
echo "   [ ] Configure SSL/TLS (recommended)"
echo "   [ ] Set up regular backups"
echo "   [ ] Monitor logs regularly"
echo ""
echo "🎉 Deployment successful!"
