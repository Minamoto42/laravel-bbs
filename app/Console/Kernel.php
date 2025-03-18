<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     *v* @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        // 一小时执行一次「活跃用户」数据生成的命令
        $schedule->command('bbs:calculate-active-user')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
