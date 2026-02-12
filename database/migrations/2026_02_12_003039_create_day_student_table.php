<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('day_student', function (Blueprint $table) {

        $table->id();

        $table->foreignId('student_id')
              ->constrained()
              ->onDelete('cascade');

        $table->foreignId('day_id')
              ->constrained()
              ->onDelete('cascade');

        $table->timestamps();

        $table->unique(['student_id', 'day_id']);
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('day_student');
    }
};
