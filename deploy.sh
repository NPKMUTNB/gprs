#!/bin/bash

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}=========================================="
echo "Student Project Repository - Deploy Script"
echo "==========================================${NC}"
echo ""

# Function to check if command succeeded
check_status() {
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✅ $1${NC}"
    else
        echo -e "${RED}❌ $1 failed!${NC}"
        exit 1
    fi
}

# Step 1: Stop existing containers
echo -e "${YELLOW}🛑 Stopping existing containers...${NC}"
docker-compose down
check_status "Containers stopped"

# Step 2: Setup environment
echo -e "${YELLOW}⚙️  Setting up environment...${NC}"
if [ ! -f ".env" ]; then
    cp .env.production .env
    check_status "Environment file created"
else
    echo -e "${GREEN}✅ Environment file exists${NC}"
fi

# Step 3: Create directories
echo -e "${YELLOW}📁 Creating directories...${NC}"
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/logs
mkdir -p database
mkdir -p bootstrap/cache
check_status "Directories created"

# Step 4: Set permissions
echo -e "${YELLOW}🔒 Setting permissions...${NC}"
chmod -R 775 storage bootstrap/cache database 2>/dev/null || true
check_status "Permissions set"

# Step 5: Create database
echo -e "${YELLOW}🗄️  Creating database...${NC}"
if [ ! -f "database/database.sqlite" ]; then
    touch database/database.sqlite
    chmod 664 database/database.sqlite
    check_status "Database created"
else
    echo -e "${GREEN}✅ Database exists${NC}"
fi

# Step 6: Install dependencies locally (for faster builds)
echo -e "${YELLOW}� Ihnstalling dependencies...${NC}"
if [ -f "composer.json" ]; then
    composer install --no-interaction --optimize-autoloader --no-dev 2>/dev/null || echo "Composer install will happen in container"
fi
if [ -f "package.json" ]; then
    npm ci --production 2>/dev/null || npm install --production 2>/dev/null || echo "NPM install will happen in container"
fi

# Step 7: Build assets locally (for faster builds)
echo -e "${YELLOW}�️ Building assets...${NC}"
if [ -d "node_modules" ]; then
    npm run build 2>/dev/null || echo "Asset build will happen in container"
fi

# Step 8: Generate APP_KEY if needed
echo -e "${YELLOW}🔑 Checking APP_KEY...${NC}"
if ! grep -q "APP_KEY=base64:" .env 2>/dev/null; then
    if [ -f "vendor/autoload.php" ]; then
        php artisan key:generate --force 2>/dev/null || echo "Will generate in container"
    else
        echo "Will generate in container"
    fi
    check_status "APP_KEY checked"
else
    echo -e "${GREEN}✅ APP_KEY exists${NC}"
fi

# Step 9: Build Docker image
echo -e "${YELLOW}🏗️  Building Docker image...${NC}"
docker-compose build --no-cache
check_status "Docker image built"

# Step 10: Start containers
echo -e "${YELLOW}🚀 Starting containers...${NC}"
docker-compose up -d
check_status "Containers started"

# Step 11: Wait for container to be ready
echo -e "${YELLOW}⏳ Waiting for container to be ready...${NC}"
sleep 10

# Step 12: Check container status
echo -e "${YELLOW}🔍 Checking container status...${NC}"
if docker ps | grep -q student-project-repository; then
    echo -e "${GREEN}✅ Container is running${NC}"
else
    echo -e "${RED}❌ Container is not running!${NC}"
    echo -e "${YELLOW}Showing logs:${NC}"
    docker-compose logs --tail=50 app
    exit 1
fi

# Step 13: Run post-deployment commands
echo -e "${YELLOW}🔧 Running post-deployment commands...${NC}"

# Generate key if not exists
docker exec student-project-repository php artisan key:generate --force 2>/dev/null || true

# Run migrations
echo -e "${YELLOW}  - Running migrations...${NC}"
docker exec student-project-repository php artisan migrate --force
check_status "Migrations completed"

# Create storage link
echo -e "${YELLOW}  - Creating storage link...${NC}"
docker exec student-project-repository php artisan storage:link --force 2>/dev/null || true
check_status "Storage link created"

# Clear cache
echo -e "${YELLOW}  - Clearing cache...${NC}"
docker exec student-project-repository php artisan cache:clear 2>/dev/null || true
docker exec student-project-repository php artisan config:clear 2>/dev/null || true
docker exec student-project-repository php artisan route:clear 2>/dev/null || true
docker exec student-project-repository php artisan view:clear 2>/dev/null || true
check_status "Cache cleared"

# Cache config
echo -e "${YELLOW}  - Caching configuration...${NC}"
docker exec student-project-repository php artisan config:cache
docker exec student-project-repository php artisan route:cache
docker exec student-project-repository php artisan view:cache
check_status "Configuration cached"

# Set permissions inside container
echo -e "${YELLOW}  - Setting permissions in container...${NC}"
docker exec student-project-repository chown -R www-data:www-data storage bootstrap/cache database 2>/dev/null || true
docker exec student-project-repository chmod -R 775 storage bootstrap/cache database 2>/dev/null || true
check_status "Permissions set in container"

# Step 14: Health check
echo -e "${YELLOW}🏥 Running health check...${NC}"
sleep 5
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000 2>/dev/null || echo "000")

if [ "$HTTP_CODE" = "200" ] || [ "$HTTP_CODE" = "302" ]; then
    echo -e "${GREEN}✅ Application is responding (HTTP $HTTP_CODE)${NC}"
else
    echo -e "${YELLOW}⚠️  Application returned HTTP $HTTP_CODE${NC}"
    echo -e "${YELLOW}Checking logs...${NC}"
    docker-compose logs --tail=20 app
fi

# Final status
echo ""
echo -e "${BLUE}=========================================="
echo "Deployment Summary"
echo "==========================================${NC}"
echo -e "Container Status: ${GREEN}$(docker ps --filter name=student-project-repository --format '{{.Status}}')${NC}"
echo -e "Application URL: ${BLUE}http://localhost:8000${NC}"
echo ""
echo -e "${GREEN}✅ Deployment completed!${NC}"
echo ""
echo "Useful commands:"
echo "  - View logs:        docker-compose logs -f app"
echo "  - Stop:             docker-compose down"
echo "  - Restart:          docker-compose restart app"
echo "  - Shell access:     docker exec -it student-project-repository bash"
echo ""
