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
       Schema::create('buses', function (Blueprint $table) {
        $table->id();

        $table->integer('number_passengers');
        $table->string('type_fuel')->nullable();

        $table->foreignId('driver_id')
              ->constrained('drivers')
              ->onDelete('restrict'); // يمنع حذف السائق إذا مرتبط بباص

        $table->timestamps();
         });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buses');
    }
};
