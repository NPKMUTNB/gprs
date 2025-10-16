#!/bin/bash

# Docker Setup Script for Student Project Repository
# This script helps you set up and run the application using Docker

echo "🚀 Student Project Repository - Docker Setup"
echo "=============================================="
echo ""

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "❌ Docker is not installed. Please install Docker first."
    echo "   Visit: https://docs.docker.com/get-docker/"
    exit 1
fi

# Check if Docker Compose is installed
if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose is not installed. Please install Docker Compose first."
    echo "   Visit: https://docs.docker.com/compose/install/"
    exit 1
fi

echo "✅ Docker and Docker Compose are installed"
echo ""

# Copy .env.docker to .env if .env doesn't exist
if [ ! -f .env ]; then
    echo "📝 Creating .env file from .env.docker..."
    cp .env.docker .env
    echo "✅ .env file created"
else
    echo "ℹ️  .env file already exists"
fi

# Generate APP_KEY if not set
if ! grep -q "APP_KEY=base64:" .env; then
    echo "🔑 Generating application key..."
    # Generate a random base64 key
    APP_KEY=$(openssl rand -base64 32)
    sed -i.bak "s|APP_KEY=|APP_KEY=base64:${APP_KEY}|" .env
    rm -f .env.bak
    echo "✅ Application key generated"
else
    echo "ℹ️  Application key already set"
fi

echo ""
echo "🏗️  Building Docker containers..."
docker-compose build

echo ""
echo "🚀 Starting Docker containers..."
docker-compose up -d

echo ""
echo "⏳ Waiting for containers to be ready..."
sleep 5

echo ""
echo "📦 Installing dependencies..."
docker-compose exec app composer install --no-interaction --optimize-autoloader

echo ""
echo "🎨 Building frontend assets..."
docker-compose exec app npm install
docker-compose exec app npm run build

echo ""
echo "🗄️  Setting up database..."
# Create database file if it doesn't exist
docker-compose exec app touch database/database.sqlite
docker-compose exec app chmod 664 database/database.sqlite

# Run migrations
docker-compose exec app php artisan migrate --force

echo ""
echo "🌱 Seeding database (optional)..."
read -p "Do you want to seed the database with sample data? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    docker-compose exec app php artisan db:seed --force
    echo "✅ Database seeded"
fi

echo ""
echo "🔗 Creating storage link..."
docker-compose exec app php artisan storage:link

echo ""
echo "🧹 Clearing and caching..."
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache

echo ""
echo "✅ Setup complete!"
echo ""
echo "🌐 Application is running at: http://localhost:8000"
echo ""
echo "📋 Useful commands:"
echo "   - View logs:        docker-compose logs -f app"
echo "   - Stop containers:  docker-compose stop"
echo "   - Start containers: docker-compose start"
echo "   - Restart:          docker-compose restart"
echo "   - Remove:           docker-compose down"
echo "   - Shell access:     docker-compose exec app bash"
echo ""
echo "🎉 Happy coding!"
