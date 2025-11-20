# Performance Optimization Guide

## Overview

Aplikasi IR-IPPI telah dioptimasi untuk performa maksimal dengan caching dan code optimizations, tanpa memerlukan server eksternal yang complex.

## Recommended Options untuk Windows

### Option 1: Laravel Octane + RoadRunner (Best)
- **Performa**: 500-1000 req/sec
- **Setup Complexity**: Medium (requires socket extension)
- **Recommended untuk**: Production di Linux/WSL

### Option 2: Swoole (Good Alternative)
- **Performa**: 300-500 req/sec
- **Setup Complexity**: Medium
- **Recommended untuk**: Development & simple production

### Option 3: Built-in Server + Caching (Simple)
- **Performa**: 100-200 req/sec (dengan heavy caching)
- **Setup Complexity**: Low
- **Recommended untuk**: Development, simple deployments

## Saat Ini Digunakan: Built-in Server + Heavy Caching

## Performance Optimizations Applied

### 1. **Query Caching**
```php
// Cache active assessments for 1 hour
Cache::remember('active_assessments_nav', now()->addHour(), function () {
    return Assessment::select('id', 'title')
        ->where('is_active', true)
        ->limit(10)
        ->get();
});
```

### 2. **File-based Caching**
- Cache driver: `file` (faster than database)
- Location: `storage/framework/cache/data/`
- Auto-cleanup: Laravel handles expired entries

### 3. **View Caching**
- Compiled views cached in `storage/framework/views/`
- Clear dengan: `php artisan view:clear`

### 4. **Configuration Caching**
```bash
# Cache configuration (disable during development)
php artisan config:cache

# Clear cached config
php artisan config:clear
```

### 5. **Route Caching**
```bash
# Cache routes (don't use during development)
php artisan route:cache

# Clear cached routes
php artisan route:clear
```

## Starting the Application

### Development (No Caching)
```bash
php artisan serve
# Server at: http://localhost:8000
```

### Development + Optimized
```bash
php artisan config:cache
php artisan route:cache
php artisan serve
# Then clear when making code changes:
# php artisan optimize:clear
```

### Production (Full Optimization)
```bash
php artisan optimize  # Cache everything
php artisan serve --host=0.0.0.0 --port=8000
# Or use proper web server: Nginx/Apache
```

## Octane Setup (Optional, untuk Linux/WSL)

Jika menggunakan Linux atau WSL di Windows, bisa install Octane:

```bash
# Install RoadRunner
php artisan octane:install --server=roadrunner

# Start
php artisan octane:start
```

Or dengan Swoole:

```bash
# Install Swoole
php artisan octane:install --server=swoole

# Start
php artisan octane:start --server=swoole
```

## Performance Monitoring

### Check Memory Usage
```bash
php artisan octane:status
```

### Monitor Worker Health
```bash
tail -f storage/logs/laravel.log
```

### Benchmarking
```bash
# Using Apache Bench
ab -n 1000 -c 10 http://localhost:8000/

# Using wrk (faster)
wrk -t4 -c100 -d30s http://localhost:8000/
```

## Configuration Files Modified

1. `.env`
   - `OCTANE_SERVER=roadrunner` (optimal untuk Windows)
   - `CACHE_STORE=file`
   - `OCTANE_WORKERS=4`
   - `OCTANE_TASK_WORKERS=6`
   - `OCTANE_MAX_REQUESTS=1000`

2. `config/octane.php`
   - Server default: roadrunner
   - Warm cache: enabled
   - RoadRunner options: optimized untuk Windows

3. `config/cache.php`
   - Cache driver: file

4. `octane-start.bat` / `octane-start.sh`
   - Updated untuk gunakan RoadRunner dengan optimizations

## Performance Tips

1. **Monitor Memory Usage**
   - Jika memory usage naik, kurangi `OCTANE_MAX_REQUESTS` atau `OCTANE_WORKERS`
   - Typical: 4-8 workers untuk production

2. **Cache Wisely**
   - Cache data yang expensive (queries, API calls)
   - Invalidate cache saat data berubah
   - Use appropriate TTL values

3. **Database Optimization**
   - Use query caching di Octane
   - Monitor slow queries dengan `SLOW_LOG`
   - Add indexes pada frequently queried columns

4. **Static Assets**
   - Serve via web server (Nginx/Apache) bukan Octane
   - Enable gzip compression
   - Use CDN untuk assets

## Troubleshooting

### Server Crashes
```bash
# Check logs
tail -f storage/logs/laravel.log

# Increase memory limit
MEMORY_LIMIT=512M php artisan octane:start
```

### Memory Leak
```bash
# Reduce max requests per worker
OCTANE_MAX_REQUESTS=100 php artisan octane:start

# Monitor memory usage
php artisan octane:status
```

### Slow Performance
```bash
# Clear caches
php artisan cache:clear

# Regenerate optimized loader
php artisan optimize:clear

# Check active workers
netstat -tlnp | grep 8000
```

## Performance Comparison

### Expected Performance (vs Laravel built-in server)

| Metric | Built-in Server | RoadRunner+Octane |
|--------|-----------------|-------------------|
| Requests/sec | 50-100 | 500-1000 |
| Memory per worker | 15-20 MB | 40-60 MB |
| Response time | 200-500ms | 20-100ms |
| Concurrent connections | 10-20 | 100+ |

### When to Use

- **RoadRunner (Windows)**: Best untuk development & production di Windows
- **FrankenPHP (Linux/Mac)**: Best untuk performa ultimate di Linux/macOS
- **Laravel built-in**: Hanya untuk development ringan

1. **Redis Caching** (untuk distributed cache)
   ```env
   CACHE_STORE=redis
   REDIS_HOST=127.0.0.1
   REDIS_PORT=6379
   ```

2. **Queue Workers** (untuk async processing)
   ```bash
   php artisan queue:work --server=frankenphp
   ```

3. **Load Balancing** (untuk multiple servers)
   - Setup Nginx/HAProxy
   - Run multiple Octane instances
   - Share cache via Redis/Memcached

4. **Database Replication** (untuk read scaling)
   - Setup read replicas
   - Configure Laravel untuk use read connections

---

**Last Updated**: November 2025
**Environment**: PHP 8.3+, Laravel 11, RoadRunner (Windows) / FrankenPHP (Linux/Mac)
**Recommended**: RoadRunner untuk Windows, FrankenPHP untuk Linux/macOS untuk performa maksimal
