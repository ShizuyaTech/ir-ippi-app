<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Menjalankan fetch berita setiap 12 jam
        $schedule->command('news:fetch-automotive --source=cnn --limit=10')
                ->twiceDaily(1, 13) // Jalankan pada jam 1 pagi dan 1 siang
                ->withoutOverlapping()
                ->appendOutputTo(storage_path('logs/news-fetch.log'));
                
        // Menjalankan fetch berita dari sumber lain setiap 12 jam
        $schedule->command('news:fetch-automotive --source=oto --limit=10')
                ->twiceDaily(2, 14) // Jalankan pada jam 2 pagi dan 2 siang
                ->withoutOverlapping()
                ->appendOutputTo(storage_path('logs/news-fetch.log'));
                
        $schedule->command('news:fetch-automotive --source=republika --limit=10')
                ->twiceDaily(3, 15) // Jalankan pada jam 3 pagi dan 3 siang
                ->withoutOverlapping()
                ->appendOutputTo(storage_path('logs/news-fetch.log'));
                
        $schedule->command('news:fetch-automotive --source=tribun --limit=10')
                ->twiceDaily(4, 16) // Jalankan pada jam 4 pagi dan 4 siang
                ->withoutOverlapping()
                ->appendOutputTo(storage_path('logs/news-fetch.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}