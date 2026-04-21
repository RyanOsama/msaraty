<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('trip_cancellations');
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::create('trip_cancellations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('trip_id');
            $table->text('reason')->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
        });
    }
};