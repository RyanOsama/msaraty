<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Models\StudentPayment;

class GenerateMonthlyStudentPayments extends Command
{
    protected $signature = 'payments:generate';

    protected $description = 'Generate monthly payments';

    public function handle()
    {
        $month = now()->format('Y-m');

        $students = Student::where(
            'state',
            'active'
        )->get();

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

        $this->info(
            'Monthly payments generated'
        );

        return Command::SUCCESS;
    }
}