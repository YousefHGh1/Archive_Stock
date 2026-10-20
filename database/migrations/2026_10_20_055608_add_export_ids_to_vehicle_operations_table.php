<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->foreignId('diesel_export_id')->nullable()->after('driver_id')->constrained('diesel_exports')->nullOnDelete();
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
            $table->dropForeign(['diesel_export_id']);
            $table->dropColumn('diesel_export_id');
        });
    }
};
