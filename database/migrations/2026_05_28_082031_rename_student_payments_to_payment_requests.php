<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('payments', 'payment_requests');
    }

    public function down(): void
    {
        Schema::rename('payment_requests', 'payments');
    }
};