# 🔧 Fix 500 Internal Server Error

## วิธีแก้ไข 500 Error

### Step 1: Run Debug Script

```bash
chmod +x debug-500.sh
./debug-500.sh
```

Script นี้จะตรวจสอบ:
- Container status
- Laravel logs
- Apache logs
- .env configuration
- APP_KEY
- Database
- Vendor directory
- Permissions

---

## สาเหตุที่พบบ่อยและวิธีแก้

### 1. APP_KEY ไม่ได้ตั้งค่า

**อาการ**: "No application encryption key has been specified"

**แก้ไข**:
```bash
# Generate key
docker-compose exec app php artisan key:generate --force

# Restart
docker-compose restart app
```

### 2. Permission Issues

**อาการ**: "Permission denied" ใน logs

**แก้ไข**:
```bash
# Fix storage permissions
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
docker-compose exec app chmod -R 775 storage bootstrap/cache

# Restart
docker-compose restart app
```

### 3. Database Not Found

**อาการ**: "Database file not found"

**แก้ไข**:
```bash
# Create database
docker-compose exec app touch database/database.sqlite
docker-compose exec app chmod 664 database/database.sqlite
docker-compose exec app chown www-data:www-data database/database.sqlite

# Run migrations
docker-compose exec app php artisan migrate --force

# Restart
docker-compose restart app
```

### 4. Cached Config Issues

**อาการ**: Configuration errors

**แก้ไข**:
```bash
# Clear all caches
docker-compose exec app php artisan optimize:clear

# Re-cache
docker-compose exec app php artisan optimize

# Restart
docker-compose restart app
```

### 5. Vendor Directory Missing

**อาการ**: "Class not found" errors

**แก้ไข**:
```bash
# Install dependencies
docker-compose exec app composer install --no-interaction --optimize-autoloader

# Restart
docker-compose restart app
```

### 6. .env File Issues

**อาการ**: Environment variables not loaded

**แก้ไข**:
```bash
# Check .env exists
docker-compose exec app ls -la .env

# If not, create it
docker-compose exec app cp .env.example .env

# Generate key
docker-compose exec app php artisan key:generate --force

# Restart
docker-compose restart app
```

---

## 🔍 ขั้นตอนการ Debug

### 1. ดู Laravel Logs

```bash
docker-compose exec app tail -100 storage/logs/laravel.log
```

### 2. ดู Apache Logs

```bash
docker-compose exec app tail -100 /var/log/apache2/error.log
```

### 3. Test Artisan

```bash
docker-compose exec app php artisan --version
docker-compose exec app php artisan config:show app
```

### 4. Check Environment

```bash
docker-compose exec app php artisan env
docker-compose exec app php artisan about
```

### 5. Test Database Connection

```bash
docker-compose exec app php artisan tinker
# ใน tinker:
DB::connection()->getPdo();
User::count();
```

---

## 🚑 Quick Fixes

### Fix #1: Complete Reset

```bash
# Stop everything
docker-compose down

# Clear cache
docker-compose exec app php artisan optimize:clear 2>/dev/null || true

# Rebuild
docker-compose build --no-cache

# Start
docker-compose up -d

# Wait
sleep 60

# Clear cache again
docker-compose exec app php artisan optimize:clear

# Test
curl http://139.59.244.163
```

### Fix #2: Fix Permissions

```bash
docker-compose exec app bash -c "
chown -R www-data:www-data storage bootstrap/cache database &&
chmod -R 775 storage bootstrap/cache &&
chmod 664 database/database.sqlite
"

docker-compose restart app
```

### Fix #3: Regenerate Everything

```bash
docker-compose exec app bash -c "
php artisan key:generate --force &&
php artisan optimize:clear &&
php artisan storage:link &&
php artisan migrate --force &&
php artisan optimize
"

docker-compose restart app
```

---

## 📊 Checklist

เมื่อเจอ 500 error ให้ตรวจสอบ:

- [ ] Container กำลังรันอยู่: `docker-compose ps`
- [ ] .env file มีอยู่: `docker-compose exec app ls -la .env`
- [ ] APP_KEY ถูกตั้งค่า: `docker-compose exec app grep APP_KEY .env`
- [ ] Database file มีอยู่: `docker-compose exec app ls -la database/database.sqlite`
- [ ] Vendor directory มีอยู่: `docker-compose exec app ls vendor/`
- [ ] Storage permissions ถูกต้อง: `docker-compose exec app ls -la storage/`
- [ ] Migrations ถูกรัน: `docker-compose exec app php artisan migrate:status`

---

## 🎯 Most Common Solution

ส่วนใหญ่แก้ได้ด้วย:

```bash
# 1. Clear cache
docker-compose exec app php artisan optimize:clear

# 2. Fix permissions
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
docker-compose exec app chmod -R 775 storage bootstrap/cache

# 3. Restart
docker-compose restart app

# 4. Test
curl http://139.59.244.163
```

---

## 📞 Still Not Working?

### Get Detailed Error

```bash
# Enable debug mode temporarily
docker-compose exec app sed -i 's/APP_DEBUG=false/APP_DEBUG=true/' .env
docker-compose restart app

# Visit the site and see detailed error
# Then disable debug again
docker-compose exec app sed -i 's/APP_DEBUG=true/APP_DEBUG=false/' .env
docker-compose restart app
```

### Export All Logs

```bash
# Export logs for analysis
docker-compose logs app > full-logs.txt
docker-compose exec app cat storage/logs/laravel.log > laravel-logs.txt
docker-compose exec app cat /var/log/apache2/error.log > apache-logs.txt

# Review logs
cat full-logs.txt
cat laravel-logs.txt
cat apache-logs.txt
```

---

## ✅ Success Indicators

เมื่อแก้ไขสำเร็จ:

```bash
# Container status
docker-compose ps
# NAME                          STATUS
# student-project-repository    Up X minutes

# Web response
curl -I http://139.59.244.163
# HTTP/1.1 200 OK

# No errors in logs
docker-compose logs --tail=20 app
# No error messages
```

---

**Good luck! 🍀**
