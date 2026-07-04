#!/bin/sh
# =============================================================================
# start.sh — Container entrypoint / startup script
# =============================================================================
# Runs every time the container starts. Order matters:
#   1. Ensure storage directories exist with correct permissions
#   2. Ensure the SQLite database file exists (if SQLite is configured)
#   3. Run pending database migrations
#   4. Cache Laravel configuration, routes, and views for production speed
#   5. Substitute ${PORT} into the Nginx site config (Railway dynamic port)
#   6. Create PHP-FPM socket directory
#   7. Hand off to Supervisord (which manages nginx + php-fpm + queue worker)

set -e

# ─────────────────────────────────────────────────────────────────────────────
# 0. Resolve PORT (Railway sets this; default to 8080 for local testing)
# ─────────────────────────────────────────────────────────────────────────────
PORT="${PORT:-8080}"
export PORT

echo "==> [start.sh] Starting IMS application on port ${PORT}"

# ─────────────────────────────────────────────────────────────────────────────
# 1. Storage structure & permissions
# ─────────────────────────────────────────────────────────────────────────────
echo "==> [start.sh] Fixing storage permissions..."

mkdir -p \
  /var/www/html/storage/app/public \
  /var/www/html/storage/framework/cache/data \
  /var/www/html/storage/framework/sessions \
  /var/www/html/storage/framework/views \
  /var/www/html/storage/logs \
  /var/www/html/bootstrap/cache

chown -R www-data:www-data \
  /var/www/html/storage \
  /var/www/html/bootstrap/cache

chmod -R 775 \
  /var/www/html/storage \
  /var/www/html/bootstrap/cache

# ─────────────────────────────────────────────────────────────────────────────
# 2. SQLite — ensure the database file exists (only if using SQLite)
# ─────────────────────────────────────────────────────────────────────────────
DB_CONNECTION="${DB_CONNECTION:-sqlite}"

if [ "$DB_CONNECTION" = "sqlite" ]; then
  DB_DATABASE="${DB_DATABASE:-/var/www/html/database/database.sqlite}"
  echo "==> [start.sh] SQLite mode: ensuring database file exists at ${DB_DATABASE}"
  mkdir -p "$(dirname "$DB_DATABASE")"
  touch "$DB_DATABASE"
  chown www-data:www-data "$DB_DATABASE"
  chmod 664 "$DB_DATABASE"
fi

# ─────────────────────────────────────────────────────────────────────────────
# 3. Wait for external DB (if MySQL / PostgreSQL is configured)
# ─────────────────────────────────────────────────────────────────────────────
if [ "$DB_CONNECTION" != "sqlite" ] && [ -n "$DB_HOST" ]; then
  echo "==> [start.sh] Waiting for database at ${DB_HOST}:${DB_PORT:-3306}..."
  RETRIES=30
  until php -r "new PDO('${DB_CONNECTION}:host=${DB_HOST};port=${DB_PORT:-3306}', '${DB_USERNAME}', '${DB_PASSWORD}');" 2>/dev/null || [ "$RETRIES" -eq 0 ]; do
    echo "    Database not ready — retrying in 2s ($RETRIES retries left)..."
    RETRIES=$((RETRIES - 1))
    sleep 2
  done

  if [ "$RETRIES" -eq 0 ]; then
    echo "ERROR: Could not connect to database after 60 seconds. Aborting."
    exit 1
  fi
  echo "==> [start.sh] Database is ready."
fi

# ─────────────────────────────────────────────────────────────────────────────
# 4. Laravel: generate APP_KEY if not set (safety net — prefer setting in Railway)
# ─────────────────────────────────────────────────────────────────────────────
if [ -z "$APP_KEY" ]; then
  echo "==> [start.sh] APP_KEY not set — generating a temporary key."
  echo "    WARNING: Set APP_KEY in Railway environment variables for stable encryption!"
  cd /var/www/html
  su www-data -s /bin/sh -c "php artisan key:generate --force"
fi

# ─────────────────────────────────────────────────────────────────────────────
# 5. Run database migrations
# ─────────────────────────────────────────────────────────────────────────────
echo "==> [start.sh] Running database migrations..."
cd /var/www/html
su www-data -s /bin/sh -c "php artisan migrate --force --no-interaction"

# ─────────────────────────────────────────────────────────────────────────────
# 6. Laravel caches (dramatically speeds up production boot time)
# ─────────────────────────────────────────────────────────────────────────────
echo "==> [start.sh] Caching Laravel config, routes, and views..."
su www-data -s /bin/sh -c "php artisan config:cache"
su www-data -s /bin/sh -c "php artisan route:cache"
su www-data -s /bin/sh -c "php artisan view:cache"

# ─────────────────────────────────────────────────────────────────────────────
# 7. Render Nginx site config — substitute ${PORT} using envsubst
# ─────────────────────────────────────────────────────────────────────────────
echo "==> [start.sh] Configuring Nginx for port ${PORT}..."

mkdir -p /etc/nginx/conf.d

envsubst '${PORT}' \
  < /etc/nginx/templates/site.conf.template \
  > /etc/nginx/conf.d/default.conf

# ─────────────────────────────────────────────────────────────────────────────
# 8. Ensure Supervisord log directory exists
# ─────────────────────────────────────────────────────────────────────────────
mkdir -p /var/log/supervisor

# ─────────────────────────────────────────────────────────────────────────────
# 10. Hand off to Supervisord
# ─────────────────────────────────────────────────────────────────────────────
echo "==> [start.sh] Starting Supervisord (nginx + php-fpm + queue worker)..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
