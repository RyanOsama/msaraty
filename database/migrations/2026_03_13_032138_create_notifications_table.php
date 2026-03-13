<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id')->nullable(); // يمكن أن يكون فارغ
            $table->string('title');
            $table->text('message');
            $table->integer('target_group');
            $table->string('type');
            $table->timestamps(); // ينشئ created_at و updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};