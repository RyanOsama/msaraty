<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'driver_salaries',
            function (
                Blueprint $table
            ) {

                $table->id();

                $table->unsignedBigInteger(
                    'driver_id'
                );

                $table->decimal(
                    'amount',
                    10,
                    2
                );

                $table->string(
                    'for_month'
                );

                $table->enum(
                    'status',
                    [
                        'paid',
                        'unpaid'
                    ]
                )->default(
                    'unpaid'
                );

                $table->timestamps();

                $table->foreign(
                    'driver_id'
                )
                ->references(
                    'id'
                )
                ->on(
                    'drivers'
                )
                ->cascadeOnDelete();

            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'driver_salaries'
        );
    }
};