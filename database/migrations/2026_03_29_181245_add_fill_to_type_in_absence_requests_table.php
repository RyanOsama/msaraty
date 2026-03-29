<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFillToTypeInAbsenceRequestsTable extends Migration
{
    public function up()
    {
        \DB::statement("ALTER TABLE absence_requests MODIFY type ENUM('pickup','dropoff','fill')");
    }

    public function down()
    {
        \DB::statement("ALTER TABLE absence_requests MODIFY type ENUM('pickup','dropoff')");
    }
}