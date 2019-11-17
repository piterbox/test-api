<?php

namespace App\Console;

use App\Console\Commands\GetCustomerBankAccounts;
use App\Console\Commands\GetCustomerBankData;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        GetCustomerBankAccounts::class,
        GetCustomerBankData::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('customer-bank-accounts:get')->hourly();
        $schedule->command('customer-bank-data:get')->hourly();
    }
}
