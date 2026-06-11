<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;

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

                // دفعات الطلاب
                Artisan::call(
                    'payments:generate'
                );

                // رواتب السواقين
                Artisan::call(
                    'drivers:generate-salaries'
                );

            }

        } catch (\Throwable $e) {

            \Log::error([
                'auto_generate_error' =>
                    $e->getMessage()
            ]);

        }
    }
}