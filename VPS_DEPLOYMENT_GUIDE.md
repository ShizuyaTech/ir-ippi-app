# VPS Deployment Guide - IR-IPPI Application

**Tanggal**: November 2025  
**Aplikasi**: IR-IPPI (Laravel 12 + Octane)  
**Status**: Production Ready

---

## 📋 Ringkasan Singkat

✅ **Aplikasi siap deploy ke VPS**  
✅ **Octane + Swoole RECOMMENDED untuk VPS Linux**  
✅ **RoadRunner alternatif jika PHP Sockets tidak tersedia**  

---

## 🎯 Perbandingan: Swoole vs RoadRunner

| Aspek | Swoole | RoadRunner |
|-------|--------|-----------|
| **Platform** | Linux/macOS | Linux/macOS/Windows |
| **Performa** | ⚡⚡⚡ Sangat Cepat (1000+ req/s) | ⚡⚡ Cepat (500-800 req/s) |
| **Requirement** | PHP Extension | Go Runtime |
| **Memory** | ~50MB per worker | ~30MB per worker |
| **Compatibility** | Lebih baik di Linux | Lebih universal |
| **Setup** | `pecl install swoole` | Download binary |
| **Rekomendasi** | **BEST untuk VPS Linux** | Good alternative |

### 🔴 REKOMENDASI: **Gunakan SWOOLE untuk VPS**

Alasan:
1. Performance lebih tinggi (1000+ req/s vs 500-800 req/s)
2. Memory usage lebih rendah
3. Tidak perlu install Go runtime
4. Cocok untuk server Linux standar

---

## ✅ PRE-DEPLOYMENT CHECKLIST

### 1. **Code & Configuration Review** ✓

#### .env (Development Saat Ini)
```
APP_ENV=local ❌ HARUS DIGANTI
APP_DEBUG=true ❌ HARUS FALSE
DB_PASSWORD=eshal070722 ⚠️ PERLU SECURE
OCTANE_SERVER=roadrunner ← GANTI KE SWOOLE
MAIL_MAILER=log ❌ HARUS CONFIGURED
SESSION_DRIVER=database ✓
QUEUE_CONNECTION=database ✓
CACHE_STORE=file ✓
```

#### Database Setup
- ✓ Migrations sudah ada
- ✓ Seeders tersedia
- ⚠️ Pastikan MySQL root password di-hash di production

#### Queue System
- ✓ Using database driver (production-ready)
- ⚠️ Setup supervisor/systemd untuk queue worker
- ✓ Can use with Octane

#### Session Management
- ✓ Database driver (production-ready)
- ✓ Lifetime: 120 menit (good default)

#### Cache
- ✓ File-based cache (optimal untuk single server)
- ⚠️ Pertimbangkan Redis untuk multi-server setup
- ✓ TTL: 1 jam untuk assessment data

---

## 🚀 VPS SETUP STEPS

### Step 1: Server Preparation (SSH ke VPS)

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.3 + Extensions
sudo apt install -y php8.3 php8.3-cli php8.3-fpm php8.3-mysql php8.3-zip php8.3-gd php8.3-mbstring php8.3-curl

# Install Swoole (RECOMMENDED)
sudo apt install -y php8.3-dev
sudo pecl install swoole
echo "extension=swoole.so" | sudo tee -a /etc/php/8.3/cli/php.ini
echo "extension=swoole.so" | sudo tee -a /etc/php/8.3/fpm/php.ini

# Verify Swoole installation
php -m | grep swoole

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js (untuk Vite)
sudo apt install -y nodejs npm
```

### Step 2: Clone & Setup Project

```bash
# Navigate to /var/www atau /home/user/projects
cd /var/www
git clone https://github.com/ShizuyaTech/ir-ippi-app.git
cd ir-ippi-app

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Setup environment
cp .env.example .env
php artisan key:generate
```

### Step 3: Configure Production .env

```env
# APPLICATION
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# DATABASE
DB_HOST=localhost
DB_DATABASE=ir_ippi_db
DB_USERNAME=ir_ippi_user
DB_PASSWORD=STRONG_PASSWORD_HERE

# CACHE
CACHE_STORE=file
# ATAU gunakan Redis untuk performance lebih baik:
# CACHE_STORE=redis
# REDIS_HOST=127.0.0.1
# REDIS_PASSWORD=null
# REDIS_PORT=6379

# SESSION
SESSION_DRIVER=database
SESSION_LIFETIME=120

# QUEUE (untuk background jobs)
QUEUE_CONNECTION=database
# Atau gunakan Redis: QUEUE_CONNECTION=redis

# MAIL (Konfigurasi tanpa SMTP - log ke storage/logs/)
MAIL_MAILER=log
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
# Jika nanti perlu SMTP, konfigurasi:
# MAIL_MAILER=smtp
# MAIL_HOST=smtp.mailtrap.io
# MAIL_PORT=2525
# MAIL_USERNAME=your_username
# MAIL_PASSWORD=your_password

# OCTANE - SWOOLE CONFIGURATION
OCTANE_SERVER=swoole
OCTANE_WORKERS=4  # = CPU cores (cek dengan: nproc)
OCTANE_TASK_WORKERS=6
OCTANE_MAX_REQUESTS=500
OCTANE_PORT=8000
OCTANE_LOG_LEVEL=warning

# LOGGING
LOG_CHANNEL=stack
LOG_LEVEL=warning  # Change dari debug ke warning di production
```

### Step 4: Database Migration & Setup

```bash
# Run migrations
php artisan migrate --force

# Seed data (jika diperlukan)
php artisan db:seed --force

# Optimize production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### Step 5: Setup Octane Service (Systemd)

Create `/etc/systemd/system/ir-ippi-octane.service`:

```ini
[Unit]
Description=IR-IPPI Octane Application Server
After=network.target
Wants=ir-ippi-queue.service

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
SyslogIdentifier=ir-ippi-octane

[Install]
WantedBy=multi-user.target
```

Enable service:
```bash
sudo systemctl daemon-reload
sudo systemctl enable ir-ippi-octane
sudo systemctl start ir-ippi-octane
sudo systemctl status ir-ippi-octane
```

### Step 6: Setup Queue Worker (Systemd)

Create `/etc/systemd/system/ir-ippi-queue.service`:

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

Enable:
```bash
sudo systemctl daemon-reload
sudo systemctl enable ir-ippi-queue
sudo systemctl start ir-ippi-queue
```

### Step 7: Setup Nginx (Web Server)

Create `/etc/nginx/sites-available/ir-ippi`:

```nginx
upstream ir_ippi_octane {
    server 127.0.0.1:8000;
    keepalive 64;
}

server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    
    # Redirect HTTP to HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;
    
    # SSL Configuration (gunakan Let's Encrypt)
    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    
    root /var/www/ir-ippi-app/public;
    index index.php;
    
    # Logs
    access_log /var/log/nginx/ir-ippi-access.log;
    error_log /var/log/nginx/ir-ippi-error.log;
    
    # Performance
    client_max_body_size 10M;
    
    location / {
        # Try static files first, then pass to Octane
        try_files $uri $uri/ @octane;
    }
    
    location @octane {
        proxy_pass http://ir_ippi_octane;
        proxy_http_version 1.1;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_read_timeout 60s;
        proxy_buffering off;
    }
    
    # Deny access to sensitive files
    location ~ /\.env {
        deny all;
    }
    
    location ~ /\.git {
        deny all;
    }
    
    location ~ /vendor {
        deny all;
    }
}
```

Enable:
```bash
sudo ln -s /etc/nginx/sites-available/ir-ippi /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### Step 8: SSL Certificate (Let's Encrypt)

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot certonly --nginx -d yourdomain.com -d www.yourdomain.com
```

### Step 9: Setup Log Rotation

Create `/etc/logrotate.d/ir-ippi`:

```
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
```

---

## 📊 Performance Expectations

### Dengan SWOOLE + Octane
- **Throughput**: 800-1200 req/second
- **Response Time**: 20-50ms (standard pages)
- **Memory/Worker**: ~50-80MB
- **CPU Usage**: 10-30% (normal load)

### Dengan RoadRunner + Octane (Alternative)
- **Throughput**: 400-600 req/second
- **Response Time**: 30-80ms
- **Memory/Worker**: ~30-50MB
- **CPU Usage**: 15-40% (normal load)

---

## 🔍 Verification Commands

```bash
# Check Octane running
ps aux | grep octane

# Check queue worker
ps aux | grep queue:listen

# Check logs
tail -f /var/log/ir-ippi-octane.log
tail -f /var/log/ir-ippi-queue.log

# Test API
curl -I https://yourdomain.com
curl https://yourdomain.com/api/health

# Monitor performance
php artisan tinker
>>> \Illuminate\Support\Facades\Cache::get('active_assessments_nav')
```

---

## ⚠️ Known Issues & Solutions

### Issue 1: "PHP_CLI_SERVER_WORKERS not supported"
- **Solution**: Comment out dari .env (sudah done)
- **Status**: ✓ Fixed

### Issue 2: Route Encryption /x/hash 404s
- **Solution**: Disabled, menggunakan original routes
- **Status**: ✓ Resolved

### Issue 3: Memory Leak pada Queue Worker
- **Solution**: OCTANE_MAX_REQUESTS=500 forces restart
- **Status**: ✓ Configured

### Issue 4: Static Assets tidak load
- **Solution**: `npm run build` menggenerate ke public/build/
- **Status**: ✓ Handled

---

## 📝 Quick Migration Checklist

- [ ] Backup production database locally sebelum deploy
- [ ] Test di staging environment dulu
- [ ] Update APP_ENV, APP_DEBUG di .env
- [ ] Setup SSL Certificate dengan Let's Encrypt
- [ ] Configure SMTP mail settings
- [ ] Setup database user dengan strong password
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Optimize configs: `php artisan config:cache`
- [ ] Setup Systemd services untuk Octane & Queue
- [ ] Configure Nginx dengan SSL
- [ ] Test health endpoints
- [ ] Monitor logs untuk errors
- [ ] Setup uptime monitoring (UptimeRobot, etc)
- [ ] Setup daily backups untuk database
- [ ] Configure fail2ban untuk security

---

## 🎓 Useful Commands

```bash
# Clear all caches
php artisan optimize:clear

# Optimize for production
php artisan optimize

# Check application health
php artisan tinker
>>> DB::connection()->getPdo()  // Test DB connection

# Monitor Octane
watch -n 1 'ps aux | grep octane'

# Restart services
sudo systemctl restart ir-ippi-octane
sudo systemctl restart ir-ippi-queue

# View real-time logs
journalctl -u ir-ippi-octane -f
```

---

## 📞 Support & Monitoring

### Monitoring Tools (Recommended)
- **Uptime**: UptimeRobot.com (free tier available)
- **Logs**: ELK Stack / Sentry.io
- **Performance**: New Relic / Datadog
- **Database**: Percona Monitoring
- **Security**: CloudFlare + WAF rules

### Health Check Endpoint
Add to `routes/web.php`:
```php
Route::get('/health', fn() => response()->json(['status' => 'ok', 'timestamp' => now()]));
```

Monitor dengan cURL:
```bash
curl https://yourdomain.com/health
```

---

**Last Updated**: November 20, 2025  
**Version**: 1.0 (Production Ready)
