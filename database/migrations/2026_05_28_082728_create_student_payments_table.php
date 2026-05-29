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

            $table->foreignId('payment_request_id')
                ->nullable()
                ->constrained('payment_requests')
                ->nullOnDelete();

            $table->foreignId('student_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->decimal('amount', 10, 2)->default(0);

            $table->string('for_month');

            // unpaid | paid
            $table->enum('status', ['unpaid', 'paid'])
                ->default('unpaid');

            $table->timestamps();

            // منع تكرار نفس الشهر لنفس الطالب
            $table->unique(['student_id', 'for_month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_payments');
    }
};