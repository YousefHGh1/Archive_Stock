<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('diesels', function (Blueprint $table) {
            $table->text('notes')->nullable();
        });

        Schema::table('diesel_exports', function (Blueprint $table) {
            $table->text('notes')->nullable();
        });
    }

    public function down()
    {
        Schema::table('diesel_exports', function (Blueprint $table) {
            $table->dropColumn('notes');
        });

        Schema::table('diesels', function (Blueprint $table) {
            $table->dropColumn('notes');
        });
    }
};
