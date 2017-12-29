<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = ['App\Console\Commands\WeeklyReports'];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
		// Run every 5 minutes
        $schedule->command('queue:work --sleep=3 --tries=3')->everyFiveMinutes();
		$file = 'command1_output.log';
        $schedule->command('weeklyreports')->everyMinute()->sendOutputTo($file)->emailOutputTo('mithlesh.navlakhe@ignatiuz.com');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
