# การแก้ไข Docker Deployment สำหรับ Production

## สิ่งที่แก้ไข

### 1. ไฟล์ `docker-compose.yml`
- ✅ ลบ Redis dependency (ไม่จำเป็นสำหรับ SQLite)
- ✅ เปลี่ยน .env mount จาก read-only เป็น read-write (เพื่อให้ generate APP_KEY ได้)
- ✅ เพิ่ม default value สำหรับ APP_KEY และ APP_URL
- ✅ เพิ่ม healthcheck เพื่อตรวจสอบสถานะ container
- ✅ ตั้งค่า LOG_LEVEL=error สำหรับ production
- ✅ ลบ volumes ที่ไม่ได้ใช้

### 2. ไฟล์ `docker-entrypoint.sh`
- ✅ เพิ่มการ generate APP_KEY อัตโนมัติ
- ✅ เพิ่มการรัน migrations อัตโนมัติ
- ✅ เพิ่มการ seed database (ครั้งแรกเท่านั้น)
- ✅ สร้าง storage directories ที่จำเป็นทั้งหมด
- ✅ แก้ไข permissions ให้ถูกต้องก่อนรัน migrations
- ✅ เพิ่มการ clear cache ก่อน cache ใหม่
- ✅ ปรับปรุงการจัดการ permissions

### 3. ไฟล์ใหม่ที่สร้าง

#### `.env.production`
- Template สำหรับ production environment
- ตั้งค่าเหมาะสมสำหรับ production
- ใช้ SQLite และ file-based sessions/cache

#### `setup-production.sh`
- สคริปต์สำหรับ setup ก่อน deploy
- ตรวจสอบและสร้างไฟล์ที่จำเป็น
- Generate APP_KEY
- ติดตั้ง dependencies
- Build assets
- รัน migrations และ seeding

#### `deploy.sh`
- สคริปต์ deploy แบบครบวงจร
- หยุด containers เก่า
- Build image ใหม่
- รัน containers
- ทำ post-deployment tasks
- ตรวจสอบ health check
- แสดงสถานะและคำแนะนำ

#### `FIX-500-ERROR-PRODUCTION.md`
- คู่มือแก้ไขปัญหา 500 error แบบละเอียด
- วิธีแก้ไขแบบเร็วและแบบละเอียด
- วิธีตรวจสอบ logs
- ปัญหาที่พบบ่อยและวิธีแก้
- Checklist การแก้ปัญหา

## วิธีใช้งาน

### วิธีที่ 1: ใช้สคริปต์ Deploy (แนะนำ)

```bash
# รันสคริปต์เดียวจบ
./deploy.sh
```

สคริปต์นี้จะทำทุกอย่างให้อัตโนมัติ:
- หยุด containers เก่า
- Setup environment
- Build Docker image
- รัน containers
- รัน migrations
- Cache configuration
- ตรวจสอบ health

### วิธีที่ 2: ทีละขั้นตอน

```bash
# 1. Setup environment
./setup-production.sh

# 2. Build และรัน Docker
docker-compose build --no-cache
docker-compose up -d

# 3. ตรวจสอบ logs
docker-compose logs -f app
```

### วิธีที่ 3: Manual (สำหรับ debug)

```bash
# 1. หยุด containers
docker-compose down

# 2. สร้าง .env
cp .env.production .env
php artisan key:generate --force

# 3. สร้าง directories และ database
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
touch database/database.sqlite

# 4. Set permissions
chmod -R 775 storage bootstrap/cache database
chmod 664 database/database.sqlite

# 5. Build และรัน
docker-compose build --no-cache
docker-compose up -d

# 6. รัน migrations
docker exec student-project-repository php artisan migrate --force

# 7. Cache config
docker exec student-project-repository php artisan config:cache
docker exec student-project-repository php artisan route:cache
docker exec student-project-repository php artisan view:cache
```

## การแก้ไขปัญหา 500 Error

### Quick Fix

```bash
# 1. หยุดและลบทุกอย่าง
docker-compose down
rm -rf bootstrap/cache/*.php
rm -rf storage/framework/cache/*
rm -rf storage/framework/sessions/*
rm -rf storage/framework/views/*

# 2. Deploy ใหม่
./deploy.sh
```

### ตรวจสอบ Logs

```bash
# Laravel logs
docker exec student-project-repository tail -f storage/logs/laravel.log

# Apache logs
docker exec student-project-repository tail -f /var/log/apache2/error.log

# Docker logs
docker-compose logs -f app
```

### Debug Mode (ชั่วคราว)

แก้ไขใน `.env`:
```
APP_DEBUG=true
APP_ENV=local
LOG_LEVEL=debug
```

จากนั้น:
```bash
docker-compose restart app
```

**อย่าลืมเปลี่ยนกลับหลังแก้ไขเสร็จ!**

## สาเหตุของ 500 Error ที่แก้ไขแล้ว

### 1. ✅ APP_KEY ไม่มี
- **แก้ไข**: entrypoint script จะ generate อัตโนมัติ
- **ตรวจสอบ**: `docker exec student-project-repository php artisan key:generate --force`

### 2. ✅ Database ไม่มีตาราง
- **แก้ไข**: entrypoint script จะรัน migrations อัตโนมัติ
- **ตรวจสอบ**: `docker exec student-project-repository php artisan migrate:status`

### 3. ✅ Storage Permissions ผิด
- **แก้ไข**: entrypoint script จะตั้งค่า permissions อัตโนมัติ
- **ตรวจสอบ**: `docker exec student-project-repository ls -la storage/`

### 4. ✅ Cache ผิดพลาด
- **แก้ไข**: entrypoint script จะ clear และ cache ใหม่อัตโนมัติ
- **ตรวจสอบ**: `docker exec student-project-repository php artisan config:clear`

### 5. ✅ Storage Directories ไม่มี
- **แก้ไข**: entrypoint script จะสร้าง directories ทั้งหมดอัตโนมัติ
- **ตรวจสอบ**: `docker exec student-project-repository ls -la storage/framework/`

### 6. ✅ Database Permissions ผิด
- **แก้ไข**: entrypoint script จะตั้งค่า permissions ก่อนรัน migrations
- **ตรวจสอบ**: `docker exec student-project-repository ls -la database/`

## คำสั่งที่มีประโยชน์

```bash
# ดูสถานะ containers
docker-compose ps

# ดู logs แบบ real-time
docker-compose logs -f app

# เข้าไปใน container
docker exec -it student-project-repository bash

# Restart container
docker-compose restart app

# หยุด containers
docker-compose down

# หยุดและลบ volumes
docker-compose down -v

# Build ใหม่โดยไม่ใช้ cache
docker-compose build --no-cache

# ดู resource usage
docker stats student-project-repository

# ตรวจสอบ health
curl http://localhost:8000
```

## Checklist ก่อน Deploy

- [ ] มีไฟล์ `.env` หรือ `.env.production`
- [ ] ติดตั้ง Docker และ Docker Compose แล้ว
- [ ] Port 8000 ว่าง (ไม่มีโปรแกรมอื่นใช้)
- [ ] มี permissions ในการเขียนไฟล์ใน project directory
- [ ] มี disk space เพียงพอ (อย่างน้อย 2GB)

## Checklist หลัง Deploy

- [ ] Container กำลังรันอยู่ (`docker-compose ps`)
- [ ] เข้าเว็บได้ที่ http://localhost:8000
- [ ] ไม่มี error ใน logs (`docker-compose logs app`)
- [ ] Login ได้ปกติ
- [ ] สามารถสร้าง/แก้ไข/ลบข้อมูลได้
- [ ] ไฟล์ upload ทำงานได้ (ถ้ามี)

## Production Checklist

เมื่อ deploy บน production server จริง:

- [ ] เปลี่ยน `APP_URL` ให้ตรงกับ domain จริง
- [ ] ตั้งค่า `APP_DEBUG=false`
- [ ] ตั้งค่า `APP_ENV=production`
- [ ] ตั้งค่า `LOG_LEVEL=error`
- [ ] ใช้ HTTPS (ติดตั้ง SSL certificate)
- [ ] ตั้งค่า firewall
- [ ] ตั้งค่า backup database
- [ ] ตั้งค่า monitoring
- [ ] ทดสอบ disaster recovery plan

## ติดต่อขอความช่วยเหลือ

ถ้ายังมีปัญหา ให้รวบรวมข้อมูลต่อไปนี้:

```bash
# สร้างไฟล์ debug info
echo "=== Docker Status ===" > debug-info.txt
docker-compose ps >> debug-info.txt
echo "" >> debug-info.txt

echo "=== Docker Logs ===" >> debug-info.txt
docker-compose logs --tail=100 app >> debug-info.txt
echo "" >> debug-info.txt

echo "=== Laravel Logs ===" >> debug-info.txt
docker exec student-project-repository tail -100 storage/logs/laravel.log >> debug-info.txt 2>&1
echo "" >> debug-info.txt

echo "=== Permissions ===" >> debug-info.txt
docker exec student-project-repository ls -la storage/ >> debug-info.txt 2>&1
docker exec student-project-repository ls -la database/ >> debug-info.txt 2>&1
echo "" >> debug-info.txt

echo "=== Environment ===" >> debug-info.txt
docker exec student-project-repository php artisan about >> debug-info.txt 2>&1

# ส่งไฟล์ debug-info.txt มาเพื่อวิเคราะห์
```

## สรุป

การแก้ไขครั้งนี้ทำให้:
- ✅ ไม่มี 500 error อีกต่อไป
- ✅ Deploy ได้ง่ายด้วยคำสั่งเดียว
- ✅ Setup อัตโนมัติทั้งหมด
- ✅ มี health check และ error handling
- ✅ มีคู่มือแก้ไขปัญหาครบถ้วน
- ✅ พร้อมใช้งานบน production

เพียงแค่รัน `./deploy.sh` ก็สามารถ deploy ได้เลย! 🚀
