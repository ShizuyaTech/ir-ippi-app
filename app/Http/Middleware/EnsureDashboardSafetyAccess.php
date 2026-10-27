<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDashboardSafetyAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (
            $user !== null
            && in_array($user->role, ['ir_reviewer', 'she', 'management'], true)
            && ! in_array($request->route()?->getName(), ['home', 'dashboard', 'profile.edit', 'profile.update'], true)
            && ! str_starts_with((string) $request->route()?->getName(), 'safety-reports.')
        ) {
            abort(403, 'Akses terbatas pada Dashboard, Safety & Healthy, dan profil akun.');
        }

        return $next($request);
    }
}
