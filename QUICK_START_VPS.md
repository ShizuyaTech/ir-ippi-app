# 🚀 QUICK START VPS - Copy & Paste Commands

**Status**: ✅ Production Ready  
**Waktu Setup**: ~30-45 menit (dengan automated script)  
**Kesulitan**: ⭐ Easy (semuanya sudah automated)

---

## ⚠️ PENTING SEBELUM MULAI

**Pastikan sudah punya:**
- [ ] VPS Linux (Ubuntu 22.04 LTS recommended)
- [ ] SSH access ke VPS
- [ ] Domain sudah pointing ke IP VPS
- [ ] Database credentials (atau buat baru)

---

## 🎯 Setup Options

### **OPTION A: Automated Setup (RECOMMENDED) ⚡**

Paling cepat & mudah - tinggal copy 1 command!

```bash
# SSH ke VPS
ssh root@your.vps.ip

# Download & jalankan automated setup script
curl -O https://raw.githubusercontent.com/ShizuyaTech/ir-ippi-app/main/setup-vps.sh
chmod +x setup-vps.sh
./setup-vps.sh
```

**Yang sudah dilakukan script:**
- ✅ Update system & install PHP 8.3 + Swoole
- ✅ Install Composer, Node.js, Nginx, SSL
- ✅ Clone project dari GitHub
- ✅ Install dependencies
- ✅ Create systemd services (Octane + Queue)
- ✅ Setup file permissions

**After script finished:**
```bash
# 1. Edit production .env
nano /var/www/ir-ippi-app/.env

# Ganti ini (paling penting):
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
DB_HOST=localhost
DB_DATABASE=ir_ippi_db
DB_USERNAME=ir_ippi_user
DB_PASSWORD=STRONG_PASSWORD  ← Generate strong password!
OCTANE_SERVER=swoole
MAIL_MAILER=log  ← Menggunakan log (tanpa SMTP)
LOG_LEVEL=warning

# 2. Run database migration
cd /var/www/ir-ippi-app
php artisan migrate --force

# 3. Optimize application
php artisan config:cache
php artisan route:cache

# 4. Start services
systemctl start ir-ippi-octane
systemctl start ir-ippi-queue

# 5. Verify running
systemctl status ir-ippi-octane
curl http://localhost:8000
```

---

### **OPTION B: Manual Setup (Step-by-Step)**

Jika ingin kontrol penuh atau script error.

```bash
# 1. SSH & Update
ssh root@your.vps.ip
sudo apt update && sudo apt upgrade -y

# 2. Install PHP 8.3 + Extensions
sudo apt install -y php8.3 php8.3-cli php8.3-fpm php8.3-mysql \
  php8.3-zip php8.3-gd php8.3-mbstring php8.3-curl php8.3-xml \
  php8.3-bcmath php8.3-dev

# 3. Install Swoole (IMPORTANT!)
sudo pecl install swoole
echo "extension=swoole.so" | sudo tee -a /etc/php/8.3/cli/php.ini
echo "extension=swoole.so" | sudo tee -a /etc/php/8.3/fpm/php.ini

# Verify
php -m | grep swoole

# 4. Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
composer --version

# 5. Install Node.js
sudo apt install -y nodejs npm

# 6. Install Nginx + SSL
sudo apt install -y nginx certbot python3-certbot-nginx

# 7. Clone Project
cd /var/www
git clone https://github.com/ShizuyaTech/ir-ippi-app.git
cd ir-ippi-app

# 8. Install Dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# 9. Setup .env
cp .env.example .env
nano .env  # Edit dengan production values

# 10. Generate Key
php artisan key:generate

# 11. Database Migration
php artisan migrate --force

# 12. Optimize
php artisan config:cache
php artisan route:cache

# 13. File Permissions
sudo chown -R www-data:www-data /var/www/ir-ippi-app
sudo chmod -R 775 storage bootstrap/cache

# 14. Create Systemd Service - Octane
sudo tee /etc/systemd/system/ir-ippi-octane.service > /dev/null << 'EOF'
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

# 15. Create Systemd Service - Queue
sudo tee /etc/systemd/system/ir-ippi-queue.service > /dev/null << 'EOF'
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
EOF

# 16. Enable & Start Services
sudo systemctl daemon-reload
sudo systemctl enable ir-ippi-octane ir-ippi-queue
sudo systemctl start ir-ippi-octane ir-ippi-queue
sudo systemctl status ir-ippi-octane

# 17. Setup Nginx (buat file konfigurasi)
# Lihat bagian "NGINX CONFIGURATION" di bawah

# 18. Setup SSL
sudo certbot certonly --nginx -d yourdomain.com -d www.yourdomain.com

# 19. Test
curl http://localhost:8000
```

---

## 📋 NGINX CONFIGURATION

Buat file `/etc/nginx/sites-available/ir-ippi`:

```bash
sudo nano /etc/nginx/sites-available/ir-ippi
```

Copy & paste ini:

```nginx
upstream ir_ippi_octane {
    server 127.0.0.1:8000;
    keepalive 64;
}

# HTTP to HTTPS redirect
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    return 301 https://$server_name$request_uri;
}

# HTTPS (Main)
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

    # Deny access to sensitive files
    location ~ /\.env { deny all; }
    location ~ /\.git { deny all; }
    location ~ /vendor { deny all; }
}
```

Enable Nginx:
```bash
sudo ln -s /etc/nginx/sites-available/ir-ippi /etc/nginx/sites-enabled/
sudo nginx -t  # Test syntax
sudo systemctl restart nginx
```

---

## ✅ POST-DEPLOYMENT CHECKLIST

Setelah setup selesai, pastikan:

```bash
# 1. Check services running
systemctl status ir-ippi-octane
systemctl status ir-ippi-queue

# 2. Check logs
tail -f /var/log/ir-ippi-octane.log
tail -f /var/log/ir-ippi-queue.log
tail -f /var/log/nginx/ir-ippi-error.log

# 3. Test application
curl https://yourdomain.com
curl https://yourdomain.com/health  # Should return JSON

# 4. Check database connection
ssh to vps
cd /var/www/ir-ippi-app
php artisan tinker
> DB::connection()->getPdo()
> exit()

# 5. Monitor performance
watch -n 1 'ps aux | grep -E "octane|queue"'

# 6. Check disk space
df -h

# 7. Check memory
free -h

# 8. Setup auto-backups (optional)
# Crontab untuk daily backup:
crontab -e
# Add: 0 2 * * * mysqldump -u root -p ir_ippi_db > /backups/ir-ippi-$(date +\%Y\%m\%d).sql
```

---

## 🔧 Production .env Template (Copy & Edit)

```env
# APPLICATION
APP_NAME="IR-IPPI"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:KEEP_EXISTING_VALUE
APP_URL=https://yourdomain.com

# DATABASE - Edit ini!
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=ir_ippi_db
DB_USERNAME=ir_ippi_user
DB_PASSWORD=GENERATE_STRONG_PASSWORD

# CACHE
CACHE_STORE=file

# SESSION
SESSION_DRIVER=database
SESSION_LIFETIME=120

# QUEUE
QUEUE_CONNECTION=database

# MAIL
MAIL_MAILER=log  # Log emails ke storage/logs/ (tanpa SMTP)
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
# Jika nanti ingin pakai SMTP, ubah ke:
# MAIL_MAILER=smtp
# MAIL_HOST=smtp.mailtrap.io
# MAIL_PORT=2525
# MAIL_USERNAME=your_username
# MAIL_PASSWORD=your_password

# OCTANE
OCTANE_SERVER=swoole
OCTANE_WORKERS=4
OCTANE_TASK_WORKERS=6
OCTANE_MAX_REQUESTS=500
OCTANE_PORT=8000

# LOGGING
LOG_CHANNEL=stack
LOG_LEVEL=warning
```

---

## 🚨 Common Issues & Quick Fixes

| Error | Solution |
|-------|----------|
| **502 Bad Gateway** | `systemctl restart ir-ippi-octane` |
| **Swoole not found** | Verify: `php -m \| grep swoole` |
| **Database error** | Check credentials di .env, test: `mysql -u user -p` |
| **Nginx 404** | Check Nginx config, test: `sudo nginx -t` |
| **Mail not sending** | Test SMTP credentials di .env |
| **Assets not loading** | Run: `npm run build` & check `public/build/` |
| **High memory usage** | Reduce OCTANE_MAX_REQUESTS di .env |
| **Slow response** | Check logs: `tail -f /var/log/ir-ippi-octane.log` |

---

## 📞 Useful Commands

```bash
# Monitoring
systemctl status ir-ippi-octane
journalctl -u ir-ippi-octane -f
tail -f /var/log/ir-ippi-*.log

# Restart services
systemctl restart ir-ippi-octane
systemctl restart ir-ippi-queue

# Database
cd /var/www/ir-ippi-app
php artisan tinker
php artisan migrate --rollback
php artisan db:seed

# Clearing caches
php artisan optimize:clear

# Performance check
ps aux | grep octane
ps aux | grep queue:listen
```

---

## 📊 Expected Results

Setelah setup berhasil:

```
✅ Application running at https://yourdomain.com
✅ Octane workers: 4 (dapat dilihat dengan: ps aux | grep octane)
✅ Queue worker: 1 (dapat dilihat dengan: ps aux | grep queue)
✅ Performance: 800-1200 req/second
✅ Response time: 20-50ms
✅ Memory usage: ~200-300MB
✅ SSL certificate: Valid (https)
```

---

## 💡 Tips Penting

1. **Always backup database** sebelum deploy
2. **Test email settings** sebelum production
3. **Monitor logs** 24 jam pertama setelah deploy
4. **Use strong passwords** untuk database & SMTP
5. **Keep backups** di tempat yang aman
6. **Monitor disk space** - setup alerts jika < 10% tersisa
7. **Update SSL certificate** sebelum expired (21 hari before)

---

## 🎯 Ringkas

**Paling mudah**: Gunakan Option A (automated script) - cukup 1 command!  
**Paling fleksibel**: Gunakan Option B (manual) - untuk kontrol penuh.  
**Dokumentasi lengkap**: Lihat `VPS_DEPLOYMENT_GUIDE.md` untuk detail.  

**Next Step**: Jalankan setup script atau manual commands sesuai pilihan Anda.

---

**Last Updated**: November 20, 2025  
**Status**: ✅ READY TO DEPLOY
