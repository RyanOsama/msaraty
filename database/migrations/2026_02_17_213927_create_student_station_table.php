<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('student_station', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                  ->constrained()
                  ->restrictOnDelete(); // ❌ يمنع حذف الطالب لو مرتبط

            $table->foreignId('station_id')
                  ->constrained()
                  ->restrictOnDelete(); // ❌ يمنع حذف المحطة لو مرتبطة

            // نوع المحطة
            $table->enum('type', ['pickup', 'dropoff']);

            $table->timestamps();

            // يمنع تكرار نفس النوع لنفس الطالب
            $table->unique(['student_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_station');
    }
};
