<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Driver;
use App\Models\DriverSalary;

class GenerateMonthlyDriverSalaries extends Command
{
    protected $signature =
        'drivers:generate-salaries';

    protected $description =
        'Generate monthly salaries';

    public function handle()
    {
        $month =
            now()->format(
                'Y-m'
            );

        $drivers =
            Driver::all();

        foreach (
            $drivers
            as $driver
        ) {

            DriverSalary::firstOrCreate(

                [
                    'driver_id' =>
                        $driver->id,

                    'for_month' =>
                        $month,
                ],

                [
                    'amount' =>
                        0,

                    'status' =>
                        'unpaid',
                ]

            );

        }

        $this->info(
            'Driver salaries generated'
        );

        return Command::SUCCESS;
    }
}