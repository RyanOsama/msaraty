<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {

            // حذف القيود القديمة
            $table->dropForeign(['university_id']);
            $table->dropForeign(['college_id']);
            $table->dropForeign(['department_id']);
            $table->dropForeign(['level_id']);

            // إضافة القيود الجديدة (RESTRICT)
            $table->foreign('university_id')
                  ->references('id')->on('universities')
                  ->restrictOnDelete();

            $table->foreign('college_id')
                  ->references('id')->on('colleges')
                  ->restrictOnDelete();

            $table->foreign('department_id')
                  ->references('id')->on('departments')
                  ->restrictOnDelete();

            $table->foreign('level_id')
                  ->references('id')->on('levels')
                  ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {

            $table->dropForeign(['university_id']);
            $table->dropForeign(['college_id']);
            $table->dropForeign(['department_id']);
            $table->dropForeign(['level_id']);

            // ترجعها cascade لو احتجت (اختياري)
            $table->foreign('university_id')
                  ->references('id')->on('universities')
                  ->cascadeOnDelete();

            $table->foreign('college_id')
                  ->references('id')->on('colleges')
                  ->cascadeOnDelete();

            $table->foreign('department_id')
                  ->references('id')->on('departments')
                  ->cascadeOnDelete();

            $table->foreign('level_id')
                  ->references('id')->on('levels')
                  ->cascadeOnDelete();
        });
    }
};
