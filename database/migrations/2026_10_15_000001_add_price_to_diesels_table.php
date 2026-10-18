<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('diesels', function (Blueprint $table) {
            // سعر اللتر وقت تسجيل الوارد؛ null = لم يُسجل السعر (بيانات تاريخية)
            $table->decimal('price', 12, 2)->nullable()->after('quantity');
        });
    }

    public function down()
    {
        Schema::table('diesels', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
};
