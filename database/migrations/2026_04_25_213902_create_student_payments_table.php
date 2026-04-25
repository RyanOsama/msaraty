<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_payments', function (Blueprint $table) {
            $table->id();

            $table->string('receipt_image'); // صورة الإيصال
            $table->string('bank_name'); // اسم البنك
            $table->string('receipt_number'); // رقم الإيصال
            $table->decimal('amount', 8, 2); // المبلغ

            $table->enum('status', ['pending', 'rejected', 'approved'])
                  ->default('pending'); // الحالة

            $table->foreignId('student_id')
                  ->constrained()
                  ->cascadeOnDelete(); // ربط مع الطلاب

            $table->text('rejection_reason')->nullable(); // سبب الرفض

            $table->string('for_month'); // الشهر (مثلاً 2026-04)

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_payments');
    }
};