<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {

            $table->foreignId('pickup_station_id')
                  ->nullable()
                  ->constrained('stations')
                  ->nullOnDelete();

            $table->foreignId('dropoff_station_id')
                  ->nullable()
                  ->constrained('stations')
                  ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {

            $table->dropForeign(['pickup_station_id']);
            $table->dropForeign(['dropoff_station_id']);

            $table->dropColumn(['pickup_station_id','dropoff_station_id']);

        });
    }
};