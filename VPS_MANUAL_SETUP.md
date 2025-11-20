# 🚀 VPS Setup - Manual Commands (Step by Step)

**Status**: ✅ Production Ready  
**Difficulty**: ⭐ Easy (copy-paste each command)  
**Time**: ~1 hour

---

## 📌 STEP 1: Connect to VPS via SSH

```bash
ssh root@your.vps.ip
```

**Replace `your.vps.ip` dengan IP address VPS Anda**

Contoh:
```bash
ssh root@192.168.1.100
ssh root@example.com
```

---

## 📌 STEP 2: Update System Packages

```bash
sudo apt update
```

Tunggu hingga selesai.

```bash
sudo apt upgrade -y
```

Tunggu hingga selesai (bisa memakan waktu 5-10 menit).

---

## 📌 STEP 3: Install Build Tools & Dependencies

```bash
sudo apt install -y \
  build-essential \
  autoconf \
  automake \
  libtool \
  pkg-config \
  curl \
  wget \
  git
```

Tunggu hingga selesai.

---

## 📌 STEP 4: Install PHP 8.3 & Extensions

```bash
sudo apt install -y php8.3 php8.3-cli php8.3-fpm
```

Tunggu hingga selesai.

```bash
sudo apt install -y \
  php8.3-mysql \
  php8.3-zip \
  php8.3-gd \
  php8.3-mbstring \
  php8.3-curl \
  php8.3-xml \
  php8.3-bcmath \
  php8.3-dev
```

Tunggu hingga selesai.

---

## 📌 STEP 5: Verify PHP Installation

```bash
php -v
```

Output harus menampilkan PHP 8.3.x

```bash
php -m | grep -E "mysql|gd|bcmath"
```

Output harus menampilkan extensions yang terinstall.

---

## 📌 STEP 6: Install Build Dependencies for Swoole

```bash
sudo apt install -y \
  libcurl4-openssl-dev \
  libbrotli-dev \
  libssl-dev \
  zlib1g-dev
```

Tunggu hingga selesai.

---

## 📌 STEP 7: Install Swoole PHP Extension

```bash
sudo pecl install swoole
```

Proses ini bisa memakan waktu 5-10 menit. Tunggu sampai selesai.

---

## 📌 STEP 8: Add Swoole to PHP CLI Configuration

```bash
echo "extension=swoole.so" | sudo tee -a /etc/php/8.3/cli/php.ini
```

---

## 📌 STEP 9: Add Swoole to PHP FPM Configuration

```bash
echo "extension=swoole.so" | sudo tee -a /etc/php/8.3/fpm/php.ini
```

---

## 📌 STEP 10: Verify Swoole Installation

```bash
php -m | grep swoole
```

Output harus menampilkan: `swoole`

Jika error, lihat **VPS_TROUBLESHOOTING.md**

---

## 📌 STEP 11: Install Composer

```bash
curl -sS https://getcomposer.org/installer | php
```

Tunggu hingga selesai.

```bash
sudo mv composer.phar /usr/local/bin/composer
```

```bash
sudo chmod +x /usr/local/bin/composer
```

---

## 📌 STEP 12: Verify Composer Installation

```bash
composer --version
```

Output harus menampilkan Composer version.

---

## 📌 STEP 13: Install Node.js & npm

```bash
sudo apt install -y nodejs npm
```

Tunggu hingga selesai.

---

## 📌 STEP 14: Verify Node.js Installation

```bash
node -v
```

```bash
npm -v
```

---

## 📌 STEP 15: Install Nginx Web Server

```bash
sudo apt install -y nginx
```

Tunggu hingga selesai.

---

## 📌 STEP 16: Install MySQL Client

```bash
sudo apt install -y mysql-client
```

---

## 📌 STEP 17: Install Certbot for SSL

```bash
sudo apt install -y certbot python3-certbot-nginx
```

---

## 📌 STEP 18: Create Application Directory

```bash
sudo mkdir -p /var/www
```

```bash
cd /var/www
```

---

## 📌 STEP 19: Clone Application Repository

```bash
sudo git clone https://github.com/ShizuyaTech/ir-ippi-app.git
```

Tunggu hingga selesai (bisa memakan waktu 2-5 menit).

---

## 📌 STEP 20: Navigate to Project Directory

```bash
cd /var/www/ir-ippi-app
```

---

## 📌 STEP 21: Install PHP Dependencies

```bash
sudo composer install --optimize-autoloader --no-dev
```

Tunggu hingga selesai (bisa memakan waktu 5-10 menit).

---

## 📌 STEP 22: Install Frontend Dependencies

```bash
sudo npm install
```

Tunggu hingga selesai (bisa memakan waktu 3-5 menit).

---

## 📌 STEP 23: Build Frontend Assets

```bash
sudo npm run build
```

Tunggu hingga selesai.

---

## 📌 STEP 24: Create .env File

```bash
sudo cp .env.example .env
```

---

## 📌 STEP 25: Generate Application Key

```bash
sudo php artisan key:generate
```

---

## 📌 STEP 26: Edit .env File with Production Values

```bash
sudo nano /var/www/ir-ippi-app/.env
```

Ubah ini:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_HOST=localhost
DB_DATABASE=ir_ippi_db
DB_USERNAME=ir_ippi_user
DB_PASSWORD=STRONG_PASSWORD_HERE

OCTANE_SERVER=swoole
OCTANE_WORKERS=4
OCTANE_TASK_WORKERS=6
OCTANE_MAX_REQUESTS=500

MAIL_MAILER=log
MAIL_FROM_ADDRESS=noreply@yourdomain.com

LOG_LEVEL=warning
```

**Cara save file di nano:**
1. Tekan `Ctrl + X`
2. Tekan `Y` (yes)
3. Tekan `Enter`

---

## 📌 STEP 27: Setup File Permissions

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

## 📌 STEP 28: Optimize Laravel Configuration

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

## 📌 STEP 29: Create MySQL Database

**Tanyakan:**
- Database password: `?`
- Atau create user baru

```bash
sudo mysql -u root -p
```

Masukkan MySQL root password.

**Di dalam MySQL prompt, jalankan satu per satu:**

```sql
CREATE DATABASE ir_ippi_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

```sql
CREATE USER 'ir_ippi_user'@'localhost' IDENTIFIED BY 'STRONG_PASSWORD_HERE';
```

```sql
GRANT ALL PRIVILEGES ON ir_ippi_db.* TO 'ir_ippi_user'@'localhost';
```

```sql
FLUSH PRIVILEGES;
```

```sql
EXIT;
```

---

## 📌 STEP 30: Run Database Migrations

```bash
cd /var/www/ir-ippi-app
```

```bash
sudo php artisan migrate --force
```

Tunggu hingga selesai.

---

## 📌 STEP 31: Create Octane Systemd Service

```bash
sudo nano /etc/systemd/system/ir-ippi-octane.service
```

Copy & paste ini:

```ini
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
```

**Simpan:** `Ctrl + X`, `Y`, `Enter`

---

## 📌 STEP 32: Create Queue Systemd Service

```bash
sudo nano /etc/systemd/system/ir-ippi-queue.service
```

Copy & paste ini:

```ini
[Unit]
Description=IR-IPPI Queue Worker
After=network.target

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

[Install]
WantedBy=multi-user.target
```

**Simpan:** `Ctrl + X`, `Y`, `Enter`

---

## 📌 STEP 33: Reload Systemd Daemon

```bash
sudo systemctl daemon-reload
```

---

## 📌 STEP 34: Enable Services to Start on Boot

```bash
sudo systemctl enable ir-ippi-octane ir-ippi-queue
```

---

## 📌 STEP 35: Start Octane Service

```bash
sudo systemctl start ir-ippi-octane
```

---

## 📌 STEP 36: Start Queue Service

```bash
sudo systemctl start ir-ippi-queue
```

---

## 📌 STEP 37: Verify Octane is Running

```bash
sudo systemctl status ir-ippi-octane
```

Output harus menampilkan: `active (running)`

---

## 📌 STEP 38: Test Octane Locally

```bash
curl http://localhost:8000
```

Output harus menampilkan HTML aplikasi (bukan error).

---

## 📌 STEP 39: Create Nginx Configuration

```bash
sudo nano /etc/nginx/sites-available/ir-ippi
```

Copy & paste ini (ganti `yourdomain.com`):

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
}
```

**Simpan:** `Ctrl + X`, `Y`, `Enter`

---

## 📌 STEP 40: Enable Nginx Site

```bash
sudo ln -s /etc/nginx/sites-available/ir-ippi /etc/nginx/sites-enabled/
```

---

## 📌 STEP 41: Test Nginx Configuration

```bash
sudo nginx -t
```

Output harus menampilkan: `successful`

---

## 📌 STEP 42: Restart Nginx

```bash
sudo systemctl restart nginx
```

---

## 📌 STEP 43: Setup SSL Certificate with Let's Encrypt

```bash
sudo certbot certonly --nginx -d yourdomain.com -d www.yourdomain.com
```

Follow prompts:
- Enter email
- Agree to terms
- Choose redirect option

---

## 📌 STEP 44: Verify SSL Certificate

```bash
sudo certbot certificates
```

Output harus menampilkan certificate untuk yourdomain.com

---

## 📌 STEP 45: Test Application

Buka di browser:

```
https://yourdomain.com
```

Aplikasi harus muncul tanpa error.

---

## 🎉 Setup Selesai!

Aplikasi Anda sekarang running di VPS dengan:
- ✅ PHP 8.3 + Swoole
- ✅ Octane performance server
- ✅ MySQL database
- ✅ Nginx reverse proxy
- ✅ SSL/HTTPS certificate
- ✅ Queue worker running
- ✅ Systemd auto-start services

---

## 📊 Verification Commands (Run these to verify everything)

### Check Services Running

```bash
sudo systemctl status ir-ippi-octane
```

```bash
sudo systemctl status ir-ippi-queue
```

```bash
sudo systemctl status nginx
```

---

### Check Logs

```bash
tail -f /var/log/ir-ippi-octane.log
```

```bash
tail -f /var/log/ir-ippi-queue.log
```

```bash
tail -f /var/log/nginx/ir-ippi-error.log
```

---

### Check Processes

```bash
ps aux | grep octane
```

```bash
ps aux | grep queue
```

---

### Test Database Connection

```bash
mysql -u ir_ippi_user -p ir_ippi_db -e "SELECT 1;"
```

---

### Monitor Performance

```bash
watch -n 2 'ps aux | grep -E "octane|queue"'
```

Press `Ctrl + C` to exit.

---

## ⚠️ If Something Goes Wrong

Check **VPS_TROUBLESHOOTING.md** untuk solusi error umum.

Quick fixes:

```bash
# Restart everything
sudo systemctl restart ir-ippi-octane ir-ippi-queue nginx mysql

# Check logs
journalctl -u ir-ippi-octane -f

# Verify .env exists
cat /var/www/ir-ippi-app/.env | head -10
```

---

## 💡 Tips

1. **Jangan copy-paste tanpa perhatian** - baca setiap command
2. **Ganti `yourdomain.com`** dengan domain Anda
3. **Ganti `STRONG_PASSWORD`** dengan password kuat
4. **Tunggu** setiap proses selesai sebelum ke step berikutnya
5. **Check logs** jika ada error

---

**Total Commands**: 45 steps  
**Total Time**: ~60 minutes  
**Difficulty**: ⭐ Easy

Setiap step adalah 1 command (atau 1 file edit). Cukup copy-paste & tunggu! 🚀
