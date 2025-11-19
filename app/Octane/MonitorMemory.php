<?php

namespace App\Octane;

use Illuminate\Support\Facades\Log;

class MonitorMemory
{
    /**
     * Handle the tick event.
     *
     * This method is called on every server "tick" and monitors
     * memory usage to detect potential leaks.
     */
    public function __invoke(): void
    {
        $currentMemory = memory_get_usage(true) / 1024 / 1024; // MB
        $peakMemory = memory_get_peak_usage(true) / 1024 / 1024; // MB

        // Log if memory usage is high
        if ($currentMemory > 100) { // 100MB threshold
            Log::warning('High memory usage detected', [
                'current' => round($currentMemory, 2) . ' MB',
                'peak' => round($peakMemory, 2) . ' MB',
            ]);
        }

        // Force garbage collection if memory is too high
        if ($currentMemory > 200) { // 200MB threshold
            gc_collect_cycles();
            Log::info('Garbage collection triggered');
        }
    }
}
