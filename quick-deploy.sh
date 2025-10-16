#!/bin/bash

# Quick Deploy Script for Production
# Server: 139.59.244.163

echo "🚀 Quick Deploy - Student Project Repository"
echo "=============================================="
echo ""

# Check if running as root
if [ "$EUID" -ne 0 ]; then 
    echo "⚠️  Please run with sudo: sudo ./quick-deploy.sh"
    exit 1
fi

# Step 1: Create .env file
echo "📝 Step 1: Creating .env file..."
if [ ! -f .env ]; then
    cat > .env << 'EOF'
APP_NAME="Student Project Repository"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_TIMEZONE=Asia/Bangkok
APP_URL=http://localhost

APP_LOCALE=en
APP_FALLBACK_LOCALE=en

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite

SESSION_DRIVER=file
SESSION_LIFETIME=120

CACHE_DRIVER=redis
REDIS_HOST=redis
REDIS_PORT=6379

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
EOF
    echo "✅ .env file created"
else
    echo "ℹ️  .env file already exists"
fi

# Step 2: Generate APP_KEY
echo ""
echo "🔑 Step 2: Generating APP_KEY..."
if ! grep -q "APP_KEY=base64:" .env 2>/dev/null; then
    APP_KEY=$(openssl rand -base64 32)
    sed -i "s|APP_KEY=|APP_KEY=base64:${APP_KEY}|" .env
    echo "✅ APP_KEY generated"
else
    echo "ℹ️  APP_KEY already set"
fi

# Step 3: Create directories
echo ""
echo "📁 Step 3: Creating directories..."
mkdir -p storage/app/public
mkdir -p storage/framework/{cache,sessions,views}
mkdir -p storage/logs
mkdir -p bootstrap/cache
mkdir -p database
chmod -R 775 storage bootstrap/cache
echo "✅ Directories created"

# Step 4: Stop old containers
echo ""
echo "🛑 Step 4: Stopping old containers..."
docker-compose down 2>/dev/null || true
echo "✅ Old containers stopped"

# Step 5: Build
echo ""
echo "🏗️  Step 5: Building Docker image..."
docker-compose build --no-cache
echo "✅ Build complete"

# Step 6: Start
echo ""
echo "🚀 Step 6: Starting containers..."
docker-compose up -d
echo "✅ Containers started"

# Step 7: Wait for container
echo ""
echo "⏳ Step 7: Waiting for container to be ready..."
echo "This may take 2-3 minutes on first run..."
sleep 10

MAX_WAIT=180
WAITED=0
while [ $WAITED -lt $MAX_WAIT ]; do
    if docker-compose exec -T app php artisan --version > /dev/null 2>&1; then
        echo "✅ Container is ready!"
        break
    fi
    
    if [ $((WAITED % 10)) -eq 0 ]; then
        echo "Still waiting... ($WAITED/$MAX_WAIT seconds)"
    fi
    
    sleep 5
    WAITED=$((WAITED + 5))
done

if [ $WAITED -ge $MAX_WAIT ]; then
    echo "❌ Container took too long to start"
    echo ""
    echo "Checking logs..."
    docker-compose logs --tail=50 app
    echo ""
    echo "Try running: docker-compose logs -f app"
    exit 1
fi

# Step 8: Run migrations
echo ""
echo "🗄️  Step 8: Running database migrations..."
docker-compose exec -T app php artisan migrate --force
echo "✅ Migrations complete"

# Step 9: Seed database (optional)
echo ""
read -p "Do you want to seed the database with sample data? (y/n) " -n 1 -r
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

# Step 10: Check status
echo ""
echo "📊 Step 10: Checking status..."
docker-compose ps

echo ""
echo "=========================================="
echo "✅ Deployment Complete!"
echo "=========================================="
echo ""
echo "🌐 Your application is running at:"
echo "   http://139.59.244.163"
echo ""
echo "📋 Useful commands:"
echo "   View logs:    docker-compose logs -f app"
echo "   Stop:         docker-compose stop"
echo "   Start:        docker-compose start"
echo "   Restart:      docker-compose restart"
echo "   Shell:        docker-compose exec app bash"
echo ""
echo "🔒 Security reminders:"
echo "   1. Change default passwords"
echo "   2. Set up firewall (ufw)"
echo "   3. Configure SSL/TLS"
echo "   4. Set up backups"
echo ""
echo "🎉 Happy deploying!"
