# 🔍 SWOOLE vs ROADRUNNER - Which One Should You Use?

**Status**: ✅ Decision Guide untuk memilih server yang tepat  
**Created**: November 21, 2025

---

## ⚡ Quick Answer

### **Untuk Ubuntu 24.04 VPS Linux:**
👉 **GUNAKAN: SWOOLE** ✅

**Alasan:**
- ✅ Performa tertinggi (1000+ req/sec)
- ✅ Memory efficient (~50MB per worker)
- ✅ Setup lebih mudah
- ✅ Built-in PHP extension (pecl install)
- ✅ Best practice untuk Linux production
- ✅ Tidak perlu Go runtime

### **Untuk Windows Development:**
👉 **GUNAKAN: ROADRUNNER** ✅

**Alasan:**
- ✅ Works on Windows natively
- ✅ Lebih ringan (~30MB per worker)
- ✅ Go runtime berjalan stabil
- ✅ Development environment standard

---

## 📊 Perbandingan Detail

| Aspek | Swoole | RoadRunner |
|-------|--------|-----------|
| **Platform** | Linux/macOS | Linux/macOS/Windows |
| **Performa** | ⭐⭐⭐ 1000+ req/s | ⭐⭐ 500-800 req/s |
| **Memory** | ~50MB/worker | ~30MB/worker |
| **Setup** | `pecl install swoole` | Download Go binary |
| **Dependencies** | PHP extension | Go runtime |
| **Security** | Native PHP | Separate binary |
| **Reliability** | ⭐⭐⭐ Proven | ⭐⭐⭐ Stable |
| **Recommendation** | **Linux Production** | Windows/Development |

---

## 🎯 Situation-Based Guide

### **Situation 1: Ubuntu 24.04 VPS Server**
```
✅ GUNAKAN SWOOLE
   - OCTANE_SERVER=swoole
   - Install: sudo pecl install swoole
   - Systemd: artisan octane:start --server=swoole
```

### **Situation 2: Windows Development Machine**
```
✅ GUNAKAN ROADRUNNER
   - OCTANE_SERVER=roadrunner
   - Install: php artisan octane:install --server=roadrunner
   - Run: php artisan octane:start --server=roadrunner
```

### **Situation 3: Mac/Linux Development**
```
✅ GUNAKAN SWOOLE (RECOMMENDED)
   Atau RoadRunner tergantung preferensi
```

### **Situation 4: Production (Any Linux)**
```
✅ GUNAKAN SWOOLE
   - Better performance
   - Lower memory
   - Simpler deployment
```

---

## 🚀 How to Switch Between Them

### **From RoadRunner to Swoole (Ubuntu 24.04)**

**Step 1: Install Swoole Extension**
```bash
sudo apt install -y libcurl4-openssl-dev libbrotli-dev libssl-dev zlib1g-dev
sudo pecl install swoole
echo "extension=swoole.so" | sudo tee -a /etc/php/8.3/cli/php.ini
echo "extension=swoole.so" | sudo tee -a /etc/php/8.3/fpm/php.ini
```

**Step 2: Verify Swoole**
```bash
php -m | grep swoole
```

**Step 3: Update .env**
```bash
sudo nano /var/www/ir-ippi-app/.env

# Change:
OCTANE_SERVER=roadrunner
# To:
OCTANE_SERVER=swoole
```

**Step 4: Restart Service**
```bash
sudo systemctl restart ir-ippi-octane
sudo systemctl status ir-ippi-octane
```

**Step 5: Verify**
```bash
ps aux | grep octane
curl http://localhost:8000
```

---

### **From Swoole to RoadRunner**

**Step 1: Install RoadRunner**
```bash
php artisan octane:install --server=roadrunner
```

**Step 2: Update .env**
```bash
sudo nano /var/www/ir-ippi-app/.env

# Change:
OCTANE_SERVER=swoole
# To:
OCTANE_SERVER=roadrunner
```

**Step 3: Update Systemd Service**
```bash
sudo nano /etc/systemd/system/ir-ippi-octane.service

# Change:
ExecStart=/usr/bin/php /var/www/ir-ippi-app/artisan octane:start --server=swoole --workers=4 --task-workers=6 --port=8000

# To:
ExecStart=/usr/bin/php /var/www/ir-ippi-app/artisan octane:start --server=roadrunner --workers=4 --task-workers=6 --port=8000
```

**Step 4: Reload & Restart**
```bash
sudo systemctl daemon-reload
sudo systemctl restart ir-ippi-octane
sudo systemctl status ir-ippi-octane
```

---

## 🔧 Current Configuration Status

### **Local Development (.env)**
```env
OCTANE_SERVER=roadrunner  ← Windows optimal
```

### **Ubuntu 24.04 Setup Guide (UBUNTU_24_SETUP.md)**
```env
OCTANE_SERVER=swoole  ← Linux optimal
```

### **VPS Deployment Guide (VPS_DEPLOYMENT_GUIDE.md)**
```env
OCTANE_SERVER=swoole  ← Recommended untuk Linux
```

### **config/octane.php Default**
```php
'server' => env('OCTANE_SERVER', 'roadrunner'),
```

---

## ✅ Recommended Setup for Ubuntu 24.04

**UNTUK SETUP BARU DI UBUNTU 24.04, IKUTI INI:**

### **Option A: Full Swoole Setup (RECOMMENDED)**

**1. Ikuti UBUNTU_24_SETUP.md steps 1-12 (Swoole installation)**

**2. .env harus:**
```env
OCTANE_SERVER=swoole
OCTANE_WORKERS=4
OCTANE_TASK_WORKERS=6
OCTANE_MAX_REQUESTS=500
OCTANE_PORT=8000
```

**3. Systemd service harus:**
```ini
ExecStart=/usr/bin/php /var/www/ir-ippi-app/artisan octane:start --server=swoole --workers=4 --task-workers=6 --port=8000
```

**4. Verify:**
```bash
php -m | grep swoole
sudo systemctl status ir-ippi-octane
ps aux | grep octane
curl http://localhost:8000
```

---

### **Option B: RoadRunner Setup (Alternative)**

**1. Install RoadRunner:**
```bash
cd /var/www/ir-ippi-app
php artisan octane:install --server=roadrunner
```

**2. .env harus:**
```env
OCTANE_SERVER=roadrunner
OCTANE_WORKERS=4
OCTANE_TASK_WORKERS=6
OCTANE_MAX_REQUESTS=500
OCTANE_PORT=8000
```

**3. Systemd service harus:**
```ini
ExecStart=/usr/bin/php /var/www/ir-ippi-app/artisan octane:start --server=roadrunner --workers=4 --task-workers=6 --port=8000
```

**4. Verify:**
```bash
sudo systemctl status ir-ippi-octane
ps aux | grep octane
curl http://localhost:8000
```

---

## 🆘 Troubleshooting: Cannot Start Octane

### **Error: octane:start not recognized**
```
Solution: Install Octane first
php artisan octane:install --server=swoole
(or --server=roadrunner)
```

### **Error: Port 8000 already in use**
```
Check what's using port:
sudo lsof -i :8000
sudo netstat -tlnp | grep 8000

Kill process:
sudo kill -9 PID
```

### **Error: Swoole extension not found**
```
Install it:
sudo pecl install swoole

Add to php.ini:
echo "extension=swoole.so" | sudo tee -a /etc/php/8.3/cli/php.ini
echo "extension=swoole.so" | sudo tee -a /etc/php/8.3/fpm/php.ini

Verify:
php -m | grep swoole
```

### **Error: RoadRunner binary not found**
```
Install it:
php artisan octane:install --server=roadrunner

Then start:
php artisan octane:start --server=roadrunner
```

### **Error: Service fails to start**
```
Check logs:
sudo journalctl -u ir-ippi-octane -n 50
tail -f /var/log/ir-ippi-octane.log
tail -f /var/log/ir-ippi-octane-error.log

Check .env:
cat /var/www/ir-ippi-app/.env | grep OCTANE

Check permissions:
sudo chown -R www-data:www-data /var/www/ir-ippi-app
sudo chmod -R 775 /var/www/ir-ippi-app/storage
```

---

## 📋 Checklist: Getting Octane to Work

- [ ] **OS Installed**: Ubuntu 24.04
- [ ] **PHP 8.3 Installed**: `php -v` shows 8.3.x
- [ ] **Extensions Installed**: `php -m | grep mysql`
- [ ] **Choice Made**: Swoole or RoadRunner?
- [ ] **Server Installed**: 
  - Swoole: `php -m | grep swoole`
  - RoadRunner: Check `rr` binary
- [ ] **.env Updated**: OCTANE_SERVER set correctly
- [ ] **Octane Installed**: `php artisan octane:install --server=swoole` (or roadrunner)
- [ ] **Systemd Service Created**: `/etc/systemd/system/ir-ippi-octane.service` exists
- [ ] **Permissions Set**: `www-data:www-data` ownership
- [ ] **Service Started**: `sudo systemctl start ir-ippi-octane`
- [ ] **Service Running**: `sudo systemctl status ir-ippi-octane` shows `active (running)`
- [ ] **Port Working**: `curl http://localhost:8000` returns HTML
- [ ] **Nginx Configured**: Points to `http://ir_ippi_octane:8000`
- [ ] **App Accessible**: `curl https://yourdomain.com` works

---

## 🎯 My Recommendation for Your Setup

**Karena Anda di Ubuntu 24.04 VPS:**

### **GUNAKAN SWOOLE** ✅

**Alasan:**
1. ✅ Performance terbaik
2. ✅ Memory efficient
3. ✅ Setup paling sederhana
4. ✅ Tidak perlu Go runtime
5. ✅ Best practice untuk Linux

**Steps:**
1. Follow UBUNTU_24_SETUP.md (Steps 1-51)
2. At STEP 8-12: Install Swoole properly
3. At STEP 31: Set OCTANE_SERVER=swoole
4. At STEP 37: Use swoole in systemd service
5. At STEP 51: Test application

---

## 📞 Support

**Jika masih ada error:**

1. Check: **VPS_TROUBLESHOOTING.md**
2. Verify: Semua checklist di atas ✅
3. Logs: `sudo journalctl -u ir-ippi-octane -f`
4. Test: `php artisan tinker` (database connection)

---

**Status**: ✅ Clear Decision Guide  
**Recommendation**: SWOOLE for Ubuntu 24.04  
**Alternative**: RoadRunner if preferred

Choose your server, follow the steps, verify everything! 🚀
