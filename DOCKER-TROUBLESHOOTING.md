# 🔧 Docker Troubleshooting Guide

## ปัญหา: Container Restart Loop

### อาการ:
```
Error response from daemon: Container XXX is restarting, wait until the container is running
```

### สาเหตุ:
- Entrypoint script มี error
- Dependencies ติดตั้งไม่สำเร็จ
- Permission issues
- Memory ไม่พอ

---

## 🛠️ วิธีแก้ไข

### 1. ตรวจสอบ Logs

```bash
# ดู logs ล่าสุด
docker-compose logs --tail=100 app

# ดู logs แบบ real-time
docker-compose logs -f app

# ดู logs ทั้งหมด
docker-compose logs app > app.log
```

### 2. Stop และ Remove Containers

```bash
# Stop ทุกอย่าง
docker-compose down

# Remove volumes ด้วย (ระวัง: จะลบ database!)
docker-compose down -v

# Remove images ด้วย
docker-compose down --rmi all
```

### 3. Rebuild จากศูนย์

```bash
# Clean build
docker-compose build --no-cache

# Start ใหม่
docker-compose up -d

# ดู logs
docker-compose logs -f app
```

### 4. ตรวจสอบ Entrypoint Script

```bash
# ดูว่า script มีอยู่หรือไม่
cat docker-entrypoint.sh

# ตรวจสอบ permissions
ls -la docker-entrypoint.sh

# ควรเป็น executable
chmod +x docker-entrypoint.sh
```

### 5. Test Container แบบ Manual

```bash
# Start container โดยไม่ใช้ entrypoint
docker-compose run --rm --entrypoint bash app

# ใน container, ทดสอบ commands
composer install
npm install
npm run build
php artisan --version
```

---

## 🐛 ปัญหาที่พบบ่อย

### 1. Composer Install Failed

**อาการ**: `composer install` error

**แก้ไข**:
```bash
# เข้า container
docker-compose exec app bash

# ลบ vendor เดิม
rm -rf vendor

# Install ใหม่
composer install --no-interaction

# ถ้ายังไม่ได้, ลอง clear cache
composer clear-cache
composer install
```

### 2. NPM Install Failed

**อาการ**: `npm install` error

**แก้ไข**:
```bash
# เข้า container
docker-compose exec app bash

# ลบ node_modules เดิม
rm -rf node_modules package-lock.json

# Install ใหม่
npm install

# Build assets
npm run build
```

### 3. Permission Denied

**อาการ**: Permission errors ใน storage หรือ bootstrap/cache

**แก้ไข**:
```bash
# Fix permissions
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
docker-compose exec app chmod -R 775 storage bootstrap/cache

# ถ้ายังไม่ได้, ลอง
docker-compose exec app chmod -R 777 storage bootstrap/cache
```

### 4. Database File Error

**อาการ**: SQLite database error

**แก้ไข**:
```bash
# เข้า container
docker-compose exec app bash

# สร้าง database ใหม่
rm -f database/database.sqlite
touch database/database.sqlite
chmod 664 database/database.sqlite
chown www-data:www-data database/database.sqlite

# Run migrations
php artisan migrate --force
```

### 5. Memory Issues

**อาการ**: Container ใช้ memory เยอะหรือ crash

**แก้ไข**:
```bash
# เพิ่ม memory limit ใน docker-compose.yml
services:
  app:
    mem_limit: 2g
    memswap_limit: 2g
```

### 6. Port Already in Use

**อาการ**: Port 80 ถูกใช้แล้ว

**แก้ไข**:
```bash
# ดูว่าอะไรใช้ port 80
sudo lsof -i :80

# Stop Apache/Nginx
sudo systemctl stop apache2
sudo systemctl stop nginx

# หรือเปลี่ยน port ใน docker-compose.yml
ports:
  - "8080:80"
```

---

## 🔍 Debug Mode

### เปิด Debug Mode

แก้ไข `.env`:
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

Restart container:
```bash
docker-compose restart app
```

ดู logs:
```bash
docker-compose logs -f app
```

---

## 📊 ตรวจสอบ Container Health

### Check Container Status

```bash
# ดู status
docker-compose ps

# ดู resource usage
docker stats

# ดู container details
docker inspect student-project-repository
```

### Check Inside Container

```bash
# เข้า container
docker-compose exec app bash

# ตรวจสอบ PHP
php -v
php -m

# ตรวจสอบ Composer
composer --version

# ตรวจสอบ Node
node -v
npm -v

# ตรวจสอบ Laravel
php artisan --version
php artisan list

# ตรวจสอบ database
ls -la database/
sqlite3 database/database.sqlite ".tables"

# ตรวจสอบ permissions
ls -la storage/
ls -la bootstrap/cache/
```

---

## 🚀 Quick Fix Script

สร้างไฟล์ `fix-docker.sh`:

```bash
#!/bin/bash

echo "🔧 Docker Quick Fix"
echo "==================="

# Stop everything
echo "Stopping containers..."
docker-compose down

# Clean up
echo "Cleaning up..."
docker system prune -f

# Rebuild
echo "Rebuilding..."
docker-compose build --no-cache

# Start
echo "Starting..."
docker-compose up -d

# Wait
echo "Waiting for container..."
sleep 30

# Check
echo "Checking status..."
docker-compose ps
docker-compose logs --tail=50 app

echo ""
echo "Done! Check logs above for any errors."
```

ใช้งาน:
```bash
chmod +x fix-docker.sh
./fix-docker.sh
```

---

## 📝 Checklist ก่อน Deploy

- [ ] `.env` file มีอยู่และถูกต้อง
- [ ] `APP_KEY` ถูก generate แล้ว
- [ ] `docker-entrypoint.sh` มี execute permission
- [ ] Port 80 ว่าง (หรือใช้ port อื่น)
- [ ] มี disk space เพียงพอ (อย่างน้อย 5GB)
- [ ] มี RAM เพียงพอ (อย่างน้อย 2GB)
- [ ] Docker และ Docker Compose ติดตั้งแล้ว

---

## 🆘 ถ้าทุกอย่างไม่ได้ผล

### Plan B: Run Without Docker

```bash
# Install dependencies locally
composer install
npm install
npm run build

# Setup database
touch database/database.sqlite
php artisan migrate --force

# Run with PHP built-in server
php artisan serve --host=0.0.0.0 --port=8000
```

### Plan C: Use Simpler Dockerfile

สร้าง `Dockerfile.simple`:

```dockerfile
FROM php:8.2-apache

WORKDIR /var/www/html

# Install basic dependencies
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

# Copy application
COPY . /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Configure Apache
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN a2enmod rewrite

EXPOSE 80
CMD ["apache2-foreground"]
```

Build และ run:
```bash
docker build -f Dockerfile.simple -t sprs-simple .
docker run -d -p 80:80 --name sprs sprs-simple
```

---

## 📞 Get Help

ถ้ายังแก้ไม่ได้:

1. Export logs: `docker-compose logs app > error.log`
2. Check container: `docker-compose ps`
3. Check resources: `docker stats`
4. Review error.log

---

## ✅ Success Indicators

Container ทำงานถูกต้องเมื่อ:

```bash
# Status เป็น Up
docker-compose ps
# NAME                          STATUS
# student-project-repository    Up X minutes

# Logs ไม่มี error
docker-compose logs --tail=20 app
# ✅ Setup complete! Starting Apache...

# เข้าเว็บได้
curl http://localhost
# หรือ
curl http://139.59.244.163
```

---

Happy debugging! 🐛🔧
