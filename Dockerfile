# Use PHP 8.2 with Apache
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application directory contents
COPY . /var/www/html

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www/html

# Configure Apache DocumentRoot to point to Laravel's public directory
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Create SQLite database directory
RUN mkdir -p /var/www/html/database

# Expose port 80
EXPOSE 80

# Create entrypoint script
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
echo "Installing Composer dependencies..."\n\
composer install --no-interaction --optimize-autoloader --no-dev\n\
\n\
echo "Installing NPM dependencies..."\n\
npm ci --production\n\
\n\
echo "Building assets..."\n\
npm run build\n\
\n\
echo "Setting up database..."\n\
touch /var/www/html/database/database.sqlite\n\
chmod 664 /var/www/html/database/database.sqlite\n\
chown www-data:www-data /var/www/html/database/database.sqlite\n\
\n\
echo "Creating storage link..."\n\
php artisan storage:link || true\n\
\n\
echo "Caching configuration..."\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
\n\
echo "Starting Apache..."\n\
exec apache2-foreground\n\
' > /usr/local/bin/docker-entrypoint.sh

RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Start with entrypoint
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
