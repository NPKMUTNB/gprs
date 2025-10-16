# แก้ไขปัญหา 500 Internal Server Error บน Production

## สาเหตุหลักของ 500 Error

1. **APP_KEY ไม่ได้ถูกสร้าง** - Laravel ต้องการ APP_KEY เพื่อ encrypt sessions และข้อมูล
2. **Database ไม่มีตาราง** - Migrations ยังไม่ได้รัน
3. **Storage permissions ไม่ถูกต้อง** - Laravel ไม่สามารถเขียนไฟล์ได้
4. **Cache ผิดพลาด** - Config cache หรือ route cache มีปัญหา

## วิธีแก้ไขแบบเร็ว (Quick Fix)

### ขั้นตอนที่ 1: หยุด Container ที่กำลังรัน

```bash
docker-compose down
```

### ขั้นตอนที่ 2: ลบ volumes และ cache เก่า

```bash
# ลบ cache และ compiled files
rm -rf bootstrap/cache/*.php
rm -rf storage/framework/cache/*
rm -rf storage/framework/sessions/*
rm -rf storage/framework/views/*

# ถ้าต้องการเริ่มใหม่ทั้งหมด (ระวัง: จะลบข้อมูลในฐานข้อมูล)
rm -f database/database.sqlite
rm -f database/.seeded
```

### ขั้นตอนที่ 3: รัน Setup Script

```bash
./setup-production.sh
```

Script นี้จะ:
- สร้างไฟล์ .env ถ้ายังไม่มี
- Generate APP_KEY
- สร้าง directories ที่จำเป็น
- ตั้งค่า permissions
- สร้างฐานข้อมูล SQLite
- ติดตั้ง dependencies
- Build assets
- รัน migrations
- Seed database (ถ้าต้องการ)
- Cache configuration

### ขั้นตอนที่ 4: Build และรัน Docker

```bash
# Build image ใหม่
docker-compose build --no-cache

# รัน container
docker-compose up -d

# ดู logs
docker-compose logs -f app
```

## วิธีแก้ไขแบบละเอียด (Manual Fix)

### 1. ตรวจสอบ .env file

```bash
# ตรวจสอบว่ามี APP_KEY หรือไม่
cat .env | grep APP_KEY

# ถ้าไม่มีหรือเป็น base64:CHANGEME ให้ generate ใหม่
php artisan key:generate --force
```

### 2. ตรวจสอบ Database

```bash
# เข้าไปใน container
docker exec -it student-project-repository bash

# ตรวจสอบว่าไฟล์ database มีอยู่
ls -la database/database.sqlite

# ตรวจสอบ permissions
ls -la database/

# ถ้าไม่มี ให้สร้าง
touch database/database.sqlite
chmod 664 database/database.sqlite
chown www-data:www-data database/database.sqlite

# รัน migrations
php artisan migrate --force

# ออกจาก container
exit
```

### 3. ตรวจสอบ Storage Permissions

```bash
# เข้าไปใน container
docker exec -it student-project-repository bash

# ตรวจสอบ permissions
ls -la storage/
ls -la bootstrap/cache/

# แก้ไข permissions
chown -R www-data:www-data storage bootstrap/cache database
chmod -R 775 storage bootstrap/cache database

# สร้าง directories ที่จำเป็น
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/logs
chmod -R 775 storage

# ออกจาก container
exit
```

### 4. Clear และ Cache ใหม่

```bash
# เข้าไปใน container
docker exec -it student-project-repository bash

# Clear cache ทั้งหมด
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cache ใหม่
php artisan config:cache
php artisan route:cache
php artisan view:cache

# สร้าง storage link
php artisan storage:link --force

# ออกจาก container
exit

# Restart container
docker-compose restart app
```

## ตรวจสอบ Logs

### ดู Laravel Logs

```bash
# ดู logs ใน container
docker exec -it student-project-repository tail -f storage/logs/laravel.log

# หรือดูจาก host
tail -f storage/logs/laravel.log
```

### ดู Apache Logs

```bash
# ดู Apache error logs
docker exec -it student-project-repository tail -f /var/log/apache2/error.log

# ดู Apache access logs
docker exec -it student-project-repository tail -f /var/log/apache2/access.log
```

### ดู Docker Logs

```bash
# ดู logs ของ container
docker-compose logs -f app

# ดู logs 100 บรรทัดล่าสุด
docker-compose logs --tail=100 app
```

## Debug Mode (ใช้เฉพาะตอน Debug)

**⚠️ อย่าเปิด Debug Mode บน Production จริง!**

ถ้าต้องการดู error message แบบละเอียด:

```bash
# แก้ไขใน .env
APP_DEBUG=true
APP_ENV=local
LOG_LEVEL=debug

# Restart container
docker-compose restart app
```

หลังจากแก้ไขเสร็จแล้ว อย่าลืมเปลี่ยนกลับ:

```bash
APP_DEBUG=false
APP_ENV=production
LOG_LEVEL=error
```

## ตรวจสอบว่าระบบทำงานปกติ

```bash
# ทดสอบเข้าเว็บ
curl http://localhost:8000

# ควรได้ HTML response กลับมา ไม่ใช่ error 500

# ทดสอบ health check
curl http://localhost:8000/login
```

## ปัญหาที่พบบ่อยและวิธีแก้

### ปัญหา: "No application encryption key has been specified"

```bash
php artisan key:generate --force
docker-compose restart app
```

### ปัญหา: "SQLSTATE[HY000]: General error: 8 attempt to write a readonly database"

```bash
docker exec -it student-project-repository bash
chmod 664 database/database.sqlite
chmod 775 database
chown -R www-data:www-data database
exit
docker-compose restart app
```

### ปัญหา: "The stream or file could not be opened"

```bash
docker exec -it student-project-repository bash
chmod -R 775 storage
chown -R www-data:www-data storage
mkdir -p storage/logs
exit
docker-compose restart app
```

### ปัญหา: "Class not found" หรือ "Interface not found"

```bash
docker exec -it student-project-repository bash
composer dump-autoload
php artisan clear-compiled
php artisan config:clear
exit
docker-compose restart app
```

## Checklist การแก้ปัญหา

- [ ] มีไฟล์ .env และมี APP_KEY ที่ถูกต้อง
- [ ] ไฟล์ database/database.sqlite มีอยู่และมี permissions ถูกต้อง
- [ ] Directory storage และ bootstrap/cache มี permissions 775
- [ ] Owner ของ storage, bootstrap/cache, database เป็น www-data
- [ ] Migrations ถูกรันแล้ว (มีตารางในฐานข้อมูล)
- [ ] Storage link ถูกสร้างแล้ว
- [ ] Config, route, view ถูก cache แล้ว
- [ ] ไม่มี error ใน storage/logs/laravel.log
- [ ] Docker container กำลังรันอยู่ (docker-compose ps)

## ติดต่อขอความช่วยเหลือ

ถ้ายังแก้ไขไม่ได้ ให้รวบรวมข้อมูลต่อไปนี้:

```bash
# 1. Docker logs
docker-compose logs --tail=100 app > docker-logs.txt

# 2. Laravel logs
tail -100 storage/logs/laravel.log > laravel-logs.txt

# 3. Apache error logs
docker exec -it student-project-repository tail -100 /var/log/apache2/error.log > apache-logs.txt

# 4. Permissions
docker exec -it student-project-repository ls -la storage/ > permissions.txt
docker exec -it student-project-repository ls -la database/ >> permissions.txt

# 5. Environment
docker exec -it student-project-repository php artisan about > environment.txt
```

แล้วส่งไฟล์เหล่านี้มาเพื่อวิเคราะห์ปัญหา
