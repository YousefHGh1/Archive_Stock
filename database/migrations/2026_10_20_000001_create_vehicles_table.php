<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_type');
            $table->string('plate_number')->unique();
            $table->string('vin')->unique();
            $table->unsignedSmallInteger('manufacture_year')->nullable();
            $table->string('color')->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('is_active');
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
};
