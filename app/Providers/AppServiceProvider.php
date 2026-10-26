<?php

namespace App\Providers;

use App\Models\Assessment;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Paginator::useBootstrapFour();

        // Load helpers
        require_once app_path('helpers.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ✅ OPTIMIZED: Cache active assessments for 1 hour, limit to header/footer
        // This prevents querying ALL assessments on every page load
        View::composer(['layouts-web.header', 'layouts-web.footer'], function ($view) {
            $activeAssessments = \Illuminate\Support\Facades\Cache::remember(
                'active_assessments_nav',
                now()->addHour(),
                function () {
                    return Assessment::select('id', 'title')
                        ->where('is_active', true)
                        ->orderBy('created_at', 'desc')
                        ->limit(10)  // Only show 10 in navigation
                        ->get();
                }
            );

            $view->with('activeAssessments', $activeAssessments);
        });

        View::composer('*', function ($view) {
            $isAdmin = \Illuminate\Support\Facades\Auth::user()?->isAdmin() ?? false;

            $view->with('isAdmin', $isAdmin);
        });
    }
}
