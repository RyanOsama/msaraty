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
    Schema::table('trip_cancellations', function (Blueprint $table) {
        $table->text('reason')->nullable();
        $table->string('status')->default('pending');
        $table->timestamp('cancelled_at')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trip_cancellations', function (Blueprint $table) {
            //
        });
    }
};
