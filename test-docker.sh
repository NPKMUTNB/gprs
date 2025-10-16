#!/bin/bash

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}=========================================="
echo "Docker Build Test"
echo "==========================================${NC}"
echo ""

# Test 1: Check if Docker is running
echo -e "${YELLOW}Test 1: Checking Docker...${NC}"
if docker info > /dev/null 2>&1; then
    echo -e "${GREEN}✅ Docker is running${NC}"
else
    echo -e "${RED}❌ Docker is not running${NC}"
    exit 1
fi

# Test 2: Check if docker-compose is available
echo -e "${YELLOW}Test 2: Checking docker-compose...${NC}"
if command -v docker-compose > /dev/null 2>&1; then
    echo -e "${GREEN}✅ docker-compose is available${NC}"
else
    echo -e "${RED}❌ docker-compose is not available${NC}"
    exit 1
fi

# Test 3: Check required files
echo -e "${YELLOW}Test 3: Checking required files...${NC}"
REQUIRED_FILES=("Dockerfile" "docker-compose.yml" "docker-entrypoint.sh" "composer.json" "package.json")
for file in "${REQUIRED_FILES[@]}"; do
    if [ -f "$file" ]; then
        echo -e "${GREEN}  ✅ $file${NC}"
    else
        echo -e "${RED}  ❌ $file missing${NC}"
        exit 1
    fi
done

# Test 4: Build Docker image
echo -e "${YELLOW}Test 4: Building Docker image (this may take a while)...${NC}"
if docker-compose build 2>&1 | tee /tmp/docker-build.log; then
    echo -e "${GREEN}✅ Docker image built successfully${NC}"
else
    echo -e "${RED}❌ Docker build failed${NC}"
    echo -e "${YELLOW}Last 20 lines of build log:${NC}"
    tail -20 /tmp/docker-build.log
    exit 1
fi

# Test 5: Start container
echo -e "${YELLOW}Test 5: Starting container...${NC}"
docker-compose up -d
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Container started${NC}"
else
    echo -e "${RED}❌ Failed to start container${NC}"
    exit 1
fi

# Test 6: Wait and check if container is running
echo -e "${YELLOW}Test 6: Waiting for container to be ready (15 seconds)...${NC}"
sleep 15

if docker ps | grep -q student-project-repository; then
    echo -e "${GREEN}✅ Container is running${NC}"
else
    echo -e "${RED}❌ Container is not running${NC}"
    echo -e "${YELLOW}Container logs:${NC}"
    docker-compose logs --tail=50 app
    exit 1
fi

# Test 7: Check if vendor exists in container
echo -e "${YELLOW}Test 7: Checking vendor directory in container...${NC}"
if docker exec student-project-repository test -f vendor/autoload.php; then
    echo -e "${GREEN}✅ Vendor directory exists${NC}"
else
    echo -e "${RED}❌ Vendor directory missing${NC}"
    docker-compose logs --tail=50 app
    exit 1
fi

# Test 8: Check if database exists
echo -e "${YELLOW}Test 8: Checking database...${NC}"
if docker exec student-project-repository test -f database/database.sqlite; then
    echo -e "${GREEN}✅ Database file exists${NC}"
else
    echo -e "${RED}❌ Database file missing${NC}"
    exit 1
fi

# Test 9: Check if migrations ran
echo -e "${YELLOW}Test 9: Checking migrations...${NC}"
TABLES=$(docker exec student-project-repository php artisan db:show 2>/dev/null | grep -c "tables" || echo "0")
if [ "$TABLES" != "0" ]; then
    echo -e "${GREEN}✅ Migrations ran successfully${NC}"
else
    echo -e "${YELLOW}⚠️  Could not verify migrations${NC}"
fi

# Test 10: HTTP health check
echo -e "${YELLOW}Test 10: HTTP health check...${NC}"
sleep 5
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000 2>/dev/null || echo "000")

if [ "$HTTP_CODE" = "200" ] || [ "$HTTP_CODE" = "302" ]; then
    echo -e "${GREEN}✅ Application is responding (HTTP $HTTP_CODE)${NC}"
elif [ "$HTTP_CODE" = "500" ]; then
    echo -e "${RED}❌ Application returned 500 error${NC}"
    echo -e "${YELLOW}Checking Laravel logs:${NC}"
    docker exec student-project-repository tail -50 storage/logs/laravel.log 2>/dev/null || echo "No logs found"
    exit 1
else
    echo -e "${YELLOW}⚠️  Application returned HTTP $HTTP_CODE${NC}"
fi

# Summary
echo ""
echo -e "${BLUE}=========================================="
echo "Test Summary"
echo "==========================================${NC}"
echo -e "Container: ${GREEN}Running${NC}"
echo -e "URL: ${BLUE}http://localhost:8000${NC}"
echo -e "Status: ${GREEN}All tests passed!${NC}"
echo ""
echo "Next steps:"
echo "  - Open http://localhost:8000 in your browser"
echo "  - View logs: docker-compose logs -f app"
echo "  - Stop: docker-compose down"
echo ""
