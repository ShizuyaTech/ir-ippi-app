#!/bin/bash
# Quick Production VPS Setup Script
# Usage: Copy this to your VPS and run

echo "🚀 IR-IPPI Application - VPS Setup Script"
echo "=========================================="

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Check if running as root
if [[ $EUID -ne 0 ]]; then
   echo -e "${RED}This script must be run as root${NC}"
   exit 1
fi

PROJECT_PATH="/var/www/ir-ippi-app"
PHP_VERSION="8.3"

echo -e "${YELLOW}Step 1: Update System${NC}"
apt update && apt upgrade -y

echo -e "${YELLOW}Step 2: Install PHP ${PHP_VERSION} & Extensions${NC}"
apt install -y \
  php${PHP_VERSION} \
  php${PHP_VERSION}-cli \
  php${PHP_VERSION}-fpm \
  php${PHP_VERSION}-mysql \
  php${PHP_VERSION}-zip \
  php${PHP_VERSION}-gd \
  php${PHP_VERSION}-mbstring \
  php${PHP_VERSION}-curl \
  php${PHP_VERSION}-xml \
  php${PHP_VERSION}-bcmath \
  php${PHP_VERSION}-dev

echo -e "${YELLOW}Step 3: Install Swoole Dependencies${NC}"
apt install -y \
  autoconf \
  automake \
  libtool \
  pkg-config \
  libcurl4-openssl-dev \
  libbrotli-dev \
  libssl-dev \
  zlib1g-dev

echo -e "${YELLOW}Step 4: Install Swoole (RECOMMENDED for best performance)${NC}"
pecl install swoole
echo "extension=swoole.so" >> /etc/php/${PHP_VERSION}/cli/php.ini
echo "extension=swoole.so" >> /etc/php/${PHP_VERSION}/fpm/php.ini

# Verify Swoole
if php -m | grep -q swoole; then
    echo -e "${GREEN}✅ Swoole installed successfully${NC}"
else
    echo -e "${RED}❌ Swoole installation failed${NC}"
    exit 1
fi

echo -e "${YELLOW}Step 5: Install Composer${NC}"
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

echo -e "${YELLOW}Step 6: Install Node.js & npm${NC}"
apt install -y nodejs npm

echo -e "${YELLOW}Step 7: Install Nginx${NC}"
apt install -y nginx

echo -e "${YELLOW}Step 8: Install MySQL Client${NC}"
apt install -y mysql-client

echo -e "${YELLOW}Step 9: Install Certbot for SSL${NC}"
apt install -y certbot python3-certbot-nginx

echo -e "${YELLOW}Step 10: Setup Application Directory${NC}"
mkdir -p /var/www
cd /var/www

# If project not cloned yet
if [ ! -d "$PROJECT_PATH" ]; then
    git clone https://github.com/ShizuyaTech/ir-ippi-app.git
    cd $PROJECT_PATH
else
    cd $PROJECT_PATH
    git pull origin main
fi

echo -e "${YELLOW}Step 11: Install Dependencies${NC}"
composer install --optimize-autoloader --no-dev
npm install
npm run build

echo -e "${YELLOW}Step 12: Create & Configure .env${NC}"
if [ ! -f "$PROJECT_PATH/.env" ]; then
    cp .env.example .env
    php artisan key:generate
    echo -e "${GREEN}✅ .env created${NC}"
else
    echo -e "${YELLOW}⚠️ .env already exists - please update manually${NC}"
fi

echo -e "${YELLOW}Step 13: Setup File Permissions${NC}"
chown -R www-data:www-data $PROJECT_PATH
chmod -R 775 storage bootstrap/cache
chmod -R 755 public

echo -e "${YELLOW}Step 14: Create Systemd Service for Octane${NC}"
cat > /etc/systemd/system/ir-ippi-octane.service << 'EOF'
[Unit]
Description=IR-IPPI Octane Application Server
After=network.target

[Service]
Type=simple
User=www-data
Group=www-data
WorkingDirectory=/var/www/ir-ippi-app
ExecStart=/usr/bin/php /var/www/ir-ippi-app/artisan octane:start --server=swoole --workers=4 --task-workers=6
Restart=on-failure
RestartSec=10
StandardOutput=append:/var/log/ir-ippi-octane.log
StandardError=append:/var/log/ir-ippi-octane-error.log

[Install]
WantedBy=multi-user.target
EOF

echo -e "${YELLOW}Step 15: Setup Log Rotation${NC}"
cat > /etc/logrotate.d/ir-ippi << 'EOF'
/var/log/ir-ippi-*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0644 www-data www-data
    sharedscripts
    postrotate
        systemctl reload ir-ippi-octane > /dev/null 2>&1 || true
    endscript
}
EOF

echo -e "${GREEN}=========================================="
echo "✅ VPS Setup Complete!"
echo "=========================================="
echo ""
echo "Next Steps:"
echo "1. Update .env with production values:"
echo "   - APP_ENV=production"
echo "   - APP_DEBUG=false"
echo "   - DB_HOST, DB_USERNAME, DB_PASSWORD"
echo "   - OCTANE_SERVER=swoole"
echo "   - MAIL_MAILER=smtp (configure SMTP)"
echo ""
echo "2. Run database migration:"
echo "   php artisan migrate --force"
echo ""
echo "3. Optimize application:"
echo "   php artisan config:cache"
echo "   php artisan route:cache"
echo ""
echo "4. Start services:"
echo "   systemctl start ir-ippi-octane"
echo "   systemctl start ir-ippi-queue"
echo ""
echo "5. Configure Nginx (see VPS_DEPLOYMENT_GUIDE.md)"
echo ""
echo "6. Setup SSL with Let's Encrypt:"
echo "   certbot certonly --nginx -d yourdomain.com"
echo ""
echo -e "${NC}"
