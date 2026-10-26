<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSafetyReportRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if ($user === null || ! $user->hasSafetyReportRole(...$roles)) {
            abort(403, 'Anda tidak memiliki akses untuk proses laporan Safety & Healthy ini.');
        }

        return $next($request);
    }
}
