# 🔧 FIX: "undefined symbol: curl_share_ce" Error

**Error Message:**
```
PHP Warning: Unable to load dynamic library 'swoole.so'
undefined symbol: curl_share_ce
```

**Penyebab:**
- Swoole dikompile dengan libcurl versi X
- Sistem memiliki libcurl versi Y (berbeda)
- Version mismatch → symbol tidak ditemukan

---

## ✅ SOLUSI (3 Langkah)

### **Langkah 1: Identifikasi Masalah**

```bash
# Cek versi libcurl yang terinstal
curl-config --version

# Cek symbols di swoole.so
nm -D /usr/lib/php/20230831/swoole.so | grep curl_share_ce
```

### **Langkah 2: Hapus & Bersihkan**

```bash
# Stop service dulu
sudo systemctl stop ir-ippi-octane

# Uninstall Swoole
sudo pecl uninstall swoole -y

# Hapus binary lama
sudo rm -f /usr/lib/php/20230831/swoole.so*

# Hapus cache PECL
rm -rf ~/.pearrc
rm -rf /var/cache/apt/*

# Rebuild cache
sudo apt clean
sudo apt update
```

### **Langkah 3: Reinstall dengan Benar**

```bash
# Install semua dependencies + libcurl
sudo apt install -y \
  build-essential \
  autoconf \
  automake \
  libtool \
  pkg-config \
  php8.3-dev \
  libcurl4-openssl-dev \
  libbrotli-dev \
  libssl-dev \
  zlib1g-dev

# Tunggu sampai selesai

# Verify libcurl terinstal
dpkg -l | grep libcurl4

# Reinstall Swoole dengan verbose
sudo pecl install swoole -v 2>&1 | tee /tmp/swoole-install.log
```

### **Langkah 4: Verifikasi**

```bash
# Cek symbols sekarang
nm -D /usr/lib/php/20230831/swoole.so | grep curl_share_ce

# Load test
php -r "var_dump(extension_loaded('swoole'));"

# Harus output: bool(true)
```

### **Langkah 5: Test Extension**

```bash
php -m | grep swoole

# Tidak boleh ada warning/error, hanya output: swoole
```

---

## 🚨 JIKA MASIH ERROR

### **Opsi A: Reinstall dari Source**

```bash
# Download source
cd /tmp
wget https://github.com/swoole/swoole-src/archive/v5.1.0.tar.gz
tar xzf v5.1.0.tar.gz
cd swoole-src-5.1.0

# Configure
phpize
./configure --with-php-config=/usr/bin/php-config

# Build
make clean
make -j$(nproc)

# Install
sudo make install

# Enable in PHP
echo "extension=swoole.so" | sudo tee -a /etc/php/8.3/cli/php.ini
echo "extension=swoole.so" | sudo tee -a /etc/php/8.3/fpm/php.ini

# Verify
php -m | grep swoole
```

### **Opsi B: Cek Kompatibilitas Libcurl**

```bash
# Cek versi libcurl
curl --version | head -1

# Cek yang dibutuhkan Swoole
strings /usr/lib/php/20230831/swoole.so | grep "libcurl"

# Reinstall libcurl yang sesuai
sudo apt reinstall -y libcurl4-openssl-dev
```

### **Opsi C: Gunakan libcurl versi stable**

```bash
# Remove dan reinstall
sudo apt remove -y libcurl4-openssl-dev
sudo apt update
sudo apt install -y libcurl4-openssl-dev=7.81.0-1ubuntu1.15

# Kemudian reinstall Swoole
sudo pecl install swoole
```

---

## 📋 FULL FIX SCRIPT (Copy-Paste Ready)

```bash
#!/bin/bash

echo "🔧 Fixing Swoole curl_share_ce error..."

# 1. Stop service
echo "⏹️  Stopping Octane service..."
sudo systemctl stop ir-ippi-octane

# 2. Uninstall
echo "🗑️  Removing Swoole..."
sudo pecl uninstall swoole -y
sudo rm -f /usr/lib/php/20230831/swoole.so*
rm -rf ~/.pearrc

# 3. Update system
echo "🔄 Updating system..."
sudo apt clean
sudo apt update

# 4. Install dependencies
echo "📦 Installing dependencies..."
sudo apt install -y \
  build-essential \
  autoconf \
  automake \
  libtool \
  pkg-config \
  php8.3-dev \
  libcurl4-openssl-dev \
  libbrotli-dev \
  libssl-dev \
  zlib1g-dev

# 5. Verify libcurl
echo "✅ Verifying libcurl..."
curl-config --version

# 6. Reinstall Swoole
echo "🚀 Installing Swoole..."
sudo pecl install swoole -v

# 7. Verify extension
echo "🔍 Verifying installation..."
if php -m | grep -q swoole; then
    echo "✅ SUCCESS: Swoole installed"
else
    echo "❌ ERROR: Swoole not loaded"
    php -m | grep -i extension
fi

# 8. Restart service
echo "▶️  Starting Octane service..."
sudo systemctl start ir-ippi-octane
sudo systemctl status ir-ippi-octane

echo "✅ Done!"
```

Simpan sebagai `/tmp/fix-swoole.sh`:
```bash
bash /tmp/fix-swoole.sh
```

---

## 🎯 RECOMMENDED: Quick Version

Coba ini dulu (paling cepat):

```bash
sudo systemctl stop ir-ippi-octane
sudo pecl uninstall swoole -y
sudo rm -f /usr/lib/php/20230831/swoole.so*
rm -rf ~/.pearrc
sudo apt update
sudo apt install -y php8.3-dev libcurl4-openssl-dev libbrotli-dev libssl-dev zlib1g-dev
sudo pecl install swoole
php -m | grep swoole
sudo systemctl start ir-ippi-octane
```

---

## ✅ Verifikasi Berhasil

```bash
# 1. No warnings
php -m | grep swoole
# Output: swoole (tanpa warning)

# 2. Extension loaded
php -r "echo extension_loaded('swoole') ? 'YES' : 'NO';"
# Output: YES

# 3. Service running
sudo systemctl status ir-ippi-octane
# Output: active (running)

# 4. Port listening
sudo netstat -tlnp | grep 8000
# Output: LISTEN
```

---

**Jalankan Quick Version dulu, tunjukkan output-nya ke saya!**
