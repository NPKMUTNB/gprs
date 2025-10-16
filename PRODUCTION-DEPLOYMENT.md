# 🚀 Production Deployment Guide

## Server Information
- **IP Address**: 139.59.244.163
- **Port**: 80 (HTTP)
- **Environment**: Production

---

## 📋 Prerequisites

### Server Requirements
- Ubuntu 20.04 LTS or higher (recommended)
- Minimum 2GB RAM
- Minimum 20GB disk space
- Root or sudo access
- Port 80 available

### Software Requirements
- Docker (will be installed automatically)
- Docker Compose (will be installed automatically)
- Git

---

## 🚀 Deployment Steps

### Step 1: Connect to Server

```bash
ssh root@139.59.244.163
# or
ssh your-user@139.59.244.163
```

### Step 2: Clone Repository

```bash
cd /var/www
git clone <your-repository-url> student-project-repository
cd student-project-repository
```

### Step 3: Run Deployment Script

```bash
# Make script executable
chmod +x deploy-production.sh

# Run deployment (requires sudo for port 80)
sudo ./deploy-production.sh
```

The script will:
1. ✅ Install Docker and Docker Compose (if needed)
2. ✅ Create `.env` file from `.env.production`
3. ✅ Generate application key
4. ✅ Build Docker containers
5. ✅ Install dependencies
6. ✅ Build frontend assets
7. ✅ Set up database
8. ✅ Run migrations
9. ✅ Seed data (optional)
10. ✅ Optimize application
11. ✅ Set permissions

### Step 4: Verify Deployment

```bash
# Check if containers are running
docker-compose ps

# Check logs
docker-compose logs -f app

# Test the application
curl http://139.59.244.163
```

---

## 🌐 Access Application

**URL**: http://139.59.244.163

### Default Credentials (if seeded)

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@example.com | password |
| Advisor | advisor@example.com | password |
| Committee | committee@example.com | password |
| Student | student@example.com | password |

**⚠️ IMPORTANT: Change these passwords immediately!**

---

## 🔒 Security Configuration

### 1. Change Default Passwords

```bash
# Access container
docker-compose exec app bash

# Run tinker
php artisan tinker

# Change admin password
$admin = User::where('email', 'admin@example.com')->first();
$admin->password = Hash::make('your-new-secure-password');
$admin->save();
```

### 2. Set Up Firewall (UFW)

```bash
# Install UFW
sudo apt-get update
sudo apt-get install ufw

# Allow SSH (IMPORTANT: Do this first!)
sudo ufw allow 22/tcp

# Allow HTTP
sudo ufw allow 80/tcp

# Allow HTTPS (for future SSL setup)
sudo ufw allow 443/tcp

# Enable firewall
sudo ufw enable

# Check status
sudo ufw status
```

### 3. Set Up SSL/TLS (Recommended)

#### Option A: Using Let's Encrypt (Free)

```bash
# Install Certbot
sudo apt-get update
sudo apt-get install certbot

# Get certificate (requires domain name)
sudo certbot certonly --standalone -d yourdomain.com

# Update docker-compose.yml to use SSL
# Add volume mapping for certificates
```

#### Option B: Using Cloudflare (Recommended)

1. Point your domain to 139.59.244.163
2. Enable Cloudflare proxy
3. Set SSL/TLS mode to "Full" or "Full (strict)"
4. Cloudflare will handle SSL automatically

### 4. Disable Root Login

```bash
# Edit SSH config
sudo nano /etc/ssh/sshd_config

# Change: PermitRootLogin no
# Save and restart SSH
sudo systemctl restart sshd
```

---

## 📊 Monitoring

### View Logs

```bash
# Application logs
docker-compose logs -f app

# Laravel logs
docker-compose exec app tail -f storage/logs/laravel.log

# Apache logs
docker-compose exec app tail -f /var/log/apache2/error.log
```

### Check Container Status

```bash
docker-compose ps
```

### Check Resource Usage

```bash
docker stats
```

### Check Disk Space

```bash
df -h
```

---

## 🔄 Updates and Maintenance

### Update Application

```bash
# Navigate to project directory
cd /var/www/student-project-repository

# Pull latest changes
git pull origin main

# Rebuild and restart
sudo docker-compose down
sudo docker-compose build --no-cache
sudo docker-compose up -d

# Update dependencies
docker-compose exec app composer install --no-interaction --optimize-autoloader --no-dev
docker-compose exec app npm ci --production
docker-compose exec app npm run build

# Run migrations
docker-compose exec app php artisan migrate --force

# Clear and cache
docker-compose exec app php artisan optimize
```

### Backup Database

```bash
# Create backup directory
mkdir -p /var/backups/sprs

# Backup database
docker-compose exec app cp database/database.sqlite database/backup-$(date +%Y%m%d-%H%M%S).sqlite

# Copy to backup directory
docker-compose exec app cp database/backup-*.sqlite /var/backups/sprs/

# Backup uploaded files
docker-compose exec app tar -czf /var/backups/sprs/storage-$(date +%Y%m%d-%H%M%S).tar.gz storage/app/public
```

### Automated Backups (Cron)

```bash
# Edit crontab
crontab -e

# Add daily backup at 2 AM
0 2 * * * cd /var/www/student-project-repository && docker-compose exec -T app cp database/database.sqlite /var/backups/sprs/db-$(date +\%Y\%m\%d).sqlite

# Add weekly cleanup (keep last 30 days)
0 3 * * 0 find /var/backups/sprs -name "db-*.sqlite" -mtime +30 -delete
```

---

## 🛠️ Common Commands

### Container Management

```bash
# Start containers
docker-compose start

# Stop containers
docker-compose stop

# Restart containers
docker-compose restart

# View status
docker-compose ps

# Remove containers
docker-compose down
```

### Application Commands

```bash
# Access shell
docker-compose exec app bash

# Clear cache
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear

# Optimize
docker-compose exec app php artisan optimize

# Run migrations
docker-compose exec app php artisan migrate

# Create admin user
docker-compose exec app php artisan tinker
```

---

## 🐛 Troubleshooting

### Port 80 Already in Use

```bash
# Check what's using port 80
sudo lsof -i :80

# Stop Apache if installed
sudo systemctl stop apache2
sudo systemctl disable apache2

# Or stop Nginx
sudo systemctl stop nginx
sudo systemctl disable nginx
```

### Permission Issues

```bash
# Fix permissions
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

### Database Issues

```bash
# Reset database
docker-compose exec app rm database/database.sqlite
docker-compose exec app touch database/database.sqlite
docker-compose exec app chmod 664 database/database.sqlite
docker-compose exec app php artisan migrate:fresh --seed --force
```

### Container Won't Start

```bash
# Check logs
docker-compose logs app

# Rebuild
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

### Out of Disk Space

```bash
# Check disk usage
df -h

# Clean Docker
docker system prune -a

# Clean old logs
docker-compose exec app truncate -s 0 storage/logs/laravel.log
```

---

## 📈 Performance Optimization

### Enable Redis Caching

Already configured in `.env.production`:
```env
CACHE_STORE=redis
```

### Enable OPcache

Already enabled in Dockerfile.

### Optimize Composer Autoloader

```bash
docker-compose exec app composer dump-autoload --optimize
```

---

## 🔐 Security Checklist

- [ ] Change all default passwords
- [ ] Set up firewall (UFW)
- [ ] Configure SSL/TLS
- [ ] Disable root login
- [ ] Set up automated backups
- [ ] Monitor logs regularly
- [ ] Keep Docker and packages updated
- [ ] Use strong APP_KEY
- [ ] Set APP_DEBUG=false
- [ ] Restrict database file permissions

---

## 📞 Support

### Check Application Status

```bash
# Container status
docker-compose ps

# Application logs
docker-compose logs -f app

# Laravel logs
docker-compose exec app tail -f storage/logs/laravel.log
```

### Restart Application

```bash
docker-compose restart app
```

### Full System Restart

```bash
docker-compose down
docker-compose up -d
```

---

## 🎉 Success!

Your application should now be running at:

**http://139.59.244.163**

For HTTPS setup, consider:
1. Getting a domain name
2. Setting up Cloudflare
3. Or using Let's Encrypt with Nginx reverse proxy

---

## 📝 Notes

- Always test updates in a staging environment first
- Keep regular backups
- Monitor disk space and logs
- Update dependencies regularly
- Review security settings periodically

**Happy deploying! 🚀**
