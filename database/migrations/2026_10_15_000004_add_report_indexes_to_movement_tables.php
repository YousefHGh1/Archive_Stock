<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->index('voucher_date');
        });
        Schema::table('invoice_exports', function (Blueprint $table) {
            $table->index('voucher_date');
        });
        Schema::table('diesels', function (Blueprint $table) {
            $table->index('date');
        });
        Schema::table('diesel_exports', function (Blueprint $table) {
            $table->index('date');
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex(['voucher_date']);
        });
        Schema::table('invoice_exports', function (Blueprint $table) {
            $table->dropIndex(['voucher_date']);
        });
        Schema::table('diesels', function (Blueprint $table) {
            $table->dropIndex(['date']);
        });
        Schema::table('diesel_exports', function (Blueprint $table) {
            $table->dropIndex(['date']);
        });
    }
};
