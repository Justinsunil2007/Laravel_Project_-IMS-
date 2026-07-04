# =============================================================================
# Stage 1: Node — Build Vite/Tailwind frontend assets
# =============================================================================
FROM node:22-alpine AS node_builder

WORKDIR /app

COPY package*.json ./
RUN npm install --ignore-scripts

COPY resources/ ./resources/
COPY vite.config.js ./
COPY public/ ./public/

RUN npm run build


# =============================================================================
# Stage 2: Composer — Install PHP production dependencies
# =============================================================================
FROM composer:2.8 AS composer_builder

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
      --no-dev \
      --no-scripts \
      --no-autoloader \
      --ignore-platform-reqs \
      --prefer-dist

COPY . .

RUN composer dump-autoload --no-dev --optimize


# =============================================================================
# Stage 3: Final runtime image — PHP 8.4-FPM + Nginx + Supervisord
# =============================================================================
FROM php:8.4-fpm-bookworm

# ---------------------------------------------------------------------------
# System packages + PHP extensions
# ---------------------------------------------------------------------------
RUN apt-get update && apt-get install -y --no-install-recommends \
      nginx \
      supervisor \
      gettext-base \
      curl \
      zip \
      unzip \
      libzip-dev \
      libpng-dev \
      libjpeg62-turbo-dev \
      libfreetype6-dev \
      libxml2-dev \
      libicu-dev \
      libonig-dev \
      libsqlite3-dev \
      libpq-dev \
    && docker-php-ext-configure gd \
         --with-freetype \
         --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
         pdo \
         pdo_mysql \
         pdo_sqlite \
         pdo_pgsql \
         zip \
         bcmath \
         gd \
         intl \
         mbstring \
         opcache \
         xml \
         pcntl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ---------------------------------------------------------------------------
# PHP ini — write inline (no COPY from build context needed)
# ---------------------------------------------------------------------------
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.enable_cli=0'; \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=16'; \
    echo 'opcache.max_accelerated_files=20000'; \
    echo 'opcache.revalidate_freq=0'; \
    echo 'opcache.validate_timestamps=0'; \
    echo 'opcache.save_comments=1'; \
    echo 'upload_max_filesize=50M'; \
    echo 'post_max_size=52M'; \
    echo 'max_execution_time=300'; \
    echo 'memory_limit=256M'; \
    echo 'date.timezone=UTC'; \
    echo 'display_errors=Off'; \
    echo 'log_errors=On'; \
    echo 'error_log=/proc/self/fd/2'; \
} > "$PHP_INI_DIR/conf.d/99-laravel.ini"

# ---------------------------------------------------------------------------
# PHP-FPM pool — write inline
# ---------------------------------------------------------------------------
RUN { \
    echo '[www]'; \
    echo 'user = www-data'; \
    echo 'group = www-data'; \
    echo 'listen = 127.0.0.1:9000'; \
    echo 'pm = dynamic'; \
    echo 'pm.max_children = 20'; \
    echo 'pm.start_servers = 4'; \
    echo 'pm.min_spare_servers = 2'; \
    echo 'pm.max_spare_servers = 8'; \
    echo 'pm.max_requests = 500'; \
    echo 'clear_env = no'; \
    echo 'php_admin_value[error_log] = /proc/self/fd/2'; \
    echo 'php_admin_flag[log_errors] = on'; \
    echo 'request_terminate_timeout = 300'; \
} > /usr/local/etc/php-fpm.d/www.conf

# ---------------------------------------------------------------------------
# Nginx — write global config inline
# ---------------------------------------------------------------------------
RUN { \
    echo 'user www-data;'; \
    echo 'worker_processes auto;'; \
    echo 'error_log /var/log/nginx/error.log warn;'; \
    echo 'pid /var/run/nginx.pid;'; \
    echo 'events { worker_connections 1024; }'; \
    echo 'http {'; \
    echo '    include /etc/nginx/mime.types;'; \
    echo '    default_type application/octet-stream;'; \
    echo '    sendfile on;'; \
    echo '    keepalive_timeout 65;'; \
    echo '    server_tokens off;'; \
    echo '    client_max_body_size 50M;'; \
    echo '    gzip on;'; \
    echo '    gzip_types text/plain text/css application/javascript application/json image/svg+xml;'; \
    echo '    include /etc/nginx/conf.d/*.conf;'; \
    echo '}'; \
} > /etc/nginx/nginx.conf

# Nginx site template — ${PORT} is replaced at container startup by envsubst
RUN mkdir -p /etc/nginx/templates && { \
    echo 'server {'; \
    echo '    listen ${PORT};'; \
    echo '    root /var/www/html/public;'; \
    echo '    index index.php index.html;'; \
    echo '    charset utf-8;'; \
    echo '    location / {'; \
    echo '        try_files $uri $uri/ /index.php?$query_string;'; \
    echo '    }'; \
    echo '    location ~ \.php$ {'; \
    echo '        fastcgi_pass 127.0.0.1:9000;'; \
    echo '        fastcgi_index index.php;'; \
    echo '        include fastcgi_params;'; \
    echo '        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;'; \
    echo '        fastcgi_param DOCUMENT_ROOT $realpath_root;'; \
    echo '        fastcgi_read_timeout 300;'; \
    echo '    }'; \
    echo '    location /build/ {'; \
    echo '        expires 1y;'; \
    echo '        add_header Cache-Control "public, immutable";'; \
    echo '        try_files $uri =404;'; \
    echo '    }'; \
    echo '    location ~ /\.(?!well-known).* { deny all; }'; \
    echo '    location = /favicon.ico { access_log off; log_not_found off; }'; \
    echo '    location = /robots.txt  { access_log off; log_not_found off; }'; \
    echo '    error_page 404 /index.php;'; \
    echo '}'; \
} > /etc/nginx/templates/site.conf.template

# ---------------------------------------------------------------------------
# Supervisord config — write inline
# ---------------------------------------------------------------------------
RUN mkdir -p /etc/supervisor/conf.d && { \
    echo '[supervisord]'; \
    echo 'nodaemon=true'; \
    echo 'user=root'; \
    echo 'logfile=/var/log/supervisor/supervisord.log'; \
    echo 'pidfile=/var/run/supervisord.pid'; \
    echo '[program:php-fpm]'; \
    echo 'command=/usr/local/sbin/php-fpm --nodaemonize'; \
    echo 'autostart=true'; \
    echo 'autorestart=true'; \
    echo 'priority=5'; \
    echo 'stdout_logfile=/dev/stdout'; \
    echo 'stdout_logfile_maxbytes=0'; \
    echo 'stderr_logfile=/dev/stderr'; \
    echo 'stderr_logfile_maxbytes=0'; \
    echo '[program:nginx]'; \
    echo 'command=/usr/sbin/nginx -g "daemon off;"'; \
    echo 'autostart=true'; \
    echo 'autorestart=true'; \
    echo 'priority=10'; \
    echo 'stdout_logfile=/dev/stdout'; \
    echo 'stdout_logfile_maxbytes=0'; \
    echo 'stderr_logfile=/dev/stderr'; \
    echo 'stderr_logfile_maxbytes=0'; \
    echo '[program:queue-worker]'; \
    echo 'command=php /var/www/html/artisan queue:work --sleep=3 --tries=3 --max-time=3600'; \
    echo 'user=www-data'; \
    echo 'autostart=true'; \
    echo 'autorestart=true'; \
    echo 'priority=20'; \
    echo 'redirect_stderr=true'; \
    echo 'stdout_logfile=/dev/stdout'; \
    echo 'stdout_logfile_maxbytes=0'; \
    echo 'stopwaitsecs=3600'; \
} > /etc/supervisor/conf.d/supervisord.conf

# ---------------------------------------------------------------------------
# Startup script — write inline
# ---------------------------------------------------------------------------
RUN cat > /usr/local/bin/start.sh << 'STARTSCRIPT'
#!/bin/sh
set -e

PORT="${PORT:-8080}"
export PORT

echo "==> Starting IMS on port ${PORT}"

# Fix storage permissions
mkdir -p \
  /var/www/html/storage/app/public \
  /var/www/html/storage/framework/cache/data \
  /var/www/html/storage/framework/sessions \
  /var/www/html/storage/framework/views \
  /var/www/html/storage/logs \
  /var/www/html/bootstrap/cache

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# SQLite: ensure database file exists
DB_CONNECTION="${DB_CONNECTION:-sqlite}"
if [ "$DB_CONNECTION" = "sqlite" ]; then
  DB_FILE="${DB_DATABASE:-/var/www/html/database/database.sqlite}"
  echo "==> SQLite: ensuring ${DB_FILE} exists"
  mkdir -p "$(dirname "$DB_FILE")"
  touch "$DB_FILE"
  chown www-data:www-data "$DB_FILE"
  chmod 664 "$DB_FILE"
fi

# Run migrations
echo "==> Running migrations..."
cd /var/www/html
su www-data -s /bin/sh -c "php artisan migrate --force --no-interaction"

# Cache config / routes / views for production speed
echo "==> Caching Laravel config, routes, views..."
su www-data -s /bin/sh -c "php artisan config:cache"
su www-data -s /bin/sh -c "php artisan route:cache"
su www-data -s /bin/sh -c "php artisan view:cache"

# Inject PORT into Nginx site config
echo "==> Configuring Nginx for port ${PORT}..."
mkdir -p /etc/nginx/conf.d
envsubst '${PORT}' \
  < /etc/nginx/templates/site.conf.template \
  > /etc/nginx/conf.d/default.conf

# Remove default nginx site if it exists
rm -f /etc/nginx/sites-enabled/default

# Ensure supervisor log dir exists
mkdir -p /var/log/supervisor

echo "==> Starting Supervisord..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
STARTSCRIPT

RUN chmod +x /usr/local/bin/start.sh

# ---------------------------------------------------------------------------
# Application source
# ---------------------------------------------------------------------------
WORKDIR /var/www/html

COPY . .
COPY --from=composer_builder /app/vendor ./vendor
COPY --from=node_builder /app/public/build ./public/build

# Storage & permissions
RUN mkdir -p \
      storage/app/public \
      storage/framework/cache/data \
      storage/framework/sessions \
      storage/framework/views \
      storage/logs \
      bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# ---------------------------------------------------------------------------
# Runtime
# ---------------------------------------------------------------------------
EXPOSE 8080

HEALTHCHECK --interval=30s --timeout=10s --start-period=60s --retries=3 \
  CMD curl -f http://localhost:${PORT:-8080}/up || exit 1

CMD ["/usr/local/bin/start.sh"]
