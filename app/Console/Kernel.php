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
        // Update currency rates every 6 hours
        $schedule->command('currency:update-rates')
                 ->everySixHours()
                 ->withoutOverlapping()
                 ->runInBackground();

        // Alternative: Update daily at 9 AM
        // $schedule->command('currency:update-rates')
        //          ->dailyAt('09:00')
        //          ->withoutOverlapping();

        // Alternative: Update every hour during business hours (9 AM - 6 PM)
        // $schedule->command('currency:update-rates')
        //          ->hourly()
        //          ->between('09:00', '18:00')
        //          ->weekdays()
        //          ->withoutOverlapping();
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
