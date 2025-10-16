#!/bin/bash

echo "🔍 Debugging 500 Error"
echo "======================"
echo ""

echo "1️⃣ Checking container status..."
docker-compose ps
echo ""

echo "2️⃣ Checking Laravel logs..."
docker-compose exec app tail -50 storage/logs/laravel.log 2>/dev/null || echo "No Laravel logs found"
echo ""

echo "3️⃣ Checking Apache error logs..."
docker-compose exec app tail -50 /var/log/apache2/error.log 2>/dev/null || echo "No Apache logs found"
echo ""

echo "4️⃣ Checking .env file..."
docker-compose exec app cat .env | head -20
echo ""

echo "5️⃣ Checking APP_KEY..."
docker-compose exec app grep APP_KEY .env
echo ""

echo "6️⃣ Checking database..."
docker-compose exec app ls -la database/
echo ""

echo "7️⃣ Checking vendor..."
docker-compose exec app ls -la vendor/ | head -10
echo ""

echo "8️⃣ Testing artisan..."
docker-compose exec app php artisan --version
echo ""

echo "9️⃣ Checking permissions..."
docker-compose exec app ls -la storage/
echo ""

echo "🔟 Testing config..."
docker-compose exec app php artisan config:show app 2>&1 | head -20
echo ""

echo "📋 Common fixes:"
echo "   1. Clear cache:     docker-compose exec app php artisan optimize:clear"
echo "   2. Fix permissions: docker-compose exec app chmod -R 775 storage bootstrap/cache"
echo "   3. Check .env:      docker-compose exec app cat .env"
echo "   4. Rebuild:         docker-compose down && docker-compose build --no-cache && docker-compose up -d"
