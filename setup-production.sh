#!/bin/bash

echo "=========================================="
echo "Production Setup Script"
echo "=========================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Step 1: Check if .env exists
if [ ! -f ".env" ]; then
    echo -e "${YELLOW}⚠️  .env file not found. Copying from .env.production...${NC}"
    cp .env.production .env
    echo -e "${GREEN}✅ .env file created${NC}"
else
    echo -e "${GREEN}✅ .env file exists${NC}"
fi

# Step 2: Generate APP_KEY if needed
if ! grep -q "APP_KEY=base64:" .env; then
    echo -e "${YELLOW}🔑 Generating APP_KEY...${NC}"
    php artisan key:generate --force
    echo -e "${GREEN}✅ APP_KEY generated${NC}"
else
    echo -e "${GREEN}✅ APP_KEY already exists${NC}"
fi

# Step 3: Create necessary directories
echo -e "${YELLOW}📁 Creating necessary directories...${NC}"
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/logs
mkdir -p database
mkdir -p bootstrap/cache
echo -e "${GREEN}✅ Directories created${NC}"

# Step 4: Set permissions
echo -e "${YELLOW}🔒 Setting permissions...${NC}"
chmod -R 775 storage bootstrap/cache database
echo -e "${GREEN}✅ Permissions set${NC}"

# Step 5: Create SQLite database
if [ ! -f "database/database.sqlite" ]; then
    echo -e "${YELLOW}🗄️  Creating SQLite database...${NC}"
    touch database/database.sqlite
    chmod 664 database/database.sqlite
    echo -e "${GREEN}✅ Database file created${NC}"
else
    echo -e "${GREEN}✅ Database file exists${NC}"
fi

# Step 6: Install dependencies
echo -e "${YELLOW}📦 Installing Composer dependencies...${NC}"
composer install --no-interaction --optimize-autoloader --no-dev
echo -e "${GREEN}✅ Composer dependencies installed${NC}"

echo -e "${YELLOW}📦 Installing NPM dependencies...${NC}"
npm ci --production || npm install --production
echo -e "${GREEN}✅ NPM dependencies installed${NC}"

# Step 7: Build assets
echo -e "${YELLOW}🎨 Building frontend assets...${NC}"
npm run build
echo -e "${GREEN}✅ Assets built${NC}"

# Step 8: Run migrations
echo -e "${YELLOW}🔄 Running migrations...${NC}"
php artisan migrate --force
echo -e "${GREEN}✅ Migrations completed${NC}"

# Step 9: Seed database (optional)
read -p "Do you want to seed the database? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo -e "${YELLOW}🌱 Seeding database...${NC}"
    php artisan db:seed --force
    echo -e "${GREEN}✅ Database seeded${NC}"
fi

# Step 10: Create storage link
echo -e "${YELLOW}🔗 Creating storage link...${NC}"
php artisan storage:link --force
echo -e "${GREEN}✅ Storage link created${NC}"

# Step 11: Clear and cache
echo -e "${YELLOW}🧹 Clearing cache...${NC}"
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
echo -e "${GREEN}✅ Cache cleared${NC}"

echo -e "${YELLOW}💾 Caching configuration...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo -e "${GREEN}✅ Configuration cached${NC}"

echo ""
echo -e "${GREEN}=========================================="
echo "✅ Production setup complete!"
echo "==========================================${NC}"
echo ""
echo "You can now run: docker-compose up -d"
echo ""
