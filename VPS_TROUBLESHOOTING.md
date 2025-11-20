# 🔧 VPS Setup Troubleshooting Guide

**Status**: Perbaikan untuk error yang sering terjadi saat setup  
**Last Updated**: November 20, 2025

---

## ❌ Error 1: Swoole Installation - Missing libcurl

### Error Message
```
configure: error: Package requirements (libcurl >= 7.56.0) were not met:
Package 'libcurl', required by 'virtual:world', not found
```

### Root Cause
Dependencies yang diperlukan untuk compile Swoole belum terinstall.

### ✅ Solution

**Update setup-vps.sh** sudah menyertakan semua dependencies:

```bash
apt install -y \
  autoconf \
  automake \
  libtool \
  pkg-config \
  libcurl4-openssl-dev \
  libbrotli-dev \
  libssl-dev \
  zlib1g-dev
```

**Atau install manual:**
```bash
sudo apt update
sudo apt install -y \
  build-essential \
  autoconf \
  automake \
  libtool \
  pkg-config \
  libcurl4-openssl-dev \
  libbrotli-dev \
  libssl-dev \
  zlib1g-dev \
  php8.3-dev
```

**Kemudian coba install Swoole lagi:**
```bash
sudo pecl install swoole
```

---

## ❌ Error 2: Swoole Not Loading - PHP Startup Warning

### Error Message
```
PHP Warning: PHP Startup: Unable to load dynamic library 'swoole.so'
(tried: /usr/lib/php/20230831/swoole.so)
```

### Root Cause
1. Swoole extension belum ditambahkan ke php.ini
2. Swoole compilation gagal (error sebelumnya)
3. Extension path salah

### ✅ Solution

**Step 1: Verify Swoole binary exists**
```bash
find /usr -name "swoole.so" 2>/dev/null
```

**Step 2: Add extension to php.ini**
```bash
# For CLI
echo "extension=swoole.so" | sudo tee -a /etc/php/8.3/cli/php.ini

# For FPM
echo "extension=swoole.so" | sudo tee -a /etc/php/8.3/fpm/php.ini
```

**Step 3: Verify installation**
```bash
php -m | grep swoole
```

Output harus menampilkan: `swoole`

**Step 4: Restart PHP-FPM**
```bash
sudo systemctl restart php8.3-fpm
```

---

## ❌ Error 3: libbrotli Package Not Found

### Error Message
```
configure: error: Package requirements (libbrotlienc) were not met:
Package 'libbrotlienc', required by 'virtual:world', not found
```

### ✅ Solution

**Install brotli development files:**
```bash
sudo apt install -y libbrotli-dev

# Verify installation
dpkg -l | grep brotli
```

**Then retry Swoole installation:**
```bash
sudo pecl install swoole
```

---

## ❌ Error 4: Git Clone Failed - Authentication

### Error Message
```
fatal: could not read Username for 'https://github.com': No such file or directory
```

### ✅ Solution - Option A: Using HTTPS with Token
```bash
git clone https://github.com/ShizuyaTech/ir-ippi-app.git
# When prompted for password, use GitHub personal access token
```

**Generate Personal Access Token:**
1. Go to: https://github.com/settings/tokens
2. Click "Generate new token"
3. Select "repo" scope
4. Copy token
5. Use as password in git

### ✅ Solution - Option B: Using SSH Key
```bash
# Generate SSH key
ssh-keygen -t ed25519 -C "your_email@example.com"

# Copy public key to GitHub
cat ~/.ssh/id_ed25519.pub

# Add to GitHub: Settings > SSH and GPG keys > New SSH key

# Then clone with SSH
git clone git@github.com:ShizuyaTech/ir-ippi-app.git
```

---

## ❌ Error 5: Package Manager Lock - "Could not get lock"

### Error Message
```
E: Could not get lock /var/lib/apt/lists/lock
```

### Root Cause
Apt sedang digunakan oleh proses lain atau belum selesai update.

### ✅ Solution

**Option A: Wait & Retry**
```bash
# Wait 10 minutes dan retry
sudo apt update
sudo apt install -y package-name
```

**Option B: Force release lock**
```bash
sudo rm /var/lib/apt/lists/lock
sudo rm /var/cache/apt/archives/lock
sudo apt update
```

**Option C: Kill running apt processes**
```bash
ps aux | grep apt
sudo kill -9 PID_HERE
sudo apt update
```

---

## ❌ Error 6: PHP Extensions Not Found

### Error Message
```
E: Package php8.3-dev is not available
```

### ✅ Solution

**Check PHP version installed:**
```bash
php -v
```

**Update package lists:**
```bash
sudo apt update
sudo apt upgrade -y
```

**Install correct PHP version dev:**
```bash
# Check available versions
apt search php | grep php.*-dev

# Install matching version
sudo apt install -y php8.3-dev
```

---

## ❌ Error 7: Composer Install - Out of Memory

### Error Message
```
PHP Fatal error: Allowed memory size exhausted
```

### ✅ Solution

**Increase memory limit temporarily:**
```bash
php -d memory_limit=-1 /usr/local/bin/composer install --optimize-autoloader --no-dev
```

**Or update php.ini permanently:**
```bash
sudo nano /etc/php/8.3/cli/php.ini

# Find: memory_limit = 128M
# Change to: memory_limit = 512M
# Save & exit
```

---

## ❌ Error 8: Npm Build Failed

### Error Message
```
npm ERR! code ERESOLVE
npm ERR! ERESOLVE unable to resolve dependency tree
```

### ✅ Solution

**Option A: Clean install**
```bash
rm -rf node_modules package-lock.json
npm install
npm run build
```

**Option B: Force resolve**
```bash
npm install --legacy-peer-deps
npm run build
```

**Option C: Use Node 18 LTS**
```bash
sudo apt install -y nodejs=18.*

npm install
npm run build
```

---

## ❌ Error 9: Systemd Service Failed

### Error Message
```
Unit ir-ippi-octane.service entered failed state.
```

### ✅ Solution

**Check service status:**
```bash
sudo systemctl status ir-ippi-octane
sudo journalctl -u ir-ippi-octane -n 50
```

**Check logs:**
```bash
tail -f /var/log/ir-ippi-octane.log
tail -f /var/log/ir-ippi-octane-error.log
```

**Verify .env exists:**
```bash
ls -la /var/www/ir-ippi-app/.env
```

**Verify permissions:**
```bash
sudo chown -R www-data:www-data /var/www/ir-ippi-app
sudo chmod -R 775 /var/www/ir-ippi-app/storage
```

**Restart service:**
```bash
sudo systemctl daemon-reload
sudo systemctl restart ir-ippi-octane
```

---

## ❌ Error 10: Database Connection Failed

### Error Message
```
SQLSTATE[HY000] [1045] Access denied for user 'ir_ippi_user'@'localhost'
```

### ✅ Solution

**Check MySQL is running:**
```bash
sudo systemctl status mysql
sudo systemctl start mysql
```

**Create database & user:**
```bash
sudo mysql -u root -p

# In MySQL:
CREATE DATABASE ir_ippi_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'ir_ippi_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL PRIVILEGES ON ir_ippi_db.* TO 'ir_ippi_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

**Test connection:**
```bash
mysql -u ir_ippi_user -p ir_ippi_db -h localhost
```

**Update .env with correct credentials:**
```bash
nano /var/www/ir-ippi-app/.env

DB_USERNAME=ir_ippi_user
DB_PASSWORD=strong_password
DB_DATABASE=ir_ippi_db
DB_HOST=localhost
```

**Run migrations:**
```bash
cd /var/www/ir-ippi-app
php artisan migrate --force
```

---

## ❌ Error 11: Nginx 502 Bad Gateway

### Error Message
```
502 Bad Gateway
```

### ✅ Solution

**Check Octane is running:**
```bash
ps aux | grep octane
curl http://localhost:8000
```

**If not running, start it:**
```bash
sudo systemctl start ir-ippi-octane
sudo systemctl status ir-ippi-octane
```

**Check Nginx configuration:**
```bash
sudo nginx -t
sudo systemctl restart nginx
```

**Check logs:**
```bash
tail -f /var/log/nginx/ir-ippi-error.log
tail -f /var/log/ir-ippi-octane.log
```

---

## ❌ Error 12: File Permissions Issues

### Error Message
```
Permission denied opening '/var/www/ir-ippi-app/storage/logs/laravel.log'
```

### ✅ Solution

**Fix ownership:**
```bash
sudo chown -R www-data:www-data /var/www/ir-ippi-app
```

**Fix permissions:**
```bash
sudo chmod -R 775 /var/www/ir-ippi-app/storage
sudo chmod -R 775 /var/www/ir-ippi-app/bootstrap/cache
sudo chmod -R 755 /var/www/ir-ippi-app/public
```

**Verify:**
```bash
ls -la /var/www/ir-ippi-app/storage/logs/
```

---

## ✅ Verification Steps

Setelah setup selesai, jalankan checklist ini:

```bash
# 1. Check PHP & Swoole
php -v
php -m | grep swoole

# 2. Check Composer
composer --version

# 3. Check Node.js
node -v
npm -v

# 4. Check MySQL
mysql --version
mysql -u ir_ippi_user -p -e "SELECT 1;"

# 5. Check Nginx
sudo nginx -t
sudo systemctl status nginx

# 6. Check Octane service
sudo systemctl status ir-ippi-octane
ps aux | grep octane

# 7. Check Queue service
sudo systemctl status ir-ippi-queue
ps aux | grep queue

# 8. Test application
curl http://localhost:8000
curl https://yourdomain.com

# 9. Check Laravel logs
tail -f /var/www/ir-ippi-app/storage/logs/laravel.log
```

---

## 🆘 Still Having Issues?

**Common debugging steps:**

```bash
# 1. Check all service logs
journalctl -xe

# 2. Check system resources
free -h
df -h
ps aux | head -20

# 3. Check network
sudo netstat -tlnp | grep 8000
sudo netstat -tlnp | grep 80

# 4. Check file integrity
ls -la /var/www/ir-ippi-app/
php artisan config:show app

# 5. Restart everything
sudo systemctl restart php8.3-fpm
sudo systemctl restart nginx
sudo systemctl restart mysql
sudo systemctl restart ir-ippi-octane ir-ippi-queue
```

---

## 📞 Quick Reference Commands

```bash
# Service management
sudo systemctl restart ir-ippi-octane
sudo systemctl restart ir-ippi-queue
sudo systemctl restart nginx
sudo systemctl restart mysql

# Log viewing
tail -f /var/log/ir-ippi-octane.log
tail -f /var/log/ir-ippi-queue.log
tail -f /var/log/nginx/ir-ippi-error.log
journalctl -u ir-ippi-octane -f

# Database
sudo mysql -u root -p
mysql -u ir_ippi_user -p ir_ippi_db

# Application
cd /var/www/ir-ippi-app
php artisan tinker
php artisan migrate --rollback
php artisan db:seed
```

---

**Last Updated**: November 20, 2025  
**Version**: 1.0 - Comprehensive Troubleshooting Guide
