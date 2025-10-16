# 🚀 Quick Start Guide

## สำหรับ Production Server (139.59.244.163)

### วิธีที่ 1: ใช้ Quick Deploy Script (แนะนำ)

```bash
# 1. SSH เข้า server
ssh root@139.59.244.163

# 2. Clone repository
cd /var/www
git clone <your-repo-url> student-project-repository
cd student-project-repository

# 3. Run quick deploy
chmod +x quick-deploy.sh
sudo ./quick-deploy.sh
```

**เสร็จแล้ว!** เข้าใช้งานที่: http://139.59.244.163

---

### วิธีที่ 2: Manual Setup

```bash
# 1. สร้าง .env file
cat > .env << 'EOF'
APP_NAME="Student Project Repository"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://139.59.244.163
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite
SESSION_DRIVER=file
CACHE_DRIVER=redis
REDIS_HOST=redis
EOF

# 2. Generate APP_KEY
APP_KEY=$(openssl rand -base64 32)
sed -i "s|APP_KEY=|APP_KEY=base64:${APP_KEY}|" .env

# 3. Build และ Start
docker-compose build
docker-compose up -d

# 4. รอ container พร้อม (2-3 นาที)
sleep 120

# 5. Run migrations
docker-compose exec -T app php artisan migrate --force

# 6. (Optional) Seed database
docker-compose exec -T app php artisan db:seed --force
```

---

## ตรวจสอบว่าทำงาน

```bash
# Check container status
docker-compose ps

# Check logs
docker-compose logs --tail=50 app

# Test artisan
docker-compose exec app php artisan --version

# Test web
curl http://139.59.244.163
```

---

## Default Credentials (ถ้า seed database)

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@example.com | password |
| Advisor | advisor@example.com | password |
| Committee | committee@example.com | password |
| Student | student@example.com | password |

**⚠️ เปลี่ยนรหัสผ่านทันที!**

---

## คำสั่งที่ใช้บ่อย

```bash
# ดู logs
docker-compose logs -f app

# Restart
docker-compose restart

# Stop
docker-compose stop

# Start
docker-compose start

# Shell access
docker-compose exec app bash

# Run artisan commands
docker-compose exec app php artisan [command]
```

---

## แก้ปัญหา

### Container ไม่ start

```bash
# ดู logs
docker-compose logs app

# Rebuild
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

### Permission errors

```bash
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

### Database errors

```bash
docker-compose exec app touch database/database.sqlite
docker-compose exec app chmod 664 database/database.sqlite
docker-compose exec app php artisan migrate:fresh --force
```

---

## ไฟล์ที่ต้องมี

- ✅ `Dockerfile`
- ✅ `docker-compose.yml`
- ✅ `docker-entrypoint.sh`
- ✅ `.dockerignore`
- ✅ `.env` (จะถูกสร้างโดย script)

---

## Next Steps

1. เปลี่ยนรหัสผ่าน default
2. ตั้งค่า firewall
3. ตั้งค่า SSL/TLS (ถ้ามี domain)
4. ตั้งค่า backup อัตโนมัติ

---

## Support

ดู troubleshooting guide: `DOCKER-TROUBLESHOOTING.md`

---

**Happy deploying! 🎉**
