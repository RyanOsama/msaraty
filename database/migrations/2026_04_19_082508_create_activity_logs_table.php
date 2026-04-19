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
    Schema::create('activity_logs', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

        $table->string('action'); // create, update, delete, login...
        $table->string('table_name')->nullable(); // users, trips...
        $table->unsignedBigInteger('record_id')->nullable(); // id السجل

        $table->text('description')->nullable(); // وصف العملية

        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('activity_logs');
}
};
