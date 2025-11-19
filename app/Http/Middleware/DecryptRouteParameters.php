<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\UrlEncryption;

class DecryptRouteParameters
{
    /**
     * Handle an incoming request.
     *
     * This middleware decrypts encrypted route parameters.
     * If a parameter like {id} is encrypted, it will be decrypted automatically.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get all route parameters
        $parameters = $request->route()?->parameters ?? [];
        
        foreach ($parameters as $key => $value) {
            // Check if this looks like an encrypted value
            if (is_string($value) && strlen($value) > 20) {
                // Try to decrypt
                $decrypted = UrlEncryption::decryptId($value);
                
                if ($decrypted !== null) {
                    // Replace with decrypted value
                    $request->route()->setParameter($key, $decrypted);
                }
            }
        }

        return $next($request);
    }
}
