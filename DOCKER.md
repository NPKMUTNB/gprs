# 🐳 Docker Deployment Guide

This guide will help you deploy the Student Project Repository System using Docker.

## 📋 Prerequisites

- Docker (version 20.10 or higher)
- Docker Compose (version 2.0 or higher)
- At least 2GB of free disk space

## 🚀 Quick Start

### Option 1: Automated Setup (Recommended)

Run the setup script:

```bash
chmod +x docker-setup.sh
./docker-setup.sh
```

This script will:
1. Check Docker installation
2. Create `.env` file
3. Generate application key
4. Build Docker containers
5. Install dependencies
6. Build frontend assets
7. Set up database
8. Seed sample data (optional)
9. Cache configurations

### Option 2: Manual Setup

#### 1. Copy Environment File

```bash
cp .env.docker .env
```

#### 2. Generate Application Key

```bash
# Generate a random base64 key
openssl rand -base64 32
# Add it to .env file: APP_KEY=base64:YOUR_GENERATED_KEY
```

#### 3. Build and Start Containers

```bash
docker-compose build
docker-compose up -d
```

#### 4. Install Dependencies

```bash
docker-compose exec app composer install --no-interaction --optimize-autoloader
docker-compose exec app npm install
docker-compose exec app npm run build
```

#### 5. Set Up Database

```bash
docker-compose exec app touch database/database.sqlite
docker-compose exec app chmod 664 database/database.sqlite
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan db:seed --force  # Optional
```

#### 6. Create Storage Link

```bash
docker-compose exec app php artisan storage:link
```

#### 7. Cache Configurations

```bash
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```

## 🌐 Access the Application

Once the containers are running, access the application at:

**URL**: http://localhost:8000

## 📦 Docker Services

### Application (app)
- **Port**: 8000 (mapped to container port 80)
- **Image**: PHP 8.2 with Apache
- **Purpose**: Main Laravel application

### Redis (redis)
- **Port**: 6379
- **Image**: Redis Alpine
- **Purpose**: Caching (optional)

## 🛠️ Common Commands

### Container Management

```bash
# View running containers
docker-compose ps

# View logs
docker-compose logs -f app

# Stop containers
docker-compose stop

# Start containers
docker-compose start

# Restart containers
docker-compose restart

# Remove containers
docker-compose down

# Remove containers and volumes
docker-compose down -v
```

### Application Commands

```bash
# Access container shell
docker-compose exec app bash

# Run artisan commands
docker-compose exec app php artisan [command]

# Run migrations
docker-compose exec app php artisan migrate

# Clear cache
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear

# Create admin user
docker-compose exec app php artisan tinker
# Then run: User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => Hash::make('password'), 'role' => 'admin']);
```

### Database Commands

```bash
# Access SQLite database
docker-compose exec app sqlite3 database/database.sqlite

# Backup database
docker-compose exec app cp database/database.sqlite database/backup.sqlite

# Restore database
docker-compose exec app cp database/backup.sqlite database/database.sqlite
```

## 🔧 Configuration

### Environment Variables

Edit `.env` file to configure:

- `APP_NAME`: Application name
- `APP_ENV`: Environment (production, local, etc.)
- `APP_DEBUG`: Debug mode (true/false)
- `APP_URL`: Application URL
- `DB_DATABASE`: Path to SQLite database

### Port Configuration

To change the application port, edit `docker-compose.yml`:

```yaml
ports:
  - "8080:80"  # Change 8080 to your desired port
```

Then restart containers:

```bash
docker-compose down
docker-compose up -d
```

## 📊 Monitoring

### View Application Logs

```bash
# All logs
docker-compose logs -f

# Application logs only
docker-compose logs -f app

# Last 100 lines
docker-compose logs --tail=100 app
```

### Check Container Status

```bash
docker-compose ps
```

### Check Resource Usage

```bash
docker stats
```

## 🐛 Troubleshooting

### Container Won't Start

```bash
# Check logs
docker-compose logs app

# Rebuild containers
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

### Permission Issues

```bash
# Fix storage permissions
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

### Database Issues

```bash
# Reset database
docker-compose exec app rm database/database.sqlite
docker-compose exec app touch database/database.sqlite
docker-compose exec app chmod 664 database/database.sqlite
docker-compose exec app php artisan migrate:fresh --seed
```

### Clear All Caches

```bash
docker-compose exec app php artisan optimize:clear
```

## 🔒 Security Considerations

### Production Deployment

1. **Change APP_KEY**: Generate a new application key
2. **Set APP_DEBUG=false**: Disable debug mode
3. **Use HTTPS**: Set up SSL/TLS certificates
4. **Secure Database**: Use proper file permissions
5. **Update Dependencies**: Keep packages up to date

### Recommended Changes for Production

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

## 📈 Performance Optimization

### Enable Caching

```bash
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```

### Use Redis for Caching

Update `.env`:

```env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
REDIS_HOST=redis
```

## 🔄 Updates and Maintenance

### Update Application

```bash
# Pull latest code
git pull

# Rebuild containers
docker-compose down
docker-compose build
docker-compose up -d

# Update dependencies
docker-compose exec app composer install --no-interaction --optimize-autoloader
docker-compose exec app npm install
docker-compose exec app npm run build

# Run migrations
docker-compose exec app php artisan migrate --force

# Clear and cache
docker-compose exec app php artisan optimize
```

### Backup

```bash
# Backup database
docker-compose exec app cp database/database.sqlite database/backup-$(date +%Y%m%d).sqlite

# Backup uploaded files
docker-compose exec app tar -czf storage-backup-$(date +%Y%m%d).tar.gz storage/app/public
```

## 📞 Support

For issues or questions:
- Check logs: `docker-compose logs -f app`
- Review Laravel logs: `storage/logs/laravel.log`
- Check Docker status: `docker-compose ps`

## 🎉 Success!

Your Student Project Repository System should now be running at http://localhost:8000

Default credentials (if seeded):
- **Admin**: admin@example.com / password
- **Advisor**: advisor@example.com / password
- **Committee**: committee@example.com / password
- **Student**: student@example.com / password

**Remember to change these passwords in production!**
