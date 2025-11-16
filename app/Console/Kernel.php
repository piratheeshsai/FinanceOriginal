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

        $schedule->command('activitylog:clean')->dailyAt('02:30');

        // Run backups at 2:00 AM (after midnight but before cleanup)
        $schedule->command('backup:run')->dailyAt('02:00');

        // Clean old backups at 3:00 AM (after backups complete)
        $schedule->command('backup:clean')->dailyAt('03:00');
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
