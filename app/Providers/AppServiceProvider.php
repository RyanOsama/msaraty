<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;
use App\Models\StudentPayment;

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

                $month = now()->format('Y-m');

                $exists = StudentPayment::where(
                    'for_month',
                    $month
                )->exists();

                if (!$exists) {

                    Artisan::call(
                        'payments:generate'
                    );
                }
            }

        } catch (\Throwable $e) {

            \Log::error(
                'Auto payments failed',
                [
                    'error' => $e->getMessage()
                ]
            );
        }
    }
}