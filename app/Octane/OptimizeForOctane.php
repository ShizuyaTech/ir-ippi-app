<?php

namespace App\Octane;

use Laravel\Octane\Events\WorkerStarting;

class OptimizeForOctane
{
    /**
     * Handle the worker starting event.
     *
     * This is called when each worker process starts and is a good
     * place to warm up caches and connections.
     */
    public function __invoke(WorkerStarting $event): void
    {
        // Warm up the cache
        $this->warmCache();

        // Initialize database connections
        $this->initializeDatabaseConnections();

        // Pre-load important service providers
        $this->preloadServiceProviders();
    }

    /**
     * Warm up critical caches.
     */
    private function warmCache(): void
    {
        // Cache active assessments
        \Illuminate\Support\Facades\Cache::remember(
            'active_assessments_nav',
            now()->addHour(),
            function () {
                return \App\Models\Assessment::select('id', 'title')
                    ->where('is_active', true)
                    ->limit(10)
                    ->get();
            }
        );

        \Illuminate\Support\Facades\Log::info('Octane: Cache warmup completed');
    }

    /**
     * Initialize database connections to prevent connection pool exhaustion.
     */
    private function initializeDatabaseConnections(): void
    {
        // Test database connection
        try {
            \Illuminate\Support\Facades\DB::connection()->getPdo();
            \Illuminate\Support\Facades\Log::info('Octane: Database connection initialized');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Octane: Database connection error', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Preload service providers for faster request handling.
     */
    private function preloadServiceProviders(): void
    {
        // Service providers are already loaded by Laravel
        \Illuminate\Support\Facades\Log::info('Octane: Service providers ready');
    }
}
