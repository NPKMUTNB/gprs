# 🐳 Docker Setup - Fixed Version

## ปัญหาที่แก้ไข

### ปัญหาเดิม:
```
Fatal error: Failed opening required '/var/www/html/vendor/autoload.php'
```

**สาเหตุ**: Volume mount ทับ vendor directory ที่ build ไว้ใน Dockerfile

### การแก้ไข:

1. **Dockerfile**: เพิ่ม entrypoint script ที่รัน composer install ทุกครั้งที่ container start
2. **docker-compose.yml**: แยก production (ไม่ mount source code) และ development (mount source code)
3. **.dockerignore**: ไม่ copy vendor และ node_modules เข้า image

---

## 📁 ไฟล์ที่เกี่ยวข้อง

### 1. Dockerfile (แก้ไขแล้ว)
- เพิ่ม entrypoint script
- Install dependencies ตอน container start
- Build assets อัตโนมัติ
- Setup database อัตโนมัติ

### 2. docker-compose.yml (Production)
- Port 80
- ไม่ mount source code
- Mount เฉพาะ storage, database, .env

### 3. docker-compose.dev.yml (Development)
- Port 8000
- Mount source code
- Anonymous volumes สำหรับ vendor และ node_modules

---

## 🚀 การใช้งาน

### Development (Local)

```bash
# Build และ start
docker-compose -f docker-compose.dev.yml build
docker-compose -f docker-compose.dev.yml up -d

# ดู logs
docker-compose -f docker-compose.dev.yml logs -f app

# เข้าใช้งาน
http://localhost:8000
```

### Production (Server)

```bash
# Build และ start
docker-compose build
docker-compose up -d

# ดู logs
docker-compose logs -f app

# เข้าใช้งาน
http://139.59.244.163
```

---

## 🔧 Entrypoint Script

Container จะทำสิ่งเหล่านี้อัตโนมัติตอน start:

1. ✅ Install Composer dependencies
2. ✅ Install NPM dependencies
3. ✅ Build assets
4. ✅ Setup database file
5. ✅ Create storage link
6. ✅ Cache configurations
7. ✅ Start Apache

---

## 📋 คำสั่งที่ใช้บ่อย

### Development

```bash
# Start
docker-compose -f docker-compose.dev.yml up -d

# Stop
docker-compose -f docker-compose.dev.yml stop

# Restart
docker-compose -f docker-compose.dev.yml restart

# Rebuild
docker-compose -f docker-compose.dev.yml build --no-cache

# Shell access
docker-compose -f docker-compose.dev.yml exec app bash

# Run migrations
docker-compose -f docker-compose.dev.yml exec app php artisan migrate

# Clear cache
docker-compose -f docker-compose.dev.yml exec app php artisan optimize:clear
```

### Production

```bash
# Start
docker-compose up -d

# Stop
docker-compose stop

# Restart
docker-compose restart

# Rebuild
docker-compose build --no-cache

# Shell access
docker-compose exec app bash

# Run migrations
docker-compose exec app php artisan migrate --force

# Clear cache
docker-compose exec app php artisan optimize:clear
```

---

## 🐛 Troubleshooting

### ถ้ายังเจอ vendor error

```bash
# เข้า container
docker-compose exec app bash

# Install dependencies manually
composer install --no-interaction --optimize-autoloader

# Exit และ restart
exit
docker-compose restart app
```

### ถ้า assets ไม่ build

```bash
# เข้า container
docker-compose exec app bash

# Build manually
npm install
npm run build

# Exit และ restart
exit
docker-compose restart app
```

### ถ้า database error

```bash
# เข้า container
docker-compose exec app bash

# Create database
touch database/database.sqlite
chmod 664 database/database.sqlite
chown www-data:www-data database/database.sqlite

# Run migrations
php artisan migrate --force
```

### ถ้า permission error

```bash
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

---

## 📊 ความแตกต่าง Development vs Production

| Feature | Development | Production |
|---------|-------------|------------|
| **File** | docker-compose.dev.yml | docker-compose.yml |
| **Port** | 8000 | 80 |
| **Source Mount** | ✅ Yes | ❌ No |
| **Debug** | true | false |
| **Hot Reload** | ✅ Yes | ❌ No |
| **Optimize** | ❌ No | ✅ Yes |

---

## ✅ ข้อดีของการแก้ไข

1. **ไม่มี vendor error**: Dependencies ถูก install ทุกครั้งที่ start
2. **แยก dev/prod**: ใช้ไฟล์ต่างกันสำหรับ development และ production
3. **Auto setup**: ทุกอย่างถูก setup อัตโนมัติ
4. **Faster rebuild**: ไม่ต้อง copy vendor และ node_modules เข้า image

---

## 🎯 Next Steps

### สำหรับ Development:
```bash
docker-compose -f docker-compose.dev.yml up -d
```

### สำหรับ Production:
```bash
# ใช้ deploy script
sudo ./deploy-production.sh

# หรือ manual
docker-compose build
docker-compose up -d
```

---

## 📝 หมายเหตุ

- **Development**: Mount source code เพื่อให้แก้ไขได้ทันที
- **Production**: ไม่ mount source code เพื่อความเร็วและความปลอดภัย
- **Entrypoint**: รัน setup script ทุกครั้งที่ container start
- **Volumes**: ใช้ anonymous volumes สำหรับ vendor และ node_modules ใน dev mode

---

ตอนนี้ Docker setup ควรทำงานได้ถูกต้องแล้วครับ! 🎉
