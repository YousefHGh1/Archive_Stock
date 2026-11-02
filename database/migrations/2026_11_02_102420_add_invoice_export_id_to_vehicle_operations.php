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
            $table->foreignId('invoice_export_id')->nullable()->constrained('invoice_exports')->nullOnDelete()->after('diesel_export_id');
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
            $table->dropForeign(['invoice_export_id']);
            $table->dropColumn('invoice_export_id');
        });
    }
};
