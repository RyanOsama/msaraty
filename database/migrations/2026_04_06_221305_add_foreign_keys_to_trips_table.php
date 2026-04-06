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
    Schema::table('trips', function (Blueprint $table) {

$table->foreign('assign_id')->references('id')->on('route_station')->cascadeOnDelete();
        $table->foreign('route_id')->references('id')->on('routes')->cascadeOnDelete();

        $table->foreign('bus_id')->references('id')->on('buses')->cascadeOnDelete();

        $table->foreign('driver_id')->references('id')->on('drivers')->cascadeOnDelete();

        $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            //
        });
    }
};
