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
        Schema::table('invoice_exports', function (Blueprint $table) {
            $table->string('vehicle_event_type', 20)->nullable()->after('notes');
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->nullOnDelete()->after('vehicle_event_type');
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->nullOnDelete()->after('vehicle_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_exports', function (Blueprint $table) {
            $table->dropForeign(['vehicle_id']);
            $table->dropForeign(['driver_id']);
            $table->dropColumn(['vehicle_event_type', 'vehicle_id', 'driver_id']);
        });
    }
};
