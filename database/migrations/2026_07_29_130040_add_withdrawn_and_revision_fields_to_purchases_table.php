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
        Schema::table('purchases', function (Blueprint $table) {
            $table->boolean('withdrawn')->default(false)->after('status');
            $table->foreignId('reopened_from_id')->nullable()->after('withdrawn')->constrained('purchases')->onDelete('set null');
            $table->integer('revision_number')->default(1)->after('reopened_from_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn(['withdrawn', 'reopened_from_id', 'revision_number']);
        });
    }
};
