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
    Schema::rename('student_station', 'station_student');
}

public function down(): void
{
    Schema::rename('station_student', 'student_station');
}

};
