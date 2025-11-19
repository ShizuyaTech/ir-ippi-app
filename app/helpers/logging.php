<?php

if (!function_exists('app_log')) {
    /**
     * Log a message to the application log
     * 
     * @param string $message The message to log
     * @param string $level The log level (debug, info, warning, error)
     * @return void
     */
    function app_log($message, $level = 'info') 
    {
        switch ($level) {
            case 'debug':
                \Illuminate\Support\Facades\Log::debug($message);
                break;
            case 'warning':
                \Illuminate\Support\Facades\Log::warning($message);
                break;
            case 'error':
                \Illuminate\Support\Facades\Log::error($message);
                break;
            default:
                \Illuminate\Support\Facades\Log::info($message);
        }
    }
}