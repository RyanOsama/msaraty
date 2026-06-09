<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;

use App\Models\StudentPayment;
use App\Models\DriverSalary;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        try {

            if (!app()->runningInConsole()) {

                $month =
                    now()->format(
                        'Y-m'
                    );

                // ==================
                // دفعات الطلاب
                // ==================

                $studentExists =
                    StudentPayment::where(
                        'for_month',
                        $month
                    )->exists();

                if (
                    !$studentExists
                ) {

                    Artisan::call(
                        'payments:generate'
                    );

                }


                // ==================
                // رواتب السواقين
                // ==================

                $driverExists =
                    DriverSalary::where(
                        'for_month',
                        $month
                    )->exists();

                if (
                    !$driverExists
                ) {

                    Artisan::call(
                        'drivers:generate-salaries'
                    );

                }

            }

        } catch (\Throwable $e) {

            \Log::error([
                'auto_generate_error' =>
                    $e->getMessage()
            ]);

        }
    }
}