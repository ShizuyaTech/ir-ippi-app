# 📚 VPS Setup - Which Guide Should I Use?

---

## 🎯 Quick Decision Tree

### **Q: What is your Ubuntu version?**

#### **Answer: Ubuntu 24.04 LTS (Newest)**
👉 **Use: UBUNTU_24_SETUP.md** ✅

**Why:**
- Optimized for Ubuntu 24.04
- Updated dependencies
- Best performance
- Tested & verified

#### **Answer: Ubuntu 22.04 LTS**
👉 **Use: VPS_MANUAL_SETUP.md** ✅

**Why:**
- Standard setup
- All dependencies verified
- Well-tested

#### **Answer: Other versions (20.04, 21.04, etc)**
👉 **Use: VPS_DEPLOYMENT_GUIDE.md** (Generic)

**Why:**
- More flexible
- Covers more versions
- Detailed explanations

---

## 📋 Guide Comparison

| Feature | UBUNTU_24_SETUP | VPS_MANUAL_SETUP | VPS_DEPLOYMENT_GUIDE |
|---------|-----------------|------------------|----------------------|
| **OS Version** | 24.04 (optimized) | 22.04 | All versions |
| **Steps** | 51 (specific) | 45 | Detailed |
| **Format** | Step-by-step | Step-by-step | Detailed + explanation |
| **Time** | ~90 min | ~60 min | ~2 hours |
| **Complexity** | ⭐ Easy | ⭐ Easy | ⭐⭐ Moderate |
| **Copy-Paste** | ✅ Yes | ✅ Yes | ✅ Yes |

---

## 📝 Ubuntu 24.04 Specific Changes

**Differences from Ubuntu 22.04:**

| Item | Ubuntu 24.04 | Ubuntu 22.04 |
|------|-------------|-------------|
| **PHP Default** | 8.3 | 8.1 |
| **MySQL Version** | 8.4 | 8.0 |
| **Node.js** | 20.x | 18.x |
| **OpenSSL** | 3.3 | 3.0 |
| **Kernel** | 6.8+ | 5.15+ |
| **Build Tools** | Latest | Stable |

**Ubuntu 24.04 Setup Requirements:**
- ✅ Uses newer OpenSSL (libssl3)
- ✅ Different MySQL initialization
- ✅ Swoole compilation specific flags
- ✅ PHP 8.3 default (automatic)

---

## 🚀 How to Use UBUNTU_24_SETUP.md

### **Option 1: Automated (Fastest)**
```bash
# Download and run automated script
curl -O https://raw.githubusercontent.com/ShizuyaTech/ir-ippi-app/main/setup-vps.sh
chmod +x setup-vps.sh
sudo ./setup-vps.sh
```

### **Option 2: Manual Step-by-Step (Most Control)**
```bash
# Use UBUNTU_24_SETUP.md
# Copy & paste each command one by one
# Verify each step before moving to next
```

---

## ✅ Step-by-Step Process for Ubuntu 24.04

**Total: 51 Steps (takes ~90 minutes)**

### Phase 1: Preparation (Steps 1-3)
- Connect SSH
- Verify Ubuntu 24.04
- Update system

### Phase 2: PHP & Swoole (Steps 4-13)
- Install PHP 8.3
- Install all PHP extensions
- Install Swoole dependencies
- Compile & install Swoole
- Verify Swoole loaded

### Phase 3: Tools & Database (Steps 14-22)
- Install Composer
- Install Node.js
- Install Nginx
- Install MySQL

### Phase 4: Application Setup (Steps 23-36)
- Clone application
- Install dependencies
- Create .env file
- Configure production .env
- Setup permissions
- Optimize Laravel
- Create database
- Run migrations

### Phase 5: Services (Steps 37-44)
- Create Octane service
- Create Queue service
- Start services
- Verify running

### Phase 6: Nginx & SSL (Steps 45-51)
- Configure Nginx
- Enable Nginx site
- Get SSL certificate
- Test application

---

## 🔧 Key Commands for Ubuntu 24.04

**Check Ubuntu version:**
```bash
lsb_release -a
cat /etc/os-release | grep VERSION
```

**Check PHP:**
```bash
php -v
php -m | grep swoole
```

**Check MySQL:**
```bash
mysql --version
sudo systemctl status mysql
```

**Check Services:**
```bash
sudo systemctl status ir-ippi-octane
sudo systemctl status ir-ippi-queue
sudo systemctl status nginx
```

**View Logs:**
```bash
tail -f /var/log/ir-ippi-octane.log
tail -f /var/log/ir-ippi-queue.log
tail -f /var/log/nginx/ir-ippi-error.log
```

---

## ⚠️ Common Ubuntu 24.04 Issues & Fixes

### Issue 1: MySQL Access Denied
**Fix:**
```bash
# For Ubuntu 24.04, use:
sudo mysql -u root
# (NOT mysql -u root -p)
```

### Issue 2: Swoole Compilation Fails
**Fix:**
```bash
# Ensure all dev packages installed
sudo apt install -y libssl-dev libcurl4-openssl-dev libbrotli-dev zlib1g-dev
sudo pecl install swoole
```

### Issue 3: PHP Extensions Not Loading
**Fix:**
```bash
# Verify .so file exists
sudo find /usr -name "swoole.so" 2>/dev/null

# Check php.ini
php -i | grep "extension_dir"
php -i | grep "loaded configuration"
```

### Issue 4: Nginx 502 Bad Gateway
**Fix:**
```bash
# Verify Octane running
ps aux | grep octane

# Check logs
tail -f /var/log/ir-ippi-octane.log

# Restart
sudo systemctl restart ir-ippi-octane
```

---

## 📞 File Reference

| File | Purpose | When to Use |
|------|---------|-----------|
| **UBUNTU_24_SETUP.md** | Step-by-step for Ubuntu 24.04 | ✅ You have Ubuntu 24.04 |
| **VPS_MANUAL_SETUP.md** | Step-by-step for Ubuntu 22.04 | ✅ You have Ubuntu 22.04 |
| **VPS_DEPLOYMENT_GUIDE.md** | Detailed guide (all versions) | ✅ Need more details |
| **PRODUCTION_CHECKLIST.md** | Pre-deployment checklist | ✅ Before going live |
| **VPS_TROUBLESHOOTING.md** | Error solutions | ❌ Something went wrong |
| **QUICK_START_VPS.md** | Copy-paste automated setup | ⏱️ Want fast setup |
| **setup-vps.sh** | Automated script | ⏱️ Want hands-off setup |

---

## 🎯 Recommended Setup Path for Ubuntu 24.04

1. **First Time?**
   - Read UBUNTU_24_SETUP.md (overview)
   - Read first 5 steps
   - Start setup

2. **During Setup?**
   - Follow UBUNTU_24_SETUP.md step by step
   - Copy-paste commands
   - Verify each step

3. **Problem?**
   - Check VPS_TROUBLESHOOTING.md
   - Find your error
   - Apply fix

4. **Before Going Live?**
   - Check PRODUCTION_CHECKLIST.md
   - Verify all items
   - Deploy

---

## 💡 Pro Tips

1. **SSH Multiple Windows**
   - Keep UBUNTU_24_SETUP.md open in one window
   - SSH terminal in another window
   - Easier to copy-paste

2. **Document Your Setup**
   - Write down passwords
   - Note domain name
   - Save MySQL credentials

3. **Take Screenshots**
   - Take pics of successful steps
   - Useful for debugging later

4. **Test Before Moving On**
   - Run verification commands
   - Check logs
   - Ensure step is successful

5. **Keep Backups**
   - Backup .env file
   - Backup database
   - Before making changes

---

## ✨ Ubuntu 24.04 Advantages

✅ PHP 8.3 (latest stable)  
✅ MySQL 8.4 (better performance)  
✅ Node.js 20 (latest LTS)  
✅ OpenSSL 3.3 (better security)  
✅ Latest kernel (6.8+)  
✅ Better performance overall  

---

## 🚀 Ready to Start?

**For Ubuntu 24.04:**
👉 Go to **UBUNTU_24_SETUP.md** and start from STEP 1

**For Ubuntu 22.04:**
👉 Go to **VPS_MANUAL_SETUP.md** and start from STEP 1

---

**Good luck! You got this! 🎉**

Questions? Check **VPS_TROUBLESHOOTING.md**
