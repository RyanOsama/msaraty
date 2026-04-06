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
        Schema::create('trips', function (Blueprint $table) {
    $table->id();

    $table->string('trip_name');
    $table->enum('trip_type', ['pickup', 'dropoff']);

    $table->date('trip_date');
    $table->time('trip_time');
    $table->dateTime('deadline');

    $table->unsignedBigInteger('assign_id');
    $table->unsignedBigInteger('route_id');
    $table->unsignedBigInteger('bus_id');
    $table->unsignedBigInteger('driver_id');
    $table->unsignedBigInteger('created_by');

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
