<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('trips', function (Blueprint $table) {

            // 🔥 حذف الـ foreign key أولًا
            $table->dropForeign(['route_id']);

            // 🔥 ثم حذف العمود
            $table->dropColumn('route_id');
        });
    }

    public function down()
    {
        Schema::table('trips', function (Blueprint $table) {

            // 👇 ترجيعه لو سويت rollback
            $table->foreignId('route_id')
                ->constrained('routes')
                ->onDelete('cascade');
        });
    }
};