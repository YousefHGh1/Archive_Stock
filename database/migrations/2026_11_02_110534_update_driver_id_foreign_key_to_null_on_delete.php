<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicle_operations', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
            $table->foreign('driver_id')->references('id')->on('drivers')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicle_operations', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
            $table->foreign('driver_id')->references('id')->on('drivers')->restrictOnDelete();
        });
    }
};
