#!/bin/bash
# Swoole Installation Diagnostic Script
# Jalankan: bash /tmp/swoole-diagnostic.sh

echo "════════════════════════════════════════════════════════"
echo "SWOOLE INSTALLATION DIAGNOSTIC"
echo "════════════════════════════════════════════════════════"
echo ""

echo "1️⃣ PHP VERSION & CONFIGURATION"
echo "─────────────────────────────────"
php -v
echo ""
echo "PHP CLI Info Path:"
php -i | grep "php.ini" | head -1
echo ""

echo "2️⃣ CURRENT SWOOLE STATUS"
echo "─────────────────────────────────"
php -m | grep -i swoole && echo "✅ Swoole FOUND" || echo "❌ Swoole NOT installed"
echo ""

echo "3️⃣ PECL INSTALLED PACKAGES"
echo "─────────────────────────────────"
pecl list | head -20
echo ""

echo "4️⃣ BUILD TOOLS AVAILABLE"
echo "─────────────────────────────────"
for tool in gcc g++ make autoconf automake libtool pecl php-config; do
    if command -v $tool &> /dev/null; then
        echo "✅ $tool: $(which $tool)"
    else
        echo "❌ $tool: NOT FOUND"
    fi
done
echo ""

echo "5️⃣ REQUIRED LIBRARIES"
echo "─────────────────────────────────"
for lib in libcurl4-openssl-dev libbrotli-dev libssl-dev zlib1g-dev; do
    dpkg -l | grep -q "^ii.*$lib" && echo "✅ $lib: installed" || echo "❌ $lib: missing"
done
echo ""

echo "6️⃣ PHP EXTENSIONS LOADED"
echo "─────────────────────────────────"
php -m | head -30
echo ""

echo "7️⃣ SWOOLE INSTALLATION LOCATION"
echo "─────────────────────────────────"
if [ -d "/usr/lib/php" ]; then
    echo "Searching swoole.so in /usr/lib/php:"
    find /usr/lib/php -name "swoole.so" 2>/dev/null || echo "❌ swoole.so not found"
fi
echo ""

echo "8️⃣ PHP.INI FILES"
echo "─────────────────────────────────"
echo "CLI php.ini:"
php -i | grep "php.ini" | grep "Loaded Configuration" || echo "Using default"
echo ""

echo "9️⃣ RECENT PECL INSTALL LOG (last 50 lines)"
echo "─────────────────────────────────"
if [ -f ~/.pearrc ]; then
    echo "PECL RC file found: ~/.pearrc"
fi
echo ""
echo "Last 50 lines of build.log (if exists):"
if [ -f "/tmp/pear/swoole/build.log" ]; then
    tail -50 /tmp/pear/swoole/build.log
else
    echo "❌ build.log not found"
fi
echo ""

echo "════════════════════════════════════════════════════════"
echo "END OF DIAGNOSTIC"
echo "════════════════════════════════════════════════════════"
