<?php

namespace AraneaDev\Electrum\App\Console;

use App\Console\Kernel as ConsoleKernel;
use Illuminate\Console\Scheduling\Schedule;

/**
 * Class Kernel.
 */
class Kernel extends ConsoleKernel
{
    /**
     * Define the package's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        parent::schedule($schedule);

        //$schedule->command('')->daily();
    }
}
