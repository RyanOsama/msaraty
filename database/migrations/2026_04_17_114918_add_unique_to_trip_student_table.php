<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('trip_student', function (Blueprint $table) {
        $table->unique(['trip_id', 'student_id']);
    });
}

public function down()
{
    Schema::table('trip_student', function (Blueprint $table) {
        $table->dropUnique(['trip_id', 'student_id']);
    });
}
};
