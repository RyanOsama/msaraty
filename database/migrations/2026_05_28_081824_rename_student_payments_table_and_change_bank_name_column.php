<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // تغيير اسم الجدول
        Schema::rename('student_payments', 'payments');

        // تغيير اسم العمود
        Schema::table('payments', function (Blueprint $table) {
            $table->renameColumn('bank_name', 'payment_type');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->renameColumn('payment_type', 'bank_name');
        });

        Schema::rename('payments', 'student_payments');
    }
};