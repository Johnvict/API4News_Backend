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
    protected $commands = [
        Commands\ArrangeNews::class,
        Commands\ObtainNews::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('news:arrange')->daily()->onOneServer();
        // $schedule->command('news:obtain')->cron('0 */6 * * *')->onOneServer(); //Every 6 hours
        $schedule->command('news:obtain')->everyMinute();
        // $schedule->command('news:obtain')-> //Every 6 hours

        // $schedule->command('catalog:update')->cron('0 */2 * * *'); // every 2 hours
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
