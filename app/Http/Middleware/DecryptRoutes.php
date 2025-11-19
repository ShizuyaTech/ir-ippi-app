<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\RouteEncryption;

class DecryptRoutes
{
    /**
     * Handle an incoming request dengan decrypt encrypted routes
     */
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah request path dimulai dengan /x/ (encrypted route)
        if (str_starts_with($request->path(), 'x/')) {
            $encryptedPath = '/' . $request->path();
            $originalPath = RouteEncryption::decryptRoute($encryptedPath);
            
            if ($originalPath) {
                // Get decrypted parameters
                $params = RouteEncryption::getDecryptedParams($request->input('p'));
                
                // Set request path ke original path
                $request->server->set('REQUEST_URI', $originalPath . ($params ? '?p=' . urlencode(json_encode($params)) : ''));
                
                // Store original encrypted path untuk reference
                $request->merge(['_encrypted_path' => $encryptedPath]);
                $request->merge(['_decrypted_params' => $params]);
            }
        }

        return $next($request);
    }
}
