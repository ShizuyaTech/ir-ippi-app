# 🚀 VPS Setup - Ubuntu 24.04 Optimized

**Status**: ✅ Optimized for Ubuntu 24.04 LTS  
**PHP Version**: 8.3  
**Web Server**: Nginx  
**Database**: MySQL 8.0  
**Application Server**: Octane + Swoole  

---

## ⚠️ PENTING: Perbedaan Ubuntu 24.04 vs Versi Lama

| Item | Ubuntu 24.04 | Ubuntu 22.04 |
|------|-------------|-------------|
| **PHP Default** | 8.3 | 8.1 |
| **MySQL** | 8.4 | 8.0 |
| **Node.js** | 20.x | 18.x |
| **Package Manager** | apt (same) | apt (same) |
| **Service Manager** | systemd (same) | systemd (same) |

---

## 📌 STEP 1: Connect to VPS

```bash
ssh root@your.vps.ip
```

---

## 📌 STEP 2: Verify Ubuntu Version

```bash
lsb_release -a
```

Output harus menampilkan: `Release: 24.04`

```bash
cat /etc/os-release | grep VERSION
```

---

## 📌 STEP 3: Update System

```bash
sudo apt update
```

```bash
sudo apt upgrade -y
```

Tunggu selesai (5-10 menit).

---

## 📌 STEP 4: Install Essential Build Tools

```bash
sudo apt install -y \
  build-essential \
  autoconf \
  automake \
  libtool \
  pkg-config \
  curl \
  wget \
  git \
  unzip
```

---

## 📌 STEP 5: Install PHP 8.3 (Ubuntu 24.04 Default)

```bash
sudo apt install -y php8.3 php8.3-cli php8.3-fpm
```

---

## 📌 STEP 6: Install PHP Extensions (Critical)

```bash
sudo apt install -y \
  php8.3-mysql \
  php8.3-zip \
  php8.3-gd \
  php8.3-mbstring \
  php8.3-curl \
  php8.3-xml \
  php8.3-bcmath \
  php8.3-dev \
  php8.3-pdo \
  php8.3-json
```

---

## 📌 STEP 7: Verify PHP Installation

```bash
php -v
```

Output harus: `PHP 8.3.x`

```bash
php -m | head -20
```

Output harus menampilkan installed extensions.

---

## 📌 STEP 8: Install Swoole Build Dependencies

**CRITICAL FOR UBUNTU 24.04:**

```bash
sudo apt install -y \
  libcurl4-openssl-dev \
  libbrotli-dev \
  libssl-dev \
  zlib1g-dev \
  libssl3 \
  libcurl4
```

---

## 📌 STEP 9: Install Swoole Extension

```bash
sudo pecl install swoole
```

**Expected Output:**
```
Build process completed successfully
Installing '/usr/lib/php/20230831/swoole.so'
```

Tunggu hingga selesai (5-10 menit).

---

## 📌 STEP 10: Add Swoole to PHP CLI

```bash
echo "extension=swoole.so" | sudo tee -a /etc/php/8.3/cli/php.ini
```

---

## 📌 STEP 11: Add Swoole to PHP-FPM

```bash
echo "extension=swoole.so" | sudo tee -a /etc/php/8.3/fpm/php.ini
```

---

## 📌 STEP 12: Verify Swoole Installation

```bash
php -m | grep swoole
```

Output **harus** menampilkan: `swoole`

Jika tidak muncul, check: `/var/log/php-error.log`

---

## 📌 STEP 13: Check PHP Configuration

```bash
php -i | grep -E "memory_limit|max_execution_time|upload_max_filesize"
```

Output harus menampilkan nilai-nilai PHP config.

---

## 📌 STEP 14: Install Composer

```bash
curl -sS https://getcomposer.org/installer | php
```

```bash
sudo mv composer.phar /usr/local/bin/composer
```

```bash
sudo chmod +x /usr/local/bin/composer
```

---

## 📌 STEP 15: Verify Composer

```bash
composer --version
```

Output harus menampilkan Composer version.

---

## 📌 STEP 16: Install Node.js (Ubuntu 24.04)

```bash
sudo apt install -y nodejs npm
```

Verify:
```bash
node -v
npm -v
```

---

## 📌 STEP 17: Install Nginx

```bash
sudo apt install -y nginx
```

---

## 📌 STEP 18: Start Nginx

```bash
sudo systemctl start nginx
```

```bash
sudo systemctl enable nginx
```

---

## 📌 STEP 19: Install MySQL Server

```bash
sudo apt install -y mysql-server
```

Tunggu selesai.

---

## 📌 STEP 20: Verify MySQL

```bash
sudo systemctl status mysql
```

Output harus: `active (running)`

---

## 📌 STEP 21: Run MySQL Security Script

```bash
sudo mysql_secure_installation
```

**Answers untuk prompts:**
```
VALIDATE PASSWORD COMPONENT: n (no)
Change root password?: n (no)
Remove anonymous users?: y (yes)
Disable root login remotely?: y (yes)
Remove test database?: y (yes)
Reload privilege tables?: y (yes)
```

---

## 📌 STEP 22: Install Certbot for SSL

```bash
sudo apt install -y certbot python3-certbot-nginx
```

---

## 📌 STEP 23: Create Application Directory

```bash
sudo mkdir -p /var/www
```

```bash
cd /var/www
```

---

## 📌 STEP 24: Clone Application

```bash
sudo git clone https://github.com/ShizuyaTech/ir-ippi-app.git
```

Tunggu selesai.

---

## 📌 STEP 25: Navigate to Project

```bash
cd /var/www/ir-ippi-app
```

---

## 📌 STEP 26: Install PHP Dependencies

```bash
sudo composer install --optimize-autoloader --no-dev
```

Tunggu selesai (10-15 menit).

---

## 📌 STEP 27: Install Frontend Dependencies

```bash
sudo npm install
```

Tunggu selesai.

---

## 📌 STEP 28: Build Assets

```bash
sudo npm run build
```

Tunggu selesai.

---

## 📌 STEP 29: Create .env File

```bash
sudo cp .env.example .env
```

---

## 📌 STEP 30: Generate Application Key

```bash
sudo php artisan key:generate
```

---

## 📌 STEP 31: Edit .env (Production Settings)

```bash
sudo nano /var/www/ir-ippi-app/.env
```

**Update these values:**

```env
APP_NAME=IR-IPPI
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
APP_KEY=base64:xxxxx (keep existing)

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ir_ippi_db
DB_USERNAME=ir_ippi_user
DB_PASSWORD=GENERATE_STRONG_PASSWORD

# Cache
CACHE_STORE=file

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Queue
QUEUE_CONNECTION=database

# Mail
MAIL_MAILER=log
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="IR-IPPI"

# Octane
OCTANE_SERVER=swoole
OCTANE_WORKERS=4
OCTANE_TASK_WORKERS=6
OCTANE_MAX_REQUESTS=500
OCTANE_PORT=8000

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=warning
```

**Save:** `Ctrl + X`, `Y`, `Enter`

---

## 📌 STEP 32: Set File Permissions

```bash
sudo chown -R www-data:www-data /var/www/ir-ippi-app
```

```bash
sudo chmod -R 775 /var/www/ir-ippi-app/storage
```

```bash
sudo chmod -R 775 /var/www/ir-ippi-app/bootstrap/cache
```

```bash
sudo chmod -R 755 /var/www/ir-ippi-app/public
```

---

## 📌 STEP 33: Create MySQL Database & User

```bash
sudo mysql -u root
```

**In MySQL prompt (copy-paste satu per satu):**

```sql
CREATE DATABASE ir_ippi_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

```sql
CREATE USER 'ir_ippi_user'@'127.0.0.1' IDENTIFIED BY 'STRONG_PASSWORD';
```

```sql
GRANT ALL PRIVILEGES ON ir_ippi_db.* TO 'ir_ippi_user'@'127.0.0.1';
```

```sql
FLUSH PRIVILEGES;
```

```sql
EXIT;
```

---

## 📌 STEP 34: Test Database Connection

```bash
mysql -u ir_ippi_user -p ir_ippi_db -h 127.0.0.1 -e "SELECT 1;"
```

Enter password yang dibuat di STEP 33.

Output harus menampilkan: `1`

---

## 📌 STEP 35: Optimize Laravel Config

```bash
cd /var/www/ir-ippi-app
```

```bash
sudo php artisan config:cache
```

```bash
sudo php artisan route:cache
```

```bash
sudo php artisan view:cache
```

---

## 📌 STEP 36: Run Database Migrations

```bash
sudo php artisan migrate --force
```

Tunggu selesai. Output harus show migrations yang jalan.

---

## 📌 STEP 37: Create Octane Service File

```bash
sudo nano /etc/systemd/system/ir-ippi-octane.service
```

**Copy & paste ini:**

```ini
[Unit]
Description=IR-IPPI Octane Application Server
After=network.target mysql.service

[Service]
Type=simple
User=www-data
Group=www-data
WorkingDirectory=/var/www/ir-ippi-app
ExecStart=/usr/bin/php /var/www/ir-ippi-app/artisan octane:start --server=swoole --workers=4 --task-workers=6 --port=8000
Restart=on-failure
RestartSec=10
StandardOutput=append:/var/log/ir-ippi-octane.log
StandardError=append:/var/log/ir-ippi-octane-error.log
SyslogIdentifier=ir-ippi-octane

[Install]
WantedBy=multi-user.target
```

**Save:** `Ctrl + X`, `Y`, `Enter`

---

## 📌 STEP 38: Create Queue Service File

```bash
sudo nano /etc/systemd/system/ir-ippi-queue.service
```

**Copy & paste ini:**

```ini
[Unit]
Description=IR-IPPI Queue Worker
After=network.target mysql.service

[Service]
Type=simple
User=www-data
Group=www-data
WorkingDirectory=/var/www/ir-ippi-app
ExecStart=/usr/bin/php /var/www/ir-ippi-app/artisan queue:listen database --tries=3 --timeout=90
Restart=on-failure
RestartSec=10
StandardOutput=append:/var/log/ir-ippi-queue.log
StandardError=append:/var/log/ir-ippi-queue-error.log
SyslogIdentifier=ir-ippi-queue

[Install]
WantedBy=multi-user.target
```

**Save:** `Ctrl + X`, `Y`, `Enter`

---

## 📌 STEP 39: Reload Systemd

```bash
sudo systemctl daemon-reload
```

---

## 📌 STEP 40: Enable Services at Boot

```bash
sudo systemctl enable ir-ippi-octane ir-ippi-queue
```

---

## 📌 STEP 41: Start Octane Service

```bash
sudo systemctl start ir-ippi-octane
```

---

## 📌 STEP 42: Start Queue Service

```bash
sudo systemctl start ir-ippi-queue
```

---

## 📌 STEP 43: Verify Services Running

```bash
sudo systemctl status ir-ippi-octane
```

Output harus: `active (running)`

```bash
sudo systemctl status ir-ippi-queue
```

Output harus: `active (running)`

---

## 📌 STEP 44: Test Local Connection

```bash
curl http://localhost:8000
```

Output harus menampilkan HTML (bukan error).

---

## 📌 STEP 45: Configure Nginx

```bash
sudo nano /etc/nginx/sites-available/ir-ippi
```

**Copy & paste (ganti yourdomain.com):**

```nginx
upstream ir_ippi_octane {
    server 127.0.0.1:8000;
    keepalive 64;
}

server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;

    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;

    root /var/www/ir-ippi-app/public;
    index index.php;

    access_log /var/log/nginx/ir-ippi-access.log;
    error_log /var/log/nginx/ir-ippi-error.log;

    client_max_body_size 10M;

    location / {
        try_files $uri $uri/ @octane;
    }

    location @octane {
        proxy_pass http://ir_ippi_octane;
        proxy_http_version 1.1;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_read_timeout 60s;
        proxy_buffering off;
    }

    location ~ /\.env { deny all; }
    location ~ /\.git { deny all; }
    location ~ /vendor { deny all; }
    location ~ /storage { deny all; }
}
```

**Save:** `Ctrl + X`, `Y`, `Enter`

---

## 📌 STEP 46: Enable Nginx Site

```bash
sudo ln -s /etc/nginx/sites-available/ir-ippi /etc/nginx/sites-enabled/
```

---

## 📌 STEP 47: Test Nginx Config

```bash
sudo nginx -t
```

Output harus: `successful`

---

## 📌 STEP 48: Restart Nginx

```bash
sudo systemctl restart nginx
```

---

## 📌 STEP 49: Get SSL Certificate

```bash
sudo certbot certonly --nginx -d yourdomain.com -d www.yourdomain.com
```

Follow prompts:
- Email: your@email.com
- Agree terms: Y
- Share email: N

---

## 📌 STEP 50: Verify SSL

```bash
sudo certbot certificates
```

Output harus menampilkan certificate domains.

---

## 📌 STEP 51: Test Application

Open in browser:
```
https://yourdomain.com
```

Application harus load without error.

---

## ✅ VERIFICATION CHECKLIST

Run these commands to verify everything:

```bash
# 1. Check PHP & Swoole
php -v
php -m | grep swoole

# 2. Check services
sudo systemctl status ir-ippi-octane
sudo systemctl status ir-ippi-queue
sudo systemctl status nginx
sudo systemctl status mysql

# 3. Check processes
ps aux | grep -E "octane|queue" | grep -v grep

# 4. Check logs
tail -20 /var/log/ir-ippi-octane.log
tail -20 /var/log/ir-ippi-queue.log

# 5. Test database
mysql -u ir_ippi_user -p ir_ippi_db -h 127.0.0.1 -e "SELECT 1;"

# 6. Check Nginx
curl https://yourdomain.com

# 7. Check SSL
openssl s_client -connect yourdomain.com:443 -servername yourdomain.com
```

---

## 🎉 SELESAI!

Aplikasi Anda sekarang running di Ubuntu 24.04 dengan:
- ✅ PHP 8.3 + Swoole
- ✅ Octane + RoadRunner
- ✅ MySQL 8.4
- ✅ Nginx reverse proxy
- ✅ SSL/HTTPS
- ✅ Queue worker
- ✅ Auto-start on reboot

---

## ⚠️ Ubuntu 24.04 Specific Notes

1. **MySQL Root Access**: Gunakan `sudo mysql` tanpa password
2. **PHP Version**: 8.3 adalah default (tidak perlu specify)
3. **Service Names**: 
   - `mysql` bukan `mysqld`
   - `nginx` bukan `apache2`
4. **Permissions**: `www-data:www-data` masih sama
5. **Systemd**: Semuanya dengan `systemctl` (sama seperti sebelumnya)

---

## 🆘 If Something Goes Wrong

1. Check logs:
   ```bash
   sudo journalctl -u ir-ippi-octane -n 50
   tail -f /var/log/ir-ippi-octane.log
   ```

2. Check service status:
   ```bash
   sudo systemctl status ir-ippi-octane
   ```

3. Restart everything:
   ```bash
   sudo systemctl restart ir-ippi-octane ir-ippi-queue mysql nginx
   ```

4. See **VPS_TROUBLESHOOTING.md** untuk error solutions

---

**Total Steps**: 51  
**Estimated Time**: ~90 minutes  
**Ubuntu Version**: 24.04 LTS  
**Status**: ✅ Production Ready

Setiap step adalah 1-2 command. Copy-paste & tunggu! 🚀
