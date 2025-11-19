<?php

namespace App\Services;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class RouteEncryption
{
    /**
     * Cache key untuk route mapping
     */
    private const ROUTE_MAP_CACHE = 'encrypted_routes_map';
    
    /**
     * Prefix untuk encrypted routes
     */
    private const ENCRYPTED_PREFIX = 'x';

    /**
     * Generate route map - maps original routes to hashed URLs
     * 
     * @return array
     * [
     *     'dashboard' => ['original' => '/dashboard', 'encrypted' => '/x/a7f8d9c3'],
     *     'feedbacks.index' => ['original' => '/feedbacks', 'encrypted' => '/x/b2e4f1d6'],
     * ]
     */
    public static function generateRouteMap()
    {
        // Cache untuk 1 hari
        return Cache::remember(self::ROUTE_MAP_CACHE, 86400, function () {
            $routes = collect(Route::getRoutes())->filter(function ($route) {
                // Exclude some routes
                $methods = $route->methods;
                return in_array('GET', $methods) && 
                       !in_array($route->getName(), ['ignition..*', 'telescope.*', 'sanctum.csrf-cookie']);
            });

            $map = [];
            foreach ($routes as $route) {
                if (!$route->getName()) {
                    continue;
                }

                $original = $route->uri;
                $encrypted = self::encryptRoute($original);

                $map[$route->getName()] = [
                    'original' => '/' . trim($original, '/'),
                    'encrypted' => $encrypted,
                    'name' => $route->getName(),
                ];
            }

            return $map;
        });
    }

    /**
     * Encrypt a route to hash
     * /dashboard → /x/a7f8d9c3
     */
    public static function encryptRoute($route)
    {
        $route = trim($route, '/');
        $hash = substr(md5($route . config('app.key')), 0, 8);
        return "/x/{$hash}";
    }

    /**
     * Decrypt encrypted route back to original
     * /x/a7f8d9c3 → /dashboard
     */
    public static function decryptRoute($encrypted)
    {
        $map = self::generateRouteMap();
        
        foreach ($map as $item) {
            if ($item['encrypted'] === $encrypted) {
                return $item['original'];
            }
        }
        
        return null;
    }

    /**
     * Generate encrypted URL for a route
     * 
     * @param string $routeName - Route name (e.g., 'dashboard', 'feedbacks.index')
     * @param array $params - Route parameters
     * @return string - Encrypted URL
     */
    public static function route($routeName, $params = [])
    {
        $map = self::generateRouteMap();
        
        if (!isset($map[$routeName])) {
            // Fallback to original URL if route not found
            return route($routeName, $params);
        }

        $encryptedPath = $map[$routeName]['encrypted'];
        
        // Add route parameters
        if (!empty($params)) {
            $paramStr = '?p=' . urlencode(json_encode($params));
            return $encryptedPath . $paramStr;
        }

        return $encryptedPath;
    }

    /**
     * Generate encrypted navigation link
     * 
     * @param string $text - Link text
     * @param string $routeName - Route name
     * @param array $params - Route parameters
     * @param array $attributes - HTML attributes
     * @return string - HTML link
     */
    public static function link($text, $routeName, $params = [], $attributes = [])
    {
        $url = self::route($routeName, $params);
        $attrs = '';
        
        foreach ($attributes as $key => $value) {
            $attrs .= " {$key}=\"{$value}\"";
        }

        return "<a href=\"{$url}\"{$attrs}>{$text}</a>";
    }

    /**
     * Clear route map cache when routes change
     */
    public static function clearCache()
    {
        Cache::forget(self::ROUTE_MAP_CACHE);
    }

    /**
     * Get route map for debugging/admin purposes
     */
    public static function getRouteMap()
    {
        return self::generateRouteMap();
    }

    /**
     * Convert route parameters back from encrypted query params
     */
    public static function getDecryptedParams($encryptedParams)
    {
        if (!$encryptedParams) {
            return [];
        }

        try {
            return json_decode($encryptedParams, true);
        } catch (\Exception $e) {
            return [];
        }
    }
}
