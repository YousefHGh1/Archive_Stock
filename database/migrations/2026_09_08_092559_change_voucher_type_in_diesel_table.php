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
        Schema::table('diesels', function (Blueprint $table) {
            $table->dropUnique('diesels_voucher_unique');
            $table->string('voucher')->change();
            $table->unique('voucher');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('diesels', function (Blueprint $table) {
            $table->dropUnique('diesels_voucher_unique');
            $table->integer('voucher')->change();
            $table->unique('voucher');
        });
    }
};
