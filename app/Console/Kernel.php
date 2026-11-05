<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Schedule SLA check command daily at 8 AM
        $schedule->command('purchase-requests:check-stale')
            ->dailyAt('08:00')
            ->description('Check for stale purchase requests and send SLA alerts');

        // Schedule daily database backup at 3 PM
        $schedule->command('backup:daily')
            ->dailyAt('15:00')
            ->description('Create daily database backup at 3 PM')
            ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
