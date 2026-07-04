# =============================================================================
# Stage 1: Node — Build Vite/Tailwind frontend assets
# =============================================================================
FROM node:22-alpine AS node_builder

WORKDIR /app

# Copy package files first for layer-cache efficiency
COPY package*.json ./

# Install Node dependencies (ci is deterministic, uses package-lock.json)
RUN npm install

# Copy the rest of the source that Vite needs
COPY resources/ ./resources/
COPY vite.config.js ./
COPY public/ ./public/

# Build production assets (outputs to public/build/)
RUN npm run build


# =============================================================================
# Stage 2: Composer — Install PHP dependencies (production-only, no dev)
# =============================================================================
FROM composer:2.8 AS composer_builder

WORKDIR /app

# Copy composer files first for layer-cache efficiency
COPY composer.json composer.lock ./

# Install production dependencies — no scripts, no dev packages
# --ignore-platform-reqs lets Composer run regardless of the builder PHP version
# Step 1: Install without autoloader so layer cache is used even if src changes
RUN composer install \
  --no-dev \
  --no-scripts \
  --no-autoloader \
  --ignore-platform-reqs \
  --prefer-dist

# Step 2: Copy full source so the autoloader can be dumped with all classes present
COPY . .

# Step 3: Generate the optimised classmap autoloader
RUN composer dump-autoload --no-dev --optimize


# =============================================================================
# Stage 3: Final runtime image — PHP 8.4-FPM + Nginx + Supervisord
# =============================================================================
FROM php:8.4-fpm-bookworm AS runtime

# ---------------------------------------------------------------------------
# System dependencies & PHP extensions
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
  default-mysql-client \
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
  && pecl install redis \
  && docker-php-ext-enable redis \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/*

# ---------------------------------------------------------------------------
# PHP runtime configuration (production tuned)
# ---------------------------------------------------------------------------
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY docker/php/php.ini "$PHP_INI_DIR/conf.d/99-laravel.ini"

# PHP-FPM pool: listen on TCP 9000 (the official php-fpm Docker image default)
# This matches the fastcgi_pass directive in nginx site.conf.template
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf

# ---------------------------------------------------------------------------
# Application source
# ---------------------------------------------------------------------------
WORKDIR /var/www/html

# Copy application code (from current build context)
COPY . .

# Overlay the production Composer vendor tree
COPY --from=composer_builder /app/vendor ./vendor

# Overlay the compiled frontend assets
COPY --from=node_builder /app/public/build ./public/build

# ---------------------------------------------------------------------------
# Storage & permissions
# ---------------------------------------------------------------------------
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
# Nginx configuration
# ---------------------------------------------------------------------------
# Remove default nginx site
RUN rm -f /etc/nginx/sites-enabled/default /etc/nginx/sites-available/default

COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/site.conf.template /etc/nginx/templates/site.conf.template

# ---------------------------------------------------------------------------
# Supervisord configuration
# ---------------------------------------------------------------------------
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# ---------------------------------------------------------------------------
# Startup script
# ---------------------------------------------------------------------------
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# ---------------------------------------------------------------------------
# Runtime
# ---------------------------------------------------------------------------
# Railway injects PORT at runtime; expose a sensible default for local testing
EXPOSE 8080

# Healthcheck via Laravel's /up endpoint
HEALTHCHECK --interval=30s --timeout=10s --start-period=60s --retries=3 \
  CMD curl -f http://localhost:${PORT:-8080}/up || exit 1

CMD ["/usr/local/bin/start.sh"]
