# 🔧 QUICK FIX: Switch from RoadRunner to Swoole for Ubuntu 24.04

**Estimated Time**: ~15 minutes  
**Difficulty**: ⭐ Easy

---

## 📋 What's Wrong (Current State)

Your Ubuntu 24.04 is configured for:
- ❌ OCTANE_SERVER=roadrunner (Windows default)

But RoadRunner is NOT optimal for Ubuntu server!

**Should be:**
- ✅ OCTANE_SERVER=swoole (Linux best practice)

---

## 🚀 Quick Fix Steps

### **Step 1: Install Swoole Extension**

```bash
cd /var/www/ir-ippi-app
```

```bash
sudo apt install -y libcurl4-openssl-dev libbrotli-dev libssl-dev zlib1g-dev
```

```bash
sudo pecl install swoole
```

Wait for compilation to finish (5-10 minutes).

---

### **Step 2: Add Swoole to PHP CLI**

```bash
echo "extension=swoole.so" | sudo tee -a /etc/php/8.3/cli/php.ini
```

---

### **Step 3: Add Swoole to PHP-FPM**

```bash
echo "extension=swoole.so" | sudo tee -a /etc/php/8.3/fpm/php.ini
```

---

### **Step 4: Verify Swoole Installed**

```bash
php -m | grep swoole
```

Output harus menampilkan: `swoole` ✅

Jika tidak muncul, lihat VPS_TROUBLESHOOTING.md

---

### **Step 5: Update .env File**

```bash
sudo nano /var/www/ir-ippi-app/.env
```

**Find line:**
```env
OCTANE_SERVER=roadrunner
```

**Replace dengan:**
```env
OCTANE_SERVER=swoole
```

**Save:** `Ctrl + X`, `Y`, `Enter`

---

### **Step 6: Install Octane with Swoole**

```bash
cd /var/www/ir-ippi-app
```

```bash
sudo php artisan octane:install --server=swoole
```

---

### **Step 7: Update Systemd Service**

```bash
sudo nano /etc/systemd/system/ir-ippi-octane.service
```

**Find line:**
```ini
ExecStart=/usr/bin/php /var/www/ir-ippi-app/artisan octane:start --server=roadrunner ...
```

**Replace dengan:**
```ini
ExecStart=/usr/bin/php /var/www/ir-ippi-app/artisan octane:start --server=swoole --workers=4 --task-workers=6 --port=8000
```

**Save:** `Ctrl + X`, `Y`, `Enter`

---

### **Step 8: Reload Systemd**

```bash
sudo systemctl daemon-reload
```

---

### **Step 9: Restart Octane Service**

```bash
sudo systemctl stop ir-ippi-octane
```

```bash
sudo systemctl start ir-ippi-octane
```

---

### **Step 10: Verify Service Running**

```bash
sudo systemctl status ir-ippi-octane
```

Output harus: `active (running)` ✅

---

### **Step 11: Check Logs**

```bash
tail -20 /var/log/ir-ippi-octane.log
```

Harus tidak ada error.

---

### **Step 12: Test Application**

```bash
curl http://localhost:8000
```

Output harus menampilkan HTML aplikasi (bukan error).

---

### **Step 13: Test via Nginx**

```bash
curl https://yourdomain.com
```

Aplikasi harus muncul normal ✅

---

## ✅ Verification Checklist

Run these commands to verify everything works:

```bash
# 1. Check Swoole loaded
php -m | grep swoole
```

Output: `swoole` ✅

```bash
# 2. Check .env correct
grep "OCTANE_SERVER" /var/www/ir-ippi-app/.env
```

Output: `OCTANE_SERVER=swoole` ✅

```bash
# 3. Check service running
sudo systemctl status ir-ippi-octane
```

Output: `active (running)` ✅

```bash
# 4. Check processes
ps aux | grep octane
```

Output: Should show octane processes ✅

```bash
# 5. Check port listening
sudo netstat -tlnp | grep 8000
```

Output: Should show PHP listening on 8000 ✅

```bash
# 6. Test local
curl http://localhost:8000
```

Output: HTML content ✅

```bash
# 7. Test via domain
curl https://yourdomain.com
```

Output: Application loads ✅

---

## 🔍 If Something Goes Wrong

### **Error: "swoole extension not found"**

```bash
# Verify binary exists
sudo find /usr -name "swoole.so" 2>/dev/null

# If not found, reinstall
sudo pecl install swoole

# If compilation fails, check:
tail -20 /var/log/php-error.log
```

### **Error: "Service failed to start"**

```bash
# Check service error
sudo journalctl -u ir-ippi-octane -n 50

# Verify .env exists and readable
cat /var/www/ir-ippi-app/.env | grep OCTANE

# Check logs
tail -f /var/log/ir-ippi-octane-error.log
```

### **Error: "Connection refused on port 8000"**

```bash
# Check if service running
ps aux | grep octane

# Start it
sudo systemctl start ir-ippi-octane

# Wait 5 seconds, then test
sleep 5
curl http://localhost:8000

# Check for errors
sudo systemctl status ir-ippi-octane
```

### **Error: "502 Bad Gateway"**

```bash
# Verify Octane running
ps aux | grep octane | grep -v grep

# Check Nginx config
sudo nginx -t

# Restart Nginx
sudo systemctl restart nginx

# Check logs
tail -f /var/log/nginx/ir-ippi-error.log
```

---

## 💡 Performance Check

After switching to Swoole, verify performance improvement:

```bash
# Monitor processes
watch -n 1 'ps aux | grep swoole'

# Check memory usage
free -h

# Monitor logs
tail -f /var/log/ir-ippi-octane.log
```

Expected: Swoole workers running, stable memory usage.

---

## 🎉 You're Done!

Your Ubuntu 24.04 is now running with:
- ✅ PHP 8.3 + Swoole (optimized)
- ✅ Octane application server
- ✅ Better performance than RoadRunner
- ✅ Efficient resource usage
- ✅ Production ready

**Performance difference:**
- Before (RoadRunner): 500-800 req/sec
- After (Swoole): 1000+ req/sec ⚡

---

## 📞 Next Steps

- Monitor application for 24 hours
- Check logs regularly: `tail -f /var/log/ir-ippi-octane.log`
- Setup monitoring/alerts
- Backup database regularly

See **VPS_TROUBLESHOOTING.md** if any issues appear.

---

**Total Time**: ~15 minutes  
**Complexity**: ⭐ Easy  
**Result**: Optimized Swoole server ✅

Questions? Check **SWOOLE_vs_ROADRUNNER.md**
