<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\StudentPayment;
use Illuminate\Support\Facades\Artisan;

class GenerateMonthlyPayments
{
    public function handle($request, Closure $next)
    {
        try {

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

        } catch (\Throwable $e) {

            \Log::error(
                'Auto payment failed',
                [
                    'error' => $e->getMessage()
                ]
            );
        }

        return $next($request);
    }
}