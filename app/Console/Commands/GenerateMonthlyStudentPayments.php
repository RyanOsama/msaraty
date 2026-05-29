<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Models\StudentPayment;

class GenerateMonthlyStudentPayments extends Command
{
    protected $signature = 'payments:generate';

    protected $description = 'Generate monthly payments for active students';

    public function handle()
    {
        $month = now()->format('Y-m');

        // الطلاب النشطين فقط
        $students = Student::whereRaw('LOWER(state) = ?', ['active'])->get();

        foreach ($students as $student) {

            StudentPayment::firstOrCreate(
                [
                    'student_id' => $student->id,
                    'for_month' => $month,
                ],
                [
                    'amount' => 0,
                    'status' => 'unpaid',
                ]
            );
        }

        $this->info('Monthly student payments generated successfully.');
    }
}